-- Migration: 002_create_users_table.sql
-- Description: Users / doctors

CREATE TABLE IF NOT EXISTS `users` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name`       VARCHAR(200) NOT NULL,
    `email`      VARCHAR(200) NOT NULL,
    `password`   VARCHAR(255) NOT NULL COMMENT 'bcrypt hash',
    `phone`      VARCHAR(20)  NULL,
    `role`       ENUM('admin','secretary') NOT NULL DEFAULT 'admin',
    `active`     TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME     NULL,
    UNIQUE KEY `uq_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
