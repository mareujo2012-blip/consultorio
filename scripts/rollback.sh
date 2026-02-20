#!/usr/bin/env bash
# ============================================================
# rollback.sh — Restaura backup do banco de dados
# Uso: ./scripts/rollback.sh [backup_file.sql.gz]
# ============================================================
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT_DIR="$(dirname "$SCRIPT_DIR")"
BACKUP_DIR="$ROOT_DIR/logs/backups"
LOG_DIR="$ROOT_DIR/logs"
LOG_FILE="$LOG_DIR/rollback_$(date +%Y%m%d_%H%M%S).log"

if [ -f "$ROOT_DIR/.env" ]; then
    set -a; source "$ROOT_DIR/.env"; set +a
fi

DB_HOST="${DB_HOST:-187.110.162.234}"
DB_PORT="${DB_PORT:-3306}"
DB_NAME="${DB_NAME:-prod9474_consultorio}"
DB_USER="${DB_USER:-prod9474_consultorio}"
DB_PASS="${DB_PASS:-90860Placa8010@#$}"

mkdir -p "$LOG_DIR"

log() { echo "[$(date +%Y-%m-%dT%H:%M:%S)] $*" | tee -a "$LOG_FILE"; }

# Resolve backup file
if [ -n "${1:-}" ]; then
    BACKUP_FILE="$1"
else
    # Select latest backup
    BACKUP_FILE=$(ls -t "$BACKUP_DIR"/*.sql.gz 2>/dev/null | head -1 || true)
fi

if [ -z "$BACKUP_FILE" ] || [ ! -f "$BACKUP_FILE" ]; then
    log "ERROR: No backup file found. Provide path as argument or place .sql.gz in $BACKUP_DIR"
    exit 1
fi

log "=== ROLLBACK START ==="
log "Backup: $BACKUP_FILE"
log "Database: $DB_HOST:$DB_PORT/$DB_NAME"

read -p "⚠️  This will REPLACE the database with the backup. Continue? [yes/no]: " confirm
if [ "$confirm" != "yes" ]; then
    log "Rollback cancelled by user."
    exit 0
fi

log "Restoring database..."
zcat "$BACKUP_FILE" | MYSQL_PWD="$DB_PASS" mysql \
    -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" "$DB_NAME" 2>>"$LOG_FILE"

log "=== ROLLBACK COMPLETE ==="
