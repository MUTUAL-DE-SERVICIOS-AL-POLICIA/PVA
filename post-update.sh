#!/usr/bin/env sh

rm -rf $HOME/.cache/composer/files/* vendor node_modules public/js/app.js
composer install
php artisan vendor:publish
php artisan migrate
yarn
yarn prod
php artisan view:clear
php artisan route:clear
php artisan cache:clear
php artisan config:clear
php artisan clear-compiled

php artisan db:seed --class=DepartureTypeSeeder
php artisan db:seed --class=DocumentTypeAdd2Seeder
php artisan db:seed --class=DepartureTypeSeeder
php artisan db:seed --class=DepartureReasonSeeder
php artisan db:seed --class=ContractTypeDepartureReasonSeeder

composer dump-autoload
notify-send "PVA RRHH MODULE" "Updated"