#!/usr/bin/env python3
"""Lista a estrutura FTP para descobrir os caminhos corretos"""
import ftplib

FTP_HOST = "187.110.162.234"
FTP_PORT = 21
FTP_USER = "consultorio@marcodaros.com.br"
FTP_PASS = "90860Placa8010@#$"

ftp = ftplib.FTP()
ftp.connect(FTP_HOST, FTP_PORT, timeout=30)
ftp.login(FTP_USER, FTP_PASS)
ftp.set_pasv(True)

print("=== DIRETÓRIO INICIAL (HOME) ===")
print("PWD:", ftp.pwd())

print("\n=== LISTAGEM RAIZ ===")
try:
    ftp.retrlines("LIST /", print)
except: pass

print("\n=== LISTAGEM /domains/ ===")
try:
    ftp.retrlines("LIST /domains/", print)
except: pass

print("\n=== LISTAGEM /domains/marcodaros.com.br/ ===")
try:
    ftp.retrlines("LIST /domains/marcodaros.com.br/", print)
except: pass

print("\n=== LISTAGEM /domains/marcodaros.com.br/public_html/ ===")
try:
    ftp.retrlines("LIST /domains/marcodaros.com.br/public_html/", print)
except: pass

print("\n=== LISTAGEM /domains/marcodaros.com.br/public_html/consultorio/ ===")
try:
    ftp.retrlines("LIST /domains/marcodaros.com.br/public_html/consultorio/", print)
except Exception as e:
    print(f"ERRO: {e}")

ftp.quit()
