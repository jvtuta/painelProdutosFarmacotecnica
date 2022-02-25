FROM php:8.0-fpm

ARG user
ARG uid

ENV TZ="America/Sao_Paulo"

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./php/uploads.ini /usr/local/etc/php/conf.d/

COPY ./php/modules/interbase.so /usr/local/lib/php/extensions/no-debug-non-zts-20200930/

COPY ./php/modules/interbase.ini /usr/local/etc/php/conf.d/

RUN apt-get -y update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo_firebird

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN useradd -G www-data,root -u $uid -d /home/$user $user

RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

RUN docker-php-ext-install mbstring exif pcntl bcmath gd zip opcache

COPY ./php/modules/zip.ini /usr/local/etc/php/conf.d/

COPY ./php/modules/opcache.ini /usr/local/etc/php/conf.d/

WORKDIR /var/www

USER $user
