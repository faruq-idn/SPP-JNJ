const { execSync } = require('child_process');

console.log('=== Development Build Setup ===\n');

// Setup temp directory
console.log('Creating temp directories...');
['/tmp/views', '/tmp/cache'].forEach(dir => {
    try {
        execSync(`mkdir -p ${dir}`);
        console.log(`Created ${dir}`);
    } catch (error) {
        console.warn(`Warning creating ${dir}: ${error.message}`);
    }
});

// Install composer and dependencies
console.log('\nInstalling PHP dependencies...');
try {
    execSync('curl -sS https://getcomposer.org/installer | php');
    execSync('php composer.phar install', { stdio: 'inherit' });
    console.log('Composer install completed');
} catch (error) {
    console.error('Failed installing PHP dependencies:', error);
    process.exit(1);
}

// Build frontend assets
console.log('\nInstalling & building frontend...');
try {
    execSync('npm install', { stdio: 'inherit' });
    execSync('npm run build', { stdio: 'inherit' });
    console.log('Frontend build completed');
} catch (error) {
    console.error('Failed building frontend:', error);
    process.exit(1);
}

console.log('\n=== Build Complete ===');
