#!/bin/sh

tail_logs() {
    while true; do
        php artisan --timeout=3600 pail
    done
}

start_app() {
    tail_logs &
    frankenphp php-server -r public/ --listen "$CHECKPOINT_HOST:$CHECKPOINT_PORT"
}

php artisan config:cache &&
php artisan migrate --force &&
php artisan app:docker-startup &&
start_app

