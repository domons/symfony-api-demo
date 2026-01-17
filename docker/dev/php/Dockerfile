FROM php:8.4-fpm

# Instalacja zależności PHP
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libicu-dev \
    libpq-dev \
    zip \
    unzip

# Konfiguracja rozszerzeń PHP
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    mbstring \
    intl

RUN ln -sf /usr/share/zoneinfo/Europe/Warsaw /etc/localtime

# Instalacja Composera
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data && chown -R www-data:www-data /var/www

WORKDIR /var/www/html
