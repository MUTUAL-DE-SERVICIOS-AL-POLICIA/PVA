# DOCKERIZING THE PROJECT

## Requirements

* [Docker](https://docs.docker.com/install/)
* Internet connection

## Despliegue del contendor Docker

* En el directorio raiz 
```sh
copy .env.example .env
```
* Configurar el archivo `.env`

* Construir la imagen si aun no existe

```sh
docker-compose build --no-cache
```
 * Levantar el proyecto

```sh
docker run -p 8181:80 --name pva --restart unless-stopped -v /PVA:/var/www/html -d muserpol/pva:1.4
```
* CAmbiar los permisos del archivo de instalación y ejecutar el script

```sh
chmod +x docs/docker/init.sh
docker exec pva /bin/bash -c /var/www/html/docs/docker/init.sh
```
* Ingreso al contenedor

```sh
docker exec -it < id_docker > /bin/bash
```