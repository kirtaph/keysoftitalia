-- Migration: Create utility (Luce & Gas) tables
-- Created At: 2026-06-12

CREATE TABLE IF NOT EXISTS utility_partners (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255) DEFAULT NULL,
    icon_class VARCHAR(50) NOT NULL DEFAULT 'ri-flashlight-line',
    icon_color VARCHAR(50) DEFAULT 'var(--ks-orange)',
    logo_path VARCHAR(255) DEFAULT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS utility_promotions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    partner_id INT UNSIGNED DEFAULT NULL,
    plan_name VARCHAR(150) NOT NULL,
    utility_type ENUM('luce', 'gas', 'dual') NOT NULL DEFAULT 'luce',
    price DECIMAL(10,2) NOT NULL,
    price_detail VARCHAR(50) NOT NULL DEFAULT '/mese',
    features TEXT DEFAULT NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_utility_promotions_partner FOREIGN KEY (partner_id) REFERENCES utility_partners(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS utility_requests (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    promotion_id INT UNSIGNED DEFAULT NULL,
    operator_name VARCHAR(100) NOT NULL,
    plan_name VARCHAR(150) NOT NULL,
    utility_type ENUM('luce', 'gas', 'dual') NOT NULL,
    current_spend DECIMAL(10,2) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    estimated_savings DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'In attesa',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_utility_requests_promotion FOREIGN KEY (promotion_id) REFERENCES utility_promotions(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed default operator partners
INSERT INTO utility_partners (name, description, icon_class, icon_color, sort_order, status) VALUES
('Enel Energia', 'Leader nazionale dell\'energia elettrica e gas', 'ri-flashlight-line', '#00a2e8', 10, 1),
('Eni Plenitude', 'Soluzioni sostenibili ed energia verde 100%', 'ri-leaf-line', '#22c55e', 20, 1),
('A2A Energia', 'Tariffe competitive e trasparenza nei consumi', 'ri-fire-line', '#ff6b35', 30, 1),
('Edison', 'Servizio affidabile e storico fornitore italiano', 'ri-lightbulb-line', '#ffbc00', 40, 1);
