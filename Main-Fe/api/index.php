<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Prepare storage directory for Vercel
if (isset($_ENV['VERCEL']) || getenv('VERCEL')) {
    $storagePath = '/tmp/storage';
    $directories = [
        $storagePath . '/app/public',
        $storagePath . '/framework/cache/data',
        $storagePath . '/framework/sessions',
        $storagePath . '/framework/testing',
        $storagePath . '/framework/views',
        $storagePath . '/logs',
        $storagePath . '/bootstrap/cache',
    ];
    foreach ($directories as $directory) {
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
    }

    // Set Laravel cache variables to use /tmp/storage
    $_SERVER['APP_CONFIG_CACHE'] = $storagePath . '/bootstrap/cache/config.php';
    $_SERVER['APP_ROUTES_CACHE'] = $storagePath . '/bootstrap/cache/routes.php';
    $_SERVER['APP_EVENTS_CACHE'] = $storagePath . '/bootstrap/cache/events.php';
    $_SERVER['APP_PACKAGES_CACHE'] = $storagePath . '/bootstrap/cache/packages.php';
    $_SERVER['APP_SERVICES_CACHE'] = $storagePath . '/bootstrap/cache/services.php';
    $_SERVER['VIEW_COMPILED_PATH'] = $storagePath . '/framework/views';
}

// Bootstrap Laravel and handle the request...
$app = require_once __DIR__ . '/../bootstrap/app.php';

if (isset($_ENV['VERCEL']) || getenv('VERCEL')) {
    $app->useStoragePath('/tmp/storage');
}

$app->handleRequest(Request::capture());