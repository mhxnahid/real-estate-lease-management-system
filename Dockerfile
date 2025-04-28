FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libjpeg-dev libpng-dev libfreetype6-dev libcurl4-openssl-dev \
    && docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && pecl install swoole \
    && docker-php-ext-enable swoole
# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mysqli mbstring exif pcntl bcmath zip

# Set PHP memory limit
# RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory-limit.ini

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Enable Apache modules
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Install Laravel Octane
# RUN composer require laravel/octane

# Set Octane memory limit
ENV OCTANE_MEMORY_LIMIT=-1

# Set Composer memory limit
ENV COMPOSER_MEMORY_LIMIT=-1

# RUN composer install

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Configure Apache for Laravel
RUN echo "<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>" > /etc/apache2/sites-available/000-default.conf

# Set ServerName
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Configure Octane to use Swoole
# RUN echo "APP_SERVER=swoole" >> .env