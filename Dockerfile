FROM php:apache

# تثبيت الحزم المطلوبة لتشغيل pgsql و pdo_pgsql
RUN apt-get update && apt-get install -y libpq-dev

# تثبيت الامتدادات الخاصة بـ MySQL و PostgreSQL
RUN docker-php-ext-install mysqli pdo pdo_mysql pgsql pdo_pgsql


# تمكين mod_rewrite
RUN a2enmod rewrite