#!/bin/bash
set -e

# Fix permissions at runtime just in case
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# This entrypoint is only called by the Reverb service container.
# The web service uses the image's native S6/AUTORUN system instead.

echo "==> Reverb container starting..."

echo "Caching Laravel config..."
php artisan config:cache
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

echo "Starting Laravel Reverb on 0.0.0.0:${PORT}..."
exec php artisan reverb:start --host=0.0.0.0 --port="${PORT}" --no-interaction
