-- Migration: Create telephony partners table
-- Created At: 2026-06-11

CREATE TABLE IF NOT EXISTS telephony_partners (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255) DEFAULT NULL,
    icon_class VARCHAR(50) NOT NULL DEFAULT 'ri-smartphone-line',
    icon_color VARCHAR(50) DEFAULT 'var(--ks-orange)',
    sort_order INT NOT NULL DEFAULT 0,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed default operator partners
INSERT INTO telephony_partners (name, description, icon_class, icon_color, sort_order, status) VALUES
('Kena Mobile', 'Rete TIM 5G a tariffe imbattibili', 'ri-smartphone-line', 'var(--ks-orange)', 10, 1),
('Lycamobile', 'Rete Vodafone 5G e chiamate all\'estero', 'ri-global-line', '#7c4dff', 20, 1),
('Fastweb Mobile', '5G incluso e massima trasparenza', 'ri-phone-fill', '#003996', 30, 1),
('Fastweb Casa', 'Fibra Ultra FTTH fino a 2.5 Gbps', 'ri-wifi-line', 'var(--ks-green)', 40, 1);
