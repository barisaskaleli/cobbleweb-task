FROM php:8.1-fpm-alpine as builder

COPY docker/php-fpm/php-ini-overrides.ini /usr/local/etc/php/conf.d/php-overrides.ini

# Install dependencies
RUN apk --update --no-cache add \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zlib-dev \
    libzip-dev \
    cronie

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Copy the cron file
COPY bin/cronfile /cron-tasks

# Add a newline at the end of the file to avoid "premature EOF" error
RUN echo "" >> /cron-tasks

# Setup the cron log and apply cron job
RUN touch /var/log/cron.log && \
    chown www-data:www-data /var/log/cron.log && \
    /usr/bin/crontab -u www-data /cron-tasks

# Install Composer
COPY --from=composer:2.0.9 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Install project dependencies
RUN composer install

# Create necessary directories and set permissions
RUN mkdir -p /var/www/var/log && chown -R www-data:www-data /var/www/var

# Create a script to run both crond and php-fpm
RUN echo -e "#!/bin/sh\nchown -R www-data:www-data /var/www/var\ncrond\nexec php-fpm" > /usr/local/bin/start.sh && \
    chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]

FROM builder AS dev

# Replace containerbuilder with the correct image or builder
FROM containerbuilder AS test
FROM containerbuilder AS prod