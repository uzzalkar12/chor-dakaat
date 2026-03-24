FROM serversideup/php:8.2-fpm-nginx

USER root

# Install Node.js 20 LTS
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install common Laravel + PostgreSQL PHP extensions
RUN install-php-extensions pdo_pgsql pgsql redis bcmath pcntl zip exif

WORKDIR /var/www/html

COPY --chown=www-data:www-data . .

ARG VITE_REVERB_HOST
ARG VITE_REVERB_APP_KEY
ARG VITE_REVERB_SCHEME=https
ARG VITE_REVERB_PORT=443

# --verbose so Render logs show the REAL error
RUN composer install --no-dev --optimize-autoloader --no-interaction --verbose

RUN npm install && npm run build && rm -rf node_modules

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY --chmod=755 entrypoint.sh /usr/local/bin/entrypoint.sh

USER www-data
