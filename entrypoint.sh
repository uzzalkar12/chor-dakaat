#!/bin/bash

if [ "$SERVICE_TYPE" = "reverb" ]; then
    echo "Starting Reverb..."
    php artisan reverb:start --host=0.0.0.0 --port=$PORT
elif [ "$SERVICE_TYPE" = "queue" ]; then
    echo "Starting Queue Worker..."
    php artisan queue:work --tries=3 --timeout=90
else
    echo "Starting Web Server..."
    # We run migrations here manually since pre-deploy is disabled
    php artisan migrate --force
    php artisan serve --host=0.0.0.0 --port=$PORT
fi
