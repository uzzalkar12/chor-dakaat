FROM serversideup/php:8.2-fpm-nginx

# Switch to root for system-level installs
USER root

# Install Node.js 20 LTS
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY --chown=www-data:www-data . .

# Pass build arguments for Vite
ARG VITE_REVERB_HOST
ARG VITE_REVERB_APP_KEY
ARG VITE_REVERB_SCHEME=https
ARG VITE_REVERB_PORT=443

# Install PHP & Node dependencies, build frontend assets
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN npm install && npm run build && rm -rf node_modules

# Fix storage and cache permissions so www-data can write at runtime
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy the reverb startup script — used ONLY by the reverb service
# This is a standalone script, NOT placed in entrypoint.d
COPY --chmod=755 entrypoint.sh /usr/local/bin/entrypoint.sh

# Drop back to non-root
USER www-data
