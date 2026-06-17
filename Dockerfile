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

# 3. Aktifkan mod_rewrite Apache & Hapus Modul MPM Event
RUN a2enmod rewrite && \
    a2dismod mpm_event || true && \
    a2enmod mpm_prefork

# 4. Atur Document Root ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Copy seluruh project (Pastikan sudah ada .dockerignore agar .env lokal tidak ikut)
COPY . /var/www/html

# 6. Copy konfigurasi MASTER supervisor ke jalur utama
COPY supervisor.conf /etc/supervisor/supervisord.conf

# 7. Ambil Composer versi terbaru
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 8. Jalankan Composer install khusus Production
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 9. Pancing file .env, Buat Symlink, dan Optimasi Caching
RUN cp .env.example .env && \
    php artisan storage:link && \
    php artisan route:cache && \
    php artisan view:cache

# 10. Atur permission menyeluruh agar diizinkan oleh user www-data milik Apache & Worker
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

# 11. JALANKAN SUPERVISOR UTAMA
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]