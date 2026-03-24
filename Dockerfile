FROM serversideup/php:8.3-fpm-nginx

WORKDIR /var/www/html
COPY --chown=www-data:www-data . .

RUN composer install --no-dev --optimize-autoloader

# --- ADD THESE LINES SO VITE WORKS ---
ARG VITE_REVERB_HOST
ARG VITE_REVERB_APP_KEY
ARG VITE_REVERB_SCHEME
ARG VITE_REVERB_PORT
# -------------------------------------

RUN npm install && npm run build

RUN chmod -R 775 storage bootstrap/cache
