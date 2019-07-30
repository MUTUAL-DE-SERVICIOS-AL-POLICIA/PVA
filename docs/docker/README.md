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
cp -f docs/docker/docker-compose.yml laradock/
cp -f docs/docker/Dockerfile-Workspace laradock/workspace/Dockerfile
cp -f docs/docker/Dockerfile-PHP-FPM laradock/php-fpm/Dockerfile
cp -f docs/docker/env-example laradock/.env
```

* Move to laradock's path

```sh
cd laradock
```

* Modify `.env` file with desired data

* Up the compose with nginx server

```sh
docker-compose up -d --build nginx php-fpm
```

* Instead you can use apache2 server

```sh
docker-compose up -d --build apache2 php-fpm
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
docker-compose exec workspace composer run-script post-root-package-install
```

```sh
docker-compose exec workspace composer install
```

* To change the application to development mode you need to run

```sh
docker-compose exec workspace yarn dev
```

* Generate laravel's session and jwt auth keys

```sh
docker-compose exec workspace composer run-script post-create-project-cmd
```

* Modify `.env` file according to the right credentials

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