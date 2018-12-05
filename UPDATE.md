# Update

---

## From [1.3.1](https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH/tree/1.3.0) to [1.3.1](https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH/tree/1.4.0)

```sh
composer update --prefer-dist --no-dev
composer run-script version-seeder
```

## From [1.3.0](https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH/tree/1.3.0) to [1.3.1](https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH/tree/1.3.1)


```sh
composer update
php artisan vendor:publish
php artisan migrate
rm public/js/app.js
yarn prod && php artisan view:clear && php artisan config:cache
```

## From [1.2.0](https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH/tree/1.2.0) to [1.3.0](https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH/tree/1.3.0)

* Remove unnecesary `adldap` library

```sh
rm -rf vendor/adldap
```

* Remove cached files

```sh
composer update
php artisan vendor:publish
php artisan migrate
rm public/js/app.js
yarn prod && php artisan view:clear && php artisan config:cache
```

`Update names on employees table, split first name when more than one name and set second name, it can be null if an employee only have one fist name`

## From [1.1.0](https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH/tree/1.1.0) to [1.2.0](https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH/tree/1.2.0)

* Enable `iconv` plugin on file `php.ini`

* Remove cached files

```sh
composer install
php artisan vendor:publish
php artisan migrate
rm public/js/app.js
yarn prod && php artisan view:clear && php artisan config:cache
```

## From [1.0.1](https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH/tree/1.0.1) to [1.1.0](https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH/tree/1.1.0)

* Remove cached files

```sh
rm public/js/app.js
yarn prod && php artisan view:clear && php artisan config:cache
```

## From [1.0.0](https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH/tree/1.0.0) to [1.0.1](https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH/tree/1.0.1)

* Remove cached files

```sh
rm public/js/app.js
yarn prod && php artisan view:clear && php artisan config:cache
```