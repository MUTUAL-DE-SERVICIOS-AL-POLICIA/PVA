#!/usr/bin/env sh

# Migration
php artisan migrate

php artisan db:seed --class=RoleRRHHSeeder
php artisan db:seed --class=RoleJuridicaSeeder
php artisan db:seed --class=RoleAlmacenesSeeder
php artisan db:seed --class=RoleFinancieraSeeder
php artisan db:seed --class=TruncateUserPermissionSeeder