#!/bin/bash
set -e

cd /var/app/current

echo "----------------------------------------"
echo "Running Laravel maintenance tasks..."

php artisan key:generate --force || true
# php artisan migrate --force || true
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear
php artisan view:cache
php artisan optimize:clear

echo "Laravel tasks completed"
echo "----------------------------------------"
