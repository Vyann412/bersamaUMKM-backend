# syntax=docker/dockerfile:1

################################################################################
# Stage 1: Install dependencies with Composer
################################################################################
FROM composer:lts as deps

WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

################################################################################
# Stage 2: Final runtime image
################################################################################
FROM php:8.4-apache as final

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required by Laravel
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Use production PHP configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Enable Apache mod_rewrite for Laravel routing
RUN a2enmod rewrite

# Configure Apache to use port 8080 (required for Render)
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf

# Configure Apache VirtualHost for Laravel
RUN echo '<VirtualHost *:8080>\n\
    ServerAdmin webmaster@localhost\n\
    DocumentRoot /var/www/html/public\n\
    \n\
    <Directory /var/www/html/public>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    \n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Copy vendor from deps stage
COPY --from=deps /app/vendor ./vendor

# Copy application files
COPY . .

# Set proper permissions (do this BEFORE switching user)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Run Laravel optimizations
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

# Switch to non-privileged user
USER www-data

# Expose port 8080
EXPOSE 8080

# Start Apache
CMD ["apache2-foreground"]
