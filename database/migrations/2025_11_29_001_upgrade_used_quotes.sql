-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Nov 29, 2025 alle 09:51
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
-- Struttura della tabella `used_device_quotes`
--

DROP TABLE IF EXISTS `used_device_quotes`;
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

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `used_device_quotes`
--
ALTER TABLE `used_device_quotes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `used_device_quotes`
--
ALTER TABLE `used_device_quotes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;