#!/usr/bin/env bash
# ============================================================
# migrate.sh — Executa migrations pendentes no banco de produção
# Versão: 1.0.0
# ============================================================
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT_DIR="$(dirname "$SCRIPT_DIR")"
LOG_DIR="$ROOT_DIR/logs"
MIGRATIONS_DIR="$ROOT_DIR/database/migrations"
LOG_FILE="$LOG_DIR/migrate_$(date +%Y%m%d_%H%M%S).log"

# Load env
if [ -f "$ROOT_DIR/.env" ]; then
    set -a; source "$ROOT_DIR/.env"; set +a
fi

DB_HOST="${DB_HOST:-187.110.162.234}"
DB_PORT="${DB_PORT:-3306}"
DB_NAME="${DB_NAME:-prod9474_consultorio}"
DB_USER="${DB_USER:-prod9474_consultorio}"
# Password read from env — never echoed
DB_PASS="${DB_PASS:-90860Placa8010@#$}"

mkdir -p "$LOG_DIR"

log() { echo "[$(date +%Y-%m-%dT%H:%M:%S)] $*" | tee -a "$LOG_FILE"; }

log "=== MIGRATION START ==="
log "Database: $DB_HOST:$DB_PORT/$DB_NAME"

# MySQL command wrapper (hides password)
mysql_cmd() {
    MYSQL_PWD="$DB_PASS" mysql \
        -h "$DB_HOST" \
        -P "$DB_PORT" \
        -u "$DB_USER" \
        "$DB_NAME" \
        "$@"
}

# Create migrations table if it doesn't exist
log "Ensuring migrations control table exists..."
mysql_cmd <<'SQL'
CREATE TABLE IF NOT EXISTS `migrations` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `filename`   VARCHAR(255) NOT NULL,
    `applied_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_filename` (`filename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL

applied=0
failed=0

for migration_file in "$MIGRATIONS_DIR"/*.sql; do
    [ -f "$migration_file" ] || continue
    filename="$(basename "$migration_file")"

    # Check if already applied
    count=$(mysql_cmd -sN -e "SELECT COUNT(*) FROM migrations WHERE filename='$filename'")
    if [ "$count" -gt 0 ]; then
        log "  SKIP  $filename (already applied)"
        continue
    fi

    log "  APPLY $filename ..."
    if mysql_cmd < "$migration_file" 2>>"$LOG_FILE"; then
        mysql_cmd -e "INSERT IGNORE INTO migrations (filename) VALUES ('$filename')"
        log "  OK    $filename"
        applied=$((applied + 1))
    else
        log "  FAIL  $filename — ABORTING"
        failed=$((failed + 1))
        exit 1
    fi
done

log "=== MIGRATION DONE: $applied applied, $failed failed ==="
log "Log: $LOG_FILE"
