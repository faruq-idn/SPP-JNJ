const { execSync } = require('child_process');

console.log('=== Starting Pre-Build Setup ===');

// Create temp directories
const dirs = [
    '/tmp/cache',
    '/tmp/views',
    '/tmp/sessions',
    '/tmp/framework/cache',
    '/tmp/framework/sessions',
    '/tmp/framework/views',
    '/tmp/bootstrap/cache'
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

// Install Composer
console.log('\nInstalling Composer...');
try {
    execSync("curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php");
    execSync("/usr/bin/php /tmp/composer-setup.php --install-dir=/tmp --filename=composer");
    execSync("rm /tmp/composer-setup.php");
} catch (error) {
    console.error('Failed to install Composer:', error);
    process.exit(1);
}

// Run composer install
console.log('\nInstalling PHP Dependencies...');
try {
    execSync('/usr/bin/php /tmp/composer install --no-dev --optimize-autoloader', { stdio: 'inherit' });
} catch (error) {
    console.error('Failed to install PHP dependencies:', error);
    process.exit(1);
}

// NPM install and build
console.log('\nInstalling NPM packages...');
try {
    execSync('/usr/local/bin/npm ci', { stdio: 'inherit' });
} catch (error) {
    console.error('Failed to install NPM packages:', error);
    process.exit(1);
}

console.log('\nBuilding assets...');
try {
    execSync('/usr/local/bin/npm run build', { stdio: 'inherit' });
} catch (error) {
    console.error('Failed to build assets:', error);
    process.exit(1);
}

console.log('\n=== Pre-Build Setup Complete ===');
