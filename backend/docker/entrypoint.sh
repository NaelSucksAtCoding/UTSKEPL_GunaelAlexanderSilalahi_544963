#!/bin/sh
set -e

if [ -z "$APP_KEY" ]; then
    echo "APP_KEY belum diatur. Berikan lewat environment variable (misalnya di berkas .env Docker Compose)." >&2
    exit 1
fi

# Siapkan file database jika memakai SQLite (default Laravel).
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ] && [ -z "$DB_DATABASE" ]; then
    touch database/database.sqlite
fi

# Migrasi dan seeder opsional, diaktifkan lewat environment variable.
if [ "$RUN_MIGRATIONS" = "true" ]; then
    php artisan migrate --force
fi

if [ "$RUN_SEEDER" = "true" ]; then
    php artisan db:seed --force
fi

# Cache konfigurasi dan route memakai environment variable container.
php artisan config:cache
php artisan route:cache

exec "$@"
