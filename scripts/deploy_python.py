#!/usr/bin/env python3
"""
deploy_python.py — Deploy completo via Python
Executar: python scripts/deploy_python.py
"""

import pymysql
import ftplib
import os
import sys
import glob
import time
import datetime

# ── Configurações ──────────────────────────────────────────
DB_HOST   = "187.110.162.234"
DB_PORT   = 3306
DB_NAME   = "prod9474_consultorio"
DB_USER   = "prod9474_consultorio"
DB_PASS   = "90860Placa8010@#$"

FTP_HOST  = "187.110.162.234"
FTP_PORT  = 21
FTP_USER  = "consultorio@marcodaros.com.br"
FTP_PASS  = "90860Placa8010@#$"
FTP_ROOT  = "/domains/marcodaros.com.br/public_html/consultorio"

ROOT_DIR  = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
MIGRATIONS_DIR = os.path.join(ROOT_DIR, "database", "migrations")
SEEDS_DIR      = os.path.join(ROOT_DIR, "database", "seeds")

# Pastas/arquivos a EXCLUIR do upload
EXCLUDE_DIRS  = {".git", ".vscode", ".agent", "_agent", "node_modules", "logs", "scripts", ".idea"}
EXCLUDE_FILES = {".env", ".gitignore", "deploy_python.py"}
EXCLUDE_EXTS  = {".sh", ".md", ".lock", ".gz", ".log"}

# ── Helpers ────────────────────────────────────────────────
def log(msg, emoji=""):
    ts = datetime.datetime.now().strftime("%H:%M:%S")
    print(f"[{ts}] {emoji}  {msg}")

def separator(title=""):
    print(f"\n{'='*56}")
    if title:
        print(f"  {title}")
        print(f"{'='*56}")

# ── STEP 1: Testar conexão MySQL ───────────────────────────
def test_db():
    separator("STEP 1 — Testando conexão com banco de dados")
    try:
        conn = pymysql.connect(
            host=DB_HOST, port=DB_PORT,
            user=DB_USER, password=DB_PASS,
            database=DB_NAME,
            charset="utf8mb4",
            connect_timeout=15
        )
        log(f"Conectado: {DB_HOST}:{DB_PORT}/{DB_NAME}", "✅")
        conn.close()
        return True
    except Exception as e:
        log(f"FALHA na conexão: {e}", "❌")
        return False

# ── STEP 2: Rodar migrations ───────────────────────────────
def run_migrations():
    separator("STEP 2 — Rodando Migrations")

    conn = pymysql.connect(
        host=DB_HOST, port=DB_PORT,
        user=DB_USER, password=DB_PASS,
        database=DB_NAME, charset="utf8mb4",
        connect_timeout=15,
        autocommit=True
    )
    cursor = conn.cursor()

    # Cria tabela de controle se não existir
    cursor.execute("""
        CREATE TABLE IF NOT EXISTS `migrations` (
            `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `filename`   VARCHAR(255) NOT NULL,
            `applied_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY `uq_filename` (`filename`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    """)
    log("Tabela de controle verificada", "📋")

    # Pegar applied
    cursor.execute("SELECT filename FROM migrations")
    applied = {row[0] for row in cursor.fetchall()}

    migration_files = sorted(glob.glob(os.path.join(MIGRATIONS_DIR, "*.sql")))
    applied_count = 0

    for filepath in migration_files:
        filename = os.path.basename(filepath)
        if filename in applied:
            log(f"  SKIP  {filename} (já aplicada)", "⏭️")
            continue

        log(f"  APPLY {filename}...", "🔄")
        with open(filepath, "r", encoding="utf-8") as f:
            sql_content = f.read()

        # Executar statement por statement
        statements = [s.strip() for s in sql_content.split(";") if s.strip() and not s.strip().startswith("--")]
        try:
            for stmt in statements:
                if stmt:
                    cursor.execute(stmt)
            cursor.execute("INSERT IGNORE INTO migrations (filename) VALUES (%s)", (filename,))
            log(f"  OK    {filename}", "✅")
            applied_count += 1
        except Exception as e:
            log(f"  FAIL  {filename}: {e}", "❌")
            conn.close()
            sys.exit(1)

    conn.close()
    log(f"Migrations: {applied_count} aplicada(s)", "✅")
    return True

# ── STEP 3: Rodar Seeds ────────────────────────────────────
def run_seeds():
    separator("STEP 3 — Rodando Seeds (dados iniciais)")

    conn = pymysql.connect(
        host=DB_HOST, port=DB_PORT,
        user=DB_USER, password=DB_PASS,
        database=DB_NAME, charset="utf8mb4",
        connect_timeout=15,
        autocommit=True
    )
    cursor = conn.cursor()

    seed_files = sorted(glob.glob(os.path.join(SEEDS_DIR, "*.sql")))
    for filepath in seed_files:
        filename = os.path.basename(filepath)
        log(f"  SEED  {filename}...", "🌱")
        with open(filepath, "r", encoding="utf-8") as f:
            sql_content = f.read()

        statements = [s.strip() for s in sql_content.split(";") if s.strip() and not s.strip().startswith("--")]
        try:
            for stmt in statements:
                if stmt:
                    cursor.execute(stmt)
            log(f"  OK    {filename}", "✅")
        except Exception as e:
            log(f"  WARN  {filename}: {e}", "⚠️")

    conn.close()

