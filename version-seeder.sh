#!/usr/bin/env sh

# Migration
php artisan migrate

php artisan db:seed --class=DepartureGroupSeeder
php artisan db:seed --class=DepartureReasonSeeder
