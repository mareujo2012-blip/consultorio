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

files_to_delete = [
    f"{FTP_ROOT}/public/test_ap2.php",
    f"{FTP_ROOT}/public/test_boot.php",
    f"{FTP_ROOT}/public/test_prescription_pdf.php",
    f"{FTP_ROOT}/public/install.php",
    f"{FTP_ROOT}/public/setup_admin.php"
]

print("Iniciando limpeza no servidor FTP...")

for file_path in files_to_delete:
    try:
        ftp.delete(file_path)
        print(f"DELETADO: {file_path}")
    except ftplib.error_perm as e:
        # 550 significa que o arquivo não existe, o que é aceitável na limpeza
        if "550" in str(e):
            print(f"IGNORADO (Nao encontrado): {file_path}")
        else:
            print(f"ERRO ao deletar {file_path}: {e}")
    except Exception as e:
        print(f"ERRO inesperado ao deletar {file_path}: {e}")

ftp.quit()
print("Limpeza concluída.")
