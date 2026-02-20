<?php

namespace App\Config;

class App
{
    public const VERSION = '1.0.0';
    public const APP_NAME = 'ControleConsultório';
    public const SESSION_TIMEOUT = 3600; // 1 hour
    public const UPLOAD_DIR = __DIR__ . '/../../public/uploads/';
    public const LOGO_DIR = __DIR__ . '/../../public/uploads/logos/';
    public const PHOTOS_DIR = __DIR__ . '/../../public/uploads/photos/';
    public const LOGS_DIR = __DIR__ . '/../../logs/';
    public const MIGRATIONS_DIR = __DIR__ . '/../../database/migrations/';

    public static function url(string $path = ''): string
    {
        $base = rtrim($_ENV['APP_URL'] ?? 'https://consultorio.marcodaros.com.br', '/');
        return $base . '/' . ltrim($path, '/');
    }

    public static function env(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $default;
    }
}
