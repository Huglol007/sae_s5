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
ENV APP_ENV=prod
ENV DATABASE_URL="postgresql://hugo:password@127.0.0.1:5432/sae_5?serverVersion=14&charset=utf8"

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build
RUN php -r 'echo "APP_ENV=" . (getenv("APP_ENV") ?: "not set") . "\n";'


# Configuration d'Apache
RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

# Exposition du port
EXPOSE 80
CMD ["apache2-foreground"]
