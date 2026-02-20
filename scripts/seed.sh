#!/usr/bin/env bash
# ============================================================
# seed.sh — Executa seeds idempotentes no banco de produção
# ============================================================
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT_DIR="$(dirname "$SCRIPT_DIR")"
LOG_DIR="$ROOT_DIR/logs"
SEEDS_DIR="$ROOT_DIR/database/seeds"
LOG_FILE="$LOG_DIR/seed_$(date +%Y%m%d_%H%M%S).log"

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

mysql_cmd() {
    MYSQL_PWD="$DB_PASS" mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" "$DB_NAME" "$@"
}

log "=== SEED START ==="

for seed_file in "$SEEDS_DIR"/*.sql; do
    [ -f "$seed_file" ] || continue
    filename="$(basename "$seed_file")"
    log "  SEED  $filename ..."
    if mysql_cmd < "$seed_file" 2>>"$LOG_FILE"; then
        log "  OK    $filename"
    else
        log "  FAIL  $filename"
        exit 1
    fi
done

log "=== SEED DONE ==="
