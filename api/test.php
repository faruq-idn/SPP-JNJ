<?php
header('Content-Type: text/plain');

echo "PHP Environment Information\n";
echo "------------------------\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Current Directory: " . __DIR__ . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n\n";

echo "Temp Directories:\n";
echo "----------------\n";
$tmpDirs = [
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

foreach ($tmpDirs as $dir) {
    echo "$dir: " . (is_dir($dir) ? "exists" : "missing") . 
         (is_writable($dir) ? " (writable)" : " (not writable)") . "\n";
}

echo "\nComposer Environment:\n";
echo "--------------------\n";
echo "COMPOSER_HOME: " . (getenv('COMPOSER_HOME') ?: 'not set') . "\n";
echo "COMPOSER_CACHE_DIR: " . (getenv('COMPOSER_CACHE_DIR') ?: 'not set') . "\n";
echo "COMPOSER_VENDOR_DIR: " . (getenv('COMPOSER_VENDOR_DIR') ?: 'not set') . "\n";
echo "Composer exists: " . (file_exists('/tmp/composer') ? 'yes' : 'no') . "\n";

echo "\nLaravel Dependencies:\n";
echo "-------------------\n";
$requiredFiles = [
    '/tmp/vendor/autoload.php' => 'Composer Autoloader',
    __DIR__ . '/../bootstrap/app.php' => 'Laravel Bootstrap',
    __DIR__ . '/../vendor/laravel/framework/src/Illuminate/Foundation/Application.php' => 'Laravel Framework',
    __DIR__ . '/../vendor/laravel/pail/src/PailServiceProvider.php' => 'Laravel Pail'
];

foreach ($requiredFiles as $file => $description) {
    echo "$description: " . (file_exists($file) ? "found" : "missing") . "\n";
}

echo "\nEnvironment Variables:\n";
echo "--------------------\n";
$envVars = [
    'APP_ENV', 'APP_DEBUG', 'APP_URL', 'LOG_CHANNEL', 'LOG_LEVEL',
    'DB_CONNECTION', 'CACHE_DRIVER', 'SESSION_DRIVER', 'FILESYSTEM_DISK'
];

foreach ($envVars as $var) {
    echo "$var: " . (getenv($var) ?: 'not set') . "\n";
}

echo "\nLoaded Extensions:\n";
echo "----------------\n";
echo implode(", ", get_loaded_extensions()) . "\n";

// Test Laravel autoloading
echo "\nTesting Laravel Bootstrap:\n";
echo "----------------------\n";
try {
    require __DIR__ . '/../vendor/autoload.php';
    echo "Autoloader loaded successfully\n";
    
    $app = require __DIR__ . '/../bootstrap/app.php';
    echo "Bootstrap loaded successfully\n";
    echo "Application class: " . get_class($app) . "\n";
    
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack Trace:\n" . $e->getTraceAsString() . "\n";
}
