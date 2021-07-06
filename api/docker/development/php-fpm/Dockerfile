FROM php:7.4-fpm-alpine

#RUN echo -e "http://nl.alpinelinux.org/alpine/v3.14/main\nhttp://nl.alpinelinux.org/alpine/v3.14/community" > /etc/apk/repositories

RUN apk add --no-cache autoconf g++ make \
    && pecl install xdebug-3.0.1 \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable xdebug

#RUN apk add --no-cache unzip

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./common/php/conf.d /usr/local/etc/php/conf.d
COPY ./development/php/conf.d /usr/local/etc/php/conf.d

WORKDIR /app
