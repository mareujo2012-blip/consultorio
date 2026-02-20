#!/usr/bin/env bash
# ============================================================
# run-all.sh — Fluxo completo: commit → push → migrate → deploy → validate
# ============================================================
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT_DIR="$(dirname "$SCRIPT_DIR")"
LOG_DIR="$ROOT_DIR/logs"
LOG_FILE="$LOG_DIR/run_all_$(date +%Y%m%d_%H%M%S).log"

mkdir -p "$LOG_DIR"

log() { echo "[$(date +%Y-%m-%dT%H:%M:%S)] $*" | tee -a "$LOG_FILE"; }

COMMIT_MSG="${1:-deploy: $(date '+%Y-%m-%d %H:%M:%S')}"

log "========================================"
log "  CONSULTÓRIO — FLUXO COMPLETO"
log "  COMMIT → PUSH → MIGRATE → DEPLOY → VALIDATE"
log "========================================"

# Step 1: Git commit + push
log ""
log "[STEP 1] Git commit & push..."
bash "$SCRIPT_DIR/git-push.sh" "$COMMIT_MSG" 2>&1 | tee -a "$LOG_FILE"

# Step 2: Migrate + Deploy (includes validation)
log ""
log "[STEP 2] Deploy (backup + migrate + upload + validate)..."
bash "$SCRIPT_DIR/deploy.sh" 2>&1 | tee -a "$LOG_FILE"

log ""
log "========================================="
log "  ✅ PIPELINE COMPLETE"
log "  Log: $LOG_FILE"
log "========================================="
