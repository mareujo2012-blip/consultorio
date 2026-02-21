import ftplib
import os

FTP_HOST = '187.110.162.234'
FTP_PORT = 21
FTP_USER = 'consultorio@marcodaros.com.br'
FTP_PASS = '90860Placa8010@#$'
FTP_ROOT = '/public_html/consultorio'

def mkd_p(ftp, path):
    parts = path.split('/')
    cur = ''
    for p in parts:
        if not p: continue
        cur += '/' + p
        try:
            ftp.mkd(cur)
        except ftplib.error_perm:
            pass

ftp = ftplib.FTP()
ftp.connect(FTP_HOST, FTP_PORT)
ftp.login(FTP_USER, FTP_PASS)
ftp.set_pasv(True)

# create dirs
mkd_p(ftp, f"{FTP_ROOT}/app/Views/users")

files_to_upload = [
    ('bootstrap/app.php', f'{FTP_ROOT}/bootstrap/app.php'),
    ('app/Controllers/MedicalRecordController.php', f'{FTP_ROOT}/app/Controllers/MedicalRecordController.php'),
    ('routes/web.php', f'{FTP_ROOT}/routes/web.php'),
    ('app/Models/User.php', f'{FTP_ROOT}/app/Models/User.php'),
    ('app/Controllers/UserController.php', f'{FTP_ROOT}/app/Controllers/UserController.php'),
    ('app/Views/partials/sidebar.php', f'{FTP_ROOT}/app/Views/partials/sidebar.php'),
    ('app/Views/users/index.php', f'{FTP_ROOT}/app/Views/users/index.php'),
    ('app/Views/users/create.php', f'{FTP_ROOT}/app/Views/users/create.php'),
    ('app/Views/users/edit.php', f'{FTP_ROOT}/app/Views/users/edit.php')
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
print("Deploy Fase 3 + Modulo de Usuarios concluído.")
