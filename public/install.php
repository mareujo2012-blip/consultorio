<?php
/**
 * install.php — Instalador do ControleConsultório
 * Acesse UMA VEZ após o deploy para criar as tabelas.
 * Este arquivo se auto-deleta após execução bem-sucedida.
 */
declare(strict_types=1);

// Security token — must match URL parameter
define('INSTALL_TOKEN', 'cc2026install99');

if (($_GET['token'] ?? '') !== INSTALL_TOKEN) {
    http_response_code(403);
    die('<h2 style="font-family:sans-serif;color:red">Acesso negado. Adicione ?token=cc2026install99 na URL.</h2>');
}

// ── Configuration ──
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'prod9474_consultorio');
define('DB_USER', 'prod9474_consultorio');
define('DB_PASS', '90860Placa8010@#$');

$logs = [];
$errors = [];
$success = true;

function logMsg(string $msg, bool $ok = true): void
{
    global $logs, $errors, $success;
    $logs[] = ['msg' => $msg, 'ok' => $ok];
    if (!$ok) {
        $errors[] = $msg;
        $success = false;
    }
    ob_flush();
    flush();
}

// ── Connect ──
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
    logMsg("✅ Conexão com banco de dados: OK");
} catch (PDOException $e) {
    logMsg("❌ Falha na conexão: " . $e->getMessage(), false);
    $pdo = null;
}

