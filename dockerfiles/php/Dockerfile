FROM php:7.3-fpm AS base

ARG DEBUG_TOOLS

RUN docker-php-ext-install pdo_mysql mbstring

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        git \
        zip \
        unzip \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install exif

RUN test -z "$DEBUG_TOOLS" || ( \
        pecl install xdebug-3.1.6 \
            && docker-php-ext-enable xdebug \
            && echo "xdebug.discover_client_host=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
            && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini \
            && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini \
            && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/xdebug.ini \
            && echo "xdebug.idekey=VSCODE" >> /usr/local/etc/php/conf.d/xdebug.ini \
            && echo "log_errors = On" >> /usr/local/etc/php/conf.d/error_log.ini \
            && echo "error_log = /dev/stderr" >> /usr/local/etc/php/conf.d/error_log.ini \
    )

RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer

# ================================================

FROM base AS prod

RUN apt-get update && apt-get install -y \
         nginx \
     && rm -rf /var/lib/apt/lists/*

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY dockerfiles/php/cnpg.conf /etc/nginx/sites-available/default
RUN sed -i 's/fastcgi_pass insights_php:9000/fastcgi_pass 127.0.0.1:9000/g' /etc/nginx/sites-available/default

COPY dockerfiles/php/start.sh /bin/start.sh
CMD /bin/start.sh

# ================================================

FROM prod AS prod_sources

COPY --chown=www-data:www-data . /var/www/html

RUN composer install --no-progress --no-interaction --no-dev --prefer-dist && composer clear-cache -n

RUN php artisan storage:link
