FROM php:8.1-apache

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_sqlite

COPY . /var/www/html/
WORKDIR /var/www/html/
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
