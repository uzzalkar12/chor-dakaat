FROM serversideup/php:8.2-fpm-nginx

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY --chown=www-data:www-data . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Compile assets
RUN npm install && npm run build

# Set permissions
RUN chmod -R 775 storage bootstrap/cache
