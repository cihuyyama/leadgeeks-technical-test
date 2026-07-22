#!/bin/sh
set -eu

cd /app

mkdir -p \
  storage/framework/sessions \
  storage/framework/views \
  storage/framework/cache/data \
  storage/logs \
  storage/app/public \
  bootstrap/cache \
  database

chmod -R ug+rwx storage bootstrap/cache database 2>/dev/null || true

if [ ! -f database/database.sqlite ]; then
  touch database/database.sqlite
  chmod 664 database/database.sqlite 2>/dev/null || true
fi

if [ -z "${APP_KEY:-}" ]; then
  echo "Generating APP_KEY..."
  export APP_KEY="$(php -r "echo 'base64:'.base64_encode(random_bytes(32));")"
  echo "  APP_KEY generated for this container run"
fi

php artisan package:discover --ansi >/dev/null 2>&1 || true
php artisan migrate --force --no-interaction

if [ "${SEED_ON_START:-true}" = "true" ]; then
  HAS_USERS="$(php -r "
    require 'vendor/autoload.php';
    \$app = require 'bootstrap/app.php';
    \$app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap();
    echo App\\Models\\User::query()->count();
  " 2>/dev/null || echo 0)"
  if [ "${HAS_USERS:-0}" = "0" ]; then
    echo "Seeding demo data..."
    php artisan db:seed --force --no-interaction
  else
    echo "Demo data already present — skip seed"
  fi
fi

if [ "${APP_ENV:-production}" = "production" ] && [ "${APP_DEBUG:-false}" = "false" ]; then
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
else
  php artisan config:clear >/dev/null 2>&1 || true
fi

exec "$@"
