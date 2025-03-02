<?php
declare(strict_types=1);

// For development debugging
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Create temp directories
$tmpDirs = [
    '/tmp/views',
    '/tmp/cache',
    '/tmp/sessions'
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Vercel environment setup
if (isset($_ENV['VERCEL_URL'])) {
    putenv("APP_URL=https://{$_ENV['VERCEL_URL']}");
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
