FROM php:8.0-apache

# Install ekstensi PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Salin semua file proyek
COPY . /var/www/html/

# Install dependencies dari composer.json
WORKDIR /var/www/html
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Ubah permission folder proyek
RUN chown -R www-data:www-data /var/www/html

# Buat folder session dan set permission
RUN mkdir -p /var/www/html/application/cache && \
    chown -R www-data:www-data /var/www/html/application/cache

# Set konfigurasi PHP untuk session
RUN echo "session.save_path = \"/var/www/html/application/cache\"" \
    >> /usr/local/etc/php/conf.d/docker-php-session.ini

# ===============================
# Install Pentaho CLI dependencies
# ===============================
RUN apt-get update && \
    apt-get install -y bash unzip openjdk-11-jre wget && \
    apt-get clean

# Salin Pentaho PDI ZIP dan unzip
COPY pentaho/pdi.zip /opt/
WORKDIR /opt
RUN unzip pdi.zip -d /opt/ && rm pdi.zip

# Salin JDBC driver ke folder lib PDI
COPY pentaho/mysql-connector-j-8.0.33.jar /opt/data-integration/lib/

RUN chmod 644 /opt/data-integration/lib/mysql-connector-j-8.0.33.jar

# Tambah permission agar bisa eksekusi shell Pentaho
RUN chmod +x /opt/data-integration/*.sh

# Buat folder Pentaho dan Kettle (hindari error XML MetaStore & kettle.properties)
RUN mkdir -p /var/www/.pentaho/metastore && \
    mkdir -p /var/www/.kettle && \
    chown -R www-data:www-data /var/www/.pentaho /var/www/.kettle

# Set environment (optional but helpful)
ENV PENTAHO_HOME=/opt/data-integration
