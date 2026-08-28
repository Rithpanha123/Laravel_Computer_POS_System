FROM php:8.3-fpm-alpine

# ដំឡើង dependencies និង build tools ចាំបាច់ (រួមទាំង libzip-dev)
RUN apk add --no-cache \
    curl \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    postgresql-dev \
    oniguruma-dev \
    && docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip

# ដំឡើង Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# ដំឡើង vendor packages
RUN composer install --no-dev --optimize-autoloader

EXPOSE 9000
CMD ["php-fpm"]

