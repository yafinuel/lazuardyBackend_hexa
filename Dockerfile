FROM php:8.4-apache

# 1. Install dependencies + SUPERVISOR
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    supervisor \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Install & aktifkan ekstensi PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 3. Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# 4. Atur Document Root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Copy seluruh project
COPY . /var/www/html

# 6. Copy konfigurasi supervisor ke folder sistem Docker
COPY supervisor.conf /etc/supervisor/conf.d/laravel-worker.conf

# 7. Ambil Composer versi terbaru
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 8. Jalankan Composer install
RUN composer install --no-dev --optimize-autoloader --no-interaction

# TAMBAHKAN BARIS INI UNTUK MEMBUAT SYMLINK STORAGE
RUN php artisan storage:link

# 9. Atur permission folder storage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# 10. JALANKAN SUPERVISOR SEBAGAI UTAMA (Menggantikan default Apache)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/laravel-worker.conf"]