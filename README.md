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
php artisan api:generate --output="public/docs" --routePrefix="api/*" --actAsUserId=1
```

* To view the documentation unput in your web browser URL: [http://server:port/docs/](http://localhost:8888/docs/)