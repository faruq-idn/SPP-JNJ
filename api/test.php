<?php
header('Content-Type: text/plain');

echo "PHP Test Script\n";
echo "---------------\n";
echo "PHP Version: " . phpversion() . "\n\n";

echo "Loaded Extensions:\n";
echo "----------------\n";
echo implode("\n", get_loaded_extensions()) . "\n\n";

echo "Environment Variables:\n";
echo "--------------------\n";
foreach ($_ENV as $key => $value) {
    if (strpos($key, 'VERCEL_') === 0) {
        echo "$key = $value\n";
    }
}

echo "\nTesting Directory Permissions:\n";
echo "-------------------------\n";
$testDir = '/tmp/test-' . time();
if (mkdir($testDir, 0777, true)) {
    echo "Successfully created test directory\n";
    rmdir($testDir);
} else {
    echo "Failed to create test directory\n";
}

echo "\nTesting Database Connection:\n";
echo "-------------------------\n";
if (extension_loaded('pgsql')) {
    echo "PostgreSQL extension is available\n";
} else {
    echo "PostgreSQL extension is NOT available\n";
}
