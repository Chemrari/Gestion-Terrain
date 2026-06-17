<?php

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define root paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');

// Include global helper functions
require_once APP_PATH . '/helpers/functions.php';

// Include database configuration
require_once ROOT_PATH . '/config/database.php';

// Class Autoloader
spl_autoload_register(function ($class) {
    $dirs = [
        APP_PATH . '/models/',
        APP_PATH . '/controllers/',
        APP_PATH . '/helpers/',
        APP_PATH . '/middleware/',
    ];
    
    foreach ($dirs as $dir) {
        $file = $dir . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Ensure uploads folder exists in public directory
$uploadsDir = __DIR__ . '/uploads';
if (!file_exists($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

// Get the router instance and dispatch request
$routes = require ROOT_PATH . '/config/routes.php';
$router = new Router($routes);

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($uri, $method);
