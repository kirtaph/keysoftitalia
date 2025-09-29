-- =============================================
-- Key Soft Italia Database Initialization Script
-- Version: 1.0.0
-- Date: 2024
-- =============================================

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS `keysoftitalia` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `keysoftitalia`;

-- =============================================
-- Table: Products (Ricondizionati)
-- =============================================
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `sku` VARCHAR(50) UNIQUE NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `category` ENUM('computer', 'notebook', 'smartphone', 'tablet', 'monitor', 'stampante', 'accessori') NOT NULL,
    `brand` VARCHAR(100),
    `model` VARCHAR(100),
    `description` TEXT,
    `specifications` TEXT,
    `price` DECIMAL(10,2) NOT NULL,
    `original_price` DECIMAL(10,2),
    `discount_percentage` INT(3) DEFAULT 0,
    `condition` ENUM('come_nuovo', 'ottimo', 'buono', 'discreto') DEFAULT 'ottimo',
    `warranty_months` INT(3) DEFAULT 12,
    `stock_quantity` INT(11) DEFAULT 1,
    `is_available` BOOLEAN DEFAULT TRUE,
    `is_featured` BOOLEAN DEFAULT FALSE,
    `main_image` VARCHAR(500),
    `gallery_images` TEXT,
    `views` INT(11) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_category` (`category`),
    INDEX `idx_available` (`is_available`),
    INDEX `idx_price` (`price`),
    INDEX `idx_featured` (`is_featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: Contact Requests
-- =============================================
CREATE TABLE IF NOT EXISTS `contact_requests` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(50) NOT NULL,
    `subject` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `newsletter` BOOLEAN DEFAULT FALSE,
    `status` ENUM('new', 'read', 'replied', 'archived') DEFAULT 'new',
    `notes` TEXT,
    `replied_at` DATETIME,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_email` (`email`),
    INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: Assistance Requests
-- =============================================
CREATE TABLE IF NOT EXISTS `assistance_requests` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `ticket_number` VARCHAR(20) UNIQUE NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(50) NOT NULL,
    `device_type` VARCHAR(50) NOT NULL,
    `problem_description` TEXT NOT NULL,
    `assistance_type` ENUM('domicilio', 'remota') NOT NULL,
    `address` VARCHAR(500),
    `urgency` ENUM('normale', 'urgente', 'immediata') DEFAULT 'normale',
    `time_preference` VARCHAR(50),
    `status` ENUM('pending', 'assigned', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    `technician_id` INT(11),
    `scheduled_date` DATE,
    `scheduled_time` TIME,
    `completion_date` DATETIME,
    `technician_notes` TEXT,
    `customer_feedback` TEXT,
    `rating` INT(1),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `ticket_number` (`ticket_number`),
    INDEX `idx_status` (`status`),
    INDEX `idx_urgency` (`urgency`),
    INDEX `idx_type` (`assistance_type`),
    INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: Quote Requests
-- =============================================
CREATE TABLE IF NOT EXISTS `quote_requests` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `quote_number` VARCHAR(20) UNIQUE NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(50) NOT NULL,
    `device_model` VARCHAR(255),
    `problem_description` TEXT NOT NULL,
    `priority` ENUM('normal', 'urgent', 'immediate') DEFAULT 'normal',
    `budget` VARCHAR(50),
    `calculated_price` DECIMAL(10,2),
    `selected_device` VARCHAR(50),
    `selected_services` TEXT,
    `newsletter` BOOLEAN DEFAULT FALSE,
    `status` ENUM('pending', 'sent', 'accepted', 'rejected', 'expired') DEFAULT 'pending',
    `quote_amount` DECIMAL(10,2),
    `quote_details` TEXT,
    `quote_sent_date` DATETIME,
    `quote_valid_until` DATE,
    `admin_notes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `quote_number` (`quote_number`),
    INDEX `idx_status` (`status`),
    INDEX `idx_priority` (`priority`),
    INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: Newsletter Subscribers
