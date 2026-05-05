FROM php:8.3-cli-alpine

# ── php-extension-installer (handles all deps automatically) ──────────────────
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

# ── PHP extensions ────────────────────────────────────────────────────────────
RUN install-php-extensions \
    pdo pdo_mysql pdo_pgsql \
    mbstring xml tokenizer ctype fileinfo dom \
    zip gd bcmath intl opcache

# ── Runtime system tools ──────────────────────────────────────────────────────
RUN apk add --no-cache nodejs npm git curl

# ── Composer ──────────────────────────────────────────────────────────────────
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# ── PHP dependencies (cached layer) ──────────────────────────────────────────
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# ── JS dependencies ───────────────────────────────────────────────────────────
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
