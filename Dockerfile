FROM php:8.1-apache

# Set server name to suppress warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Install SQLite and PHP extensions
RUN apt-get update && apt-get install -y \
    libsqlite3-dev sqlite3 pkg-config \
    && docker-php-ext-install pdo pdo_sqlite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite module
RUN a2enmod rewrite

# Create directory for SQLite database
RUN mkdir -p /var/www/data \
    && chown -R www-data:www-data /var/www/data

# Copy Apache virtual host config
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Copy application files
COPY . /var/www/html/
COPY src/ /var/www/html/src/
COPY composer.json composer.lock /var/www/html/

# Fix permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
