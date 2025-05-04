# Dockerfile

FROM php:8.2-apache

# تثبيت الحزم المطلوبة لامتدادات PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev unzip \
    && docker-php-ext-install pgsql pdo_pgsql

# تفعيل mod_rewrite
RUN a2enmod rewrite

# نسخ ملفات المشروع
COPY ./src /var/www/html

# تعيين مجلد العمل
WORKDIR /var/www/html
