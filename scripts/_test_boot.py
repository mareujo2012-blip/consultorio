import ftplib
import urllib.request

FTP_HOST = '187.110.162.234'
FTP_PORT = 21
FTP_USER = 'consultorio@marcodaros.com.br'
FTP_PASS = '90860Placa8010@#$'
FTP_ROOT = '/public_html/consultorio'

ftp = ftplib.FTP()
ftp.connect(FTP_HOST, FTP_PORT)
ftp.login(FTP_USER, FTP_PASS)
ftp.set_pasv(True)

with open("e:/ProjetosAntigravity/ControleConsultorio/public/test_boot.php", "rb") as f:
    ftp.storbinary(f'STOR {FTP_ROOT}/test_boot.php', f)

ftp.quit()

try:
    response = urllib.request.urlopen("https://consultorio.marcodaros.com.br/test_boot.php")
    print(response.read().decode('utf-8'))
except Exception as e:
    import urllib.error
    if isinstance(e, urllib.error.HTTPError):
        print(f"HTTP Error {e.code}: {e.read().decode('utf-8')}")
    else:
        print(f"Error accessing script: {e}")
