#!/usr/bin/env python3
"""
deploy_corrigido.py — Deploy CORRIGIDO para o caminho FTP correto
=================================================================
PROBLEMA IDENTIFICADO:
  - Caminho ERRADO anterior: /domains/marcodaros.com.br/public_html/consultorio
  - Caminho CORRETO no servidor: /public_html/consultorio

ESTRUTURA NO SERVIDOR APÓS ESTE DEPLOY:
  /public_html/consultorio/          ← pasta do subdomínio
    public/                          ← DocumentRoot do Apache (configurar no cPanel!)
      index.php                      ← entry point da aplicação
      .htaccess                      ← URL rewrite + HTTPS
      install.php                    ← instalador do banco
      uploads/                       ← fotos/logos
    app/                             ← controllers, models, views, config
    bootstrap/                       ← app.php (sessão, headers, dispatch)
    vendor/                          ← autoloader (dompdf, etc.)
    routes/                          ← web.php
    database/                        ← migrations e seeds
    composer.json

⚠️ AÇÃO NECESSÁRIA NO CPANEL:
  Subdomínio: consultorio.marcodaros.com.br
  DocumentRoot atual: /public_html/consultorio
  DocumentRoot CORRETO: /public_html/consultorio/public

EXECUTAR: python scripts/deploy_corrigido.py
"""
import ftplib
import pymysql
import os
import sys
import glob
import time
import datetime
import io

# ── Configurações ──────────────────────────────────────────────────────────
DB_HOST   = "187.110.162.234"
DB_PORT   = 3306
DB_NAME   = "prod9474_consultorio"
DB_USER   = "prod9474_consultorio"
DB_PASS   = "90860Placa8010@#$"

FTP_HOST  = "187.110.162.234"
FTP_PORT  = 21
FTP_USER  = "consultorio@marcodaros.com.br"
FTP_PASS  = "90860Placa8010@#$"

# ✅ CAMINHO CORRIGIDO
FTP_ROOT  = "/public_html/consultorio"

ROOT_DIR        = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
MIGRATIONS_DIR  = os.path.join(ROOT_DIR, "database", "migrations")
SEEDS_DIR       = os.path.join(ROOT_DIR, "database", "seeds")

# Pastas/arquivos a EXCLUIR do upload
EXCLUDE_DIRS  = {".git", ".vscode", ".agent", "_agent", "node_modules", "logs", "scripts", ".idea", "__pycache__"}
EXCLUDE_FILES = {".env", ".gitignore"}
EXCLUDE_EXTS  = {".sh", ".gz", ".log", ".py"}

# ── Helpers ──────────────────────────────────────────────────────────────────
def log(msg, emoji="ℹ️"):
    ts = datetime.datetime.now().strftime("%H:%M:%S")
    print(f"[{ts}] {emoji}  {msg}", flush=True)

def separator(title=""):
    print(f"\n{'='*60}", flush=True)
    if title:
        print(f"  {title}", flush=True)
        print(f"{'='*60}", flush=True)

# ── STEP 1: Testar conexão MySQL ──────────────────────────────────────────────
def test_db():
    separator("STEP 1 — Testando conexão com banco de dados")
    try:
        conn = pymysql.connect(
            host=DB_HOST, port=DB_PORT,
            user=DB_USER, password=DB_PASS,
            database=DB_NAME, charset="utf8mb4", connect_timeout=15
        )
        log(f"Conectado: {DB_HOST}:{DB_PORT}/{DB_NAME}", "✅")
        conn.close()
        return True
    except Exception as e:
        log(f"FALHA na conexão: {e}", "❌")
        return False

# ── STEP 2: Rodar migrations ─────────────────────────────────────────────────
def run_migrations():
    separator("STEP 2 — Rodando Migrations")
    conn = pymysql.connect(
        host=DB_HOST, port=DB_PORT, user=DB_USER, password=DB_PASS,
        database=DB_NAME, charset="utf8mb4", connect_timeout=15, autocommit=True
    )
    cursor = conn.cursor()
    cursor.execute("""
        CREATE TABLE IF NOT EXISTS `migrations` (
            `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `filename`   VARCHAR(255) NOT NULL,
            `applied_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY `uq_filename` (`filename`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    """)
    log("Tabela de controle verificada", "📋")

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

