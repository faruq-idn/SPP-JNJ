<?php

// Vercel serverless specific configurations
if (isset($_ENV['VERCEL_URL'])) {
    $_ENV['APP_URL'] = 'https://' . $_ENV['VERCEL_URL'];
    $_ENV['APP_ENV'] = 'production';
    $_ENV['APP_DEBUG'] = false;
}

// For Vercel, we need to use /tmp for cache
$_ENV['APP_CONFIG_CACHE'] = '/tmp/config.php';
$_ENV['APP_EVENTS_CACHE'] = '/tmp/events.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
$_ENV['APP_ROUTES_CACHE'] = '/tmp/routes.php';
$_ENV['APP_SERVICES_CACHE'] = '/tmp/services.php';
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/views';

// Ensure temp directory exists
@mkdir('/tmp/views', 0777, true);

// Load composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Run the application
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);
