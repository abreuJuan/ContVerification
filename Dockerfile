# Use the official PHP 7.3 base image
FROM php:7.3-apache

# Set the working directory
WORKDIR /var/www/html

# Update the package manager and install required extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    iputils-ping \
    default-mysql-client \
    vim \
    && docker-php-ext-install pdo_mysql zip 

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the Laravel application files
COPY . /var/www/html

# Install the application dependencies
RUN composer install --no-dev --optimize-autoloader

# Set file permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Expose port 80 and start Apache service
EXPOSE 80
CMD ["apache2-foreground"]
