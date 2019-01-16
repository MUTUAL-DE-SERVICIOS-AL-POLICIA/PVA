#!/usr/bin/env sh

# Migration
php artisan migrate

# Seeders
php artisan db:seed --class=EmployeePermissionSeeder
php artisan db:seed --class=EventualPermissionSeeder
php artisan db:seed --class=ConsultantPermissionSeeder
php artisan db:seed --class=ProcedureEventualPermissionSeeder
php artisan db:seed --class=ProcedureConsultantPermissionSeeder
php artisan db:seed --class=UserActionPermissionSeeder
php artisan db:seed --class=DeparturePermissionSeeder
php artisan db:seed --class=AdminPermissionSeeder