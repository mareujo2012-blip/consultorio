<?php
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$host = $_ENV['DB_HOST'];
$db = $_ENV['DB_DATABASE'];
$user = $_ENV['DB_USERNAME'];
$pass = $_ENV['DB_PASSWORD'];

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("ALTER TABLE users ADD COLUMN photo VARCHAR(255) NULL AFTER email");
    echo "Column 'photo' added successfully to 'users' table!";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false || $e->getCode() == '42S21') {
        echo "Column 'photo' already exists.";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
@unlink(__FILE__); // self-destruct
