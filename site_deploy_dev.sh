#!/usr/bin/env bash
php ./phpunit
php artisan api:generate --routePrefix='api/*'
