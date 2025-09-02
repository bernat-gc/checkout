FROM php:8.3-fpm-alpine

RUN apk add --no-cache bash

RUN docker-php-ext-install pdo_mysql 

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer