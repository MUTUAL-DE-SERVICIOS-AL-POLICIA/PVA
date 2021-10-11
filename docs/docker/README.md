# DOCKERIZING THE PROJECT

## Requirements

* [Docker](https://docs.docker.com/install/)
* Internet connection

## Docker deploy

* In the root project's path

copy .env
* Modify `.env` file with desired data

```sh
docker run -p 80:80 -v .:/var/www/html/public muserpol/pva:1.1
docker exec id_docker /bin/sh -c /var/www/html/public/docs/docker/init.sh
```

