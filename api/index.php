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

// Load Laravel
require __DIR__ . '/../vendor/autoload.php';

// Use Vercel bootstrap if available
if (file_exists(__DIR__ . '/../bootstrap/vercel.php')) {
    $app = require __DIR__ . '/../bootstrap/vercel.php';
} else {
    $app = require __DIR__ . '/../bootstrap/app.php';
}

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();
$kernel->terminate($request, $response);
