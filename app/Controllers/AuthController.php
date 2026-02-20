<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\User;
use App\Models\AuditLog;

class AuthController extends BaseController
{
    public function showLogin(): void
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('dashboard');
        }

        $csrfToken = $this->csrfToken();
        // Generate math captcha
        $a = random_int(2, 9);
        $b = random_int(1, 9);
        $_SESSION['captcha_answer'] = $a + $b;
        $captchaQuestion = "{$a} + {$b} = ?";

        $this->view('auth.login', compact('csrfToken', 'captchaQuestion'), 'auth');
    }

    public function login(): void
    {
        $this->validateCsrf();

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $captcha = (int) ($_POST['captcha'] ?? -1);

        // Validate captcha
        if ($captcha !== ($_SESSION['captcha_answer'] ?? -99)) {
            $this->flashError('Resposta do captcha incorreta.');
            $this->redirect('login');
        }
        unset($_SESSION['captcha_answer']);

        if (empty($email) || empty($password)) {
            $this->flashError('E-mail e senha são obrigatórios.');
            $this->redirect('login');
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user || !$userModel->verifyPassword($password, $user['password'])) {
            sleep(1); // Slow down brute force
            $this->flashError('Credenciais inválidas.');
            $this->redirect('login');
        }

        // Regenerate session id to prevent session fixation
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['logged_at'] = time();

        (new AuditLog())->log('login', "Login realizado por {$user['email']}", $user['id']);

        $this->redirect('dashboard');
    }

    public function logout(): void
    {
        (new AuditLog())->log('logout', "Logout realizado");
        session_unset();
        session_destroy();
        $this->redirect('login');
    }
}
