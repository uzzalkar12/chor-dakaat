#!/bin/bash
set -e

# --- 1. Preparation ---
echo "Optimizing Laravel for Production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# --- 2. Database Migrations ---
# We run this in the entrypoint because Render Free Tier
# does not allow the 'preDeployCommand' property.
echo "Running migrations..."
php artisan migrate --force

# --- 3. Process Execution ---
# Check the SERVICE_TYPE env variable we defined in render.yaml
if [ "$SERVICE_TYPE" = "reverb" ]; then
    echo "Starting Laravel Reverb on port $PORT..."
    # Reverb must stay in the foreground to keep the container alive
    exec php artisan reverb:start --host=0.0.0.0 --port=$PORT

elif [ "$SERVICE_TYPE" = "queue" ]; then
    echo "Starting Queue Worker..."
    # If you had a separate worker, it would run this.
    # But on Free Tier, we usually bundle this.
    exec php artisan queue:work --tries=3 --timeout=90

else
    echo "Starting Web Server & Background Queue..."

    # Start the Queue Worker in the BACKGROUND
    # The '&' allows the script to continue to the next command
    php artisan queue:work --tries=3 --timeout=90 &

    # Start the Web Server in the FOREGROUND
    # This must be the last command to keep the container running
    echo "App is live on port $PORT"
    exec php artisan serve --host=0.0.0.0 --port=$PORT
fi
