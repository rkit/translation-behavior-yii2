FROM php:7.1-fpm

# --- SOFT --- #

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    git

# --- EXTENSIONS --- #

RUN docker-php-ext-install pdo_mysql && \
    docker-php-ext-install zip

# --- COMPOSER --- #

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

# --- DATA --- #

WORKDIR /var/www/html
