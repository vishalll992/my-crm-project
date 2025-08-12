FROM php:8.2-apache

# Install mysqli and netcat (for waiting on MySQL)
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli \
    && apt-get update && apt-get install -y netcat-openbsd

# Copy project files to Apache's root
COPY . /var/www/html/

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Wait for MySQL before starting Apache
CMD bash -c "until nc -z mysql 3306; do echo 'Waiting for MySQL...'; sleep 2; done && apache2-foreground"
