FROM php:7.3-alpine

RUN apk add --update supervisor \
    alpine-sdk \
    openssl-dev \
    php7-pear \
    php7-dev && rm  -rf /tmp/* /var/cache/apk/*

RUN docker-php-ext-install pcntl mysqli pdo pdo_mysql bcmath

ADD supervisord.conf /etc/

RUN mkdir /etc/supervisord.d/

ADD supervisord.d/* /etc/supervisord.d/

RUN pecl install mongodb \
    && pecl clear-cache

RUN echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongo.ini

RUN mkdir /var/log/supervisor/

WORKDIR /var/www/html

ENTRYPOINT ["supervisord", "--nodaemon", "--configuration", "/etc/supervisord.conf"]
