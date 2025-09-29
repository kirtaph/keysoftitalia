-- Key Soft Italia Database Schema
-- Version: 1.0.0
-- Database: keysoftitalia_db

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+01:00";

-- --------------------------------------------------------
-- Database Creation
-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `keysoftitalia_db` 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `keysoftitalia_db`;

-- --------------------------------------------------------
-- Table structure for `brands`
-- --------------------------------------------------------

CREATE TABLE `brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data for brands
INSERT INTO `brands` (`name`, `slug`) VALUES
('Apple', 'apple'),
('Samsung', 'samsung'),
('Xiaomi', 'xiaomi'),
('Huawei', 'huawei'),
('OnePlus', 'oneplus'),
('Google', 'google'),
('Microsoft', 'microsoft'),
('Lenovo', 'lenovo'),
('HP', 'hp'),
('Dell', 'dell'),
('Asus', 'asus'),
('Acer', 'acer');

-- --------------------------------------------------------
-- Table structure for `categories`
-- --------------------------------------------------------

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `description` text,
  `parent_id` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `parent_id` (`parent_id`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data for categories
INSERT INTO `categories` (`name`, `slug`, `icon`) VALUES
('Smartphone', 'smartphone', 'ri-smartphone-line'),
('Tablet', 'tablet', 'ri-tablet-line'),
('Laptop', 'laptop', 'ri-macbook-line'),
('Desktop', 'desktop', 'ri-computer-line'),
('Smartwatch', 'smartwatch', 'ri-time-line'),
('Accessori', 'accessori', 'ri-headphone-line');

-- --------------------------------------------------------
-- Table structure for `conditions`
-- --------------------------------------------------------

CREATE TABLE `conditions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `short_code` varchar(10) NOT NULL,
  `description` text,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `short_code` (`short_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data for conditions
INSERT INTO `conditions` (`name`, `short_code`, `description`, `sort_order`) VALUES
('Come Nuovo', 'A+', 'Prodotto in condizioni eccellenti, praticamente nuovo', 1),
('Ottimo', 'A', 'Minime tracce di utilizzo, perfettamente funzionante', 2),
('Buono', 'B', 'Normali segni di utilizzo, completamente funzionante', 3),
('Discreto', 'C', 'Evidenti segni di utilizzo ma funzionante al 100%', 4);

-- --------------------------------------------------------
-- Table structure for `products`
-- --------------------------------------------------------

CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` varchar(50) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `condition_id` int(11) NOT NULL,
  `price_eur` decimal(10,2) NOT NULL,
  `price_old_eur` decimal(10,2) DEFAULT NULL,
  `warranty_months` int(11) DEFAULT 12,
  `short_desc` text,
  `full_desc` text,
  `highlights_json` text,
  `specs_json` text,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_available` tinyint(1) DEFAULT 1,
  `stock_qty` int(11) DEFAULT 1,
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `brand_id` (`brand_id`),
  KEY `category_id` (`category_id`),
  KEY `condition_id` (`condition_id`),
  KEY `is_featured` (`is_featured`),
  KEY `is_available` (`is_available`),
  KEY `price_eur` (`price_eur`),
  FOREIGN KEY (`brand_id`) REFERENCES `brands`(`id`),
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`),
  FOREIGN KEY (`condition_id`) REFERENCES `conditions`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for `product_images`
-- --------------------------------------------------------

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `url` varchar(500) NOT NULL,
  `alt` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `is_primary` (`is_primary`),
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for `leads`
-- --------------------------------------------------------

