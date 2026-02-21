#!/usr/bin/env python3
"""
ftp_only_deploy.py — Upload FTP apenas (sem banco, sem migrations)
===================================================================
Use este script quando o banco de dados não aceita conexão externa.
Após o upload, acesse o install.php pelo browser para criar as tabelas.

FTP Root CORRETO: /public_html/consultorio

EXECUTAR: python scripts/ftp_only_deploy.py
"""
import ftplib
import os
import sys
import time
import datetime
import io

FTP_HOST  = "187.110.162.234"
FTP_PORT  = 21
FTP_USER  = "consultorio@marcodaros.com.br"
FTP_PASS  = "90860Placa8010@#$"
FTP_ROOT  = "/public_html/consultorio"   # ✅ CAMINHO CORRETO

ROOT_DIR  = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))

# Pastas/arquivos a EXCLUIR do upload
EXCLUDE_DIRS  = {".git", ".vscode", ".agent", "_agent", "node_modules",
                 "logs", "scripts", ".idea", "__pycache__"}
EXCLUDE_FILES = {".env", ".gitignore"}
EXCLUDE_EXTS  = {".sh", ".gz", ".log", ".py"}

def log(msg, emoji="ℹ️"):
    ts = datetime.datetime.now().strftime("%H:%M:%S")
    print(f"[{ts}] {emoji}  {msg}", flush=True)

def separator(title=""):
    print(f"\n{'='*60}", flush=True)
    if title:
        print(f"  {title}", flush=True)
        print(f"{'='*60}", flush=True)

def should_exclude(name, is_dir):
    if is_dir and name in EXCLUDE_DIRS:
        return True
    if not is_dir:
        if name in EXCLUDE_FILES:
            return True
        _, ext = os.path.splitext(name)
        if ext.lower() in EXCLUDE_EXTS:
            return True
    return False

def ftp_mkdirs(ftp, remote_dir):
    parts = remote_dir.replace("\\", "/").split("/")
    current = ""
    for part in parts:
        if not part:
            continue
        current = current + "/" + part
        try:
            ftp.mkd(current)
        except ftplib.error_perm:
            pass

def upload_directory(ftp, local_dir, remote_dir, counter):
    for item in sorted(os.listdir(local_dir)):
        local_path  = os.path.join(local_dir, item)
        is_dir      = os.path.isdir(local_path)
        if should_exclude(item, is_dir):
            continue
        remote_path = remote_dir.rstrip("/") + "/" + item
        if is_dir:
            ftp_mkdirs(ftp, remote_path)
            upload_directory(ftp, local_path, remote_path, counter)
        else:
            try:
                with open(local_path, "rb") as f:
                    ftp.storbinary(f"STOR {remote_path}", f, blocksize=32768)
                counter[0] += 1
                if counter[0] % 20 == 0:
                    log(f"  ... {counter[0]} arquivos enviados", "📤")
            except Exception as e:
                log(f"  WARN upload {item}: {e}", "⚠️")

def create_env_on_server(ftp):
    env_content = (
        "APP_ENV=production\n"
        "APP_URL=https://consultorio.marcodaros.com.br\n"
        "APP_DEBUG=false\n"
        "\n"
        "DB_HOST=187.110.162.234\n"
        "DB_PORT=3306\n"
        "DB_NAME=prod9474_consultorio\n"
        "DB_USER=prod9474_consultorio\n"
        "DB_PASS=90860Placa8010@#$\n"
        "\n"
        "SESSION_TIMEOUT=3600\n"
    )
    env_bytes = env_content.encode("utf-8")
    ftp.storbinary(f"STOR {FTP_ROOT}/.env", io.BytesIO(env_bytes))
    log(f".env criado no servidor", "✅")

if __name__ == "__main__":
    separator("CONSULTÓRIO — UPLOAD FTP (caminho corrigido)")
    log(f"Início: {datetime.datetime.now().strftime('%d/%m/%Y %H:%M:%S')}", "🚀")
    log(f"FTP Root: {FTP_ROOT}", "📍")

    log("Conectando ao FTP...", "🔌")
    try:
        ftp = ftplib.FTP()
        ftp.connect(FTP_HOST, FTP_PORT, timeout=60)
        ftp.login(FTP_USER, FTP_PASS)
        ftp.set_pasv(True)
        log("FTP conectado!", "✅")
    except Exception as e:
        log(f"Falha FTP: {e}", "❌")
        sys.exit(1)

    ftp_mkdirs(ftp, FTP_ROOT)

    counter = [0]
    start   = time.time()

    log(f"Enviando projeto de: {ROOT_DIR}", "📤")
    log("(Excluindo: .git, .env, logs/, scripts/)", "ℹ️")

    upload_directory(ftp, ROOT_DIR, FTP_ROOT, counter)

    log("Criando .env no servidor...", "🔧")
    create_env_on_server(ftp)

    elapsed = time.time() - start
    ftp.quit()

    separator("UPLOAD CONCLUÍDO!")
    log(f"Arquivos enviados: {counter[0]}", "✅")
    log(f"Tempo total: {elapsed:.1f}s", "⏱️")
    log("")
    log("PRÓXIMOS PASSOS:", "📋")
    log("")
    log("1️⃣  NO CPANEL — Alterar DocumentRoot do subdomínio:", "")
    log("    Subdomínio: consultorio.marcodaros.com.br", "")
    log("    DocumentRoot ATUAL:    /public_html/consultorio", "")
    log("    DocumentRoot CORRETO:  /public_html/consultorio/public", "")
    log("")
    log("2️⃣  NO BROWSER — Rodar o instalador do banco:", "")
    log("    https://consultorio.marcodaros.com.br/install.php", "")
    log("")
    log("3️⃣  APÓS INSTALAR — Fazer login:", "")
    log("    https://consultorio.marcodaros.com.br", "🌐")
    log("    Login: admin@consultorio.marcodaros.com.br", "👤")
    log("    Senha: Admin@2026!", "🔑")
    log("    ⚠️  TROQUE A SENHA NO PRIMEIRO ACESSO!", "⚠️")
