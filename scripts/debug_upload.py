import ftplib
import os

FTP_HOST = '187.110.162.234'
FTP_PORT = 21
FTP_USER = 'consultorio@marcodaros.com.br'
FTP_PASS = '90860Placa8010@#$'
FTP_ROOT = '/public_html/consultorio'

ftp = ftplib.FTP()
ftp.connect(FTP_HOST, FTP_PORT)
ftp.login(FTP_USER, FTP_PASS)
ftp.set_pasv(True)

local_file = "e:/ProjetosAntigravity/ControleConsultorio/public/debug_login.php"
remote_file = f"{FTP_ROOT}/public/debug_login.php"

print(f"Uploading {local_file} to {remote_file}")
with open(local_file, "rb") as f:
    res = ftp.storbinary(f"STOR {remote_file}", f)
    print(f"Result: {res}")

ftp.quit()
