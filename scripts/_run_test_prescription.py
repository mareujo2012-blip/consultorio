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

local_path = "e:/ProjetosAntigravity/ControleConsultorio/public/test_prescription_pdf.php"
remote_path = f"{FTP_ROOT}/public/test_prescription_pdf.php"

try:
    with open(local_path, "rb") as f:
        ftp.storbinary(f'STOR {remote_path}', f)
        print("ENVIADO test_prescription_pdf.php!")
except Exception as e:
    print(f"ERRO enviando: {e}")

ftp.quit()

try:
    response = urllib.request.urlopen("https://consultorio.marcodaros.com.br/test_prescription_pdf.php")
    data = response.read()
    if data.startswith(b'%PDF'):
        print("SUCESSO: PDF gerado!")
    else:
        print("\nRESPOSTA:\n" + data.decode('utf-8', errors='replace'))
except Exception as e:
    import urllib.error
    if isinstance(e, urllib.error.HTTPError):
        print(f"HTTP Error {e.code}:\n{e.read().decode('utf-8', errors='replace')}")
    else:
        print(f"Error accessing script: {e}")
