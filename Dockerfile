FROM php:8.2-apache

# Install PHP extensions for CI4 + MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache rewrite (CI4 butuh ini)
RUN a2enmod rewrite

# Set DocumentRoot ke folder public CI4
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

# Copy source code
COPY . /var/www/html

# Set permission writable/
RUN chown -R www-data:www-data /var/www/html/writable

EXPOSE 8080
