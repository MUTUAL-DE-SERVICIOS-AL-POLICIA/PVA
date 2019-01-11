#!/usr/bin/env sh

# Migration
php artisan migrate

# Seeders
php artisan db:seed --class=EmployeePermissionSeeder