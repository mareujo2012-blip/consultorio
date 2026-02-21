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

        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $limitKey = 'login_attempts_' . md5($ip);
        $attempts = $_SESSION[$limitKey] ?? 0;
        $blockedUntil = $_SESSION['login_blocked_until_' . md5($ip)] ?? 0;

        if (time() < $blockedUntil) {
            $wait = ceil(($blockedUntil - time()) / 60);
            $this->flashError("Muitas tentativas falhas. Tente novamente em {$wait} minuto(s).");
            $this->redirect('login');
        }

        if (empty($email) || empty($password)) {
            $this->flashError('E-mail e senha são obrigatórios.');
            $this->redirect('login');
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user || !$userModel->verifyPassword($password, $user['password'])) {
            $_SESSION[$limitKey] = $attempts + 1;

            if ($_SESSION[$limitKey] >= 5) {
                // Block for 15 minutes
                $_SESSION['login_blocked_until_' . md5($ip)] = time() + (15 * 60);
                (new AuditLog())->log('security.brute_force', "IP {$ip} bloqueado temporariamente.");
                $this->flashError('Muitas tentativas falhas. Conta bloqueada por 15 minutos.');
            } else {
                sleep(1); // Slow down
                $this->flashError('Credenciais inválidas.');
            }
            $this->redirect('login');
        }

        // Reset attempts
        unset($_SESSION[$limitKey], $_SESSION['login_blocked_until_' . md5($ip)]);

        // Check if password needs rehash (upgrade from bcrypt to argon2id)
        if ($userModel->needsRehash($user['password'])) {
            $userModel->updatePassword($user['id'], $password);
            (new AuditLog())->log('security.password_upgrade', "Senha do usuário #{$user['id']} atualizada para Argon2id");
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
