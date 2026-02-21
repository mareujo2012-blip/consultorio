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

import io
mem_file = io.BytesIO()

try:
    ftp.retrbinary(f'RETR {FTP_ROOT}/storage/logs/error.log', mem_file.write)
    print("LOG:")
    content = mem_file.getvalue().decode('utf-8')
    lines = content.strip().split('\n')
    for line in lines[-20:]:
        print(line)
except Exception as e:
    print(f"Erro ao baixar log: {e}")

ftp.quit()
