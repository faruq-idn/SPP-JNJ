#!/bin/bash

# Build assets
npm run build

# Copy .env.hosting to .env
cp .env.hosting .env

# Clear Laravel cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Generate optimized files
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev

echo "Files ready for upload to shared hosting (faruq.whoami.my.id)"
echo "Please upload using your preferred FTP client"
echo "After uploading, run these commands on the server:"
echo "1. php artisan migrate --force"
echo "2. php artisan storage:link"
echo "3. chmod -R 775 storage bootstrap/cache"
