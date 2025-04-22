#!/bin/sh

tail_logs() {
    while true; do
        php artisan --timeout=3600 pail
        sleep 2
    done
}

start_worker() {
    while true; do
        php artisan queue:work --queue=default
        sleep 2
    done
}

start_scheduler() {
    while true; do
        php artisan schedule:work
        sleep 2
    done
}

start_app() {
    tail_logs &
    start_worker &
    start_scheduler &
    frankenphp php-server -r public/ --listen "$CHECKPOINT_HOST:$CHECKPOINT_PORT"
}

php artisan config:cache &&
php artisan migrate --force &&
php artisan app:docker-startup &&
start_app

