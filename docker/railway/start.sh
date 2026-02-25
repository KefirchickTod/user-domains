#!/bin/bash
set -e

# ─── Port ─────────────────────────────────────────────────────────────────────
PORT=${PORT:-8080}
sed -i "s/RAILWAY_PORT/${PORT}/g" /etc/nginx/sites-available/default

echo ">>> Starting on port ${PORT}"

# ─── Laravel bootstrap ────────────────────────────────────────────────────────
cd /var/www

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Run migrations (idempotent)
php artisan migrate --force --no-interaction

echo ">>> Migrations done"

# ─── Start services ───────────────────────────────────────────────────────────
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