# ── STEP 3: Rodar Seeds ──────────────────────────────────────────────────────
def run_seeds():
    separator("STEP 3 — Rodando Seeds (dados iniciais)")
    conn = pymysql.connect(
        host=DB_HOST, port=DB_PORT, user=DB_USER, password=DB_PASS,
        database=DB_NAME, charset="utf8mb4", connect_timeout=15, autocommit=True
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

# ── STEP 4: Upload FTP ─────────────────────────────────────────────────────
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

def run_ftp_upload():
    separator("STEP 4 — Upload via FTP (caminho CORRIGIDO)")
    log(f"Destino: {FTP_HOST}:{FTP_PORT}{FTP_ROOT}", "🔌")

    try:
        ftp = ftplib.FTP()
        ftp.connect(FTP_HOST, FTP_PORT, timeout=60)
        ftp.login(FTP_USER, FTP_PASS)
        ftp.set_pasv(True)
        log("FTP conectado!", "✅")
    except Exception as e:
        log(f"Falha FTP: {e}", "❌")
        return False

    ftp_mkdirs(ftp, FTP_ROOT)
    log(f"Diretório raiz: {FTP_ROOT}", "📁")

    counter = [0]
    start   = time.time()

    log(f"Iniciando upload de {ROOT_DIR} ...", "📤")
    log(f"(Excluindo: .git, .env, logs/, scripts/)", "ℹ️")

    upload_directory(ftp, ROOT_DIR, FTP_ROOT, counter)

    elapsed = time.time() - start
    ftp.quit()
    log(f"Upload completo: {counter[0]} arquivo(s) em {elapsed:.1f}s", "✅")
    return True

# ── STEP 5: Criar .env no servidor ────────────────────────────────────────────
def create_env_on_server():
    separator("STEP 5 — Criando .env no servidor")
    env_content = (
        "APP_ENV=production\n"
        "APP_URL=https://consultorio.marcodaros.com.br\n"
        "APP_DEBUG=false\n"
        "\n"
        f"DB_HOST={DB_HOST}\n"
        f"DB_PORT={DB_PORT}\n"
        f"DB_NAME={DB_NAME}\n"
        f"DB_USER={DB_USER}\n"
        f"DB_PASS={DB_PASS}\n"
        "\n"
        "SESSION_TIMEOUT=3600\n"
    )
    try:
        ftp = ftplib.FTP()
        ftp.connect(FTP_HOST, FTP_PORT, timeout=30)
        ftp.login(FTP_USER, FTP_PASS)
        ftp.set_pasv(True)
        env_bytes = env_content.encode("utf-8")
        ftp.storbinary(f"STOR {FTP_ROOT}/.env", io.BytesIO(env_bytes))
        log(f".env criado em {FTP_ROOT}/.env", "✅")
        ftp.quit()
    except Exception as e:
        log(f"Falha ao criar .env: {e}", "❌")

# ── STEP 6: Validar deployment ──────────────────────────────────────────────
def validate():
    separator("STEP 6 — Validando deployment")
    import urllib.request
    url = "https://consultorio.marcodaros.com.br"
    try:
        req  = urllib.request.Request(url, headers={"User-Agent": "ConsultorioHealthCheck/1.0"})
        resp = urllib.request.urlopen(req, timeout=15)
        log(f"HTTP {resp.getcode()} — Sistema respondendo!", "✅")
    except urllib.error.HTTPError as e:
        if e.code in (200, 301, 302):
            log(f"HTTP {e.code} — OK", "✅")
        else:
            log(f"HTTP {e.code} — Verifique: {url}", "⚠️")
    except Exception as e:
        log(f"Não foi possível validar: {e}", "⚠️")
        log(f"Acesse manualmente: {url}", "ℹ️")

# ── MAIN ──────────────────────────────────────────────────────────────────────
if __name__ == "__main__":
    separator("CONSULTÓRIO — DEPLOY CORRIGIDO")
    log(f"Início: {datetime.datetime.now().strftime('%d/%m/%Y %H:%M:%S')}", "🚀")
    log(f"FTP Root CORRIGIDO: {FTP_ROOT}", "📍")
    log("")
    log("⚠️  LEMBRETE: Após o deploy, configure no cPanel:", "⚠️")
    log(f"   Subdomínio consultorio.marcodaros.com.br", "")
    log(f"   DocumentRoot → /public_html/consultorio/public", "")
    log("")

    if not test_db():
        log("Abortando: banco de dados inacessível", "❌")
        sys.exit(1)

    run_migrations()
    run_seeds()
    run_ftp_upload()
    create_env_on_server()
    validate()

    separator("DEPLOY FINALIZADO!")
    log("", "")
    log("⚠️  PRÓXIMO PASSO OBRIGATÓRIO NO CPANEL:", "⚠️")
    log("   Vá em: Subdomínios → consultorio.marcodaros.com.br", "")
    log("   Mude o DocumentRoot para: /public_html/consultorio/public", "")
    log("", "")
    log("URL: https://consultorio.marcodaros.com.br", "🌐")
    log("Login: admin@consultorio.marcodaros.com.br", "👤")
    log("Senha: Admin@2026!", "🔑")
    log("⚠️  TROQUE A SENHA NO PRIMEIRO ACESSO!", "⚠️")
