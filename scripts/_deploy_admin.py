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

files_to_upload = [
    ('public/setup_admin.php', f'{FTP_ROOT}/public/setup_admin.php'),
    ('app/Views/auth/login.php', f'{FTP_ROOT}/app/Views/auth/login.php')
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
print("Upload concluído. Rodando setup DB...")

try:
    response = urllib.request.urlopen("https://consultorio.marcodaros.com.br/setup_admin.php")
    print(response.read().decode('utf-8'))
except Exception as e:
    import urllib.error
    if isinstance(e, urllib.error.HTTPError):
        print(f"HTTP Error {e.code}: {e.read().decode('utf-8')}")
    else:
        print(f"Error accessing script: {e}")
