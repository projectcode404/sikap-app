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

# RUN chown -R www-data:www-data storage bootstrap/cache

RUN mkdir -p storage bootstrap/cache && \
    chmod -R 777 storage bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache

# Run as User www-data
USER www-data

# Migrate & start FrankenPHP
CMD ["sh", "-c", "php artisan config:clear && php artisan cache:clear && php artisan migrate --force && frankenphp run"]