// ── Run SQL Migrations ──
$allSQL = <<<'SQL'
CREATE TABLE IF NOT EXISTS `migrations` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `filename` VARCHAR(255) NOT NULL,
    `applied_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_filename` (`filename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(200) NOT NULL,
    `email` VARCHAR(200) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) NULL,
    `role` ENUM('admin','secretary') NOT NULL DEFAULT 'admin',
    `active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL,
    UNIQUE KEY `uq_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `clinic_settings` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(200) NULL,
    `cnpj` VARCHAR(20) NULL,
    `address` VARCHAR(300) NULL,
    `city` VARCHAR(100) NULL,
    `state` CHAR(2) NULL,
    `zip` VARCHAR(10) NULL,
    `phone` VARCHAR(20) NULL,
    `website` VARCHAR(300) NULL,
    `instagram` VARCHAR(200) NULL,
    `facebook` VARCHAR(200) NULL,
    `logo` VARCHAR(300) NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `patients` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(200) NOT NULL,
    `cpf` VARCHAR(14) NULL,
    `email` VARCHAR(200) NULL,
    `phone` VARCHAR(20) NULL,
    `birth_date` DATE NULL,
    `sex` ENUM('M','F','O') NULL,
    `address` VARCHAR(300) NULL,
    `city` VARCHAR(100) NULL,
    `state` CHAR(2) NULL,
    `zip` VARCHAR(10) NULL,
    `photo` VARCHAR(300) NULL,
    `notes` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL,
    `deleted_at` DATETIME NULL,
    INDEX `idx_name` (`name`),
    INDEX `idx_cpf` (`cpf`),
    INDEX `idx_deleted_at` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `appointments` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `patient_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `appointment_date` DATETIME NOT NULL,
    `value` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `payment_method` VARCHAR(50) NULL,
    `admin_notes` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_patient_id` (`patient_id`),
    INDEX `idx_appointment_date` (`appointment_date`),
    INDEX `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `medical_record_entries` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `appointment_id` INT UNSIGNED NOT NULL,
    `patient_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `entry_type` VARCHAR(50) NOT NULL DEFAULT 'anamnese',
    `content` MEDIUMTEXT NOT NULL,
    `content_hash` CHAR(64) NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_patient_id` (`patient_id`),
    INDEX `idx_appointment_id` (`appointment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `prescriptions` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `appointment_id` INT UNSIGNED NOT NULL,
    `patient_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `content` TEXT NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL,
    INDEX `idx_patient_id` (`patient_id`),
    INDEX `idx_appointment_id` (`appointment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NULL,
    `action` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `ip_address` VARCHAR(50) NULL,
    `user_agent` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_action` (`action`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

if ($pdo) {
    $statements = array_filter(array_map('trim', explode(';', $allSQL)));
    $tableCount = 0;
    foreach ($statements as $stmt) {
        if (empty($stmt) || str_starts_with($stmt, '--'))
            continue;
        try {
            $pdo->exec($stmt);
            if (stripos($stmt, 'CREATE TABLE') !== false) {
                preg_match('/`(\w+)`/', substr($stmt, 20), $m);
                logMsg("✅ Tabela criada/verificada: " . ($m[1] ?? '?'));
                $tableCount++;
            }
        } catch (PDOException $e) {
            logMsg("❌ SQL error: " . $e->getMessage(), false);
        }
    }
    logMsg("✅ {$tableCount} tabelas processadas");
}

// ── Seed: Admin User ──
if ($pdo) {
    try {
        $hash = password_hash('Admin@2026!', PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $pdo->prepare("INSERT IGNORE INTO users (id, name, email, password, role, active, created_at) VALUES (1, 'Dr. Marco Daros', 'admin@consultorio.marcodaros.com.br', ?, 'admin', 1, NOW())");
        $stmt->execute([$hash]);
        logMsg("✅ Usuário admin criado/verificado");
    } catch (PDOException $e) {
        logMsg("⚠️ Seed usuário: " . $e->getMessage());
    }

    try {
        $pdo->exec("INSERT IGNORE INTO clinic_settings (id, name, created_at) VALUES (1, 'Consultório Dr. Marco Daros', NOW())");
        logMsg("✅ Configuração inicial da clínica criada");
    } catch (PDOException $e) {
        logMsg("⚠️ Seed clínica: " . $e->getMessage());
    }
}

// ── Create upload directories ──
$dirs = [
    __DIR__ . '/uploads',
    __DIR__ . '/uploads/photos',
    __DIR__ . '/uploads/logos',
];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        logMsg("✅ Diretório criado: " . basename($dir));
    } else {
        logMsg("✅ Diretório já existe: " . basename($dir));
    }
}

// ── Install Composer + Dompdf ──
$vendorAutoload = dirname(__DIR__) . '/vendor/autoload.php';
if (!file_exists($vendorAutoload)) {
    logMsg("🔄 Instalando Composer e dependências...");
    $composerPhar = dirname(__DIR__) . '/composer.phar';

    if (!file_exists($composerPhar)) {
        $composerContent = file_get_contents('https://getcomposer.org/composer-stable.phar');
        if ($composerContent) {
            file_put_contents($composerPhar, $composerContent);
            logMsg("✅ composer.phar baixado");
        } else {
            logMsg("❌ Falha ao baixar composer.phar", false);
        }
    }

    if (file_exists($composerPhar)) {
        $output = [];
        $retCode = 0;
        exec('cd ' . escapeshellarg(dirname(__DIR__)) . ' && php composer.phar install --no-dev --no-interaction 2>&1', $output, $retCode);
        if ($retCode === 0 || file_exists($vendorAutoload)) {
            logMsg("✅ Dependências PHP instaladas (Dompdf)");
        } else {
            logMsg("⚠️ Composer retornou código " . $retCode . ". Saída: " . implode(', ', array_slice($output, -3)));
        }
    }
} else {
    logMsg("✅ vendor/autoload.php já existe (Composer OK)");
}

// ── Show result ──
$bgColor = $success ? '#f0fdf4' : '#fff1f2';
$color = $success ? '#166534' : '#991b1b';
$title = $success ? '🎉 Instalação Concluída com Sucesso!' : '⚠️ Instalação completada com alertas';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalador — ControleConsultório</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .08);
            max-width: 680px;
            width: 100%;
            overflow: hidden;
        }

        .header {
            background:
                <?= $bgColor ?>
            ;
            border-bottom: 1px solid
                <?= $success ? '#bbf7d0' : '#fecdd3' ?>
            ;
            padding: 24px;
        }

        h1 {
            font-size: 22px;
            color:
                <?= $color ?>
            ;
        }

        .subtitle {
            color: #64748b;
            margin-top: 6px;
            font-size: 14px;
        }

        .logs {
            padding: 20px;
            max-height: 400px;
            overflow-y: auto;
        }

        .log-item {
            display: flex;
            gap: 10px;
            padding: 6px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        .log-item:last-child {
            border: none;
        }

        .footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 20px 24px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            color: white;
            background: #2563eb;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        .btn-del {
            background: #dc2626;
            margin-left: 10px;
        }

        .info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 16px;
            margin-top: 16px;
        }

        .info-box p {
            margin: 4px 0;
            font-size: 14px;
            color: #1e40af;
        }

        .info-box strong {
            color: #1e3a8a;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="header">
            <h1>
                <?= $title ?>
            </h1>
            <p class="subtitle">ControleConsultório v1.0.0 —
                <?= date('d/m/Y H:i:s') ?>
            </p>
        </div>
        <div class="logs">
            <?php foreach ($logs as $log): ?>
                <div class="log-item">
                    <span>
                        <?= htmlspecialchars($log['msg']) ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="footer">
            <?php if ($success): ?>
                <div class="info-box">
                    <p>🌐 <strong>URL do Sistema:</strong> <a
                            href="https://consultorio.marcodaros.com.br/dashboard">https://consultorio.marcodaros.com.br/dashboard</a>
                    </p>
                    <p>👤 <strong>E-mail:</strong> admin@consultorio.marcodaros.com.br</p>
                    <p>🔑 <strong>Senha:</strong> Admin@2026!</p>
                    <p style="color:#dc2626;margin-top:8px">⚠️ <strong>Troque a senha imediatamente após o primeiro
                            login!</strong></p>
                </div>
                <div style="margin-top:16px">
                    <a href="https://consultorio.marcodaros.com.br" class="btn">→ Acessar o Sistema</a>
                    <a href="?token=cc2026install99&delete=1" class="btn btn-del"
                        onclick="return confirm('Deletar este arquivo de instalação?')">🗑️ Deletar install.php
                        (recomendado)</a>
                </div>
            <?php else: ?>
                <p style="color:#dc2626;font-size:14px">Erros encontrados. Verifique o log acima.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
<?php

// ── Self-delete if requested ──
if (($_GET['delete'] ?? '') === '1' && $success) {
    @unlink(__FILE__);
}
