FROM php:8.2-apache

# 1. Install semua system dependencies dan ekstensi yang DIWAJIBKAN oleh Laravel & Composer
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Install & aktifkan ekstensi PHP (ditambah zip, bcmath, dan gd untuk token/payment)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 3. Aktifkan mod_rewrite Apache untuk routing API Laravel
RUN a2enmod rewrite

# 4. Atur Document Root Apache ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Copy seluruh project Laravel kamu ke dalam container
COPY . /var/www/html

# 6. Ambil Composer versi terbaru
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 7. Jalankan Composer install secara aman
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 8. Atur permission folder storage agar tidak error 500
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80