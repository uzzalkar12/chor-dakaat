#!/bin/bash
set -e

echo "==> Running Laravel startup for SERVICE_TYPE=${SERVICE_TYPE}"

# --- 1. Laravel Optimization ---
echo "Caching config, routes, and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# --- 2. Database Migrations ---
echo "Running migrations..."
php artisan migrate --force

# --- 3. Branch by SERVICE_TYPE ---
if [ "$SERVICE_TYPE" = "reverb" ]; then
    echo "Starting Laravel Reverb on 0.0.0.0:$PORT..."
    # exec replaces the shell; Reverb stays in foreground as PID 1 for this container
    exec php artisan reverb:start --host=0.0.0.0 --port=$PORT --no-interaction

elif [ "$SERVICE_TYPE" = "web" ]; then
    echo "Starting queue worker in background..."
    # Run queue worker in background so nginx/php-fpm (started by S6) serves HTTP
    php artisan queue:work --tries=3 --timeout=90 --sleep=3 --max-time=3600 &

    echo "Web startup complete. Handing off to nginx+php-fpm via S6..."
    # Do NOT exec anything here — the image's S6 Overlay will start nginx + php-fpm
fi
