#!/usr/bin/env sh

# Seeders
php artisan migrate

php artisan db:seed --class=DocumentTypeAdd2Seeder
php artisan db:seed --class=DepartureTypeSeeder
php artisan db:seed --class=DepartureReasonSeeder
php artisan db:seed --class=ContractTypeDepartureReasonSeeder
php artisan db:seed --class=RoleFinancieraSeeder