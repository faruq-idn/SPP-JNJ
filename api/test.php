<?php
header('Content-Type: text/plain');

echo "PHP Test Environment\n";
echo "==================\n\n";

echo "PHP Version: " . PHP_VERSION . "\n";
echo "Current time: " . date('Y-m-d H:i:s') . "\n";
echo "Current dir: " . __DIR__ . "\n\n";

echo "Checking files:\n";
$files = [
    'autoloader' => __DIR__ . '/../vendor/autoload.php',
    'bootstrap' => __DIR__ . '/../bootstrap/app.php',
];

foreach ($files as $name => $path) {
    echo "$name: " . (file_exists($path) ? "Found" : "Missing") . "\n";
}

echo "\nTrying to load autoloader...\n";
try {
    require __DIR__ . '/../vendor/autoload.php';
    echo "✓ Autoloader loaded successfully\n";
} catch (Error $e) {
    echo "✗ Error loading autoloader: " . $e->getMessage() . "\n";
}

echo "\nTrying to load Laravel...\n";
try {
    $app = require __DIR__ . '/../bootstrap/app.php';
    echo "✓ Laravel bootstrap loaded\n";
    echo "✓ Application class: " . get_class($app) . "\n";
} catch (Error $e) {
    echo "✗ Error loading Laravel: " . $e->getMessage() . "\n";
}
