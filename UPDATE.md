# Update

---

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