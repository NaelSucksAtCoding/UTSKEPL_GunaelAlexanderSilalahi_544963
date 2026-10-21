#!/bin/sh
set -e

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

chown -R www-data:www-data storage bootstrap/cache database

exec "$@"
