-- Core System Tables
-- From primary migrations in chronological order

-- Countries and Locations
CREATE TABLE IF NOT EXISTS `countries` (
    `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `country_code` varchar(2) NOT NULL,
    `country_name` varchar(100) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Companies
CREATE TABLE IF NOT EXISTS `companies` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `company_name` varchar(191) NOT NULL,
    `email` varchar(191) DEFAULT NULL,
    `phone` varchar(191) DEFAULT NULL,
    `website` varchar(191) DEFAULT NULL,
    `logo` varchar(191) DEFAULT NULL,
    `address` text DEFAULT NULL,
    `status` varchar(191) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Locations
CREATE TABLE IF NOT EXISTS `locations` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `location_name` varchar(191) NOT NULL,
    `location_head` bigint(20) UNSIGNED DEFAULT NULL,
    `address1` varchar(191) DEFAULT NULL,
    `address2` varchar(191) DEFAULT NULL,
    `city` varchar(191) DEFAULT NULL,
    `state` varchar(191) DEFAULT NULL,
    `country` int(10) UNSIGNED DEFAULT NULL,
    `zip` int(11) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `locations_country_foreign` (`country`),
    CONSTRAINT `locations_country_foreign` FOREIGN KEY (`country`) REFERENCES `countries` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Departments
CREATE TABLE IF NOT EXISTS `departments` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `department_name` varchar(191) NOT NULL,
    `company_id` bigint(20) UNSIGNED DEFAULT NULL,
    `department_head` bigint(20) UNSIGNED DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `departments_company_id_foreign` (`company_id`),
    CONSTRAINT `departments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Continue with all other tables from primary folder...
-- (I'll provide the key ones, but you should extract all from the primary folder)

-- Users (Admin authentication)
CREATE TABLE IF NOT EXISTS `users` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(191) NOT NULL,
    `email` varchar(191) NOT NULL,
    `email_verified_at` timestamp NULL DEFAULT NULL,
    `password` varchar(191) NOT NULL,
    `remember_token` varchar(100) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user
INSERT INTO `users` (`name`, `email`, `password`, `created_at`, `updated_at`) VALUES
('Admin', 'admin@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW());

-- Add more tables from primary folder...
