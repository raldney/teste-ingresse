#!/bin/sh

composer install
./artisan migrate 
./artisan db:seed 
service php7.2-fpm start && nginx -g "daemon off;"

