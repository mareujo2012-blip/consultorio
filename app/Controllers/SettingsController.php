<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\User;
use App\Models\ClinicSettings;
use App\Models\AuditLog;

class SettingsController extends BaseController
{
    public function index(): void
    {
        $this->requireAuth();

        $userModel = new User();
        $user = $userModel->find($_SESSION['user_id']);
        $clinic = (new ClinicSettings())->getSettings();
        $csrfToken = $this->csrfToken();

        $this->view('settings.index', compact('user', 'clinic', 'csrfToken'));
    }

    public function updateUser(): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $userId = $_SESSION['user_id'];
        $userModel = new User();

        $data = [
            'name' => $this->sanitize($_POST['name'] ?? ''),
            'email' => strtolower(trim($_POST['email'] ?? '')),
            'phone' => preg_replace('/\D/', '', $_POST['phone'] ?? ''),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Processa foto de perfil do médico caso enviada
        if (!empty($_FILES['user_photo']['tmp_name']) && $_FILES['user_photo']['error'] === UPLOAD_ERR_OK) {
            $photoPath = $this->handleUserPhotoUpload($_FILES['user_photo']);
            if ($photoPath) {
                $data['photo'] = $photoPath;
            }
        }

        $userModel->update($userId, $data);
        $_SESSION['user_name'] = $data['name'];
        (new AuditLog())->log('settings.user_update', "Dados do usuário #{$userId} atualizados");
        $this->flashSuccess('Dados e foto atualizados com sucesso!');
        $this->redirect('settings');
    }

    public function updatePassword(): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $userId = $_SESSION['user_id'];
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ($new !== $confirm) {
            $this->flashError('As senhas não coincidem.');
            $this->redirect('settings');
        }

        if (strlen($new) < 8) {
            $this->flashError('A nova senha deve ter pelo menos 8 caracteres.');
            $this->redirect('settings');
        }

        $userModel = new User();
        $user = $userModel->find($userId);

        if (!$userModel->verifyPassword($current, $user['password'])) {
            $this->flashError('Senha atual incorreta.');
            $this->redirect('settings');
        }

        $userModel->updatePassword($userId, $new);
        (new AuditLog())->log('settings.password_change', "Senha alterada para usuário #{$userId}");
        $this->flashSuccess('Senha alterada com sucesso!');
        $this->redirect('settings');
    }

    public function updateClinic(): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $clinicModel = new ClinicSettings();
        $existing = $clinicModel->getSettings();

        $data = [
            'name' => $this->sanitize($_POST['clinic_name'] ?? ''),
            'cnpj' => preg_replace('/\D/', '', $_POST['cnpj'] ?? ''),
            'address' => $this->sanitize($_POST['address'] ?? ''),
            'city' => $this->sanitize($_POST['city'] ?? ''),
            'state' => $this->sanitize($_POST['state'] ?? ''),
            'phone' => preg_replace('/\D/', '', $_POST['phone'] ?? ''),
            'website' => $this->sanitize($_POST['website'] ?? ''),
            'instagram' => $this->sanitize($_POST['instagram'] ?? ''),
            'facebook' => $this->sanitize($_POST['facebook'] ?? ''),
        ];

        // Handle logo upload
        if (!empty($_FILES['logo']['tmp_name']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $logoPath = $this->handleLogoUpload($_FILES['logo']);
            if ($logoPath) {
                $data['logo'] = $logoPath;
            }
        }

        $clinicModel->saveSettings($data);
        (new AuditLog())->log('settings.clinic_update', "Dados da clínica atualizados");
        $this->flashSuccess('Dados da clínica atualizados com sucesso!');
        $this->redirect('settings');
    }

    private function handleLogoUpload(array $file): string|false
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($file['type'], $allowedMimes) || $file['size'] > $maxSize) {
            return false;
        }

        $dir = __DIR__ . '/../../public/uploads/logos/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'logo_' . time() . '.' . $ext;
        $destPath = $dir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            return 'uploads/logos/' . $filename;
        }

        return false;
    }

    private function handleUserPhotoUpload(array $file): string|false
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize = 2 * 1024 * 1024; // 2MB is more than enough for a cropped image

        if (!in_array($file['type'], $allowedMimes) || $file['size'] > $maxSize) {
            return false;
        }

        $dir = __DIR__ . '/../../public/uploads/users/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (!$ext) {
            $ext = 'jpg';
        }
        $filename = 'user_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destPath = $dir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            return 'uploads/users/' . $filename;
        }

        return false;
    }
}
