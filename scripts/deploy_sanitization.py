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

def ftp_mkdirs(ftp, remote_dir):
    parts = remote_dir.replace('\\', '/').split('/')
    cur = ''
    for part in parts:
        if not part: continue
        cur = cur + '/' + part
        try:
            ftp.mkd(cur)
        except:
            pass

files_to_upload = [
    ('app/Core/Router.php', f'{FTP_ROOT}/app/Core/Router.php'),
    ('app/Views/errors/500.php', f'{FTP_ROOT}/app/Views/errors/500.php')
]

# Ensure directory exists
ftp_mkdirs(ftp, f"{FTP_ROOT}/app/Views/errors")

for local, remote in files_to_upload:
    local_path = os.path.join("e:/ProjetosAntigravity/ControleConsultorio", local)
    try:
        with open(local_path, "rb") as f:
            ftp.storbinary(f'STOR {remote}', f)
            print(f"ENVIADO! {remote}")
    except Exception as e:
        print(f"ERRO enviando {local}: {e}")

ftp.quit()
print("Deploy de Sanitização e Páginas de Erro Concluído.")
