FROM php:8.0-fpm

ARG uid
ARG user

ENV TZ="America/Sao_Paulo"

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./assets/uploads.ini /usr/local/etc/php/conf.d/

COPY ./assets/modules/interbase.so /usr/local/lib/php/extensions/no-debug-non-zts-20200930/

COPY ./assets/modules/interbase.ini /usr/local/etc/php/conf.d/



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


RUN docker-php-ext-install mbstring exif pcntl bcmath gd zip

COPY ./assets/modules/zip.ini /usr/local/etc/php/conf.d/

WORKDIR /var/www

USER $user
