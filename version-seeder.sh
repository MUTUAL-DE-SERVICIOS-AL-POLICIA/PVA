#!/usr/bin/env sh

# Migration
php artisan migrate
php artisan db:seed --class=SupplyPermissionSeeder