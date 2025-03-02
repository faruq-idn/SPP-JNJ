<?php
header('Content-Type: text/plain');

echo "PHP Environment Information\n";
echo "------------------------\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Current Directory: " . __DIR__ . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n\n";

echo "Directory Structure:\n";
echo "-----------------\n";
function list_directory($path, $indent = '') {
    if (!is_dir($path)) return;
    
    $files = scandir($path);
    foreach ($files as $file) {
        if ($file == '.' || $file == '..') continue;
        echo $indent . $file . "\n";
        if (is_dir($path . '/' . $file)) {
            list_directory($path . '/' . $file, $indent . '  ');
        }
    }
}
list_directory(__DIR__ . '/..');

echo "\nEnvironment Variables:\n";
echo "--------------------\n";
$env_vars = getenv();
foreach ($env_vars as $key => $value) {
    if (strpos($key, 'VERCEL_') === 0 || strpos($key, 'APP_') === 0) {
        echo "$key = $value\n";
    }
}

echo "\nPHP Extensions:\n";
echo "--------------\n";
echo implode("\n", get_loaded_extensions()) . "\n";

echo "\nTemp Directory Test:\n";
echo "-----------------\n";
$test_dir = '/tmp/test-' . time();
if (mkdir($test_dir, 0755, true)) {
    echo "Successfully created: $test_dir\n";
    file_put_contents($test_dir . '/test.txt', 'Test content');
    if (file_exists($test_dir . '/test.txt')) {
        echo "Successfully wrote to test file\n";
        echo "File contents: " . file_get_contents($test_dir . '/test.txt') . "\n";
    }
    unlink($test_dir . '/test.txt');
    rmdir($test_dir);
    echo "Successfully cleaned up test directory\n";
} else {
    echo "Failed to create test directory\n";
}

echo "\nVendor Check:\n";
echo "------------\n";
$vendor_path = __DIR__ . '/../vendor';
if (is_dir($vendor_path)) {
    echo "Vendor directory exists\n";
    if (file_exists($vendor_path . '/autoload.php')) {
        echo "Autoloader exists\n";
    } else {
        echo "Autoloader missing!\n";
    }
} else {
    echo "Vendor directory missing!\n";
}

echo "\nBootstrap Check:\n";
echo "--------------\n";
$bootstrap_path = __DIR__ . '/../bootstrap/app.php';
if (file_exists($bootstrap_path)) {
    echo "Bootstrap file exists\n";
} else {
    echo "Bootstrap file missing!\n";
}
