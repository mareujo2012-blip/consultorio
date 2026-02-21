<?php

namespace App\Core;

abstract class BaseController
{
    protected function view(string $viewPath, array $data = [], string $layout = 'main'): void
    {
        extract($data);

        $viewFile = __DIR__ . '/../../app/Views/' . str_replace('.', '/', $viewPath) . '.php';

        if (!file_exists($viewFile)) {
            throw new \Exception("View [{$viewPath}] not found em [{$viewFile}].");
        }

        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        $layoutFile = __DIR__ . '/../../app/Views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            include $layoutFile;
        } else {
            echo $content;
        }
    }

    protected function json(mixed $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function redirect(string $path): void
    {
        $base = rtrim($_ENV['APP_URL'] ?? '', '/');
        header('Location: ' . $base . '/' . ltrim($path, '/'));
        exit;
    }

    protected function back(): void
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? '/';
        header('Location: ' . $ref);
        exit;
    }

    protected function requireAuth(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
    }

    protected function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function validateCsrf(): void
    {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            die('CSRF token inválido.');
        }
    }

    protected function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function sanitize(string $value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    protected function flashError(string $msg): void
    {
        $_SESSION['flash_error'] = $msg;
    }

    protected function flashSuccess(string $msg): void
    {
        $_SESSION['flash_success'] = $msg;
    }
}
