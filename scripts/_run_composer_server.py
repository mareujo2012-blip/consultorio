#!/usr/bin/env python3
"""
Envia um arquivo PHP que executa o composer install no servidor
"""
import ftplib
import io

FTP_HOST = '187.110.162.234'
FTP_PORT = 21
FTP_USER = 'consultorio@marcodaros.com.br'
FTP_PASS = '90860Placa8010@#$'
FTP_PUBLIC = '/public_html/consultorio/public'

# Este PHP roda o composer install direto no servidor via exec()
run_composer_php = """<?php
// Seguranca: token obrigatorio
if (($_GET['token'] ?? '') !== 'cc2026install99') {
    die('Acesso negado.');
}

$projectRoot = dirname(__DIR__);
$composerPhar = $projectRoot . '/composer.phar';
$vendorAutoload = $projectRoot . '/vendor/autoload.php';

echo '<pre>';
echo "Project Root: $projectRoot\\n";
echo "composer.phar exists: " . (file_exists($composerPhar) ? 'YES' : 'NO') . "\\n";
echo "vendor/autoload.php exists: " . (file_exists($vendorAutoload) ? 'YES' : 'NO') . "\\n\\n";

if (file_exists($vendorAutoload)) {
    echo "vendor/ ja existe! Nada a fazer.\\n";
    echo '</pre>';
    exit;
}

if (!file_exists($composerPhar)) {
    echo "Baixando composer.phar...\\n";
    flush();
    $data = file_get_contents('https://getcomposer.org/composer-stable.phar');
    if ($data) {
        file_put_contents($composerPhar, $data);
        echo "composer.phar baixado!\\n";
    } else {
        echo "ERRO: Nao foi possivel baixar composer.phar\\n";
        echo '</pre>';
        exit;
    }
}

echo "Executando: php composer.phar install --no-dev --no-interaction\\n";
echo str_repeat('-', 60) . "\\n";
flush();

$cmd = 'cd ' . escapeshellarg($projectRoot) 
     . ' && php composer.phar install --no-dev --no-interaction --no-scripts 2>&1';

$output = [];
$retCode = 0;
exec($cmd, $output, $retCode);

foreach ($output as $line) {
    echo htmlspecialchars($line) . "\\n";
    flush();
}

echo str_repeat('-', 60) . "\\n";
echo "Exit code: $retCode\\n";
echo "vendor/autoload.php now: " . (file_exists($vendorAutoload) ? 'EXISTS!' : 'STILL MISSING') . "\\n";

if (file_exists($vendorAutoload)) {
    echo "\\n=== SUCESSO! Composer instalado. ===\\n";
    echo "Acesse: https://consultorio.marcodaros.com.br\\n";
} else {
    echo "\\n=== FALHOU. Tente via SSH: ===\\n";
    echo "cd /home/prod9474/domains/marcodaros.com.br/public_html/consultorio\\n";
    echo "php composer.phar install --no-dev --no-interaction\\n";
}

echo '</pre>';
"""

content = run_composer_php.encode('utf-8')

ftp = ftplib.FTP()
ftp.connect(FTP_HOST, FTP_PORT, timeout=30)
ftp.login(FTP_USER, FTP_PASS)
ftp.set_pasv(True)

ftp.storbinary(f'STOR {FTP_PUBLIC}/run_composer.php', io.BytesIO(content))
print(f'OK: {FTP_PUBLIC}/run_composer.php')
print('Acesse: https://consultorio.marcodaros.com.br/run_composer.php?token=cc2026install99')

ftp.quit()
print('FTP OK')
