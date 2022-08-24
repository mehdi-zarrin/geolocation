FROM php:7.4-fpm

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get update
RUN apt-get install -y git zip zlib1g-dev libzip-dev zip && docker-php-ext-install zip pdo pdo_mysql

USER www-data
ENV WARMUP_COMMAND="bin/console cache:warmup --quiet"
COPY --chown=www-data:www-data . /app
RUN composer install --no-dev --no-progress --prefer-dist --no-interaction
