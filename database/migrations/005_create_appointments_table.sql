-- Migration: 005_create_appointments_table.sql
-- Description: Consultations / appointments

CREATE TABLE IF NOT EXISTS `appointments` (
    `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `patient_id`       INT UNSIGNED NOT NULL,
    `user_id`          INT UNSIGNED NOT NULL COMMENT 'Doctor who performed',
    `appointment_date` DATETIME     NOT NULL,
    `value`            DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `payment_method`   VARCHAR(50)  NULL,
    `admin_notes`      TEXT         NULL,
    `created_at`       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_patient_id`       (`patient_id`),
    INDEX `idx_appointment_date` (`appointment_date`),
    INDEX `idx_user_id`          (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
