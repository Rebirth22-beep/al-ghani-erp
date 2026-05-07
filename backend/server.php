<?php

/**
 * Custom router for PHP built-in server.
 * Uses __DIR__ instead of getcwd() to avoid path-with-spaces issues on Windows.
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '');
$publicPath = __DIR__ . '/public';

if ($uri !== '/' && file_exists($publicPath . $uri)) {
    return false;
}

$_SERVER['SCRIPT_FILENAME'] = $publicPath . '/index.php';
$_SERVER['DOCUMENT_ROOT']   = $publicPath;

require_once $publicPath . '/index.php';
