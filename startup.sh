#!/bin/sh
set -e

# ── Auto-generate APP_KEY if missing or missing the base64: prefix ────────────
if [ -z "$APP_KEY" ] || ! echo "$APP_KEY" | grep -q "^base64:"; then
    echo "[startup] APP_KEY missing or malformed — generating a new one..."
    # Create a minimal .env so artisan can write the key
    echo "APP_KEY=" > /app/.env
    php artisan key:generate --force
    # Export it into the current shell environment
    export APP_KEY=$(grep "^APP_KEY=" /app/.env | cut -d '=' -f2-)
    rm -f /app/.env
    echo "[startup] APP_KEY generated: ${APP_KEY:0:20}..."
fi

# ── Cache configuration ───────────────────────────────────────────────────────
echo "[startup] Caching config, routes and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ── Database migrations ───────────────────────────────────────────────────────
echo "[startup] Running migrations..."
php artisan migrate --force

# ── Storage symlink ───────────────────────────────────────────────────────────
php artisan storage:link 2>/dev/null || true

# ── Start PHP built-in server ─────────────────────────────────────────────────
echo "[startup] Starting server on port ${PORT:-8080}..."
exec php -S 0.0.0.0:${PORT:-8080} -t public
