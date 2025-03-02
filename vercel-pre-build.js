const { execSync } = require('child_process');

console.log('Starting pre-build setup...');

try {
    // Download composer installer
    console.log('Downloading composer installer...');
    execSync('curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php');

    // Install composer locally
    console.log('Installing composer...');
    execSync('php /tmp/composer-setup.php --install-dir=/tmp');

    // Run composer install
    console.log('Installing PHP dependencies...');
    execSync('/tmp/composer install --no-dev --optimize-autoloader');

    console.log('Pre-build setup completed successfully');
} catch (error) {
    console.error('Pre-build setup failed:', error);
    process.exit(1);
}
