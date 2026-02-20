-- Seed: Initial admin user and clinic settings
-- IDEMPOTENT: safe to run multiple times

-- Admin user (password: Admin@2026! — bcrypt hash below)
INSERT IGNORE INTO `users` (`id`, `name`, `email`, `password`, `role`, `active`, `created_at`)
VALUES (
    1,
    'Dr. Marco Daros',
    'admin@consultorio.marcodaros.com.br',
    '$2y$12$eRU0wNqZj0nK5M7tU7d4F.q3oN8MmWtBFc7tWiN.e7WBVkN5D1Vva',
    'admin',
    1,
    NOW()
);

-- Clinic initial settings
INSERT IGNORE INTO `clinic_settings` (`id`, `name`, `city`, `state`, `created_at`)
VALUES (
    1,
    'Consultório Dr. Marco Daros',
    '',
    '',
    NOW()
);
