FROM php:8.4-cli

WORKDIR /var/www/html

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libpng-dev libjpeg-dev libfreetype6-dev \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql

# Installer Node.js et npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier le fichier .env dans le conteneur
COPY .env .env

# Définir les variables d'environnement sans .env
ENV APP_ENV=prod
ENV DATABASE_URL="postgresql://hugo:EVPVvZ44qvj2q8vfHItSsCEls1x3Od5t@dpg-cv7vigt6l47c73f3c9u0-a.frankfurt-postgres.render.com:5432/sae_5_ecu8?sslmode=require"
ENV COMPOSER_ALLOW_SUPERUSER=1


# Copier les fichiers du projet (sans .env)
COPY . .

# Installer les dépendances PHP sans le mode dev
RUN composer install --no-dev --optimize-autoloader

# Forcer l'installation de symfony/runtime si nécessaire
RUN composer require symfony/runtime


# Installer les dépendances front-end et construire les assets
RUN npm install && npm run build

# Exposer le port
EXPOSE 8000

# Commande par défaut
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
