FROM php:8-apache

RUN apt-get update && apt-get install --no-install-recommends -yq \
    build-essential \
    ssh \
    vim \
    wget \
    unzip \
    libmcrypt-dev \
    libicu-dev \
    libzip-dev \
    && apt-get clean

ENV PHP_EXTENSIONS opcache pdo_mysql pcntl intl zip

RUN docker-php-ext-install ${PHP_EXTENSIONS}

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin

RUN a2enmod rewrite

COPY docker/vhost.conf /etc/apache2/sites-enabled/000-default.conf
