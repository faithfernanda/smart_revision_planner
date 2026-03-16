<?php
// utils/helpers.php

function url($path) {
    // Get the script directory (e.g., /smart_revision_planner/public)
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    $scriptDir = str_replace('\\', '/', $scriptDir);
    
    // Ensure path starts with /
    if ($path[0] !== '/') {
        $path = '/' . $path;
    }
    
    // Return combined path
    // If scriptDir is just '/', don't double slash
    if ($scriptDir === '/') {
        return $path;
    }
    
    return $scriptDir . $path;
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    $token = generate_csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

function verify_csrf_token($token) {
    if (!isset($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

function site_name() {
    return Setting::getSiteName();
}

/**
 * Translation helper
 */
function __($key) {
    static $translations = [];
    static $currentLang = null;

    $lang = $_SESSION['lang'] ?? 'fr';

    if ($currentLang !== $lang) {
        $file = __DIR__ . "/../lang/{$lang}.php";
        if (file_exists($file)) {
            $translations = include $file;
        } else {
            $translations = include __DIR__ . '/../lang/fr.php';
        }
        $currentLang = $lang;
    }

    return $translations[$key] ?? $key;
}
