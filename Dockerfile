FROM php:8.2-apache

# Install mysqli
RUN docker-php-ext-install mysqli

# Optional: enable rewrite
RUN a2enmod rewrite

# Copy your PHP code
COPY ./ /var/www/html/