CREATE TABLE `leads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_type` enum('contact','quote','assistance','repair_estimate','newsletter') NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text,
  `device_type` varchar(100) DEFAULT NULL,
  `device_model` varchar(100) DEFAULT NULL,
  `problem_type` varchar(255) DEFAULT NULL,
  `source_page` varchar(255) DEFAULT NULL,
  `utm_source` varchar(50) DEFAULT NULL,
  `utm_medium` varchar(50) DEFAULT NULL,
  `utm_campaign` varchar(50) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `status` enum('new','processing','completed','archived') DEFAULT 'new',
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `form_type` (`form_type`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for `telefonia_offers`
-- --------------------------------------------------------

CREATE TABLE `telefonia_offers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operator` varchar(100) NOT NULL,
  `plan_name` varchar(255) NOT NULL,
  `type` enum('mobile','internet','energia','business') NOT NULL,
  `price_month` decimal(8,2) NOT NULL,
  `activation_fee` decimal(8,2) DEFAULT NULL,
  `data_gb` int(11) DEFAULT NULL,
  `minutes` int(11) DEFAULT NULL,
  `sms` int(11) DEFAULT NULL,
  `download_mbps` int(11) DEFAULT NULL,
  `upload_mbps` int(11) DEFAULT NULL,
  `modem_included` tinyint(1) DEFAULT 0,
  `min_duration_months` int(11) DEFAULT NULL,
  `key_values_json` text,
  `badge` varchar(50) DEFAULT NULL,
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `is_published` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `is_featured` (`is_featured`),
  KEY `is_published` (`is_published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for `monthly_offers`
-- --------------------------------------------------------

CREATE TABLE `monthly_offers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `category_slug` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `highlights_json` text,
  `price_current` decimal(10,2) NOT NULL,
  `price_old` decimal(10,2) DEFAULT NULL,
  `discount_pct` int(11) GENERATED ALWAYS AS (ROUND((1 - price_current/price_old)*100,0)) STORED,
  `badge` varchar(50) DEFAULT NULL,
  `stock_status` enum('disponibile','esaurito','ordinabile') DEFAULT 'disponibile',
  `cta` enum('whatsapp','preventivo') DEFAULT 'whatsapp',
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `is_published` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `is_featured` (`is_featured`),
  KEY `is_published` (`is_published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for `monthly_offer_images`
-- --------------------------------------------------------

CREATE TABLE `monthly_offer_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `offer_id` int(11) NOT NULL,
  `url` varchar(500) NOT NULL,
  `alt` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `offer_id` (`offer_id`),
  FOREIGN KEY (`offer_id`) REFERENCES `monthly_offers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for `faq`
-- --------------------------------------------------------

CREATE TABLE `faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(50) DEFAULT 'general',
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_published` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `is_published` (`is_published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample FAQ data
INSERT INTO `faq` (`category`, `question`, `answer`, `sort_order`) VALUES
('assistenza', 'Quanto costa l\'assistenza a domicilio?', 'Il costo dell\'assistenza a domicilio parte da 30€ per la prima ora, con tariffe aggiuntive per ore successive. Il preventivo è sempre gratuito e senza impegno.', 1),
('assistenza', 'L\'assistenza remota è sicura?', 'Sì, utilizziamo software certificati e connessioni criptate. Avrete sempre il controllo completo del vostro dispositivo e potrete interrompere la connessione in qualsiasi momento.', 2),
('assistenza', 'Quanto tempo serve per un intervento?', 'Per le riparazioni più comuni garantiamo interventi entro 24-48 ore. Per problemi complessi potrebbero essere necessari 3-5 giorni lavorativi.', 3),
('assistenza', 'Cosa include l\'assistenza?', 'L\'assistenza include diagnosi completa, riparazione del problema, test di funzionamento, pulizia del dispositivo e garanzia di 12 mesi sul lavoro svolto.', 4);

-- --------------------------------------------------------
-- Table structure for `admin_users`
-- --------------------------------------------------------

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `role` enum('admin','editor','viewer') DEFAULT 'editor',
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin user (password: KeySoft2024!)
INSERT INTO `admin_users` (`username`, `email`, `password_hash`, `full_name`, `role`) VALUES
('admin', 'admin@keysoftitalia.it', '$2y$10$YourHashedPasswordHere', 'Administrator', 'admin');

-- --------------------------------------------------------
-- Indexes and Performance Optimization
-- --------------------------------------------------------

-- Add full-text search indexes
ALTER TABLE `products` ADD FULLTEXT(`title`, `short_desc`);
ALTER TABLE `leads` ADD INDEX `idx_created_form` (`created_at`, `form_type`);

COMMIT;