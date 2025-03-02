<?php
declare(strict_types=1);

// For development debugging
error_reporting(-1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('log_errors', '1');
ini_set('error_log', '/tmp/php_errors.log');

// Useful for debugging
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Extensions loaded: " . implode(', ', get_loaded_extensions()) . "\n";

// Create temp directories
$tmpDirs = [
    '/tmp/views',
    '/tmp/cache',
    '/tmp/sessions',
    '/tmp/framework/views',
    '/tmp/framework/cache',
    '/tmp/framework/sessions'
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Vercel environment setup
if (isset($_ENV['VERCEL_URL'])) {
    putenv("APP_URL=https://{$_ENV['VERCEL_URL']}");
    putenv("ASSET_URL=https://{$_ENV['VERCEL_URL']}");
    putenv("SESSION_DOMAIN={$_ENV['VERCEL_URL']}");
}

// Handle Laravel application
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();
$kernel->terminate($request, $response);
