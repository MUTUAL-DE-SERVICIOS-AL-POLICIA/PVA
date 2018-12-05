#!/usr/bin/env sh

rm -rf public/js/app.js
yarn install --production --force
yarn prod
php artisan vendor:publish
php artisan view:clear
php artisan route:clear
php artisan cache:clear
php artisan config:clear
php artisan clear-compiled

# Update notification

if ! [ -x "$(notify-send 'PVA RRHH MODULE' 'Updated')" ]; then
  echo 'Error: notify-send is not allowed.' >&2
  exit 1
fi