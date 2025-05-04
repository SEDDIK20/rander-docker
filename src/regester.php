<?php
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $servername = "db";               // اسم السيرفر أو الحاوية الخاصة بقاعدة البيانات
    $dbusername = "php_docker";       // اسم المستخدم كما هو معرف في docker-compose.yml
    $dbpassword = "password";         // كلمة المرور كما هو معرف
    $dbname = "php_docker";           // اسم قاعدة البيانات

    // إنشاء الاتصال باستخدام PostgreSQL بدلاً من MySQL
    $conn = pg_connect("host=$servername dbname=$dbname user=$dbusername password=$dbpassword");

    if (!$conn) {
        die("فشل الاتصال: " . pg_last_error());
    }

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // استخدام استعلام PostgreSQL لإدخال المستخدم الجديد
    $result = pg_query_params($conn, "INSERT INTO users (username, password) VALUES ($1, $2)", array($username, $password));

    if ($result) {
        $message = "تم التسجيل بنجاح! يمكنك الآن تسجيل الدخول.";
    } else {
        $message = "خطأ: " . pg_last_error($conn);
    }

    pg_close($conn);
}
?>



<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل</title>
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
            color: green;
            margin-bottom: 10px;
        }

        .error {
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
    <h2>تسجيل مستخدم جديد</h2>

    <?php
    if (!empty($message)) {
        $msg_class = strpos($message, 'تم') === 0 ? 'message' : 'error';
        echo "<div class='$msg_class'>$message</div>";
    }
    ?>

    <form method="POST">
        <input type="text" name="username" placeholder="اسم المستخدم" required><br>
        <input type="password" name="password" placeholder="كلمة المرور" required><br>
        <button type="submit">تسجيل</button>
    </form>
    <p>هل لديك حساب؟ <a href="index.php">تسجيل الدخول</a></p>
</div>

</body>
</html>
