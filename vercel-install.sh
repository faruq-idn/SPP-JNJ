#!/bin/bash

echo "=== Vercel Installation Script ==="
echo "Current directory: $(pwd)"

# Create required directories
echo "Creating temp directories..."
mkdir -p /tmp/build
mkdir -p public/build

# Display system info
echo "System information:"
php -v || echo "PHP not found"
echo "Node version: $(node -v)"
echo "NPM version: $(npm -v)"

# Build assets
echo "Building assets..."
npm run build

# Download and install Composer manually
echo "Setting up Composer..."
EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')"
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]; then
    echo 'ERROR: Invalid Composer installer checksum'
    rm composer-setup.php
    exit 1
fi

php composer-setup.php --install-dir=/tmp --filename=composer
RESULT=$?
rm composer-setup.php

if [ $RESULT -eq 0 ]; then
    echo "Composer installed successfully"
    echo "Installing PHP dependencies..."
    export COMPOSER_ALLOW_SUPERUSER=1
    php /tmp/composer install --no-dev --no-interaction --prefer-dist
else
    echo "Composer installation failed"
    exit 1
fi

# Verify build
echo "Verifying build..."
echo "Directory structure:"
ls -R

echo "=== Installation Complete ==="
