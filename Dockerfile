FROM php:8.4-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
    bash \
    git \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    zlib-dev \
    oniguruma-dev \
    curl-dev \
    shadow \
    autoconf \
    gcc \
    g++ \
    make \
    musl-dev

RUN docker-php-ext-install \
    zip \
    pdo \
    pdo_mysql \
    mbstring \
    fileinfo \
    xml \
    dom \
    curl

RUN pecl install redis \
    && docker-php-ext-enable redis

COPY --from=node:lts-alpine /usr/local/ /usr/local/

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

EXPOSE 9000

CMD ["php-fpm"]
