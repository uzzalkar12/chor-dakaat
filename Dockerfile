FROM serversideup/php:8.2-fpm-nginx

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY --chown=www-data:www-data . .

# Pass build arguments for Vite (Required for Reverb connections)
ARG VITE_REVERB_HOST
ARG VITE_REVERB_APP_KEY
ARG VITE_REVERB_SCHEME=https
ARG VITE_REVERB_PORT=443

# Install dependencies
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Copy and set permissions for entrypoint
COPY --chmod=755 entrypoint.sh /usr/local/bin/entrypoint.sh

# Set the entrypoint (This bypasses Render's startCommand restriction)
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
