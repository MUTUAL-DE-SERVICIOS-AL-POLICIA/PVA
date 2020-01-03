#!/usr/bin/env bash

APP_ENV=$(grep APP_ENV .env | cut -d '=' -f2)
PROD="production"

rm -rf public/js/app.js
if [[ "$APP_ENV" == "$PROD" ]]; then
    yarn install --production --force
    yarn prod
else
    yarn install
    yarn dev
fi
composer dump-autoload
php artisan vendor:publish
php artisan view:clear
php artisan route:clear
php artisan cache:clear
php artisan config:clear
php artisan clear-compiled

# Update notification
if type "notify-send" &> /dev/null; then
  notify-send 'PVA RRHH MODULE' 'Updated'
fi

echo -e "\n\e[92mUpdated successfully\n"