#!/usr/bin/env python3
"""
ftp_upload.py — Upload completo do projeto via FTP
"""
import ftplib
import os
import time
import sys

FTP_HOST  = "187.110.162.234"
FTP_PORT  = 21
FTP_USER  = "consultorio@marcodaros.com.br"
FTP_PASS  = "90860Placa8010@#$"
FTP_ROOT  = "/domains/marcodaros.com.br/public_html/consultorio"

ROOT_DIR  = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))

# Pastas a excluir do upload
EXCLUDE_DIRS  = {".git", ".vscode", ".idea", "node_modules", "logs", "__pycache__"}
EXCLUDE_FILES = {".gitignore", "ftp_upload.py", "deploy_python.py", "run-all.sh",
                 "deploy.sh", "migrate.sh", "seed.sh", "git-push.sh", "rollback.sh",
                 "setup-server.sh"}
EXCLUDE_EXTS  = {".sh", ".gz", ".zip"}

def log(msg):
    print(f"[{time.strftime('%H:%M:%S')}] {msg}", flush=True)

def should_skip(name, is_dir):
    if is_dir and name in EXCLUDE_DIRS:
        return True
    if not is_dir:
        if name in EXCLUDE_FILES:
            return True
        _, ext = os.path.splitext(name)
        if ext.lower() in EXCLUDE_EXTS:
            return True
        # Skip .env file — it contains credentials
        if name == '.env':
            return True
    return False

def ftp_mkdir_p(ftp, path):
    parts = path.replace("\\", "/").split("/")
    cur = ""
    for p in parts:
        if not p:
            continue
        cur = cur + "/" + p
        try:
            ftp.mkd(cur)
        except ftplib.error_perm:
            pass

def upload_dir(ftp, local_dir, remote_dir, counter):
    items = sorted(os.listdir(local_dir))
    for name in items:
        local_path  = os.path.join(local_dir, name)
        is_dir      = os.path.isdir(local_path)
        remote_path = remote_dir.rstrip("/") + "/" + name

        if should_skip(name, is_dir):
            continue

        if is_dir:
            ftp_mkdir_p(ftp, remote_path)
            counter = upload_dir(ftp, local_path, remote_path, counter)
        else:
            try:
                with open(local_path, "rb") as f:
                    ftp.storbinary(f"STOR {remote_path}", f, blocksize=32768)
                counter[0] += 1
                if counter[0] % 10 == 0:
                    log(f"  {counter[0]} arquivos enviados...")
            except Exception as e:
                log(f"  WARN: {name} — {e}")
    return counter

def main():
    log("=" * 56)
    log("  CONSULTÓRIO — FTP UPLOAD")
    log(f"  Host: {FTP_HOST}:{FTP_PORT}")
    log(f"  Destino: {FTP_ROOT}")
    log("=" * 56)

    log("Conectando ao FTP...")
    try:
        ftp = ftplib.FTP()
        ftp.connect(FTP_HOST, FTP_PORT, timeout=60)
        ftp.login(FTP_USER, FTP_PASS)
        ftp.set_pasv(True)
        welcome = ftp.getwelcome()
        log(f"✅ FTP conectado! {welcome[:80]}")
    except Exception as e:
        log(f"❌ Falha FTP: {e}")
        sys.exit(1)

    # Garantir diretório raiz
    ftp_mkdir_p(ftp, FTP_ROOT)
    log(f"📁 Diretório remoto: {FTP_ROOT}")

    log(f"📤 Iniciando upload de: {ROOT_DIR}")
    log(f"   (excluindo: .git, logs/, scripts/, .env)")

    start   = time.time()
    counter = [0]

    upload_dir(ftp, ROOT_DIR, FTP_ROOT, counter)

    elapsed = time.time() - start
    ftp.quit()

    log("=" * 56)
    log(f"✅ UPLOAD COMPLETO!")
    log(f"   Arquivos enviados: {counter[0]}")
    log(f"   Tempo: {elapsed:.1f}s")
    log("=" * 56)
    log("")
    log("PRÓXIMO PASSO:")
    log("  Acesse no browser:")
    log("  https://consultorio.marcodaros.com.br/install.php?token=cc2026install99")
    log("  Isso irá criar as tabelas e configurar o sistema.")

if __name__ == "__main__":
    main()
