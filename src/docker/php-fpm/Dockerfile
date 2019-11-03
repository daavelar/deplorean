FROM php:7.3-fpm-alpine

RUN docker-php-ext-install mysqli pdo pdo_mysql bcmath pcntl

RUN apk --update add \
    alpine-sdk \
    openssl-dev \
    php7-pear \
    php7-dev \
    && rm -rf /var/cache/apk/*

RUN pecl install mongodb \
    && pecl clear-cache

RUN echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongo.ini

WORKDIR /var/www/html/
