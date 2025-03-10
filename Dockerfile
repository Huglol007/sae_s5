FROM php:8.2-apache

# Installation des extensions PHP requises
RUN apt-get update && apt-get install -y libpng-dev zip unzip git curl \
    && docker-php-ext-install pdo pdo_mysql gd

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installation des dépendances PHP et Node.js
WORKDIR /var/www/html
COPY . .
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Configuration d'Apache
RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

# Exposition du port
EXPOSE 80
CMD ["apache2-foreground"]
