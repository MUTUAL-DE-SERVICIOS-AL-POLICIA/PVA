# PLATAFORMA VIRTUAL ADMINISTRATIVA

## Requirements

* (Optional) LDAP server to authenticate users
* PHP 7.1.22 (with `LDAP, GD, PGSQL, PDO_PGSQL, MBSTRING, MYSQL, XML, ZIP` modules enabled)
* Node.js 8.12.0
* NPM 6.4.1 or Yarn 1.9.4
* PostgreSQL 10.4

## Install

* Roboto fonts support (Based on Ubuntu 16.04 distro)

```sh
sudo apt update
sudo apt install fonts-roboto fonts-roboto-fontface unzip fontconfig
cd /tmp
wget -O RobotoMono.zip https://fonts.google.com/download\?family\=Roboto%20Mono
sudo mkdir /usr/share/fonts/googlefonts
sudo unzip -d /usr/share/fonts/googlefonts /tmp/RobotoMono.zip
sudo chmod -R --reference=/usr/share/fonts/googlefonts /usr/share/fonts/googlefonts
sudo fc-cache -fv
fc-match "Roboto Mono"
```

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