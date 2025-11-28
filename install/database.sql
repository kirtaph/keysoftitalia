-- Key Soft Italia Database Schema
-- Version: 1.0.0
-- Date: 2024

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS keysoftitalia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE keysoftitalia;

-- --------------------------------------------------------
-- Table: products (Prodotti Ricondizionati)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `sku` VARCHAR(50) UNIQUE,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) UNIQUE,
    `category` VARCHAR(100) NOT NULL,
    `brand` VARCHAR(100),
    `model` VARCHAR(100),
    `condition` ENUM('nuovo', 'ricondizionato_a', 'ricondizionato_b', 'ricondizionato_c', 'usato') DEFAULT 'ricondizionato_a',
    `price` DECIMAL(10,2) NOT NULL,
    `original_price` DECIMAL(10,2),
    `discount_percentage` INT(3),
    `description` TEXT,
    `specifications` TEXT,
    `warranty_months` INT(3) DEFAULT 12,
    `stock` INT(5) DEFAULT 0,
    `images` JSON,
    `featured` BOOLEAN DEFAULT FALSE,
    `status` ENUM('available', 'out_of_stock', 'coming_soon', 'discontinued') DEFAULT 'available',
    `views` INT(11) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_category` (`category`),
    KEY `idx_brand` (`brand`),
    KEY `idx_status` (`status`),
    KEY `idx_featured` (`featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: contacts (Contatti/Lead)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `contacts` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `surname` VARCHAR(100),
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(50),
    `company` VARCHAR(255),
    `subject` VARCHAR(255),
    `message` TEXT NOT NULL,
    `source` VARCHAR(50) DEFAULT 'website',
    `utm_source` VARCHAR(100),
    `utm_medium` VARCHAR(100),
    `utm_campaign` VARCHAR(100),
    `ip_address` VARCHAR(45),
    `user_agent` TEXT,
    `privacy_accepted` BOOLEAN DEFAULT TRUE,
    `newsletter_accepted` BOOLEAN DEFAULT FALSE,
    `status` ENUM('new', 'read', 'replied', 'archived') DEFAULT 'new',
    `notes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_email` (`email`),
    KEY `idx_status` (`status`),
    KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: assistance_requests (Richieste Assistenza)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `assistance_requests` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `ticket_number` VARCHAR(20) UNIQUE,
    `name` VARCHAR(100) NOT NULL,
    `surname` VARCHAR(100),
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(50) NOT NULL,
    `company` VARCHAR(255),
    `device_type` VARCHAR(100),
    `device_brand` VARCHAR(100),
    `device_model` VARCHAR(100),
    `problem_type` VARCHAR(100),
    `problem_description` TEXT NOT NULL,
    `urgency` ENUM('low', 'normal', 'high', 'critical') DEFAULT 'normal',
    `pickup_requested` BOOLEAN DEFAULT FALSE,
    `backup_needed` BOOLEAN DEFAULT FALSE,
    `status` ENUM('pending', 'in_progress', 'waiting_parts', 'ready', 'completed', 'cancelled') DEFAULT 'pending',
    `technician_notes` TEXT,
    `internal_notes` TEXT,
    `estimated_cost` DECIMAL(10,2),
    `final_cost` DECIMAL(10,2),
    `completion_date` DATE,
    `ip_address` VARCHAR(45),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `ticket_number` (`ticket_number`),
    KEY `idx_status` (`status`),
    KEY `idx_urgency` (`urgency`),
    KEY `idx_email` (`email`),
    KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: quotes (Preventivi)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `quotes` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `quote_number` VARCHAR(20) UNIQUE,
    `first_name` VARCHAR(100) NOT NULL,
    `last_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(50) NOT NULL,
    `company` VARCHAR(255),
    `service_type` VARCHAR(100) NOT NULL,
    `urgency` ENUM('normal', 'urgent', 'very_urgent') DEFAULT 'normal',
    `budget` VARCHAR(50),
    `description` TEXT NOT NULL,
    `device_info` TEXT,
    `pickup_requested` BOOLEAN DEFAULT FALSE,
    `warranty_extension` BOOLEAN DEFAULT FALSE,
    `newsletter` BOOLEAN DEFAULT FALSE,
    `status` ENUM('pending', 'sent', 'accepted', 'rejected', 'expired') DEFAULT 'pending',
    `quote_amount` DECIMAL(10,2),
    `quote_valid_until` DATE,
    `admin_notes` TEXT,
    `ip_address` VARCHAR(45),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `quote_number` (`quote_number`),
    KEY `idx_status` (`status`),
    KEY `idx_email` (`email`),
    KEY `idx_service_type` (`service_type`),
    KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: newsletter_subscribers
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `name` VARCHAR(100),
    `status` ENUM('active', 'unsubscribed', 'bounced') DEFAULT 'active',
    `token` VARCHAR(64) UNIQUE,
    `subscribed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `unsubscribed_at` TIMESTAMP NULL,
    `ip_address` VARCHAR(45),
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`),
    KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: admin_users (Utenti Admin)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100),
    `role` ENUM('admin', 'technician', 'sales') DEFAULT 'technician',
    `is_active` BOOLEAN DEFAULT TRUE,
    `last_login` TIMESTAMP NULL,
    `password_reset_token` VARCHAR(64),
    `password_reset_expires` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`),
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: activity_log (Log Attività)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `activity_log` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11),
    `action` VARCHAR(100) NOT NULL,
    `entity_type` VARCHAR(50),
    `entity_id` INT(11),
    `details` JSON,
    `ip_address` VARCHAR(45),
    `user_agent` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_user` (`user_id`),
    KEY `idx_entity` (`entity_type`, `entity_id`),
    KEY `idx_created` (`created_at`),
    FOREIGN KEY (`user_id`) REFERENCES `admin_users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: settings (Impostazioni Sistema)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT,
    `setting_type` VARCHAR(50) DEFAULT 'text',
    `category` VARCHAR(50) DEFAULT 'general',
    `description` TEXT,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `setting_key` (`setting_key`),
    KEY `idx_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Insert default admin user (password: admin123)
-- --------------------------------------------------------
INSERT INTO `admin_users` (`username`, `email`, `password`, `full_name`, `role`) VALUES
('admin', 'admin@keysoftitalia.it', '$2y$10$YZ1NqRkPOx.hhMr96XU6ZuH8KDWjK5f/KGFhHSWiHxLHTZCq.hIGa', 'Amministratore', 'admin');

-- --------------------------------------------------------
-- Insert default settings
-- --------------------------------------------------------
INSERT INTO `settings` (`setting_key`, `setting_value`, `category`, `description`) VALUES
('site_maintenance', '0', 'general', 'Modalità manutenzione'),
('email_notifications', '1', 'notifications', 'Abilita notifiche email'),
('auto_ticket_number', '1', 'assistance', 'Genera numero ticket automaticamente'),
('quote_validity_days', '30', 'quotes', 'Giorni validità preventivo'),
('max_upload_size', '10485760', 'uploads', 'Dimensione massima upload in bytes'),
('smtp_enabled', '0', 'email', 'Usa SMTP per invio email');

-- --------------------------------------------------------
-- Insert sample products
-- --------------------------------------------------------
INSERT INTO `products` (`name`, `slug`, `category`, `brand`, `model`, `condition`, `price`, `original_price`, `discount_percentage`, `description`, `warranty_months`, `stock`, `featured`) VALUES
('iPhone 13 Pro 128GB', 'iphone-13-pro-128gb', 'smartphone', 'Apple', 'iPhone 13 Pro', 'ricondizionato_a', 749.00, 999.00, 25, 'iPhone 13 Pro ricondizionato grado A, come nuovo. Batteria sostituita, garanzia 12 mesi.', 12, 3, 1),
('MacBook Air M1 256GB', 'macbook-air-m1-256gb', 'notebook', 'Apple', 'MacBook Air M1', 'ricondizionato_a', 899.00, 1299.00, 30, 'MacBook Air con chip M1, ricondizionato professionale. Perfette condizioni estetiche e funzionali.', 12, 2, 1),
('Samsung Galaxy S22', 'samsung-galaxy-s22', 'smartphone', 'Samsung', 'Galaxy S22', 'ricondizionato_b', 499.00, 799.00, 38, 'Samsung Galaxy S22 ricondizionato grado B. Piccoli segni di usura, perfettamente funzionante.', 12, 5, 0);


CREATE TABLE `used_device_quotes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  `device_type` VARCHAR(50) NOT NULL,
  `device_id` INT UNSIGNED NULL,

  `brand_id` INT UNSIGNED NULL,
  `brand_name` VARCHAR(100) NULL,
  `model_id` INT UNSIGNED NULL,
  `model_name` VARCHAR(150) NULL,

  `device_condition` ENUM('ottimo','buono','usurato','danneggiato') NOT NULL,
  `defects` JSON NULL,
  `accessories` JSON NULL,

  `expected_price` DECIMAL(10,2) NULL,
  `notes` TEXT NULL,

  `customer_first_name` VARCHAR(80) NOT NULL,
  `customer_last_name` VARCHAR(80) NOT NULL,
  `customer_email` VARCHAR(150) NOT NULL,
  `customer_phone` VARCHAR(40) NOT NULL,
  `contact_channel` VARCHAR(40) NOT NULL DEFAULT 'form',
  `privacy_accepted` TINYINT(1) NOT NULL DEFAULT 0,

  `status` ENUM('pending','reviewed','contacted') NOT NULL DEFAULT 'pending',

  `ip_address` VARBINARY(16) NULL,
  `user_agent` VARCHAR(255) NULL,

  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `repair_bookings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

  -- da dove arriva la prenotazione
  `channel` ENUM('web','whatsapp','phone','internal') NOT NULL DEFAULT 'web',
  `source`  VARCHAR(100) NULL DEFAULT NULL,
  `external_ref` VARCHAR(100) NULL DEFAULT NULL, -- es. ORD-2025-0001, PR-2025-0003

  -- device / brand / model
  `device_type` VARCHAR(50) NOT NULL,     -- smartphone/tablet/computer/console/tv/altro
  `device_id`   INT UNSIGNED NULL DEFAULT NULL,

  `brand_id`    INT UNSIGNED NULL DEFAULT NULL,
  `brand_name`  VARCHAR(120) NOT NULL,

  `model_id`    INT UNSIGNED NULL DEFAULT NULL,
  `model_name`  VARCHAR(160) NOT NULL,

  -- problema e note
  `problem_summary` VARCHAR(255) NULL DEFAULT NULL,
  `notes`           TEXT NULL,

  -- slot prenotazione
  `preferred_date` DATE NOT NULL,
  `preferred_time_slot` VARCHAR(50) NOT NULL,
  `dropoff_type` ENUM('in_store','pickup','on_site') NOT NULL DEFAULT 'in_store',

  `backup_done` TINYINT(1) NOT NULL DEFAULT 0,
  `tests_ok`    TINYINT(1) NOT NULL DEFAULT 0,

  -- cliente
  `customer_first_name` VARCHAR(80)  NOT NULL,
  `customer_last_name`  VARCHAR(80)  NOT NULL,
  `customer_email`      VARCHAR(190) NOT NULL,
  `customer_phone`      VARCHAR(40)  NOT NULL,
  `customer_company`    VARCHAR(160) NULL DEFAULT NULL,
  `contact_channel`     VARCHAR(20)  NULL DEFAULT NULL, -- whatsapp/telefono/email

  `privacy_accepted` TINYINT(1) NOT NULL DEFAULT 0,

  `status` ENUM('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',

  PRIMARY KEY (`id`),
  KEY `idx_preferred_date` (`preferred_date`),
  KEY `idx_status_date` (`status`, `preferred_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;