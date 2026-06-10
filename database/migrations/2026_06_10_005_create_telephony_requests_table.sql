-- Migration: Create telephony requests table
-- Created At: 2026-06-10

CREATE TABLE IF NOT EXISTS telephony_requests (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    promotion_id INT UNSIGNED DEFAULT NULL,
    operator_name VARCHAR(100) NOT NULL,
    plan_name VARCHAR(150) NOT NULL,
    current_spend DECIMAL(10,2) NOT NULL,
    num_lines INT UNSIGNED NOT NULL DEFAULT 1,
    phone VARCHAR(30) NOT NULL,
    estimated_savings DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'In attesa',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
