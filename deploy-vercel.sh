#!/bin/bash

# Pastikan Vercel CLI terinstall
if ! command -v vercel &> /dev/null; then
    echo "Installing Vercel CLI..."
    npm install -g vercel
fi

# Build assets
npm run build

# Copy .env.vercel to .env
cp .env.vercel .env

# Clear Laravel cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Install dependencies for production
composer install --optimize-autoloader --no-dev

echo "Deploying to Vercel..."
echo "Please make sure you have:"
echo "1. Created a new project on Vercel"
echo "2. Connected your GitHub repository"
echo "3. Set up the following environment variables on Vercel dashboard:"
echo "   - DATABASE_URL (PostgreSQL connection string)"
echo "   - POSTGRES_HOST"
echo "   - POSTGRES_DATABASE"
echo "   - POSTGRES_USER"
echo "   - POSTGRES_PASSWORD"
echo "   - APP_KEY (run 'php artisan key:generate --show')"
echo "   - Other environment variables from .env.vercel"
echo ""
echo "Then run: vercel --prod"
