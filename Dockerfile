FROM node:20-slim AS pnpm-base
ENV PNPM_HOME="/pnpm"
ENV PATH="$PNPM_HOME:$PATH"
RUN corepack enable
COPY . /app
WORKDIR /app

FROM pnpm-base AS prod-deps
RUN --mount=type=cache,id=pnpm,target=/pnpm/store pnpm install --prod --frozen-lockfile

FROM pnpm-base AS build
RUN --mount=type=cache,id=pnpm,target=/pnpm/store pnpm install --frozen-lockfile
RUN pnpm run build

FROM dunglas/frankenphp:1-php8.3-alpine AS php-base

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
COPY --from=prod-deps /app/node_modules /app/node_modules
COPY --from=build /app/public/build /app/public/build
RUN composer install --no-dev --optimize-autoloader --classmap-authoritative
RUN php artisan vendor:publish --tag="livewire:assets"
RUN php artisan optimize

ENV CHECKPOINT_HOST=localhost
ENV CHECKPOINT_PORT=8080

ENV SERVER_NAME=:${CHECKPOINT_PORT}
ENTRYPOINT ["/app/docker/entrypoint.sh"]
