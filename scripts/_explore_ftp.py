#!/usr/bin/env python3
"""Verifica o que foi enviado ao servidor"""
import ftplib

FTP_HOST = '187.110.162.234'
FTP_PORT = 21
FTP_USER = 'consultorio@marcodaros.com.br'
FTP_PASS = '90860Placa8010@#$'

LOG_FILE = r'e:\ProjetosAntigravity\ControleConsultorio\logs\ftp_diag.txt'
lines = []

ftp = ftplib.FTP()
ftp.connect(FTP_HOST, FTP_PORT, timeout=30)
ftp.login(FTP_USER, FTP_PASS)
ftp.set_pasv(True)

lines.append(f"HOME PWD: {ftp.pwd()}")

paths = [
    '/public_html/consultorio',
    '/public_html/consultorio/public',
    '/public_html/consultorio/app',
    '/public_html/consultorio/bootstrap',
    '/public_html/consultorio/vendor',
    '/public_html/consultorio/routes',
    '/public_html/consultorio/database',
]

for path in paths:
    lines.append(f"\n{'='*60}")
    lines.append(f"PATH: {path}")
    try:
        ftp.cwd(path)
        items = []
        ftp.retrlines('NLST', items.append)
        items = [i for i in items if i not in ('.', '..') and not i.endswith('/.') and not i.endswith('/..')  ]
        for item in sorted(items):
            name = item.split('/')[-1]
            if name and name not in ('.', '..'):
                lines.append(f"  {name}")
        if not items:
            lines.append("  (VAZIO)")
    except Exception as e:
        lines.append(f"  NAO EXISTE: {e}")

ftp.quit()
lines.append("\nFTP OK")

with open(LOG_FILE, 'w', encoding='utf-8') as f:
    f.write('\n'.join(lines))
print(f"Salvo em {LOG_FILE}")
