<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\User;
use App\Models\AuditLog;

class UserController extends BaseController
{
    public function index(): void
    {
        $this->requireAuth();
        // Check if admin (if applicable)
        if (($_SESSION['user_role'] ?? 'admin') !== 'admin') {
            $this->flashError('Acesso restrito a administradores.');
            $this->redirect('dashboard');
        }

        $userModel = new User();
        // Just fetch all users for now; can add pagination and search later
        $users = $userModel->listAll();
        $csrfToken = $this->csrfToken();

        $this->view('users.index', compact('users', 'csrfToken'));
    }

    public function create(): void
    {
        $this->requireAuth();
        if (($_SESSION['user_role'] ?? 'admin') !== 'admin') {
            $this->flashError('Acesso restrito a administradores.');
            $this->redirect('dashboard');
        }

        $csrfToken = $this->csrfToken();
        $this->view('users.create', compact('csrfToken'));
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->validateCsrf();
        if (($_SESSION['user_role'] ?? 'admin') !== 'admin') {
            $this->redirect('dashboard');
        }

        $email = strtolower(trim($_POST['email'] ?? ''));
        $name = trim($_POST['name'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'secretary';

        if (empty($email) || empty($password) || empty($name)) {
            $this->flashError('Nome, e-mail e senha são obrigatórios.');
            $this->redirect('users/create');
        }

        $userModel = new User();
        if ($userModel->findByEmail($email)) {
            $this->flashError('E-mail já cadastrado no sistema.');
            $this->redirect('users/create');
        }

        $id = $userModel->createUser([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'phone' => preg_replace('/\D/', '', $_POST['phone'] ?? ''),
            'role' => $role,
            'active' => 1
        ]);

        if ($id) {
            (new AuditLog())->log('user.create', "Usuário #{$id} ({$email}) cadastrado.", $id);
            $this->flashSuccess('Profissional cadastrado com sucesso!');
            $this->redirect('users');
        } else {
            $this->flashError('Erro ao cadastrar profissional.');
            $this->redirect('users/create');
        }
    }

    public function edit(string $id): void
    {
        $this->requireAuth();
        if (($_SESSION['user_role'] ?? 'admin') !== 'admin') {
            $this->redirect('dashboard');
        }

        $userModel = new User();
        $user = $userModel->find((int) $id);

        if (!$user) {
            $this->flashError('Profissional não encontrado.');
            $this->redirect('users');
        }

        $csrfToken = $this->csrfToken();
        $this->view('users.edit', compact('user', 'csrfToken'));
    }

    public function update(string $id): void
    {
        $this->requireAuth();
        $this->validateCsrf();
        if (($_SESSION['user_role'] ?? 'admin') !== 'admin') {
            $this->redirect('dashboard');
        }

        $userId = (int) $id;
        $userModel = new User();
        $user = $userModel->find($userId);

        if (!$user) {
            $this->flashError('Profissional não encontrado.');
            $this->redirect('users');
        }

        $email = strtolower(trim($_POST['email'] ?? ''));
        if (empty($email) || empty($_POST['name'])) {
            $this->flashError('Nome e e-mail são obrigatórios.');
            $this->redirect("users/{$userId}/edit");
        }

        if ($email !== $user['email'] && $userModel->findByEmail($email)) {
            $this->flashError('E-mail já está sendo utilizado por outro usuário.');
            $this->redirect("users/{$userId}/edit");
        }

        $data = [
            'name' => trim($_POST['name']),
            'email' => $email,
            'phone' => preg_replace('/\D/', '', $_POST['phone'] ?? ''),
            'role' => $_POST['role'] ?? 'secretary',
            'active' => isset($_POST['active']) ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if (!empty($_POST['password'])) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_ARGON2ID);
        }

        if (!empty($_FILES['photo']['tmp_name']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photoPath = $this->handlePhotoUpload($_FILES['photo']);
            if ($photoPath) {
                $data['photo'] = $photoPath;
            }
        }

        $userModel->update($userId, $data);
        (new AuditLog())->log('user.update', "Dados do profissional #{$userId} atualizados.");
        $this->flashSuccess('Profissional atualizado com sucesso!');
        $this->redirect('users');
    }

    private function handlePhotoUpload(array $file): string|false
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize = 2 * 1024 * 1024;
        if (!in_array($file['type'], $allowedMimes) || $file['size'] > $maxSize) {
            return false;
        }
        $dir = __DIR__ . '/../../public/uploads/users/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = md5(uniqid((string) rand(), true)) . '.' . ($ext ?: 'jpg');
        $destPath = $dir . $filename;
        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            return 'uploads/users/' . $filename;
        }
        return false;
    }
}
