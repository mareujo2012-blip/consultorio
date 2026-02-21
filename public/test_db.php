<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $envVars = [];
    $envFile = dirname(__DIR__) . '/.env';
    if (file_exists($envFile)) {
        foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (str_starts_with($line, '#') || !str_contains($line, '='))
                continue;
            [$key, $value] = explode('=', $line, 2);
            $envVars[trim($key)] = trim($value, " \t\n\r\0\x0B\"'");
        }
    }

    $host = $envVars['DB_HOST'] ?? '127.0.0.1';
    $db = $envVars['DB_NAME'] ?? '';
    $user = $envVars['DB_USER'] ?? '';
    $pass = $envVars['DB_PASS'] ?? '';

    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("ALTER TABLE users ADD COLUMN photo VARCHAR(255) NULL AFTER email");
    echo "Column 'photo' added successfully to 'users' table!";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false || $e->getCode() == '42S21') {
        echo "Column 'photo' already exists.";
    } else {
        echo "DB Error: " . $e->getMessage();
    }
} catch (Exception $e) {
    echo "General Error: " . $e->getMessage();
}
@unlink(__FILE__); // self-destruct
