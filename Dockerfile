FROM php:8.3-cli-alpine

# ── System dependencies ───────────────────────────────────────────────────────
RUN apk add --no-cache \
    nodejs npm git curl \
    libpng-dev libzip-dev zip unzip \
    libxml2-dev oniguruma-dev \
    postgresql-dev \
    freetype-dev libjpeg-turbo-dev

# ── PHP extensions ────────────────────────────────────────────────────────────
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo pdo_mysql pdo_pgsql \
        mbstring xml tokenizer ctype fileinfo dom \
        zip gd bcmath intl opcache

# ── Composer ──────────────────────────────────────────────────────────────────
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# ── PHP dependencies (cached layer) ──────────────────────────────────────────
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# ── JS dependencies & build ───────────────────────────────────────────────────
COPY package.json package-lock.json ./
RUN npm ci

# ── Copy application source ───────────────────────────────────────────────────
COPY . .

# ── Build frontend assets ─────────────────────────────────────────────────────
RUN npm run build

# ── Composer post-install scripts ────────────────────────────────────────────
RUN composer dump-autoload --optimize

# ── Storage & cache directories ───────────────────────────────────────────────
RUN mkdir -p storage/logs \
        storage/framework/sessions \
        storage/framework/views \
        storage/framework/cache \
        bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8080

# ── Runtime: cache config, run migrations, serve ─────────────────────────────
CMD ["sh", "-c", "php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan migrate --force && php artisan storage:link && php -S 0.0.0.0:${PORT:-8080} -t public"]
