#!/usr/bin/env python3
"""Envia os novos arquivos de design premium para o servidor"""
import ftplib
import os

FTP_HOST = '187.110.162.234'
FTP_PORT = 21
FTP_USER = 'consultorio@marcodaros.com.br'
FTP_PASS = '90860Placa8010@#$'
FTP_ROOT = '/public_html/consultorio'

files_to_upload = [
    ('public/assets/css/style.css', f'{FTP_ROOT}/public/assets/css/style.css'),
    ('app/Views/layouts/auth.php', f'{FTP_ROOT}/app/Views/layouts/auth.php'),
    ('app/Views/layouts/main.php', f'{FTP_ROOT}/app/Views/layouts/main.php'),
    ('app/Views/auth/login.php', f'{FTP_ROOT}/app/Views/auth/login.php'),
    ('app/Views/dashboard/index.php', f'{FTP_ROOT}/app/Views/dashboard/index.php'),
    ('app/Views/partials/sidebar.php', f'{FTP_ROOT}/app/Views/partials/sidebar.php'),
]

ftp = ftplib.FTP()
ftp.connect(FTP_HOST, FTP_PORT)
ftp.login(FTP_USER, FTP_PASS)
ftp.set_pasv(True)

# Garantir que o diretorio de assets existe no servidor
try:
    ftp.mkd('/public_html/consultorio/public/assets')
except: pass
try:
    ftp.mkd('/public_html/consultorio/public/assets/css')
except: pass

for local, remote in files_to_upload:
    with open(local, 'rb') as f:
        ftp.storbinary(f'STOR {remote}', f)
        print(f"ENVIADO: {remote}")

ftp.quit()
print("\nDesign Premium aplicado com sucesso!")
