#!/bin/bash

echo "=== Starting Vercel Build Process ==="

echo "1. System Information:"
echo "----------------------"
echo "Node Version: $(node -v)"
echo "NPM Version: $(npm -v)"
echo "Current Directory: $(pwd)"
echo "Directory Contents:"
ls -la

echo -e "\n2. Installing Dependencies..."
echo "----------------------"
npm install --no-optional

echo -e "\n3. Building Assets..."
echo "----------------------"
npm run build

echo -e "\n4. Setting up Directories..."
echo "----------------------"
mkdir -p /tmp/{cache,views,sessions}
mkdir -p public/build
chmod -R 755 /tmp

echo -e "\n5. Verifying Build..."
echo "----------------------"
if [ -d "public/build" ]; then
    echo "public/build contents:"
    ls -la public/build/
    echo -e "\nBuild verification successful!"
else
    echo "Warning: public/build directory not found"
    echo "Current directory structure:"
    find . -maxdepth 3 -type d
fi

echo -e "\n6. Environment Check..."
echo "----------------------"
env | grep -E "^(VERCEL_|NODE_|NPM_|PATH=)" || true

echo -e "\n=== Build Process Complete ==="
