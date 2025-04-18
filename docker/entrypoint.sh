#!/bin/sh

start_app() {
    php artisan pail &
    frankenphp php-server -r public/ --listen "$CHECKPOINT_HOST:$CHECKPOINT_PORT"
}

php artisan config:cache &&
php artisan migrate --force &&
php artisan app:docker-startup &&
start_app

