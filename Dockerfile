# Utiliser l'image de base PHP 8.1 avec FPM pour Alpine Linux
FROM php:8.1-fpm-alpine

# Installer les outils de base sans cache pour réduire l'image finale
RUN apk add --no-cache \
    bash \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    freetype-dev \
    oniguruma-dev \
    postgresql-dev \
    zlib-dev

# Installer les extensions PHP nécessaires pour Symfony
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql pdo_pgsql mbstring gd

# Installer Composer (gestionnaire de dépendances PHP)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /usr/src/app

# Copier les fichiers de configuration Composer
COPY composer.json composer.lock ./

# Installer les dépendances PHP avec Composer
RUN composer install

# Copier tous les fichiers du projet Symfony
COPY . .

# Ajouter les répertoires bin et vendor/bin au PATH
ENV PATH="/usr/src/app/vendor/bin:bin:${PATH}"

# Donner les permissions sur le dossier de cache et logs de Symfony
RUN chown -R www-data:www-data /usr/src/app/var

# Commande par défaut pour lancer le serveur PHP-FPM
CMD ["php-fpm"]
