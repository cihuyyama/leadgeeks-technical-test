# Minimal multi-stage Laravel 13 + Vue/Inertia (SQLite). Runtime has no Node.

# ─── Stage 1: PHP deps + frontend build ──────────────────────────────────────
FROM php:8.4-cli-alpine AS builder

COPY --from=mlocati/php-extension-installer:2 /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache nodejs npm git unzip \
    && install-php-extensions pdo_sqlite mbstring zip bcmath opcache pcntl intl

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --prefer-dist \
        --no-interaction \
        --no-progress \
        --no-scripts \
        --optimize-autoloader

COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund

COPY . .

ENV APP_ENV=production \
    APP_KEY=base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA= \
    DB_CONNECTION=sqlite

RUN mkdir -p database \
        storage/framework/sessions \
        storage/framework/views \
        storage/framework/cache/data \
        storage/logs \
        bootstrap/cache \
    && touch database/database.sqlite \
    && composer dump-autoload --optimize --no-dev --no-interaction \
    && php artisan package:discover --ansi \
    && npm run build \
    && rm -rf node_modules /root/.npm /tmp/*

# ─── Stage 2: slim runtime (no node/npm/git) ─────────────────────────────────
FROM php:8.4-cli-alpine AS runtime

COPY --from=mlocati/php-extension-installer:2 /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions pdo_sqlite mbstring zip bcmath opcache pcntl intl \
    && rm -f /usr/local/bin/install-php-extensions \
    && rm -rf /tmp/* /var/cache/apk/*

WORKDIR /app

COPY --from=builder --chown=www-data:www-data /app /app
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh \
    && mkdir -p \
        storage/framework/sessions \
        storage/framework/views \
        storage/framework/cache/data \
        storage/logs \
        storage/app/public \
        bootstrap/cache \
        database \
    && chown -R www-data:www-data storage bootstrap/cache database \
    && chmod -R ug+rwx storage bootstrap/cache database

ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    DB_CONNECTION=sqlite \
    SESSION_DRIVER=database \
    CACHE_STORE=database \
    QUEUE_CONNECTION=database \
    SEED_ON_START=true \
    PORT=8080

EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
# PORT overridable (local 8080, VPS often 3002)
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
