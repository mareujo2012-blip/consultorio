<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('ROOT_PATH', dirname(__DIR__));

$envFile = ROOT_PATH . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with($line, '#') || !str_contains($line, '='))
            continue;
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value, " \t\n\r\0\x0B\"'");
    }
}
require ROOT_PATH . '/vendor/autoload.php';

use App\Controllers\PrescriptionController;

$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = 'Admin Test';

try {
    $c = new PrescriptionController();
    $c->pdf('1');
} catch (\Throwable $e) {
    echo "ERROR_TRACE_START\n" . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine() . "\n" . $e->getTraceAsString() . "\nERROR_TRACE_END";
}
@unlink(__FILE__);
