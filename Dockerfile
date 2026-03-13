FROM chilio/laravel-dusk-ci:php-7.4

ARG PORT=80
ENV PORT=${PORT}
EXPOSE ${PORT}

ENV DEBIAN_FRONTEND=noninteractive
ENV COMPOSER_PROCESS_TIMEOUT=600
ENV NODE_OPTIONS=--openssl-legacy-provider

RUN rm -f /etc/apt/sources.list.d/yarn.list /etc/apt/sources.list.d/*yarn* || true

RUN apt-get update && apt-get install -y --no-install-recommends \
    ca-certificates curl gnupg dirmngr apt-transport-https \
    unzip wget nano git \
    cron supervisor \
    gcc g++ make autoconf pkg-config \
    php7.4-dev php-pear \
    unixodbc unixodbc-dev \
 && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /usr/share/keyrings \
 && curl -fsSL https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor -o /usr/share/keyrings/yarn.gpg \
 && echo "deb [signed-by=/usr/share/keyrings/yarn.gpg] https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
 && apt-get update && apt-get install -y --no-install-recommends yarn \
 && rm -rf /var/lib/apt/lists/*

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
 && apt-get update && apt-get install -y --no-install-recommends nodejs \
 && rm -rf /var/lib/apt/lists/*

RUN curl -fsSL https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor -o /usr/share/keyrings/microsoft.gpg \
 && echo "deb [arch=amd64 signed-by=/usr/share/keyrings/microsoft.gpg] https://packages.microsoft.com/ubuntu/24.04/prod noble main" > /etc/apt/sources.list.d/mssql-release.list \
 && apt-get update \
 && ACCEPT_EULA=Y apt-get install -y --no-install-recommends msodbcsql18 \
 && rm -rf /var/lib/apt/lists/*

RUN pecl channel-update pecl.php.net \
 && printf "\n" | pecl install sqlsrv-5.10.1 \
 && printf "\n" | pecl install pdo_sqlsrv-5.10.1 \
 && echo "extension=sqlsrv.so" > /etc/php/7.4/mods-available/sqlsrv.ini \
 && echo "extension=pdo_sqlsrv.so" > /etc/php/7.4/mods-available/pdo_sqlsrv.ini \
 && phpenmod sqlsrv pdo_sqlsrv

RUN printf '%s\n' \
'#!/bin/bash' \
'cd /var/www/html || exit 1' \
'echo "cron ejecutado $(date)" >> /var/log/cron.log' \
'/usr/bin/php artisan schedule:run >> /var/log/cron.log 2>&1' \
> /usr/local/bin/laravel-scheduler.sh \
 && chmod +x /usr/local/bin/laravel-scheduler.sh

RUN printf '%s\n' \
'SHELL=/bin/bash' \
'PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin' \
'' \
'* * * * * root /usr/local/bin/laravel-scheduler.sh' \
> /etc/cron.d/laravel-cron \
 && chmod 0644 /etc/cron.d/laravel-cron \
 && touch /var/log/cron.log

RUN mkdir -p /etc/supervisor/conf.d \
 && printf "%s\n" \
"[supervisord]" \
"nodaemon=true" \
"" \
"[program:cron]" \
"command=/usr/sbin/cron -f" \
"autostart=true" \
"autorestart=true" \
"priority=10" \
"stdout_logfile=/var/log/cron.out.log" \
"stderr_logfile=/var/log/cron.err.log" \
"" \
"[program:php-fpm]" \
"command=/usr/sbin/php-fpm7.4 -F" \
"autostart=true" \
"autorestart=true" \
"priority=20" \
"stdout_logfile=/var/log/php-fpm.out.log" \
"stderr_logfile=/var/log/php-fpm.err.log" \
"" \
"[program:nginx]" \
"command=/usr/sbin/nginx -g 'daemon off;'" \
"autostart=true" \
"autorestart=true" \
"priority=30" \
"stdout_logfile=/var/log/nginx.out.log" \
"stderr_logfile=/var/log/nginx.err.log" \
> /etc/supervisor/supervisord.conf

WORKDIR /var/www/html
COPY . /var/www/html

RUN git config --global --add safe.directory /var/www/html
RUN composer install --no-interaction --prefer-dist --no-progress
RUN yarn install --frozen-lockfile || yarn install

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
 && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]