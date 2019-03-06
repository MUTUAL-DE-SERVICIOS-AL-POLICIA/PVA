#!/usr/bin/env sh

rm -rf public/js/app.js
yarn install --production --force
yarn prod
composer dump-autoload
php artisan vendor:publish
php artisan view:clear
php artisan route:clear
php artisan cache:clear
php artisan config:clear
php artisan clear-compiled

# Update notification
if type "notify-send" > /dev/null; then
  notify-send 'PVA RRHH MODULE' 'Updated'
fi