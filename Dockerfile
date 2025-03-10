FROM php:8.2-cli

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

# Définir les variables d'environnement
ENV APP_ENV=prod
ENV DATABASE_URL="postgresql://hugo:password@127.0.0.1:5432/sae_5?serverVersion=14&charset=utf8"

# Copier le projet
COPY . .

# Copier les fichiers .env nécessaires
COPY .env .env

# Installer les dépendances PHP sans le mode dev
RUN composer install --no-dev --optimize-autoloader

# Supprimer les fichiers .env si nécessaire (si vous voulez forcer l'utilisation de variables d'environnement Docker)
# RUN rm -f .env .env.local .env.dev .env.test

# Installer les dépendances front-end et construire les assets
RUN npm install && npm run build

# Exposer le port
EXPOSE 8000

# Commande par défaut
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
