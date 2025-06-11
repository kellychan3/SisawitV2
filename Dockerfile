FROM php:8.0-apache

# Install ekstensi PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# Salin semua file ke direktori kerja Apache
COPY . /var/www/html/

# Ubah permission folder proyek
RUN chown -R www-data:www-data /var/www/html

# Buat folder session dan set permission
RUN mkdir -p /var/www/html/application/cache && \
    chown -R www-data:www-data /var/www/html/application/cache

# Set konfigurasi PHP untuk session
RUN echo "session.save_path = \"/var/www/html/application/cache\"" \
    >> /usr/local/etc/php/conf.d/docker-php-session.ini
