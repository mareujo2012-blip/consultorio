<?php
require_once __DIR__ . '/../bootstrap/app.php';
$db = \App\Config\Database::getInstance();
$stmt = $db->query("SELECT name, email FROM users");
$users = $stmt->fetchAll();
foreach ($users as $u) {
    echo "USER: " . $u['name'] . " | LOGIN: " . $u['email'] . "\n";
}
@unlink(__FILE__);
