const { execSync } = require('child_process');

console.log('=== Starting Pre-Build Setup ===');

// Create temp directories
const dirs = [
    '/tmp/cache',
    '/tmp/views',
    '/tmp/sessions',
    '/tmp/framework/cache',
    '/tmp/framework/views',
    '/tmp/framework/sessions',
    '/tmp/bootstrap/cache',
    '/tmp/.composer',
    '/tmp/.composer/cache',
    '/tmp/vendor'
];

console.log('Creating temp directories...');
dirs.forEach(dir => {
    try {
        execSync(`mkdir -p ${dir}`);
        console.log(`Created: ${dir}`);
    } catch (error) {
        console.warn(`Warning creating ${dir}:`, error.message);
    }
});

// Set Composer environment variables
process.env.COMPOSER_HOME = '/tmp/.composer';
process.env.COMPOSER_CACHE_DIR = '/tmp/.composer/cache';
process.env.COMPOSER_VENDOR_DIR = '/tmp/vendor';
process.env.COMPOSER_ALLOW_SUPERUSER = '1';

// Install Composer
console.log('\nInstalling Composer...');
try {
    execSync("curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php", { stdio: 'inherit' });
    execSync("/usr/bin/php /tmp/composer-setup.php --install-dir=/tmp --filename=composer", { stdio: 'inherit' });
    execSync("rm /tmp/composer-setup.php");
    console.log('Composer installed successfully');
} catch (error) {
    console.error('Failed to install Composer:', error);
    process.exit(1);
}

// Install Laravel dependencies with dev packages
console.log('\nInstalling PHP Dependencies (including dev)...');
try {
    execSync('/tmp/composer install --prefer-dist', { stdio: 'inherit' });
    console.log('PHP dependencies installed successfully');
} catch (error) {
    console.error('Failed to install PHP dependencies:', error);
    process.exit(1);
}

// Generate Laravel key
console.log('\nGenerating application key...');
try {
    execSync('php artisan key:generate', { stdio: 'inherit' });
    console.log('Application key generated');
} catch (error) {
    console.warn('Warning: Could not generate app key:', error.message);
}

// Install NPM packages
console.log('\nInstalling NPM packages...');
try {
    execSync('npm install', { stdio: 'inherit' });
    console.log('NPM packages installed successfully');
} catch (error) {
    console.error('Failed to install NPM packages:', error);
    process.exit(1);
}

// Build assets
console.log('\nBuilding assets...');
try {
    execSync('npm run build', { stdio: 'inherit' });
    console.log('Assets built successfully');
} catch (error) {
    console.error('Failed to build assets:', error);
    process.exit(1);
}

console.log('\n=== Pre-Build Setup Complete ===');
