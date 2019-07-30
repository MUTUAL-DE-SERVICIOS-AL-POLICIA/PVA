#!/bin/sh

cd /tmp && \
apt-get update -yqq && \
apt-get -y install fonts-roboto fonts-roboto-fontface unzip fontconfig unzip wget && \
wget -O RobotoMono.zip https://fonts.google.com/download\?family\=Roboto%20Mono && \
mkdir -p /usr/share/fonts/googlefonts && \
unzip -d /usr/share/fonts/googlefonts /tmp/RobotoMono.zip && \
chmod -R --reference=/usr/share/fonts/googlefonts /usr/share/fonts/googlefonts && \
apt-get -y remove --purge wget unzip
apt-get clean && \
rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
fc-cache -fv && \
fc-match "Roboto Mono"