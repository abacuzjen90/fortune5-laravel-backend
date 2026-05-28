#!/bin/bash
set -e

APP_PATH="/var/app/staging"

echo "Fixing Laravel permissions..."

mkdir -p "$APP_PATH/storage" "$APP_PATH/bootstrap/cache"
chown -R webapp:nginx "$APP_PATH/storage" "$APP_PATH/bootstrap/cache"
chmod -R 775 "$APP_PATH/storage" "$APP_PATH/bootstrap/cache"

echo "Permissions fixed"
