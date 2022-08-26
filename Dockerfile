FROM php:7.4-fpm

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get update
RUN apt-get install -y git zip zlib1g-dev libzip-dev zip && docker-php-ext-install zip pdo pdo_mysql

WORKDIR /app
RUN chmod 777 -R /app
RUN chown -R www-data:www-data /app
COPY --chown=www-data:www-data . /app
