-- Migration: 004_create_patients_table.sql
-- Description: Patient records

CREATE TABLE IF NOT EXISTS `patients` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id`    INT UNSIGNED NOT NULL COMMENT 'Who registered the patient',
    `name`       VARCHAR(200) NOT NULL,
    `cpf`        VARCHAR(14)  NULL,
    `email`      VARCHAR(200) NULL,
    `phone`      VARCHAR(20)  NULL,
    `birth_date` DATE         NULL,
    `sex`        ENUM('M','F','O') NULL,
    `address`    VARCHAR(300) NULL,
    `city`       VARCHAR(100) NULL,
    `state`      CHAR(2)      NULL,
    `zip`        VARCHAR(10)  NULL,
    `photo`      VARCHAR(300) NULL COMMENT 'Relative path from public/',
    `notes`      TEXT         NULL,
    `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME     NULL,
    `deleted_at` DATETIME     NULL COMMENT 'Soft delete',
    INDEX `idx_name`       (`name`),
    INDEX `idx_cpf`        (`cpf`),
    INDEX `idx_deleted_at` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
