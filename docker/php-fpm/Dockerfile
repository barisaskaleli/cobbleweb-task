FROM php:8.1-fpm-alpine as builder

COPY docker/php-fpm/php-ini-overrides.ini /usr/local/etc/php/conf.d/php-overrides.ini

# Install dependencies
RUN apk --update --no-cache add \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zlib-dev \
    libzip-dev

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2.0.9 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Install project dependencies
RUN composer install

FROM builder AS dev

FROM containerbuilder AS test

FROM containerbuilder AS prod