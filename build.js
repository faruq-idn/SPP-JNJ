const { execSync } = require('child_process');
const path = require('path');

console.log('=== Starting Build Process ===');

// Function to run command and log output
function runCommand(command, name) {
    console.log(`\nRunning ${name}...`);
    try {
        execSync(command, { stdio: 'inherit' });
        console.log(`${name} completed successfully`);
        return true;
    } catch (error) {
        console.error(`${name} failed:`, error.message);
        return false;
    }
}

// Create required directories
const dirs = [
    '/tmp/cache',
    '/tmp/views',
    '/tmp/sessions',
    '/tmp/bootstrap/cache'
];

dirs.forEach(dir => {
    try {
        execSync(`mkdir -p ${dir}`);
        console.log(`Created directory: ${dir}`);
    } catch (error) {
        console.warn(`Warning creating ${dir}:`, error.message);
    }
});

// Run build steps
console.log('\nEnvironment:', process.env.NODE_ENV);
console.log('Current directory:', process.cwd());

// Install Composer
console.log('\nInstalling Composer...');
try {
    execSync('curl -sS https://getcomposer.org/installer > composer-setup.php');
    execSync('php composer-setup.php --install-dir=/tmp --filename=composer');
    execSync('rm composer-setup.php');
    console.log('Composer installed successfully');
} catch (error) {
    console.error('Composer installation failed:', error.message);
    process.exit(1);
}

// Install PHP dependencies
if (!runCommand('/tmp/composer install --no-dev --optimize-autoloader', 'PHP Dependencies')) {
    process.exit(1);
}

// Install NPM packages
if (!runCommand('npm install', 'NPM Dependencies')) {
    process.exit(1);
}

// Run Vite build
if (!runCommand('npm run build', 'Asset Build')) {
    process.exit(1);
}

console.log('\n=== Build Process Completed ===');
