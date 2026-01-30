<?php
define('ROOTPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('APPPATH', __DIR__ . '/app/');
require_once __DIR__ . '/vendor/autoload.php';

// Mocking some CI4 constants if needed, but let's try to load Boot
// We can just check $_SERVER['CI_ENVIRONMENT'] or getenv

echo "getenv('CI_ENVIRONMENT'): " . getenv('CI_ENVIRONMENT') . "\n";
echo "\$_SERVER['CI_ENVIRONMENT']: " . ($_SERVER['CI_ENVIRONMENT'] ?? 'not set') . "\n";
echo "\$_ENV['CI_ENVIRONMENT']: " . ($_ENV['CI_ENVIRONMENT'] ?? 'not set') . "\n";

// Load Config/Database
require_once __DIR__ . '/app/Config/Database.php';
// We need the constants like ENVIRONMENT.
// Let's rely on what spark does.
