INSERT INTO `admins` (`username`, `name`, `email`, `password`, `role`, `is_active`, `created_at`, `updated_at`) 
VALUES (
    'admin',
    'Administrator',
    'admin@admin.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password is "password"
    'admin',
    1,
    NOW(),
    NOW()
); 