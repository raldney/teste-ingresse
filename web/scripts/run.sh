#!/bin/sh

composer dump-autoload
composer install
./artisan migrate 
./artisan db:seed 

