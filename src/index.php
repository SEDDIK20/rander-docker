<?php
session_start();

$servername = "db";               // اسم السيرفر أو الحاوية الخاصة بقاعدة البيانات
$dbusername = "php_docker";       // اسم المستخدم كما هو معرف في docker-compose.yml
$dbpassword = "password";         // كلمة المرور كما هو معرف
$dbname = "php_docker";           // اسم قاعدة البيانات

// إنشاء الاتصال باستخدام PostgreSQL بدلاً من MySQL
$conn = pg_connect("host=$servername dbname=$dbname user=$dbusername password=$dbpassword");

// التحقق من الاتصال
if (!$conn) {
    die("فشل الاتصال: " . pg_last_error());
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // استخدام استعلام PostgreSQL للتحقق من اسم المستخدم وكلمة المرور
    $result = pg_query_params($conn, "SELECT id, password FROM users WHERE username = $1", array($username));

    if (pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $id = $row['id'];
        $hashed_password = $row['password'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            header("Location: welcome.php");
            exit;
        } else {
            $message = "كلمة المرور غير صحيحة.";
        }
    } else {
        $message = "اسم المستخدم غير موجود.";
    }
}

pg_close($conn);
?>


<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 400px;
            max-width: 90%;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            border: none;
            border-bottom: 2px solid #ccc;
            padding: 10px;
            margin: 20px 0;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-bottom: 2px solid #007BFF;
            outline: none;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 25px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            color: red;
            margin-bottom: 10px;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>تسجيل الدخول</h2>

    <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="اسم المستخدم" required><br>
        <input type="password" name="password" placeholder="كلمة المرور" required><br>
        <button type="submit">دخول</button>
    </form>
    <p>ليس لديك حساب؟ <a href="regester.php">سجّل الآن</a></p>
</div>

</body>
</html>
