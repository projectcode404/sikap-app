# ===== BUILD STAGE =====
FROM composer:latest AS builder
WORKDIR /app

# Copy all project files
COPY . .

# ===== RUNTIME STAGE =====
FROM dunglas/frankenphp:latest

# Install PostgreSQL & Redis PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libpq-dev \
    libzip-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libxml2-dev \
    libonig-dev \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        gd \
        zip \
        dom \
        xml \
        mbstring \
        pdo \
        pdo_pgsql \
    && pecl install redis && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory
WORKDIR /app

# Copy application files
COPY --from=builder /app /app

# Install production dependencies
RUN composer install --no-dev --optimize-autoloader

# Ensure Laravel folders are writable
RUN mkdir -p bootstrap/cache storage/framework/cache/data && \
    chmod -R 775 bootstrap/cache storage && \
    chown -R www-data:www-data bootstrap/cache storage

# Run as www-data user
USER www-data

# FrankenPHP configuration
ENV FRANKENPHP_ADDRESS=0.0.0.0:8080

# Start Laravel and FrankenPHP
CMD ["sh", "-c", "php artisan config:clear && php artisan cache:clear && php artisan migrate --force && frankenphp run"]
