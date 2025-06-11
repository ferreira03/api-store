# Use PHP 8.1 FPM as base image
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Create necessary directories
RUN mkdir -p /var/www/html/public \
    /var/www/html/src \
    /var/www/html/config \
    /var/www/html/var \
    /var/www/html/vendor

# Set permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html/public
RUN chmod -R 777 /var/www/html/var

# Expose port 9000
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
