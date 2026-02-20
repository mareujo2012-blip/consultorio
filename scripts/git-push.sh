#!/usr/bin/env bash
# ============================================================
# git-push.sh — Commit + Push automático para GitHub
# ============================================================
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT_DIR="$(dirname "$SCRIPT_DIR")"
LOG_DIR="$ROOT_DIR/logs"
LOG_FILE="$LOG_DIR/git_$(date +%Y%m%d_%H%M%S).log"

mkdir -p "$LOG_DIR"

log() { echo "[$(date +%Y-%m-%dT%H:%M:%S)] $*" | tee -a "$LOG_FILE"; }

COMMIT_MSG="${1:-deploy: auto-commit $(date '+%Y-%m-%d %H:%M:%S')}"
BRANCH="${GIT_BRANCH:-main}"

log "=== GIT PUSH START ==="
log "Branch: $BRANCH"
log "Message: $COMMIT_MSG"

cd "$ROOT_DIR"

# Check if there's anything to commit
if git diff --quiet && git diff --staged --quiet; then
    log "Nothing to commit. Working tree is clean."
else
    git add -A
    git commit -m "$COMMIT_MSG"
    log "Committed: $COMMIT_MSG"
fi

git push origin "$BRANCH" 2>&1 | tee -a "$LOG_FILE"

log "=== GIT PUSH DONE ==="
