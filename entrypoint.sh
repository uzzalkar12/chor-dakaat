#!/bin/bash

# Run migrations (only once)
php artisan migrate --force

if [ "$SERVICE_TYPE" = "reverb" ]; then
    echo "Starting Reverb..."
    php artisan reverb:start --host=0.0.0.0 --port=$PORT
else
    echo "Starting Web + Queue..."
    # Start the queue worker in the background
    php artisan queue:work --tries=3 --timeout=90 &

    # Start the web server in the foreground
    php artisan serve --host=0.0.0.0 --port=$PORT
fi
