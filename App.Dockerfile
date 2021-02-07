FROM php:7.3.9-fpm-alpine3.10

WORKDIR /var/www/html

RUN set -x && \
    docker-php-ext-install pdo pdo_mysql

COPY /php/php.ini /usr/local/etc/php/php.ini

RUN apk --update add --no-cache php7-bcmath
RUN apk add --no-cache libzip-dev git libjpeg-turbo-dev libpng-dev freetype-dev libwebp-dev && \
    docker-php-ext-configure zip --with-libzip=/usr/include && \
    docker-php-ext-install zip bcmath opcache

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-webp-dir=/usr/include/
RUN docker-php-ext-install gd exif

COPY --chown=www-data:www-data . .

USER www-data

EXPOSE 9000

ENTRYPOINT ["php-fpm"]