# ── STEP 4: Upload FTP ─────────────────────────────────────
def should_exclude(path, name, is_dir):
    """Retorna True se o item deve ser excluído do upload"""
    if name in EXCLUDE_DIRS and is_dir:
        return True
    if name in EXCLUDE_FILES:
        return True
    _, ext = os.path.splitext(name)
    if ext.lower() in EXCLUDE_EXTS:
        return True
    # Exclude vendor binaries but keep PHP files
    return False

def ftp_mkdirs(ftp, remote_dir):
    """Cria diretórios recursivamente no FTP"""
    parts = remote_dir.replace("\\", "/").split("/")
    current = ""
    for part in parts:
        if not part:
            continue
        current = current + "/" + part
        try:
            ftp.mkd(current)
        except ftplib.error_perm:
            pass  # Dir already exists

def upload_directory(ftp, local_dir, remote_dir):
    """Faz upload recursivo de um diretório"""
    total_files = 0
    skipped = 0

    for item in os.listdir(local_dir):
        local_path  = os.path.join(local_dir, item)
        is_dir      = os.path.isdir(local_path)

        if should_exclude(local_path, item, is_dir):
            skipped += 1
            continue

        remote_path = remote_dir.rstrip("/") + "/" + item

        if is_dir:
            try:
                ftp_mkdirs(ftp, remote_path)
            except:
                pass
            sub_total, sub_skip = upload_directory(ftp, local_path, remote_path)
            total_files += sub_total
            skipped += sub_skip
        else:
            try:
                with open(local_path, "rb") as f:
                    ftp.storbinary(f"STOR {remote_path}", f)
                total_files += 1
                if total_files % 20 == 0:
                    log(f"  ... {total_files} arquivos enviados", "📤")
            except Exception as e:
                log(f"  WARN upload {item}: {e}", "⚠️")

    return total_files, skipped

def run_ftp_upload():
    separator("STEP 4 — Upload via FTP")
    log(f"Conectando a {FTP_HOST}:{FTP_PORT} ...", "🔌")

    try:
        ftp = ftplib.FTP()
        ftp.connect(FTP_HOST, FTP_PORT, timeout=30)
        ftp.login(FTP_USER, FTP_PASS)
        ftp.set_pasv(True)
        log(f"Conectado ao FTP!", "✅")
    except Exception as e:
        log(f"Falha FTP: {e}", "❌")
        return False

    # Garantir que o diretório raiz existe
    try:
        ftp_mkdirs(ftp, FTP_ROOT)
        log(f"Diretório raiz: {FTP_ROOT}", "📁")
    except:
        pass

    log(f"Iniciando upload de {ROOT_DIR} ...", "📤")
    log(f"(Excluindo: .git, .env, logs/, scripts/)", "ℹ️")

    start = time.time()
    total, skipped = upload_directory(ftp, ROOT_DIR, FTP_ROOT)
    elapsed = time.time() - start

    ftp.quit()
    log(f"Upload completo: {total} arquivo(s) em {elapsed:.1f}s (ignorados: {skipped})", "✅")
    return True

# ── STEP 5: Validar deployment ─────────────────────────────
def validate():
    separator("STEP 5 — Validando deployment")
    import urllib.request
    url = "https://consultorio.marcodaros.com.br"
    try:
        req = urllib.request.Request(url, headers={"User-Agent": "ConsultorioHealthCheck/1.0"})
        resp = urllib.request.urlopen(req, timeout=15)
        code = resp.getcode()
        log(f"HTTP {code} — Sistema respondendo em {url}", "✅")
    except urllib.error.HTTPError as e:
        if e.code in (200, 302, 301):
            log(f"HTTP {e.code} — OK", "✅")
        else:
            log(f"HTTP {e.code} — Verifique manualmente: {url}", "⚠️")
    except Exception as e:
        log(f"Não foi possível validar automaticamente: {e}", "⚠️")
        log(f"Acesse manualmente: {url}", "ℹ️")

# ── MAIN ───────────────────────────────────────────────────
if __name__ == "__main__":
    separator("CONSULTÓRIO — AUTO DEPLOY COMPLETO")
    log(f"Início: {datetime.datetime.now().strftime('%d/%m/%Y %H:%M:%S')}", "🚀")

    if not test_db():
        sys.exit(1)

    run_migrations()
    run_seeds()
    run_ftp_upload()
    validate()

    separator("DEPLOY FINALIZADO!")
    log("URL: https://consultorio.marcodaros.com.br", "🌐")
    log("Login: admin@consultorio.marcodaros.com.br", "👤")
    log("Senha: Admin@2026!", "🔑")
    log("⚠️  TROQUE A SENHA NO PRIMEIRO ACESSO!", "⚠️")
