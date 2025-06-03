<?php

function formatPrice($price) {
    return 'Rs. ' . number_format($price, 2);
}

function formatDate($date, $format = 'd M Y') {
    return date($format, strtotime($date));
}

function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function getCurrentUser() {
    return $_SESSION['user'] ?? null;
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

function formatFileSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, 2) . ' ' . $units[$pow];
}

function truncateText($text, $length = 100, $append = '...') {
    if (strlen($text) > $length) {
        $text = substr($text, 0, $length);
        $text = substr($text, 0, strrpos($text, ' '));
        $text .= $append;
    }
    return $text;
}

function getCurrentUrl() {
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . 
           "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

function isNumeric($string) {
    return ctype_digit($string);
}

function getRootPath($path = '') {
    $projectRoot = realpath($_SERVER['DOCUMENT_ROOT']);
    $currentScript = realpath($_SERVER['SCRIPT_FILENAME']);
    $relativePath = str_replace($projectRoot, '', dirname($currentScript));
    $relativePath = trim($relativePath, DIRECTORY_SEPARATOR);
    $depth = substr_count($relativePath, DIRECTORY_SEPARATOR);
    // keep the depth to 0
    if ($depth === 0) return $path;
    $path = trim($path, '/');
    return str_repeat('../', $depth) . $path;
}
