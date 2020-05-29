FROM php:7.3.6-apache

RUN apt-get update && apt-get upgrade -y && apt-get install git-core -y && apt-get install -y zlib1g
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && mv composer.phar /usr/local/bin/composer && php -r "unlink('composer-setup.php');"

RUN set -eux; apt-get install -y libzip-dev zlib1g-dev libxml2-dev; docker-php-ext-install zip

RUN docker-php-ext-install pdo pdo_mysql bcmath dom

RUN a2enmod rewrite && service apache2 restart

WORKDIR /var/www/html
COPY . .

RUN composer install
EXPOSE 80