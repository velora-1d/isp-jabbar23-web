FROM php:8.4-cli-alpine

# Install system dependencies
RUN apk add --no-cache \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    postgresql-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy existing application directory contents
COPY . .

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port 8080 (Railway default)
EXPOSE 8080

# Command to run the application
CMD php artisan migrate --force && \
    php artisan optimize && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
