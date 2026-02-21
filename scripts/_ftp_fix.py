#!/usr/bin/env python3
"""Move install.php para public_html raiz e também lista /public_html"""
import ftplib

FTP_HOST = "187.110.162.234"
FTP_PORT = 21
FTP_USER = "consultorio@marcodaros.com.br"
FTP_PASS = "90860Placa8010@#$"

LOCAL = r"e:/ProjetosAntigravity/ControleConsultorio/public/install.php"

ftp = ftplib.FTP()
ftp.connect(FTP_HOST, FTP_PORT, timeout=30)
ftp.login(FTP_USER, FTP_PASS)
ftp.set_pasv(True)

# List public_html raiz para descobrir o que está servindo
print("=== /public_html/ ===")
ftp.retrlines("LIST /public_html/", print)

# Upload para /public_html/consultorio/public/ (o DocumentRoot real do app)
targets = [
    "/public_html/install.php",                     # raiz do domínio principal
    "/public_html/consultorio/public/install.php",  # dentro do app (DocumentRoot configurado)
]

for t in targets:
    with open(LOCAL, "rb") as f:
        ftp.storbinary(f"STOR {t}", f)
    print(f"UPLOADED: {t}")

ftp.quit()
print("DONE")
