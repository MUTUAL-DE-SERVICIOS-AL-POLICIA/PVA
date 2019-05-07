# PLATAFORMA VIRTUAL ADMINISTRATIVA

## Requirements

* (Optional) LDAP server to authenticate users
* PHP 7.1.22 (with `LDAP, GD, PGSQL, PDO_PGSQL, ICONV, MYSQL` modules enabled)
* Node.js 8.12.0
* NPM 6.4.1 or Yarn 1.9.4
* PostgreSQL 10.4

## Install

* Clone the project

```sh
git clone https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH.git
cd PVA-RRHH
git fetch --tags
latestVersion=$(git describe --tags `git rev-list --tags --max-count=1`)
git checkout $latestVersion
```

* Install dependencies

```sh
composer run-script post-root-package-install
composer install
yarn
```

* Editar el archivo `.env` de acuerdo a las variables de la base de datos a utilizar

* Generate keys and compile JS files

```sh
composer run-script post-create-project-cmd
php artisan jwt:secret
yarn prod
composer run-script post-autoload-dump
```

## Development

* To generate a fresh database

```sh
php artisan migrate:fresh
php artisan db:seed --class=DatabaseProductionSeeder
```

* To seed the database

```sh
php artisan migrate:fresh --seed
```

* To generate the documentation

```sh
php artisan api:generate --output="public/docs" --routePrefix="api/*" --actAsUserId=1
```

* To view the documentation unput in your web browser URL: [http://server:port/docs/](http://localhost:8888/docs/)