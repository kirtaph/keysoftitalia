-- Migration: Create web packages and showcase tables
-- Created At: 2026-06-10

CREATE TABLE IF NOT EXISTS web_packages (
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

CREATE TABLE IF NOT EXISTS web_showcase (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description VARCHAR(255) NOT NULL,
    technologies VARCHAR(255) NOT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    project_url VARCHAR(255) DEFAULT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert seed data for packages
INSERT INTO web_packages (title, price, price_detail, subtitle, features, is_featured, status, sort_order) VALUES
('Landing Page', 499.00, '', 'Perfetta per campagne marketing', 'Design personalizzato\n1 pagina ottimizzata\nForm contatti ed email\nMobile responsive\nSEO base di indicizzazione', 0, 1, 1),
('Sito Aziendale', 1499.00, '', 'Ideale per PMI e professionisti', 'Design su misura professionale\nFino a 10 pagine dedicate\nBlog integrato per notizie\nGallery prodotti o servizi\nSEO avanzato sui testi\nGoogle Analytics incluso', 1, 1, 2),
('E-commerce', 2999.00, '', 'Vendi online con successo', 'Shop completo auto-gestito\nSistemi pagamento integrati\nGestione magazzino e ordini\nCoupon, sconti e promozioni\nSupporto multilingua completo\nSpedizioni automatiche\nApp mobile di controllo', 0, 1, 3),
('Progetto Custom', NULL, 'Su misura', 'Soluzioni software enterprise', 'Analisi approfondita requisiti\nSviluppo custom da zero\nIntegrazioni API e gestionali\nWeb application interattiva\nDatabase dedicato ottimizzato\nScalabilità futura garantita\nSupporto tecnico dedicato', 0, 1, 4);

-- Insert seed data for showcase
INSERT INTO web_showcase (title, description, technologies, image_path, project_url, status, sort_order) VALUES
('Fashion Store', 'E-commerce moda completo con oltre 5000 prodotti attivi.', 'WooCommerce, Payment Gateway, Stripe', NULL, NULL, 1, 1),
('Studio Legale', 'Sito istituzionale con area riservata clienti e gestione documenti.', 'WordPress, Custom Theme, PHP', NULL, NULL, 1, 2),
('Ristorante Gourmet', 'Sito web con prenotazione online dei tavoli e menu digitale interattivo.', 'React, Node.js, MongoDB', NULL, NULL, 1, 3),
('Agenzia Immobiliare', 'Portale annunci immobiliari con ricerca avanzata su mappa interattiva.', 'Laravel, Vue.js, MySQL', NULL, NULL, 1, 4),
('Palestra & Fitness', 'Sistema di prenotazione corsi e gestione abbonamenti mensili ricorrenti.', 'Custom CMS, Stripe, JavaScript', NULL, NULL, 1, 5),
('Clinica Medica', 'Portale sanitario per prenotazione visite mediche e telemedicina integrata.', 'WebRTC, PHP, MySQL', NULL, NULL, 1, 6);
