# RR.HH. SPA

## Install

* The project requires an LDAP server to authenticate users

```sh
cp .env.example .env
composer install
npm install
npm run dev
php artisan vendor:publish
php artisan key:generate
php artisan jwt:secret
php artisan migrate:fresh --seed
php artisan api:generate --routePrefix="api/v1/*"
```