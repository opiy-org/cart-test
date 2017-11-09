#!/usr/bin/env bash
php artisan ide-helper:models -N
php artisan ide-helper:meta
php artisan ide-helper:generate
./phpunit
php artisan api:generate --routePrefix='api/*' --force
