#!/bin/bash

# Pastikan Vercel CLI terinstall
if ! command -v vercel &> /dev/null; then
    echo "Installing Vercel CLI..."
    npm install -g vercel
fi

echo "Preparing for development deployment to Vercel..."

# Install dependencies
echo "Installing NPM dependencies..."
npm install

echo "Building assets..."
npm run build

echo "Installing Composer dependencies..."
composer install

echo "Setting up environment..."
cp .env.vercel .env

echo "Clearing Laravel cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear

echo "Ready for deployment!"
echo ""
echo "To deploy for testing:"
echo "1. Run: vercel"
echo "2. Set up database credentials in Vercel dashboard"
echo "3. Run migrations: vercel run php artisan migrate"
echo ""
echo "Note: This is a development deployment with debug mode enabled"
