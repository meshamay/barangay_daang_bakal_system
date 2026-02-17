### Multi-stage Dockerfile for Laravel (build assets + run PHP-FPM)

FROM node:18 AS node_builder
WORKDIR /app
COPY package*.json vite.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm ci --silent
RUN npm run build

FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip intl bcmath \
    && rm -rf /var/lib/apt/lists/*

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files and install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy application code
COPY . .

# Copy built frontend assets from node stage
COPY --from=node_builder /app/public/build /var/www/html/public/build

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build || true

EXPOSE 9000

CMD ["php-fpm"]