-- =============================================
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `name` VARCHAR(100),
    `is_active` BOOLEAN DEFAULT TRUE,
    `unsubscribe_token` VARCHAR(64),
    `subscribed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `unsubscribed_at` DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`),
    INDEX `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: Admin Users
-- =============================================
CREATE TABLE IF NOT EXISTS `admin_users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) UNIQUE NOT NULL,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100),
    `role` ENUM('super_admin', 'admin', 'technician', 'sales') DEFAULT 'admin',
    `is_active` BOOLEAN DEFAULT TRUE,
    `last_login` DATETIME,
    `login_attempts` INT(3) DEFAULT 0,
    `locked_until` DATETIME,
    `reset_token` VARCHAR(64),
    `reset_token_expires` DATETIME,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`),
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: Services
-- =============================================
CREATE TABLE IF NOT EXISTS `services` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) UNIQUE NOT NULL,
    `category` VARCHAR(100),
    `description` TEXT,
    `short_description` VARCHAR(500),
    `price_from` DECIMAL(10,2),
    `icon` VARCHAR(100),
    `image` VARCHAR(500),
    `is_active` BOOLEAN DEFAULT TRUE,
    `display_order` INT(11) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug` (`slug`),
    INDEX `idx_active` (`is_active`),
    INDEX `idx_order` (`display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: FAQ
-- =============================================
CREATE TABLE IF NOT EXISTS `faq` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `question` VARCHAR(500) NOT NULL,
    `answer` TEXT NOT NULL,
    `category` VARCHAR(100),
    `is_active` BOOLEAN DEFAULT TRUE,
    `display_order` INT(11) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_category` (`category`),
    INDEX `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: Activity Logs
-- =============================================
CREATE TABLE IF NOT EXISTS `activity_logs` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11),
    `action` VARCHAR(100) NOT NULL,
    `entity_type` VARCHAR(50),
    `entity_id` INT(11),
    `details` TEXT,
    `ip_address` VARCHAR(45),
    `user_agent` VARCHAR(500),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_user` (`user_id`),
    INDEX `idx_entity` (`entity_type`, `entity_id`),
    INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Insert Default Admin User
-- =============================================
-- Password: Admin123! (change immediately after first login)
INSERT INTO `admin_users` (`username`, `email`, `password_hash`, `full_name`, `role`) 
VALUES (
    'admin',
    'admin@keysoftitalia.it',
    '$2y$10$YqI6HRxbxPxQvT7GVY6zN.GkDmUZKdWJfLxHpNvRlBzLTMzEcBOGy', -- Admin123!
    'Amministratore',
    'super_admin'
);

-- =============================================
-- Insert Sample Products
-- =============================================
INSERT INTO `products` (`sku`, `name`, `category`, `brand`, `model`, `description`, `price`, `original_price`, `discount_percentage`, `condition`, `warranty_months`, `stock_quantity`, `is_featured`) VALUES
('NB001', 'Notebook HP ProBook 450 G8', 'notebook', 'HP', 'ProBook 450 G8', 'Notebook professionale ricondizionato, Intel Core i5-1135G7, 8GB RAM, 256GB SSD, Display 15.6" FHD', 599.00, 999.00, 40, 'ottimo', 12, 2, TRUE),
('PC001', 'Desktop Dell OptiPlex 7080', 'computer', 'Dell', 'OptiPlex 7080', 'PC Desktop ricondizionato, Intel Core i7-10700, 16GB RAM, 512GB SSD, Windows 11 Pro', 799.00, 1299.00, 38, 'come_nuovo', 12, 1, TRUE),
('SM001', 'iPhone 12 64GB', 'smartphone', 'Apple', 'iPhone 12', 'iPhone 12 ricondizionato, 64GB, Blu, Batteria nuova, Garanzia 12 mesi', 499.00, 759.00, 34, 'ottimo', 12, 3, TRUE),
('TB001', 'iPad Air 4a Gen 64GB', 'tablet', 'Apple', 'iPad Air', 'iPad Air 4a generazione, 64GB WiFi, Space Grey, Come nuovo', 549.00, 719.00, 24, 'come_nuovo', 12, 1, FALSE),
('MN001', 'Monitor Samsung 27" 4K', 'monitor', 'Samsung', 'U28E590D', 'Monitor 4K UHD 27 pollici, 3840x2160, HDMI/DisplayPort', 249.00, 399.00, 38, 'ottimo', 6, 2, FALSE);

-- =============================================
-- Insert Sample Services
-- =============================================
INSERT INTO `services` (`name`, `slug`, `category`, `description`, `short_description`, `price_from`, `icon`, `is_active`, `display_order`) VALUES
('Riparazione Computer', 'riparazione-computer', 'riparazioni', 'Servizio completo di riparazione per desktop e all-in-one', 'Riparazione hardware e software per PC desktop', 30.00, 'ri-computer-line', TRUE, 1),
('Riparazione Notebook', 'riparazione-notebook', 'riparazioni', 'Riparazione professionale per laptop e notebook', 'Assistenza specializzata per portatili', 40.00, 'ri-macbook-line', TRUE, 2),
('Riparazione Smartphone', 'riparazione-smartphone', 'riparazioni', 'Riparazione per tutti i modelli di smartphone', 'Sostituzione schermo, batteria e componenti', 35.00, 'ri-smartphone-line', TRUE, 3),
('Assistenza Remota', 'assistenza-remota', 'assistenza', 'Supporto tecnico da remoto per problemi software', 'Risoluzione problemi via TeamViewer', 25.00, 'ri-global-line', TRUE, 4),
('Recupero Dati', 'recupero-dati', 'servizi', 'Recupero dati da hard disk e SSD danneggiati', 'Recupero file cancellati o da dispositivi danneggiati', 100.00, 'ri-database-2-line', TRUE, 5);

-- =============================================
-- Insert Sample FAQ
-- =============================================
INSERT INTO `faq` (`question`, `answer`, `category`, `is_active`, `display_order`) VALUES
('Quanto costa la diagnosi?', 'La diagnosi è sempre gratuita e senza impegno. Analizziamo il problema e forniamo un preventivo dettagliato prima di procedere.', 'generale', TRUE, 1),
('Quali sono i tempi di riparazione?', 'I tempi variano in base al tipo di intervento. Generalmente le riparazioni standard richiedono 24-48 ore, mentre interventi più complessi possono richiedere 3-5 giorni lavorativi.', 'riparazioni', TRUE, 2),
('Offrite garanzia sulle riparazioni?', 'Sì, tutte le nostre riparazioni sono coperte da garanzia di 3-12 mesi a seconda del tipo di intervento e dei componenti sostituiti.', 'garanzia', TRUE, 3),
('Fate assistenza a domicilio?', 'Certamente! Offriamo servizio di assistenza a domicilio su appuntamento con un piccolo supplemento per la trasferta.', 'assistenza', TRUE, 4),
('Accettate pagamenti rateali?', 'Sì, per importi superiori a €200 offriamo la possibilità di pagamento rateale tramite finanziamento.', 'pagamenti', TRUE, 5);

-- =============================================
-- Create Views for Statistics
-- =============================================
CREATE OR REPLACE VIEW `v_monthly_statistics` AS
SELECT 
    DATE_FORMAT(created_at, '%Y-%m') as month,
    COUNT(DISTINCT CASE WHEN status IN ('completed') THEN id END) as repairs_completed,
    COUNT(DISTINCT CASE WHEN status IN ('pending', 'assigned', 'in_progress') THEN id END) as repairs_pending,
    AVG(rating) as avg_rating
FROM assistance_requests
GROUP BY DATE_FORMAT(created_at, '%Y-%m');

CREATE OR REPLACE VIEW `v_product_statistics` AS
SELECT 
    p.category,
    COUNT(*) as total_products,
    SUM(p.stock_quantity) as total_stock,
    AVG(p.discount_percentage) as avg_discount,
    SUM(p.views) as total_views
FROM products p
WHERE p.is_available = TRUE
GROUP BY p.category;

-- =============================================
-- Grant Permissions (adjust as needed)
-- =============================================
-- GRANT ALL PRIVILEGES ON keysoftitalia.* TO 'keysoftuser'@'localhost' IDENTIFIED BY 'secure_password';
-- FLUSH PRIVILEGES;

-- =============================================
-- Success Message
-- =============================================
SELECT 'Database initialization completed successfully!' AS message;