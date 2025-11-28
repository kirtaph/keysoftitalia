-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Nov 28, 2025 alle 18:35
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `ks_site_db`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `brands`
--

CREATE TABLE `brands` (
  `id` int(10) UNSIGNED NOT NULL,
  `device_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `devices`
--

CREATE TABLE `devices` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(40) NOT NULL,
  `name` varchar(80) NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `flyers`
--

CREATE TABLE `flyers` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `show_home` tinyint(1) NOT NULL DEFAULT 0,
  `cover_image` varchar(255) DEFAULT NULL,
  `pdf_file` varchar(255) DEFAULT NULL,
  `internal_notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `issues`
--

CREATE TABLE `issues` (
  `id` int(10) UNSIGNED NOT NULL,
  `device_id` int(10) UNSIGNED NOT NULL,
  `label` varchar(140) NOT NULL,
  `severity` enum('low','mid','high') NOT NULL DEFAULT 'mid',
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ks_store_holidays`
--

CREATE TABLE `ks_store_holidays` (
  `id` int(10) UNSIGNED NOT NULL,
  `rule_type` enum('fixed','easter') NOT NULL,
  `month` tinyint(3) UNSIGNED DEFAULT NULL,
  `day` tinyint(3) UNSIGNED DEFAULT NULL,
  `offset_days` smallint(6) DEFAULT 0,
  `name` varchar(100) NOT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 1,
  `notice` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ks_store_hours_exceptions`
--

CREATE TABLE `ks_store_hours_exceptions` (
  `id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `seg` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `open_time` time DEFAULT NULL,
  `close_time` time DEFAULT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `notice` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ks_store_hours_weekly`
--

CREATE TABLE `ks_store_hours_weekly` (
  `id` int(10) UNSIGNED NOT NULL,
  `dow` tinyint(3) UNSIGNED NOT NULL,
  `seg` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `open_time` time NOT NULL,
  `close_time` time NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `models`
--

CREATE TABLE `models` (
  `id` int(10) UNSIGNED NOT NULL,
  `brand_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `year` smallint(6) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `price_rules`
--

CREATE TABLE `price_rules` (
  `id` int(10) UNSIGNED NOT NULL,
  `device_id` int(10) UNSIGNED NOT NULL,
  `brand_id` int(10) UNSIGNED DEFAULT NULL,
  `model_id` int(10) UNSIGNED DEFAULT NULL,
  `issue_id` int(10) UNSIGNED NOT NULL,
  `min_price` decimal(10,2) NOT NULL,
  `max_price` decimal(10,2) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `model_id` int(10) UNSIGNED NOT NULL,
  `sku` varchar(40) NOT NULL,
  `color` varchar(40) DEFAULT NULL,
  `storage_gb` smallint(5) UNSIGNED DEFAULT NULL,
  `grade` enum('Nuovo','Expo','A+','A','B','C') DEFAULT 'A',
  `list_price` decimal(10,2) DEFAULT NULL,
  `price_eur` decimal(10,2) NOT NULL,
  `short_desc` varchar(255) DEFAULT NULL,
  `full_desc` text DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `product_images`
--

CREATE TABLE `product_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `alt_text` varchar(120) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_cover` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `quotes`
--

CREATE TABLE `quotes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `device_id` int(10) UNSIGNED NOT NULL,
  `brand_text` varchar(120) NOT NULL,
  `model_text` varchar(160) DEFAULT NULL,
  `problems_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`problems_json`)),
  `description` text DEFAULT NULL,
  `booking_date` date DEFAULT NULL,
  `booking_time` time DEFAULT NULL,
  `est_min` decimal(10,2) DEFAULT NULL,
  `est_max` decimal(10,2) DEFAULT NULL,
  `first_name` varchar(80) NOT NULL,
  `last_name` varchar(80) NOT NULL,
  `email` varchar(140) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `company` varchar(120) DEFAULT NULL,
  `ip_address` varbinary(16) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `repair_bookings`
--

CREATE TABLE `repair_bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `channel` enum('web','whatsapp','phone','internal') NOT NULL DEFAULT 'web',
  `source` varchar(100) DEFAULT NULL,
  `external_ref` varchar(100) DEFAULT NULL,
  `device_type` varchar(50) NOT NULL,
  `device_id` int(10) UNSIGNED DEFAULT NULL,
  `brand_id` int(10) UNSIGNED DEFAULT NULL,
  `brand_name` varchar(120) NOT NULL,
  `model_id` int(10) UNSIGNED DEFAULT NULL,
  `model_name` varchar(160) NOT NULL,
  `problem_summary` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `preferred_date` date NOT NULL,
  `preferred_time_slot` varchar(50) NOT NULL,
  `dropoff_type` enum('in_store','pickup','on_site') NOT NULL DEFAULT 'in_store',
  `backup_done` tinyint(1) NOT NULL DEFAULT 0,
  `tests_ok` tinyint(1) NOT NULL DEFAULT 0,
  `customer_first_name` varchar(80) NOT NULL,
  `customer_last_name` varchar(80) NOT NULL,
  `customer_email` varchar(190) NOT NULL,
  `customer_phone` varchar(40) NOT NULL,
  `customer_company` varchar(160) DEFAULT NULL,
  `contact_channel` varchar(20) DEFAULT NULL,
  `privacy_accepted` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `used_device_quotes`
--

CREATE TABLE `used_device_quotes` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `device_type` varchar(50) NOT NULL,
  `device_id` int(10) UNSIGNED DEFAULT NULL,
  `brand_id` int(10) UNSIGNED DEFAULT NULL,
  `brand_name` varchar(100) DEFAULT NULL,
  `model_id` int(10) UNSIGNED DEFAULT NULL,
  `model_name` varchar(150) DEFAULT NULL,
  `device_condition` enum('ottimo','buono','usurato','danneggiato') NOT NULL,
  `defects` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`defects`)),
  `accessories` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`accessories`)),
  `expected_price` decimal(10,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `customer_first_name` varchar(80) NOT NULL,
  `customer_last_name` varchar(80) NOT NULL,
  `customer_email` varchar(150) NOT NULL,
  `customer_phone` varchar(40) NOT NULL,
  `contact_channel` varchar(40) NOT NULL DEFAULT 'form',
  `privacy_accepted` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('pending','reviewed','contacted') NOT NULL DEFAULT 'pending',
  `ip_address` varbinary(16) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_device_brand` (`device_id`,`name`);

--
-- Indici per le tabelle `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indici per le tabelle `flyers`
--
ALTER TABLE `flyers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_slug` (`slug`),
  ADD KEY `idx_status_dates` (`status`,`start_date`,`end_date`),
  ADD KEY `idx_show_home` (`show_home`);

--
-- Indici per le tabelle `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_device_issue` (`device_id`,`label`);

--
-- Indici per le tabelle `ks_store_holidays`
--
ALTER TABLE `ks_store_holidays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fixed` (`rule_type`,`month`,`day`),
  ADD KEY `idx_easter` (`rule_type`,`offset_days`);

--
-- Indici per le tabelle `ks_store_hours_exceptions`
--
ALTER TABLE `ks_store_hours_exceptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_date` (`date`);

--
-- Indici per le tabelle `ks_store_hours_weekly`
--
ALTER TABLE `ks_store_hours_weekly`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_dow_seg` (`dow`,`seg`),
  ADD KEY `idx_dow` (`dow`);

--
-- Indici per le tabelle `models`
--
ALTER TABLE `models`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_brand_model` (`brand_id`,`name`);

--
-- Indici per le tabelle `price_rules`
--
ALTER TABLE `price_rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lookup` (`device_id`,`brand_id`,`model_id`,`issue_id`),
  ADD KEY `fk_rule_brand` (`brand_id`),
  ADD KEY `fk_rule_model` (`model_id`),
  ADD KEY `fk_rule_issue` (`issue_id`);

--
-- Indici per le tabelle `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_sku` (`sku`),
  ADD KEY `ix_model` (`model_id`),
  ADD KEY `ix_featured_available` (`is_featured`,`is_available`,`created_at`);

--
-- Indici per le tabelle `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_product` (`product_id`,`sort_order`);

--
-- Indici per le tabelle `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `repair_bookings`
--
ALTER TABLE `repair_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_preferred_date` (`preferred_date`),
  ADD KEY `idx_status_date` (`status`,`preferred_date`);

--
-- Indici per le tabelle `used_device_quotes`
--
ALTER TABLE `used_device_quotes`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `flyers`
--
ALTER TABLE `flyers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `issues`
--
ALTER TABLE `issues`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ks_store_holidays`
--
ALTER TABLE `ks_store_holidays`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ks_store_hours_exceptions`
--
ALTER TABLE `ks_store_hours_exceptions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ks_store_hours_weekly`
--
ALTER TABLE `ks_store_hours_weekly`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `models`
--
ALTER TABLE `models`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `price_rules`
--
ALTER TABLE `price_rules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `quotes`
--
ALTER TABLE `quotes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `repair_bookings`
--
ALTER TABLE `repair_bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `used_device_quotes`
--
ALTER TABLE `used_device_quotes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `brands`
--
ALTER TABLE `brands`
  ADD CONSTRAINT `fk_brand_device` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `issues`
--
ALTER TABLE `issues`
  ADD CONSTRAINT `fk_issue_device` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `models`
--
ALTER TABLE `models`
  ADD CONSTRAINT `fk_model_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `price_rules`
--
ALTER TABLE `price_rules`
  ADD CONSTRAINT `fk_rule_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_rule_device` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_rule_issue` FOREIGN KEY (`issue_id`) REFERENCES `issues` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_rule_model` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`) ON DELETE SET NULL;

--
-- Limiti per la tabella `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_refurb_model` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_image_refurb` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
