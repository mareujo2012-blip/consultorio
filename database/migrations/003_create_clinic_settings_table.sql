-- Migration: 003_create_clinic_settings_table.sql
-- Description: Clinic configuration (one row)

CREATE TABLE IF NOT EXISTS `clinic_settings` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name`       VARCHAR(200) NULL,
    `cnpj`       VARCHAR(20)  NULL,
    `address`    VARCHAR(300) NULL,
    `city`       VARCHAR(100) NULL,
    `state`      CHAR(2)      NULL,
    `zip`        VARCHAR(10)  NULL,
    `phone`      VARCHAR(20)  NULL,
    `website`    VARCHAR(300) NULL,
    `instagram`  VARCHAR(200) NULL,
    `facebook`   VARCHAR(200) NULL,
    `logo`       VARCHAR(300) NULL COMMENT 'Relative path from public/',
    `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME     NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
