#!/usr/bin/env python3
"""Envia PHP que tenta carregar o bootstrap e mostra o erro exato"""
import ftplib
import io

FTP_HOST = '187.110.162.234'
FTP_PORT = 21
FTP_USER = 'consultorio@marcodaros.com.br'
FTP_PASS = '90860Placa8010@#$'
FTP_PUBLIC = '/public_html/consultorio/public'

php = r"""<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

echo '<pre>';
echo "=== TESTE DO AUTOLOADER ===\n";

// 1. Tentar carregar o autoload
$autoload = dirname(__DIR__) . '/vendor/autoload.php';
echo "1. require vendor/autoload.php: ";
try {
    require $autoload;
    echo "OK\n";
} catch (\Throwable $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
    echo "   em: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo '</pre>';
    exit;
}

// 2. Testar classes do app
echo "\n=== TESTE DAS CLASSES DA APP ===\n";
$classes = [
    'App\Core\Router',
    'App\Config\App',
    'App\Config\Database',
    'App\Controllers\AuthController',
];
foreach ($classes as $class) {
    echo "  class $class: ";
    echo (class_exists($class) ? "OK\n" : "NAO ENCONTRADA\n");
}

// 3. Tentar carregar o bootstrap direto
echo "\n=== TENTANDO CARREGAR bootstrap/app.php ===\n";
$bootstrap = dirname(__DIR__) . '/bootstrap/app.php';
echo "bootstrap existe: " . (file_exists($bootstrap) ? 'SIM' : 'NAO') . "\n";

// Verificar routes/web.php
$routes = dirname(__DIR__) . '/routes/web.php';
echo "routes/web.php existe: " . (file_exists($routes) ? 'SIM' : 'NAO') . "\n";

// Verificar app/Core/Router.php
$router_file = dirname(__DIR__) . '/app/Core/Router.php';
echo "app/Core/Router.php existe: " . (file_exists($router_file) ? 'SIM' : 'NAO') . "\n";

// 4. Simular o que o bootstrap faz
echo "\n=== SIMULANDO BOOTSTRAP ===\n";
define('ROOT_PATH', dirname(__DIR__));
define('APP_START', microtime(true));

// Carregar .env
$envFile = ROOT_PATH . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with($line, '#') || !str_contains($line, '=')) continue;
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value, " \t\n\r\0\x0B\"'");
    }
    echo ".env carregado OK\n";
    echo "APP_URL = " . ($_ENV['APP_URL'] ?? 'NAO DEFINIDO') . "\n";
    echo "DB_HOST = " . ($_ENV['DB_HOST'] ?? 'NAO DEFINIDO') . "\n";
}

// Testar Router
echo "\n=== TESTE DO ROUTER ===\n";
try {
    if (file_exists($router_file)) {
        require_once $router_file;
        $r = new \App\Core\Router();
        echo "Router instanciado: OK\n";
    }
} catch (\Throwable $e) {
    echo "Router ERRO: " . $e->getMessage() . "\n";
    echo "  em: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

// Testar routes/web.php
echo "\n=== CARREGANDO routes/web.php ===\n";
try {
    if (file_exists($routes) && isset($r)) {
        $router = include $routes;
        echo "routes/web.php carregado: OK\n";
    }
} catch (\Throwable $e) {
    echo "routes ERRO: " . $e->getMessage() . "\n";
    echo "  em: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== FIM DO DIAGNOSTICO ===\n";
echo '</pre>';
"""

ftp = ftplib.FTP()
ftp.connect(FTP_HOST, FTP_PORT, timeout=30)
ftp.login(FTP_USER, FTP_PASS)
ftp.set_pasv(True)

ftp.storbinary(f'STOR {FTP_PUBLIC}/diag.php', io.BytesIO(php.encode('utf-8')))
print(f'OK: {FTP_PUBLIC}/diag.php')
print('Acesse: https://consultorio.marcodaros.com.br/diag.php')
ftp.quit()
