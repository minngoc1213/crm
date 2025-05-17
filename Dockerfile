# Dockerfile
FROM php:8.2-apache

# Cài extensions cần thiết
RUN apt-get update && apt-get install -y \
    libpq-dev libpng-dev libjpeg-dev libonig-dev libxml2-dev zip unzip git libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql gd zip exif

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set PHP configuration values
RUN { \
    echo 'max_execution_time = 180'; \
    echo 'max_input_time = 180'; \
    echo 'memory_limit = 256M'; \
    echo 'post_max_size = 20M'; \
    echo 'upload_max_filesize = 20M'; \
} > /usr/local/etc/php/conf.d/espocrm-recommended.ini

# Copy source code vào container
COPY ./src /var/www/html/

# Set quyền thư mục
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose cổng
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
