<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Basic temp directory
if (!is_dir('/tmp/cache')) {
    mkdir('/tmp/cache', 0777, true);
}

// Test PHP availability
echo "PHP is working. Version: " . phpversion();

// Handle Laravel application
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();
$kernel->terminate($request, $response);
