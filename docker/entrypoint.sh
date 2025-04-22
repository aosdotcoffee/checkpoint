#!/bin/sh

tail_logs() {
    while true; do
        php artisan --timeout=3600 pail
    done
}

start_worker() {
    while true; do
        php artisan queue:work --queue=default
    done
}

start_app() {
    tail_logs &
    start_worker &
    frankenphp php-server -r public/ --listen "$CHECKPOINT_HOST:$CHECKPOINT_PORT"
}

php artisan config:cache &&
php artisan migrate --force &&
php artisan app:docker-startup &&
start_app

