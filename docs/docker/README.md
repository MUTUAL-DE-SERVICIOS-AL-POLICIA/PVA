# DOCKERIZING THE PROJECT

## Requirements

* [Docker](https://docs.docker.com/install/)
* Internet connection

## Docker deploy

* In the root project's path

copy .env
* Modify `.env` file with desired data

```sh
docker compose up -d --build
docker compose ps
docker run -p 80:80 --name pva -v /PVA:/var/www/html/public -d muserpol/pva:2.0
docker compose exec pva bash docs/docker/init.sh
docker compose exec -u root pva chown -R www-data:www-data \
  /var/www/html/public/storage /var/www/html/public/bootstrap/cache
docker ps
```
* Verify crontab status
```sh
service cron status

service cron start
```
