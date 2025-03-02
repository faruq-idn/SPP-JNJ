#!/bin/bash

echo "=== Vercel Installation Script ==="
echo "Current directory: $(pwd)"
echo "PHP Version: $(php -v)"

# Create required directories
echo "Creating temp directories..."
mkdir -p /tmp/build
mkdir -p public/build

# Install npm dependencies and build assets
echo "Installing NPM dependencies..."
npm install --no-optional

echo "Building assets..."
npm run build

# Display debug info
echo "Checking directories..."
ls -la
ls -la public/

echo "Node/NPM versions:"
node -v
npm -v

echo "=== Installation Complete ==="
