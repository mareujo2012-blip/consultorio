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

files_to_upload = [
    ('routes/web.php', f'{FTP_ROOT}/routes/web.php'),
    ('app/Controllers/FinancialController.php', f'{FTP_ROOT}/app/Controllers/FinancialController.php'),
    ('app/Views/financial/index.php', f'{FTP_ROOT}/app/Views/financial/index.php')
]

for local, remote in files_to_upload:
    local_path = os.path.join("e:/ProjetosAntigravity/ControleConsultorio", local)
    try:
        with open(local_path, "rb") as f:
            ftp.storbinary(f'STOR {remote}', f)
            print(f"ENVIADO! {remote}")
    except Exception as e:
        print(f"ERRO enviando {local}: {e}")

ftp.quit()
