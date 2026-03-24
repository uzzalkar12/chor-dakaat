FROM serversideup/php:8.2-fpm-nginx

# Switch to root for install steps
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

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies and build assets
RUN npm install && npm run build

# Clean up node_modules after build (saves image space)
RUN rm -rf node_modules

# Copy startup script into entrypoint.d so it runs before nginx/php-fpm starts
# The image executes scripts in /etc/entrypoint.d alphabetically
COPY --chmod=755 etc/entrypoint.sh /etc/entrypoint.d/99-laravel-startup.sh

# Drop back to unprivileged user
USER www-data
