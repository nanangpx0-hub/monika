<?php

/**
 * Front Controller Redirect for CodeIgniter 4
 * 
 * Redirects all requests to the public/index.php since Apache virtual host
 * is configured to point to the project root folder (not public/).
 * 
 * @package MONIKA
 */

// Get the base path from the request
$basePath = '/monika';
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';

// Parse the path relative to the base
$path = $requestUri;
if (strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
}

// Clean the path - remove leading slashes and index.php references
$path = trim($path, '/');
$path = preg_replace('#^index\.php/?#', '', $path);

// Redirect to public/index.php with the path
$redirectUrl = $basePath . '/public/index.php';
if (!empty($path)) {
    $redirectUrl .= '/' . $path;
}

// Preserve query string if present
if (!empty($_SERVER['QUERY_STRING'])) {
    $redirectUrl .= '?' . $_SERVER['QUERY_STRING'];
}

header('Location: ' . $redirectUrl);
exit;
