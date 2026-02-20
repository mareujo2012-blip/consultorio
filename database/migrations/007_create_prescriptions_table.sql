-- Migration: 007_create_prescriptions_table.sql
-- Description: Medical prescriptions / receitas

CREATE TABLE IF NOT EXISTS `prescriptions` (
    `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `appointment_id` INT UNSIGNED NOT NULL,
    `patient_id`     INT UNSIGNED NOT NULL,
    `user_id`        INT UNSIGNED NOT NULL,
    `content`        TEXT         NOT NULL,
    `created_at`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     DATETIME     NULL,
    INDEX `idx_patient_id`     (`patient_id`),
    INDEX `idx_appointment_id` (`appointment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
