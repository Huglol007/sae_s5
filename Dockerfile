FROM php:8.2-cli

WORKDIR /var/www/html

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libpng-dev libjpeg-dev libfreetype6-dev \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Définir les variables d'environnement directement
ENV APP_ENV=prod
ENV DATABASE_URL="postgresql://hugo:password@127.0.0.1:5432/sae_5?serverVersion=14&charset=utf8"

# Copier le projet
COPY . .

# Supprimer le fichier `.env` pour forcer Symfony à utiliser les variables d'environnement
RUN rm -f .env .env.local .env.dev .env.test

# Installer les dépendances PHP sans le mode dev
RUN composer install --no-dev --optimize-autoloader

# Construire les assets front-end
RUN npm install && npm run build

# Vérifier que Symfony CLI est installé et accessible
RUN symfony -v

# Exposer le port (si nécessaire)
EXPOSE 8000

# Commande par défaut
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
