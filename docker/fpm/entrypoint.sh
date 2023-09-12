#!/bin/bash

/usr/local/bin/composer install -n --prefer-dist -o -d /code

/code/bin/console doctrine:migrations:migrate -n

mkdir -p /code/var/log /code/var/cache
chown -R www-data:www-data /code/var/cache /code/var/log -f

php-fpm
