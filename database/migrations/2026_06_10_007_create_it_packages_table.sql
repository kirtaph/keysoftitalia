-- Migration: Create IT packages table
-- Created At: 2026-06-10

CREATE TABLE IF NOT EXISTS it_packages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) DEFAULT NULL,
    price_detail VARCHAR(50) DEFAULT NULL,
    subtitle VARCHAR(255) DEFAULT NULL,
    features TEXT DEFAULT NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    status TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert seed data for IT packages
INSERT INTO it_packages (title, price, price_detail, subtitle, features, is_featured, status, sort_order) VALUES
('Base', 99.00, '/mese', 'Ideale per uffici professionali e piccole attività', 'Fino a 5 PC/Postazioni coperte\nAssistenza remota illimitata\nTempo di intervento entro 24 ore\nReport ed ottimizzazioni mensili', 0, 1, 1),
('Professional', 299.00, '/mese', 'La soluzione ottimale per aziende strutturate', 'Fino a 20 Postazioni coperte\nSupporto remoto e on-site incluso\nTempo risposta garantito entro 8 ore\nMonitoraggio proattivo dei server\nGestione del backup in Cloud', 1, 1, 2),
('Enterprise', NULL, 'Su misura', 'Per aziende con infrastrutture complesse', 'Server e client illimitati\nTecnico sistemista dedicato\nSLA personalizzati ad alta priorità\nReperibilità telefonica 24/7\nPiani avanzati di Disaster Recovery', 0, 1, 3);
