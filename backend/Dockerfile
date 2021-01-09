FROM php:7.4-cli as base

RUN apt-get update && apt-get install -y git libmcrypt-dev libpq-dev librabbitmq-dev libssl-dev libicu-dev libxslt-dev zip unzip wait-for-it \
    && pecl install amqp redis \
    && docker-php-ext-configure intl && docker-php-ext-install intl \
    && docker-php-ext-install pdo pdo_pgsql pcntl bcmath xsl \
    && docker-php-ext-enable amqp redis

RUN mkdir /app
WORKDIR /app

#=======================================================================================================================
FROM base as dev
RUN pecl install xdebug && docker-php-ext-enable xdebug

#=======================================================================================================================
FROM dev as cli
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#=======================================================================================================================
FROM cli as build
COPY . /app
RUN composer install

#=======================================================================================================================
FROM dev as worker-dev

#=======================================================================================================================
FROM php:7.4-fpm as fpm-base

RUN apt-get update && apt-get install -y git libmcrypt-dev libpq-dev librabbitmq-dev libssl-dev libicu-dev libxslt-dev zip unzip \
    && pecl install amqp redis \
    && docker-php-ext-configure intl && docker-php-ext-install intl \
    && docker-php-ext-install pdo pdo_pgsql pcntl bcmath xsl \
    && docker-php-ext-enable amqp redis

RUN mkdir /app
WORKDIR /app

#=======================================================================================================================
FROM fpm-base as fpm-dev
RUN pecl install xdebug && docker-php-ext-enable xdebug

#=======================================================================================================================
FROM fpm-base as fpm-prod
RUN  docker-php-ext-configure opcache --enable-opcache \
     && docker-php-ext-install opcache \
     && docker-php-ext-enable opcache
COPY --from=build /app /app

#=======================================================================================================================
FROM base as worker-prod
COPY --from=build /app /app