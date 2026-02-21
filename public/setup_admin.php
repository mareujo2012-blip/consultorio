<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../bootstrap/app.php';

use App\Models\User;

$userModel = new User();
$email = 'MarcoAGD2026';
$pass = '90860Placa8010';

$exists = $userModel->findByEmail($email);
if (!$exists) {
    $id = $userModel->createUser([
        'name' => 'Dr. Marco Antonio (Geral)',
        'email' => $email,
        'password' => $pass,
        'role' => 'admin',
        'active' => 1,
        'phone' => ''
    ]);
    if ($id) {
        echo "Admin 'MarcoAGD2026' criado com sucesso!";
    } else {
        echo "Falha técnica ao criar usuário admin.";
    }
} else {
    $userModel->updatePassword($exists['id'], $pass);
    echo "Admin 'MarcoAGD2026' já existia. Senha atualizada com sucesso.";
}

@unlink(__FILE__);
