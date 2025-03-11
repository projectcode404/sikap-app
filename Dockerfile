# Build Stage: Install Composer Dependencies
FROM composer:latest AS builder
WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader

# Runtime Stage: FrankenPHP
FROM dunglas/frankenphp:latest

# Install PostgreSQL & Redis Extension
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && pecl install redis && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# FrankenPHP Configuration
ENV FRANKENPHP_ADDRESS=0.0.0.0:8080

# Set Working Directory
WORKDIR /app
COPY --from=builder /app /app

# Run Laravel Migrations Before Starting FrankenPHP
RUN chown -R www-data:www-data storage bootstrap/cache
USER www-data
CMD ["sh", "-c", "php artisan config:clear && php artisan cache:clear && php artisan migrate --force && frankenphp run"]
