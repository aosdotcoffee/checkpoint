FROM dunglas/frankenphp:1-php8.3-alpine

RUN install-php-extensions \
    pcntl \
    sqlite3 \
    pdo_mysql \
    pdo_sqlite \
    gd \
    intl \
    zip \
    opcache

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY --from=composer /usr/bin/composer /usr/bin/composer
ENV FRANKENPHP_CONFIG="worker ./public/index.php"
WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader --classmap-authoritative
RUN php artisan vendor:publish --tag="livewire:assets"
RUN php artisan optimize

ENV CHECKPOINT_HOST=localhost
ENV CHECKPOINT_PORT=8080

ENV SERVER_NAME=:${CHECKPOINT_PORT}
ENTRYPOINT ["/app/docker/entrypoint.sh"]
