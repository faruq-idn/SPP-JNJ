<?php

// Create required temporary directories for Laravel in Vercel
$tmpDirs = [
    '/tmp/cache',
    '/tmp/sessions',
    '/tmp/views',
    '/tmp/framework/cache',
    '/tmp/framework/sessions',
    '/tmp/framework/views',
    '/tmp/bootstrap/cache',
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Set proper permissions
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator('/tmp', RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($iterator as $item) {
    chmod($item, 0755);
}

// Load Laravel bootstrap
require __DIR__ . '/app.php';
