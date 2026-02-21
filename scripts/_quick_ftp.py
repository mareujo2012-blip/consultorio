#!/usr/bin/env python3
import ftplib, sys

FTP_HOST  = "187.110.162.234"
FTP_PORT  = 21
FTP_USER  = "consultorio@marcodaros.com.br"
FTP_PASS  = "90860Placa8010@#$"

# Upload install.php to FTP root AND inside /public/
local = r"e:/ProjetosAntigravity/ControleConsultorio/public/install.php"
targets = [
    "/domains/marcodaros.com.br/public_html/consultorio/install.php",
    "/domains/marcodaros.com.br/public_html/consultorio/public/install.php",
]

ftp = ftplib.FTP()
ftp.connect(FTP_HOST, FTP_PORT, timeout=30)
ftp.login(FTP_USER, FTP_PASS)
ftp.set_pasv(True)
print(f"FTP OK: {ftp.getwelcome()[:60]}")

for target in targets:
    with open(local, "rb") as f:
        ftp.storbinary(f"STOR {target}", f)
    print(f"OK: {target}")

ftp.quit()
print("DONE")
