#!/usr/bin/env python3
"""Corrige o DB_HOST no .env e remove arquivos temporarios"""
import ftplib
import io

FTP_HOST = '187.110.162.234'
FTP_PORT = 21
FTP_USER = 'consultorio@marcodaros.com.br'
FTP_PASS = '90860Placa8010@#$'
FTP_ROOT = '/public_html/consultorio'

# Novo conteúdo do .env usando localhost
env_content = """APP_ENV=production
APP_URL=https://consultorio.marcodaros.com.br
APP_DEBUG=false

DB_HOST=localhost
DB_PORT=3306
DB_NAME=prod9474_consultorio
DB_USER=prod9474_consultorio
DB_PASS=90860Placa8010@#$

SESSION_TIMEOUT=3600
"""

ftp = ftplib.FTP()
ftp.connect(FTP_HOST, FTP_PORT, timeout=30)
ftp.login(FTP_USER, FTP_PASS)
ftp.set_pasv(True)

# 1. Atualizar .env
ftp.storbinary(f'STOR {FTP_ROOT}/.env', io.BytesIO(env_content.encode('utf-8')))
print("OK: .env atualizado para localhost")

# 2. Limpeza de arquivos (na raiz e na public)
files_to_delete = [
    f'{FTP_ROOT}/public/diag.php',
    f'{FTP_ROOT}/public/run_composer.php',
    f'{FTP_ROOT}/public/install.php',
    f'{FTP_ROOT}/composer.phar'
]

for f in files_to_delete:
    try:
        ftp.delete(f)
        print(f"DELETADO: {f}")
    except:
        print(f"PULADO (ja removido ou inexistente): {f}")

ftp.quit()
print("\nConcluido! Tente fazer o login agora.")
