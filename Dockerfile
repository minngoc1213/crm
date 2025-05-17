# Dockerfile
FROM php:8.2-apache

# Cài extensions cần thiết
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libonig-dev libxml2-dev zip unzip git libzip-dev mariadb-client \
    && docker-php-ext-install pdo_mysql mysqli gd zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy source code vào container
COPY ./src /var/www/html/

# Set quyền thư mục
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose cổng
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
