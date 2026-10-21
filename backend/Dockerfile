# Image backend Laravel (PHP 8.3 + Apache).
# Tidak ada nilai rahasia di file ini: APP_KEY, DB_PASSWORD, dll. diberikan
# lewat environment variable saat container dijalankan.
FROM php:8.3.35-apache-bookworm

# Ekstensi PHP yang dibutuhkan dan mod_rewrite untuk routing Laravel.
RUN apt-get update \
    && apt-get install -y --no-install-recommends unzip \
    && docker-php-ext-install pdo_mysql opcache \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.9.2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Salin composer.json dan composer.lock lebih dulu, lalu pasang dependency.
# Lapisan ini hanya dibangun ulang jika kedua file tersebut berubah,
# sehingga perubahan kode aplikasi tidak memicu composer install lagi.
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-progress --no-scripts --no-autoloader

# Baru setelah itu salin kode aplikasi.
COPY . .
RUN composer dump-autoload --no-dev --optimize \
    && chown -R www-data:www-data storage bootstrap/cache database

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint && chmod +x /usr/local/bin/entrypoint

ENV APP_ENV=production \
    APP_DEBUG=false \
    APP_LOCALE=id \
    LOG_CHANNEL=stderr

EXPOSE 80

ENTRYPOINT ["entrypoint"]
CMD ["apache2-foreground"]
