FROM php:8.3-apache

# Installer dépendances système et extensions PHP nécessaires à Laravel
RUN apt-get clean && rm -rf /var/lib/apt/lists/* \
    && apt-get update --fix-missing \
    && apt-get install -y --no-install-recommends \
       git unzip libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev curl libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && rm -rf /var/lib/apt/lists/*

# Activer mod_rewrite pour Laravel
RUN a2enmod rewrite

# Installer Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier projet
COPY . .

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache || true

# Exposer le port HTTP
EXPOSE 8000

# Ne pas lancer composer install ni artisan migrate ici !
CMD ["apache2-foreground"]
