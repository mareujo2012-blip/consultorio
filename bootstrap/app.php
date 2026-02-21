<?php

declare(strict_types=1);

// ── Bootstrap ──────────────────────────────────────────────────────────────

define('ROOT_PATH', dirname(__DIR__));
define('APP_START', microtime(true));

// Load environment
$envFile = ROOT_PATH . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with($line, '#') || !str_contains($line, '='))
            continue;
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value, " \t\n\r\0\x0B\"'");
    }
}

// Autoloader
require ROOT_PATH . '/vendor/autoload.php';

// ── Security headers ───────────────────────────────────────────────────────
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    header('Strict-Transport-Security: max-age=63072000; includeSubDomains');
}

// ── Session ────────────────────────────────────────────────────────────────
$cookieParams = session_get_cookie_params();
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => $cookieParams['domain'],
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Strict',
]);
session_name('CONSULTORIO_SESS');
session_start();

// Session timeout check
$timeout = (int) ($_ENV['SESSION_TIMEOUT'] ?? 3600);
if (isset($_SESSION['logged_at']) && (time() - $_SESSION['logged_at']) > $timeout) {
    session_unset();
    session_destroy();
    header('Location: ' . ($_ENV['APP_URL'] ?? '') . '/login?expired=1');
    exit;
}

// Refresh session activity
if (isset($_SESSION['user_id'])) {
    $_SESSION['logged_at'] = time();
}

// Error Handling & Environment
$isProd = ($_ENV['APP_ENV'] ?? 'production') === 'production';
if ($isProd) {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(E_ALL);
    // In production, log errors to a secure hidden file
    ini_set('log_errors', '1');
    ini_set('error_log', ROOT_PATH . '/storage/logs/error.log');
} else {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

// ── Method override ────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method'])) {
    $_SERVER['REQUEST_METHOD'] = strtoupper($_POST['_method']);
}

// Session Regeneration (Fixation Defense)
$regenerationInterval = 1800; // 30 minutes
if (isset($_SESSION['last_regeneration'])) {
    if (time() - $_SESSION['last_regeneration'] >= $regenerationInterval) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
} else {
    $_SESSION['last_regeneration'] = time();
}

// ── Dispatch ───────────────────────────────────────────────────────────────
$router = require ROOT_PATH . '/routes/web.php';
$router->dispatch();
