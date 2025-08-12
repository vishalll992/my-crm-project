FROM php:8.2-apache

# Copy project files from inventory/ to Apache root
COPY ./inventory /var/www/html/

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Expose port 80
EXPOSE 80
