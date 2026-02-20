#!/usr/bin/env bash
# ============================================================
# deploy.sh — Deploy via FTP para produção (DirectAdmin)
# Fluxo: Backup DB → Migrate → FTP Upload → Validate
# ============================================================
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT_DIR="$(dirname "$SCRIPT_DIR")"
LOG_DIR="$ROOT_DIR/logs"
LOG_FILE="$LOG_DIR/deploy_$(date +%Y%m%d_%H%M%S).log"
BACKUP_DIR="$ROOT_DIR/logs/backups"

# Load .env
if [ -f "$ROOT_DIR/.env" ]; then
    set -a; source "$ROOT_DIR/.env"; set +a
fi

# Config
APP_URL="${APP_URL:-https://consultorio.marcodaros.com.br}"
DB_HOST="${DB_HOST:-187.110.162.234}"
DB_PORT="${DB_PORT:-3306}"
DB_NAME="${DB_NAME:-prod9474_consultorio}"
DB_USER="${DB_USER:-prod9474_consultorio}"
DB_PASS="${DB_PASS:-90860Placa8010@#$}"

FTP_HOST="${FTP_HOST:-187.110.162.234}"
FTP_PORT="${FTP_PORT:-21}"
FTP_USER="${FTP_USER:-consultorio@marcodaros.com.br}"
FTP_PASS="${FTP_PASS:-90860Placa8010@#$}"
FTP_ROOT="${FTP_ROOT:-/domains/marcodaros.com.br/public_html/consultorio/}"

mkdir -p "$LOG_DIR" "$BACKUP_DIR"

log() { echo "[$(date +%Y-%m-%dT%H:%M:%S)] $*" | tee -a "$LOG_FILE"; }
error() { log "ERROR: $*"; exit 1; }

log "=============================="
log "  CONSULTÓRIO — AUTO DEPLOY"
log "  $(date '+%d/%m/%Y %H:%M:%S')"
log "=============================="
log "Target: $APP_URL"

# ──────────────────────────────────
# STEP 1: Backup database
# ──────────────────────────────────
log ""
log "[1/5] Backing up database..."
BACKUP_FILE="$BACKUP_DIR/backup_$(date +%Y%m%d_%H%M%S).sql.gz"

MYSQL_PWD="$DB_PASS" mysqldump \
    -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" \
    --single-transaction \
    --routines --events --triggers \
    "$DB_NAME" 2>>"$LOG_FILE" | gzip > "$BACKUP_FILE"

log "  Backup saved: $(basename "$BACKUP_FILE")"

# ──────────────────────────────────
# STEP 2: Run migrations
# ──────────────────────────────────
log ""
log "[2/5] Running migrations..."
bash "$SCRIPT_DIR/migrate.sh" 2>&1 | tee -a "$LOG_FILE" || error "Migration failed — aborting deploy!"

# ──────────────────────────────────
# STEP 3: Run seeds (if flag set)
# ──────────────────────────────────
if [ "${RUN_SEED:-false}" = "true" ]; then
    log ""
    log "[3/5] Running seeds..."
    bash "$SCRIPT_DIR/seed.sh" 2>&1 | tee -a "$LOG_FILE" || error "Seed failed!"
else
    log ""
    log "[3/5] Skipping seeds (RUN_SEED=false)"
fi

# ──────────────────────────────────
# STEP 4: FTP Upload
# ──────────────────────────────────
log ""
log "[4/5] Uploading files via FTP..."

# Create lftp mirror command
# Exclude dev files
EXCLUDE_PATTERNS=(
    ".git"
    ".gitignore"
    "node_modules"
    "logs"
    "*.sh"
    ".env"
    "*.md"
    "*.lock"
    "scripts"
    ".agent*"
    "_agent*"
)

EXCLUDE_ARGS=""
for p in "${EXCLUDE_PATTERNS[@]}"; do
    EXCLUDE_ARGS="$EXCLUDE_ARGS --exclude $p"
done

lftp -p "$FTP_PORT" -u "$FTP_USER,$FTP_PASS" "ftp://$FTP_HOST" <<LFTP_EOF
set ssl:verify-certificate no
set ftp:ssl-allow yes
mirror --reverse \
       --delete \
       --verbose \
       $EXCLUDE_ARGS \
       "$ROOT_DIR/" \
       "$FTP_ROOT"
bye
LFTP_EOF

log "  FTP upload complete."

# ──────────────────────────────────
# STEP 5: Validate
# ──────────────────────────────────
log ""
log "[5/5] Validating deployment..."
sleep 3

HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" --max-time 15 "$APP_URL" 2>>"$LOG_FILE" || echo "000")

if [ "$HTTP_CODE" = "200" ] || [ "$HTTP_CODE" = "302" ]; then
    log "  ✅ HTTP $HTTP_CODE — Application is responding!"
else
    log "  ⚠️  HTTP $HTTP_CODE — Check $APP_URL manually"
fi

log ""
log "=============================="
log "  DEPLOY COMPLETE"
log "  Log: $LOG_FILE"
log "=============================="
