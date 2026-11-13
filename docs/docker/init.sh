cd /var/www/html/public
composer run-script post-root-package-install
composer install --no-scripts
yarn install
yes|composer run-script post-create-project-cmd
yarn prod
composer run-script post-autoload-dump
chown www-data -R /var/www/html/public
nginx -s reload
