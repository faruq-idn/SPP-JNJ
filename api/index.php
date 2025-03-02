<?php
declare(strict_types=1);

// For development debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');
ini_set('error_log', '/tmp/php_errors.log');

// Create base temp directories
$tmpDirs = [
    '/tmp/cache',
    '/tmp/views',
    '/tmp/sessions',
    '/tmp/framework/cache',
    '/tmp/framework/views',
    '/tmp/framework/sessions',
    '/tmp/bootstrap/cache'
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Setup Vercel URL if available
if (isset($_ENV['VERCEL_URL'])) {
    putenv("APP_URL=https://{$_ENV['VERCEL_URL']}");
    putenv("ASSET_URL=https://{$_ENV['VERCEL_URL']}");
    putenv("SESSION_DOMAIN={$_ENV['VERCEL_URL']}");
}

// Verify vendor autoload exists
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    die("Vendor autoload.php not found. Please run 'composer install'");
}

// Load composer autoloader
require $autoloadPath;

// Load Laravel bootstrap file
$bootstrapPath = __DIR__ . '/../bootstrap/app.php';
if (!file_exists($bootstrapPath)) {
    die("Bootstrap file not found at {$bootstrapPath}");
}

// Initialize Laravel application
try {
    $app = require $bootstrapPath;
    
    if (!is_object($app)) {
        throw new RuntimeException('Failed to initialize Laravel application');
    }

    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );

    $response->send();
    $kernel->terminate($request, $response);

} catch (Exception $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo "Internal Server Error: " . $e->getMessage();
}
