FROM php:8.3-fpm

ARG APP_ENV=local

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    zip unzip && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip && \
    pecl install redis && docker-php-ext-enable redis && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/backend

COPY . .

RUN if [ "$APP_ENV" = "production" ]; then \
    composer install --no-dev --optimize-autoloader; \
    else \
    composer install; \
    fi

RUN chown -R www-data:www-data /var/www/backend/storage /var/www/backend/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
