# DOCKERIZING THE PROJECT

## Requirements

* [Docker](https://docs.docker.com/install/)
* Internet connection

## Docker deploy

* In the root project's path

copy .env
* Modify `.env` file with desired data

```sh
docker run -p 80:80 --name pva -v /PVA:/var/www/html/public -d muserpol/pva:1.1
chmod +x docs/docker/init.sh
docker exec pva /bin/sh -c /var/www/html/public/docs/docker/init.sh
```
* Enter the container
```sh
docker exec -it < id_docker > /bin/sh
```
* Edit the cron service with the secon line
```sh
crontab -e

* * * * * php /var/www/html/public/artisan schedule:run >> /dev/null 2>&1
```
* Verify crontab status
```sh
service cron status

service cron start
```
