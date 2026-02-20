-- Migration: 008_create_audit_logs_table.sql
-- Description: Security and activity audit trail

CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id`     INT UNSIGNED NULL,
    `action`      VARCHAR(100) NOT NULL,
    `description` TEXT         NULL,
    `ip_address`  VARCHAR(50)  NULL,
    `user_agent`  TEXT         NULL,
    `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_user_id`    (`user_id`),
    INDEX `idx_action`     (`action`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
