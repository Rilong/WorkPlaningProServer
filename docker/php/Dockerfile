FROM php:7.3.6-apache

RUN apt-get -y update && apt-get -y upgrade
RUN docker-php-ext-install pdo && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install bcmath