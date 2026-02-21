#!/usr/bin/env python3
"""Corrige o autoload_psr4.php adicionando o namespace App da aplicação"""
import ftplib
import io

FTP_HOST = '187.110.162.234'
FTP_PORT = 21
FTP_USER = 'consultorio@marcodaros.com.br'
FTP_PASS = '90860Placa8010@#$'
FTP_ROOT = '/public_html/consultorio'

# autoload_psr4.php CORRETO — inclui App\\ (a aplicação) + dependências
psr4 = r"""<?php
$vendorDir = dirname(__DIR__);
$baseDir = dirname($vendorDir);

return array(
    'App\\' => array($baseDir . '/app'),
    'Dompdf\\' => array($vendorDir . '/dompdf/dompdf/src'),
    'FontLib\\' => array($vendorDir . '/phenx/php-font-lib/src'),
    'Svg\\' => array($vendorDir . '/phenx/php-svg-lib/src'),
);
"""

ftp = ftplib.FTP()
ftp.connect(FTP_HOST, FTP_PORT, timeout=30)
ftp.login(FTP_USER, FTP_PASS)
ftp.set_pasv(True)

remote = f'{FTP_ROOT}/vendor/composer/autoload_psr4.php'
ftp.storbinary(f'STOR {remote}', io.BytesIO(psr4.encode('utf-8')))
print(f'OK: {remote}')

ftp.quit()
print('Autoloader corrigido! Namespace App\\\\ adicionado.')
