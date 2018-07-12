# RR.HH. SPA

## Install

* The project requires an LDAP server to authenticate users

```sh
cp .env.example .env
composer install
npm install
npm run dev
php artisan jwt:secret
php artisan vendor:publish --tag="adldap"
php artisan migrate:fresh --seed
```