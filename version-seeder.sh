#!/usr/bin/env sh

# Seeders
php artisan db:seed --class=RoleFinancieraSeeder

php artisan migrate