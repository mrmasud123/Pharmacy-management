#!/bin/sh
set -e

# Only run composer if vendor is empty (named volume, first boot)
if [ ! -f /var/www/vendor/autoload.php ]; then
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Generate key if not set
php artisan key:generate --no-interaction --force 2>/dev/null || true

# Run migrations (safe to repeat)
php artisan migrate --no-interaction --force 2>/dev/null || true

# Cache config/routes in non-local envs
if [ "$APP_ENV" != "local" ]; then
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

exec "$@"