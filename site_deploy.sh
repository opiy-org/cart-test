#!/usr/bin/env bash
php artisan view:clear && php artisan route:clear && php artisan cache:clear && php artisan config:cache
php artisan key:generate
php artisan migrate
php artisan optimize
php artisan vendor:publish
php artisan view:clear && php artisan route:clear && php artisan cache:clear && php artisan config:cache
php artisan up


