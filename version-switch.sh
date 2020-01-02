#!/usr/bin/env bash

APP_ENV=$(grep APP_ENV .env | cut -d '=' -f2)
PROD="production"

if [[ "$APP_ENV" == "$PROD" ]]; then
    git fetch --tags
    latestVersion=$(git describe --tags `git rev-list --tags --max-count=1`)
else
    latestVersion="development"
fi
git checkout $latestVersion
echo -e "\n\e[34mSwitched to version \e[31m$latestVersion\n"