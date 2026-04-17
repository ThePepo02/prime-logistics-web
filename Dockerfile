# ============================================
# STAGE 1: Build del frontend (Vue + Vite)
# ============================================
FROM node:20-alpine AS frontend-build

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

# ============================================
# STAGE 2: Imagen final con PHP + NGINX
# ============================================
FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    unzip \
    git \
    oniguruma-dev \
    autoconf \
    g++ \
    make

RUN docker-php-ext-install pdo mbstring bcmath pcntl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .
COPY --from=frontend-build /app/public/build ./public/build

RUN git config --global --add safe.directory /var/www/html && \
    rm -f composer.lock && \
    composer install --no-dev --optimize-autoloader --ignore-platform-reqs

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]
