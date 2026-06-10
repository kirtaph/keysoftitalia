-- Migration: Create telephony promotions table
-- Created At: 2026-06-10

CREATE TABLE IF NOT EXISTS telephony_promotions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    operator_name VARCHAR(100) NOT NULL,
    logo_path VARCHAR(255) DEFAULT NULL,
    plan_name VARCHAR(150) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    price_detail VARCHAR(50) NOT NULL DEFAULT '/mese',
    features TEXT DEFAULT NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
