import ftplib

FTP_HOST = '187.110.162.234'
FTP_PORT = 21
FTP_USER = 'consultorio@marcodaros.com.br'
FTP_PASS = '90860Placa8010@#$'
FTP_ROOT = '/public_html/consultorio'

ftp = ftplib.FTP()
ftp.connect(FTP_HOST, FTP_PORT)
ftp.login(FTP_USER, FTP_PASS)
ftp.set_pasv(True)

local_path = "e:/ProjetosAntigravity/ControleConsultorio/app/Views/patients/edit.php"
remote_path = f"{FTP_ROOT}/app/Views/patients/edit.php"

with open(local_path, "rb") as f:
    ftp.storbinary(f'STOR {remote_path}', f)
    print("ENVIADO!")

ftp.quit()
