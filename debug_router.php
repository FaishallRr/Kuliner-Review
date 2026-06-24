<?php
file_put_contents('D:/MyProject/kuliner-review/debug_log.txt',
    'REQUEST_URI: ' . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "\n" .
    'SCRIPT_NAME: ' . ($_SERVER['SCRIPT_NAME'] ?? 'N/A') . "\n" .
    'DOCUMENT_ROOT: ' . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "\n" .
    'REQUEST_METHOD: ' . ($_SERVER['REQUEST_METHOD'] ?? 'N/A') . "\n" .
    'PHP_SAPI: ' . PHP_SAPI . "\n" .
    'SCRIPT_FILENAME: ' . ($_SERVER['SCRIPT_FILENAME'] ?? 'N/A') . "\n",
    FILE_APPEND);

$uri = urldecode(
    parse_url('https://codeigniter.com' . $_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '',
);
$_SERVER['SCRIPT_NAME'] = '/index.php';
$path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . ltrim($uri, '/');

file_put_contents('D:/MyProject/kuliner-review/debug_log.txt',
    'Parsed URI: ' . $uri . "\n" .
    'Full path: ' . $path . "\n" .
    'is_file: ' . (is_file($path) ? 'true' : 'false') . "\n" .
    "---\n",
    FILE_APPEND);

if ($uri !== '/' && (is_file($path) || is_dir($path))) {
    return false;
}

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'index.php';
