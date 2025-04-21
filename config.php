<?php

if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__);
}
define('PUBLIC_PATH', BASE_PATH . '/public');
define('ASSET_PATH', PUBLIC_PATH . '/assets');
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

function loadEnv($path) {
    // Try loading from .env
    if(file_exists($path)) {
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                
                if (!array_key_exists($name, $_ENV)) {
                    putenv(sprintf('%s=%s', $name, $value));
                    $_ENV[$name] = $value;
                    $_SERVER[$name] = $value;
                }
            }
        }
        return true;
    }
    
    // Fallback to default values if .env not found
    $defaults = [
        'DB_HOST' => 'localhost',
        'DB_PORT' => '3306',
        'DB_NAME' => 'defaultdb',
        'DB_USER' => 'root',
        'DB_PASS' => '',
        'DB_CHARSET' => 'utf8mb4'
    ];

    foreach ($defaults as $key => $value) {
        putenv(sprintf('%s=%s', $key, $value));
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }

    if (DEBUG_MODE) {
        error_log("Warning: .env file not found at {$path}, using default values");
    }
    
    return false;
}

// Try to load .env file
loadEnv(BASE_PATH . '/.env');
