#!/bin/sh

php artisan config:cache &&
php artisan migrate --force &&
php artisan app:docker-startup &&
frankenphp php-server -r public/ --listen "$CHECKPOINT_HOST:$CHECKPOINT_PORT"
