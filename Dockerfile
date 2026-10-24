FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    unzip zip git curl \
    libicu-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install intl zip pdo_mysql bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

WORKDIR /app

COPY . .

RUN mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

RUN composer install --no-dev --optimize-autoloader

RUN rm -rf public/storage && ln -s /app/storage/app/public /app/public/storage

RUN npm install

RUN npm run build

EXPOSE 8080

CMD php artisan serve --host=0.0.0.0 --port=$PORT