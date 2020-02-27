# DOCKERIZING THE PROJECT

## Requirements

* [Docker](https://docs.docker.com/install/)
* [Docker Compose](https://docs.docker.com/compose/install/)
* Internet connection

## Configuration

* Within the root project's path, clone laradock submodule

```sh
git submodule update --init --recursive
```

* Copy the modified files to laradock folder

```sh
cp -f docs/docker/php-fpm/Dockerfile laradock/php-fpm/
cp -f docs/docker/docker-compose.yml laradock/
cp -f docs/docker/env-example laradock/.env
```

* Move to laradock's path

```sh
cd laradock
```

* Modify `.env` file with desired data

* Build the images

```sh
docker-compose up build --parallel nginx php-fpm workspace postgres
```

* Up the compose with nginx server

```sh
docker-compose up -d nginx php-fpm workspace postgres
```

## Install project dependencies

* Within the laradock's path verify which containers are running

```sh
docker-compose ps
```

* Install needed fonts into `php-fpm` container

```sh
docker-compose exec php-fpm /var/www/install-roboto-fonts.sh
```

* Within the container called `workspace` you need to run

```sh
docker-compose exec --user laradock workspace composer run-script post-root-package-install
```

* Generate laravel's session and jwt auth keys

```sh
docker-compose exec --user laradock workspace composer run-script post-create-project-cmd
```

* Modify `.env` file according to the right credentials and install the dependencies

```sh
docker-compose exec --user laradock workspace composer install
```

* To change the application to development mode you need to run

```sh
docker-compose exec --user laradock workspace yarn dev
```

## Issues

* If you have error console output you can verify `exited` containers

```sh
docker ps -a
```

* And remove every unused container

```sh
docker rm every_unused_container
```

* And finally erase all unused containers, builded images, unused networks and volumes

```sh
docker container prune
docker image prune -a
docker network prune
docker volume prune
```

* Then you can build images and recreate containers from scratch