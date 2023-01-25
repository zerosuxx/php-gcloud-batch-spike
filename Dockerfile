FROM php:8.1

ARG COMPOSER_VERSION=2.5.1
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /tmp

RUN docker-php-ext-install pcntl sysvmsg sysvsem sysvshm

RUN apt-get update && apt-get install -y \
    procps \
    git \
    zip

RUN curl -o /usr/local/bin/composer -L https://github.com/composer/composer/releases/download/${COMPOSER_VERSION}/composer.phar \
    && chmod +x /usr/local/bin/composer