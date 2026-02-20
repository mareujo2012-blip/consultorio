#!/usr/bin/env bash
# ============================================================
# setup-server.sh — Configuração inicial no servidor Linux
# Execute este script UMA VEZ via SSH no servidor
# ============================================================
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT_DIR="$(dirname "$SCRIPT_DIR")"

echo "=== Setup Server ==="

# 1. Install Composer if not present
if ! command -v composer &>/dev/null; then
    echo "[1/4] Installing Composer..."
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer
    rm composer-setup.php
    echo "  Composer installed."
else
    echo "[1/4] Composer already installed."
fi

# 2. Install PHP dependencies
echo "[2/4] Installing PHP dependencies..."
cd "$ROOT_DIR"
composer install --no-dev --optimize-autoloader

# 3. Create necessary directories
echo "[3/4] Creating directories..."
mkdir -p public/uploads/photos public/uploads/logos logs/backups
chmod -R 755 public/uploads logs

# 4. Run migrations + seed
echo "[4/4] Running migrations and seeds..."
bash "$SCRIPT_DIR/migrate.sh"
RUN_SEED=true bash "$SCRIPT_DIR/seed.sh"

echo ""
echo "=== SETUP COMPLETE ==="
echo "Access the system at: ${APP_URL:-https://consultorio.marcodaros.com.br}"
echo "Default credentials: admin@consultorio.marcodaros.com.br / Admin@2026!"
echo "⚠️  CHANGE THE PASSWORD IMMEDIATELY!"
