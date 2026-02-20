-- Migration: 006_create_medical_record_entries_table.sql
-- Description: Immutable medical record entries (prontuário)

CREATE TABLE IF NOT EXISTS `medical_record_entries` (
    `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `appointment_id` INT UNSIGNED NOT NULL,
    `patient_id`     INT UNSIGNED NOT NULL,
    `user_id`        INT UNSIGNED NOT NULL COMMENT 'Doctor who created the entry',
    `entry_type`     VARCHAR(50)  NOT NULL DEFAULT 'anamnese'
                     COMMENT 'anamnese|exame|hipotese|conduta|adendo|evolucao',
    `content`        MEDIUMTEXT   NOT NULL,
    `content_hash`   CHAR(64)     NOT NULL COMMENT 'SHA-256 of content + appointment_id + patient_id + timestamp',
    `created_at`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_patient_id`       (`patient_id`),
    INDEX `idx_appointment_id`   (`appointment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Immutable entries. No UPDATE or DELETE should ever be issued on this table.';
