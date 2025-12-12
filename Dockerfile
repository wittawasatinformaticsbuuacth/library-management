# ========================================================
# Dockerfile - ระบบจัดการห้องสมุด (PHP Web Application)
# ========================================================
# ภาพพื้นฐาน: PHP 8.1 พร้อม Apache Web Server
FROM php:8.1-apache

# ตั้งค่าข้อมูล Label สำหรับเอกสารภาพ
LABEL maintainer="Library Management Team"
LABEL description="Docker Image สำหรับระบบจัดการห้องสมุด (PHP 8.1 + Apache)"
LABEL version="1.0"

# ========================================================
# ติดตั้ง System Dependencies: ไลบรารี่ระบบ
# ========================================================
# อัพเดท Package Manager และติดตั้ง curl สำหรับการตรวจสอบสุขภาพ
RUN apt-get update && apt-get install -y \
    curl \
    && rm -rf /var/lib/apt/lists/*

# ========================================================
# ติดตั้ง PHP Extensions: เพิ่มเติมสำหรับ PHP
# ========================================================
# ติดตั้ง mysqli (MySQL Improved) extension สำหรับเชื่อมต่อฐานข้อมูล MySQL
# จำเป็นต้องมีในการเชื่อมต่อกับ MySQL database จากไฟล์ PHP
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# ========================================================
# ตั้งค่า Apache Web Server: ปรับแต่ง Apache
# ========================================================
# เปิดใช้งาน Apache mod_rewrite สำหรับ URL rewriting และ .htaccess
# อนุญาตให้มี Clean URLs และการ Routing ที่ถูกต้องในแอปพลิเคชัน
RUN a2enmod rewrite

# เปิดใช้งาน Apache mod_dir สำหรับการจัดการไดเร็กทอรี่
RUN a2enmod dir

# ========================================================
# ตั้งค่า Apache Directory Settings: การอนุญาตไฟล์
# ========================================================
# ตั้งค่า Apache เพื่ออนุญาต .htaccess files และ symbolic links
# อนุญาตให้แอปพลิเคชันใช้ .htaccess สำหรับ Routing และกฎความปลอดภัย
RUN echo '<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/docker-php.conf \
    && a2enconf docker-php

# เพิ่ม PHP Handler เพื่อให้ Apache รู้ว่าต้อง Process ไฟล์ .php ด้วย PHP
RUN echo '<FilesMatch "\.php$">\n\
    SetHandler "proxy:unix:/run/php/php-fpm.sock|fcgi://localhost"\n\
</FilesMatch>' >> /etc/apache2/conf-available/docker-php.conf \
    || echo 'SetHandler application/x-httpd-php' >> /etc/apache2/conf-available/docker-php.conf

# ========================================================
# ตั้งค่า Working Directory: โฟลเดอร์ทำงาน
# ========================================================
# กำหนดโฟลเดอร์ทำงานเริ่มต้นภายใน Container
# คำสั่งที่ตามมาทั้งหมดจะทำงานในโฟลเดอร์นี้
WORKDIR /var/www/html

# ========================================================
# ตั้งค่าสิทธิ์ File: สิทธิ์ไฟล์และโฟลเดอร์
# ========================================================
# เปลี่ยน Owner ของ /var/www/html เป็น www-data user และ group
# จำเป็นสำหรับให้ Apache อ่านและเขียนไฟล์ได้อย่างถูกต้อง
# ตั้งค่าสิทธิ์โฟลเดอร์เป็น 755 (rwxr-xr-x) สำหรับการเข้าถึงที่ถูกต้อง
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# ========================================================
# Health Check Configuration: การตรวจสอบสุขภาพ
# ========================================================
# ตรวจสอบว่า Apache ทำงานปกติทุก 30 วินาที
# หมดเวลาหลังจาก 5 วินาที ล้มเหลวเมื่อมีข้อผิดพลาด 3 ครั้ง
# ช่วยให้ Docker ตรวจสอบว่า Container ทำงานปกติหรือไม่
HEALTHCHECK --interval=30s --timeout=5s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

# ========================================================
# เปิด Port: พอร์ตที่ใช้งาน
# ========================================================
# เปิดพอร์ต 80 สำหรับ HTTP Traffic
# พอร์ตนี้จะถูกแมปใน docker-compose.yml ไปยังพอร์ต 8080
EXPOSE 80

# ========================================================
# Default Command: คำสั่งเริ่มต้น
# ========================================================
# เริ่ม Apache ในโหมด Foreground (จำเป็นสำหรับ Docker)
# รักษา Container ให้ทำงานต่อไป และสามารถเห็น Logs ได้
CMD ["apache2-foreground"]