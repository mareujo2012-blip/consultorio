import ftplib
import urllib.request
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

try:
    with open("e:/ProjetosAntigravity/ControleConsultorio/routes/web.php", "rb") as f:
        ftp.storbinary(f'STOR {FTP_ROOT}/routes/web.php', f)
    print("Web.php upload success")
except Exception as e:
    print(e)
ftp.quit()

try:
    response = urllib.request.urlopen("https://consultorio.marcodaros.com.br/api/debug-users")
    print(response.read().decode('utf-8'))
except Exception as e:
    print(e)
