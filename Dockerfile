FROM php:8.4-apache

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y git unzip libicu-dev \
    && docker-php-ext-install intl pdo_mysql \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
