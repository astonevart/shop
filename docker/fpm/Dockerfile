FROM php:8.1-fpm

ENV APP_ENV=dev

RUN apt-get update \
    && apt-get install -y \
        sudo libcurl4-openssl-dev curl git netcat-traditional zip unzip libzip-dev \
        apt-utils libpq-dev \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /var/cache/apt/archives/*; \
    docker-php-ext-install pdo_mysql opcache zip

RUN echo "realpath_cache_size=4096k" >> /usr/local/etc/php/conf.d/zzz.ini; \
    echo "realpath_cache_ttl=7200" >> /usr/local/etc/php/conf.d/zzz.ini; \
    echo "error_reporting = E_ALL & ~E_NOTICE & ~E_DEPRECATED" >> /usr/local/etc/php/conf.d/zzz.ini; \
    echo "date.timezone=Europe/Kiev" >> /usr/local/etc/php/conf.d/zzz.ini; \
    echo "max_execution_time=1800" >> /usr/local/etc/php/conf.d/zzz.ini; \
    echo "memory_limit=8G" >> /usr/local/etc/php/conf.d/zzz.ini;

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer:2.5.1 /usr/bin/composer /usr/local/bin/composer

WORKDIR /code/
VOLUME /code
COPY . ./

RUN mkdir -p /code/var; \
    rm -rf /code/var/*; \
    /usr/local/bin/composer install --no-interaction --no-scripts --prefer-dist --optimize-autoloader

ENTRYPOINT /code/docker/fpm/entrypoint.sh
