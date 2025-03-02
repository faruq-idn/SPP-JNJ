<?php
use Illuminate\Http\Request;

require __DIR__ . '/../public/index.php';

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';
// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

// echo <<<HTML
// <pre>
// PHP Environment Test
// -------------------
// PHP Version: " . PHP_VERSION . "
// Time: " . date('Y-m-d H:i:s') . "
// Server: " . php_uname() . "

// Environment Variables:
// --------------------
// VERCEL_URL: {$_ENV['VERCEL_URL']}
// APP_ENV: {$_ENV['APP_ENV']}

// Extensions:
// ----------
// HTML;

// echo implode("\n", get_loaded_extensions());

// echo "\n\nDirectory Test:\n";
// echo "-------------\n";
// $testDir = '/tmp/test-' . time();
// if (mkdir($testDir, 0777, true)) {
//     echo "Successfully created test directory\n";
//     if (is_writable($testDir)) {
//         echo "Directory is writable\n";
//     }
//     rmdir($testDir);
// } else {
//     echo "Failed to create directory\n";
// }

// echo "</pre>";
