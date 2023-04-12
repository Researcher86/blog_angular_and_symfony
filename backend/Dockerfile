FROM php:8.2-cli as base

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
FROM dev as worker-dev

#=======================================================================================================================
FROM dev as cli
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#=======================================================================================================================
FROM php:8.2-fpm as fpm-base

RUN apt-get update && apt-get install -y git libmcrypt-dev libpq-dev librabbitmq-dev libssl-dev libicu-dev libxslt-dev zip unzip \
    && pecl install amqp redis \
    && docker-php-ext-configure intl && docker-php-ext-install intl \
    && docker-php-ext-install pdo pdo_pgsql pcntl bcmath xsl \
    && docker-php-ext-enable amqp redis \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

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
COPY . /app
RUN rm -f -R z_coverage && \
    rm -f -R migrations && \
    rm -f -R src/DataFixtures && \
    rm -f -R tests && \
    rm -f -R var && mkdir var && chown www-data:www-data var && \
    rm -f  .php_cs.dist .php_cs.cache .env.local .env.test

#=======================================================================================================================
FROM base as worker-prod
COPY . /app
RUN rm -f -R z_coverage && \
    rm -f -R tests && \
    rm -f -R var && mkdir var && chown www-data:www-data var && \
    rm -f  .php_cs.dist .php_cs.cache .env.local .env.test