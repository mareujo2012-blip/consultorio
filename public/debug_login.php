<?php
require_once __DIR__ . '/../bootstrap/app.php';
$db = \App\Config\Database::getInstance();
$stmt = $db->query("SELECT * FROM users WHERE email LIKE '%Marco%'");
$users = $stmt->fetchAll();
if (empty($users)) {
    echo "Nenhum usuário encontrado com 'Marco' no login.\n";
    $stmt2 = $db->query("SELECT * FROM users");
    $all = $stmt2->fetchAll();
    foreach ($all as $u) {
        echo "Existente: [" . $u['email'] . "] (ID: " . $u['id'] . ")\n";
    }
} else {
    foreach ($users as $u) {
        echo "Encontrado: [" . $u['email'] . "] (ID: " . $u['id'] . ")\n";
    }
}
@unlink(__FILE__);
