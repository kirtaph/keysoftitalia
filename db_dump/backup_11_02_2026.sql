-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 11, 2026 alle 12:54
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ks_site_db`
--
CREATE DATABASE IF NOT EXISTS `ks_site_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ks_site_db`;

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

--
-- Dump dei dati per la tabella `brands`
--

INSERT INTO `brands` (`id`, `device_id`, `name`, `is_active`) VALUES
(1, 1, 'Apple', 1),
(2, 1, 'Samsung', 1),
(38, 1, 'Xiaomi', 1),
(39, 1, 'Huawei', 1),
(40, 1, 'Oppo', 1),
(41, 1, 'OnePlus', 1),
(42, 1, 'Google', 1),
(43, 1, 'Motorola', 1),
(44, 1, 'Realme', 1),
(45, 1, 'Honor', 1),
(47, 1, 'Nokia', 1),
(48, 1, 'ASUS', 1),
(49, 1, 'TCL', 1),
(50, 2, 'Apple', 1),
(51, 2, 'Samsung', 1),
(52, 2, 'Lenovo', 1),
(53, 2, 'Huawei', 1),
(54, 2, 'Xiaomi', 1),
(56, 2, 'Microsoft', 1),
(57, 3, 'Apple', 1),
(58, 3, 'HP', 1),
(59, 3, 'Dell', 1),
(60, 3, 'Lenovo', 1),
(61, 3, 'Acer', 1),
(62, 3, 'ASUS', 1),
(63, 3, 'MSI', 1),
(64, 4, 'Sony PlayStation', 1),
(65, 4, 'Microsoft Xbox', 1),
(66, 4, 'Nintendo Switch', 1),
(67, 5, 'Samsung', 1),
(68, 5, 'LG', 1),
(69, 5, 'Sony', 1),
(70, 5, 'Philips', 1),
(71, 5, 'Hisense', 1),
(72, 5, 'TCL', 1),
(73, 1, 'Redmi9c', 1),
(74, 1, 'Iphone11pro', 1),
(75, 1, 'Samsunga03s', 1),
(76, 1, 'Note8', 1),
(77, 1, 'Samsungs10', 1),
(78, 1, 'Samsunga33', 1),
(79, 1, 'Samsunga41', 1),
(80, 1, 'Samsunga15', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `dashboard_test_table`
--

CREATE TABLE `dashboard_test_table` (
  `id` int(11) NOT NULL,
  `info` varchar(50) DEFAULT NULL
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

--
-- Dump dei dati per la tabella `devices`
--

INSERT INTO `devices` (`id`, `slug`, `name`, `sort_order`) VALUES
(1, 'smartphone', 'Smartphone', 1),
(2, 'tablet', 'Tablet', 2),
(3, 'computer', 'Computer/Notebook', 3),
(4, 'console', 'Console', 4),
(5, 'tv', 'TV', 5),
(6, 'altro', 'Altro', 9);

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

--
-- Dump dei dati per la tabella `flyers`
--

INSERT INTO `flyers` (`id`, `title`, `slug`, `description`, `start_date`, `end_date`, `status`, `show_home`, `cover_image`, `pdf_file`, `internal_notes`, `created_at`, `updated_at`) VALUES
(1, 'Volantino Black Friday 2025', 'blackfriday25', 'Le migliori promozioni per il Black Friday 2025', '2025-11-18', '2025-12-01', 1, 1, 'uploads/flyers/691c5edfb6522-691b5fc4cf8ad-Black Friday 2025 (1).png', 'uploads/flyers/691c5edfc12b5-691b5fc4d080f-Black Friday 25 NEW.pdf', '', '2025-11-14 11:14:51', '2025-11-28 18:52:21');

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

--
-- Dump dei dati per la tabella `issues`
--

INSERT INTO `issues` (`id`, `device_id`, `label`, `severity`, `is_active`, `sort_order`) VALUES
(1, 1, 'Display rotto', 'high', 1, 10),
(2, 1, 'Batteria', 'mid', 1, 20),
(3, 1, 'Back Cover/Scocca', 'mid', 1, 30),
(4, 1, 'Non carica', 'mid', 1, 40),
(5, 1, 'Microfono', 'low', 1, 50),
(6, 1, 'Auricolare', 'low', 1, 60),
(7, 1, 'Fotocamera', 'mid', 1, 70),
(8, 1, 'Non si accende', 'high', 1, 80),
(9, 1, 'Andato in Acqua', 'mid', 1, 90),
(10, 1, 'Altro', 'mid', 1, 99),
(16, 2, 'Display rotto', 'high', 1, 10),
(17, 2, 'Batteria', 'mid', 1, 20),
(18, 2, 'Non carica', 'mid', 1, 30),
(19, 2, 'Lento/ottimizzazione', 'low', 1, 40),
(20, 2, 'Non si accende', 'high', 1, 50),
(21, 2, 'Wi-Fi/Bluetooth', 'low', 1, 60),
(22, 2, 'Altro', 'mid', 1, 99),
(23, 3, 'Non si accende', 'high', 1, 10),
(24, 3, 'Non si avvia (OS/boot)', 'mid', 1, 20),
(25, 3, 'Formattazione/Reinstallazione', 'low', 1, 30),
(26, 3, 'Lento/ottimizzazione', 'low', 1, 40),
(27, 3, 'Virus/Malware', 'mid', 1, 50),
(28, 3, 'Schermo rotto', 'high', 1, 60),
(29, 3, 'Tastiera/Trackpad', 'mid', 1, 70),
(30, 3, 'Recupero dati', 'high', 1, 80),
(31, 3, 'Surriscaldamento', 'mid', 1, 90),
(32, 3, 'Altro', 'mid', 1, 99),
(38, 4, 'Non legge dischi', 'mid', 1, 10),
(39, 4, 'Surriscaldamento/ventola', 'mid', 1, 20),
(40, 4, 'Errore aggiornamento', 'low', 1, 30),
(41, 4, 'Porta HDMI danneggiata', 'high', 1, 40),
(42, 4, 'Alimentazione', 'high', 1, 50),
(43, 4, 'Controller non si connette', 'low', 1, 60),
(44, 4, 'Memoria/archiviazione', 'mid', 1, 70),
(45, 4, 'Altro', 'mid', 1, 99),
(53, 5, 'Schermo nero', 'high', 1, 10),
(54, 5, 'Linee sul display', 'high', 1, 20),
(55, 5, 'Nessun segnale HDMI', 'mid', 1, 30),
(56, 5, 'Audio assente', 'mid', 1, 40),
(57, 5, 'Wi-Fi/rete', 'low', 1, 50),
(58, 5, 'Non si accende', 'high', 1, 60),
(59, 5, 'Aggiornamento firmware', 'low', 1, 70),
(60, 5, 'Altro', 'mid', 1, 99),
(71, 3, 'Non si avvia', 'mid', 1, 0);

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

--
-- Dump dei dati per la tabella `ks_store_holidays`
--

INSERT INTO `ks_store_holidays` (`id`, `rule_type`, `month`, `day`, `offset_days`, `name`, `is_closed`, `notice`, `active`) VALUES
(1, 'fixed', 1, 1, 0, 'Capodanno', 1, 'Chiuso per festività', 1),
(2, 'fixed', 1, 6, 0, 'Epifania', 1, 'Chiuso per festività', 1),
(3, 'fixed', 4, 25, 0, 'Liberazione', 1, 'Chiuso per festività', 1),
(4, 'fixed', 5, 1, 0, 'Festa del Lavoro', 1, 'Chiuso per festività', 1),
(5, 'fixed', 6, 2, 0, 'Festa della Repubblica', 1, 'Chiuso per festività', 1),
(6, 'fixed', 8, 15, 0, 'Ferragosto', 1, 'Chiuso per festività', 1),
(7, 'fixed', 11, 1, 0, 'Ognissanti', 1, 'Chiuso per festività', 1),
(8, 'fixed', 12, 8, 0, 'Immacolata', 1, 'Chiuso per festività', 1),
(9, 'fixed', 12, 25, 0, 'Natale', 1, 'Chiuso per festività', 1),
(11, 'easter', NULL, NULL, 0, 'Pasqua', 1, 'Chiuso per Pasqua', 1),
(12, 'easter', NULL, NULL, 1, 'Lunedì dell’Angelo', 1, 'Chiuso per Lunedì dell’Angelo', 1),
(13, 'fixed', 12, 26, 1, 'Santo Stefano', 1, NULL, 1),
(14, 'fixed', 12, 27, 1, 'Cazzo', 1, NULL, 1);

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

--
-- Dump dei dati per la tabella `ks_store_hours_exceptions`
--

INSERT INTO `ks_store_hours_exceptions` (`id`, `date`, `seg`, `open_time`, `close_time`, `is_closed`, `notice`) VALUES
(5, '2025-12-02', 1, '00:00:00', '00:00:00', 1, ''),
(14, '2025-12-21', 1, '10:00:00', '13:00:00', 0, ''),
(15, '2025-12-21', 2, '00:00:00', '00:00:00', 1, ''),
(24, '2025-12-18', 1, '09:00:00', '13:00:00', 0, ''),
(25, '2025-12-18', 2, '17:00:00', '20:30:00', 0, ''),
(26, '2025-12-26', 1, '10:00:00', '13:00:00', 0, 'Ci siamo rotti il cazzo!'),
(27, '2025-12-26', 2, NULL, NULL, 1, 'Ci siamo rotti il cazzo!');

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

--
-- Dump dei dati per la tabella `ks_store_hours_weekly`
--

INSERT INTO `ks_store_hours_weekly` (`id`, `dow`, `seg`, `open_time`, `close_time`, `active`) VALUES
(1, 1, 1, '09:00:00', '13:00:00', 1),
(2, 1, 2, '17:00:00', '20:30:00', 1),
(3, 2, 1, '09:00:00', '13:00:00', 1),
(4, 2, 2, '17:00:00', '20:30:00', 1),
(5, 3, 1, '09:00:00', '13:30:00', 1),
(6, 3, 2, '17:00:00', '20:30:00', 1),
(7, 4, 1, '09:00:00', '13:00:00', 1),
(8, 5, 1, '09:00:00', '13:00:00', 1),
(9, 5, 2, '17:00:00', '20:30:00', 1),
(10, 6, 1, '09:00:00', '13:00:00', 1),
(11, 6, 2, '17:00:00', '20:30:00', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `migrations`
--

CREATE TABLE `migrations` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `executed_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `migrations`
--

INSERT INTO `migrations` (`id`, `filename`, `executed_at`) VALUES
(2, '2025_11_28_002_dashboard_test.sql', '2025-11-28 19:25:40');

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

--
-- Dump dei dati per la tabella `models`
--

INSERT INTO `models` (`id`, `brand_id`, `name`, `year`, `is_active`) VALUES
(1, 1, 'iPhone 6', NULL, 1),
(2, 1, 'iPhone 6 Plus', NULL, 1),
(3, 1, 'iPhone 6s', NULL, 1),
(4, 1, 'iPhone 6s Plus', NULL, 1),
(5, 1, 'iPhone SE (2016)', NULL, 1),
(6, 1, 'iPhone 7', NULL, 1),
(7, 1, 'iPhone 7 Plus', NULL, 1),
(8, 1, 'iPhone 8', NULL, 1),
(9, 1, 'iPhone 8 Plus', NULL, 1),
(10, 1, 'iPhone X', NULL, 1),
(11, 1, 'iPhone XR', NULL, 1),
(12, 1, 'iPhone XS', NULL, 1),
(13, 1, 'iPhone XS Max', NULL, 1),
(14, 1, 'iPhone 11', NULL, 1),
(15, 1, 'iPhone 11 Pro', NULL, 1),
(16, 1, 'iPhone 11 Pro Max', NULL, 1),
(17, 1, 'iPhone SE (2020)', NULL, 1),
(18, 1, 'iPhone SE (2022)', NULL, 1),
(19, 1, 'iPhone 12 mini', NULL, 1),
(20, 1, 'iPhone 12', NULL, 1),
(21, 1, 'iPhone 12 Pro', NULL, 1),
(22, 1, 'iPhone 12 Pro Max', NULL, 1),
(23, 1, 'iPhone 13 mini', NULL, 1),
(24, 1, 'iPhone 13', NULL, 1),
(25, 1, 'iPhone 13 Pro', NULL, 1),
(26, 1, 'iPhone 13 Pro Max', NULL, 1),
(27, 1, 'iPhone 14', NULL, 1),
(28, 1, 'iPhone 14 Plus', NULL, 1),
(29, 1, 'iPhone 14 Pro', NULL, 1),
(30, 1, 'iPhone 14 Pro Max', NULL, 1),
(31, 1, 'iPhone 15', NULL, 1),
(32, 1, 'iPhone 15 Plus', NULL, 1),
(33, 1, 'iPhone 15 Pro', NULL, 1),
(34, 1, 'iPhone 15 Pro Max', NULL, 1),
(35, 1, 'iPhone 16', NULL, 1),
(36, 1, 'iPhone 16e', NULL, 1),
(37, 1, 'iPhone 16 Plus', NULL, 1),
(38, 1, 'iPhone 16 Pro', NULL, 1),
(39, 1, 'iPhone 16 Pro Max', NULL, 1),
(40, 1, 'iPhone 17', NULL, 1),
(41, 1, 'iPhone 17 Pro', NULL, 1),
(42, 1, 'iPhone 17 Pro Max', NULL, 1),
(43, 1, 'iPhone Air', NULL, 1),
(44, 1, 'iPhone SE (3ª gen)', NULL, 1),
(45, 1, 'iPhone SE (2ª gen)', NULL, 1),
(47, 2, 'Galaxy S24 Ultra', NULL, 1),
(48, 2, 'Galaxy S24+', NULL, 1),
(49, 2, 'Galaxy S24', NULL, 1),
(50, 2, 'Galaxy S23 Ultra', NULL, 1),
(51, 2, 'Galaxy S23+', NULL, 1),
(52, 2, 'Galaxy S23', NULL, 1),
(53, 2, 'Galaxy S23 FE', NULL, 1),
(54, 2, 'Galaxy S22 Ultra', NULL, 1),
(55, 2, 'Galaxy S22+', NULL, 1),
(56, 2, 'Galaxy S22', NULL, 1),
(57, 2, 'Galaxy S21 Ultra', NULL, 1),
(58, 2, 'Galaxy S21+', NULL, 1),
(59, 2, 'Galaxy S21', NULL, 1),
(60, 2, 'Galaxy S21 FE', NULL, 1),
(61, 2, 'Galaxy S20 Ultra', NULL, 1),
(62, 2, 'Galaxy S20+', NULL, 1),
(63, 2, 'Galaxy S20', NULL, 1),
(64, 2, 'Galaxy A55', NULL, 1),
(65, 2, 'Galaxy A54', NULL, 1),
(66, 2, 'Galaxy A53', NULL, 1),
(67, 2, 'Galaxy A35', NULL, 1),
(68, 2, 'Galaxy A34', NULL, 1),
(69, 2, 'Galaxy A25', NULL, 1),
(70, 2, 'Galaxy A15', NULL, 1),
(71, 2, 'Galaxy A14', NULL, 1),
(72, 2, 'Galaxy Z Fold5', NULL, 1),
(73, 2, 'Galaxy Z Flip5', NULL, 1),
(74, 2, 'Galaxy Z Fold4', NULL, 1),
(75, 2, 'Galaxy Z Flip4', NULL, 1),
(76, 2, 'Galaxy Z Fold3', NULL, 1),
(77, 2, 'Galaxy Z Flip3', NULL, 1),
(78, 2, 'Galaxy Note 20 Ultra', NULL, 1),
(79, 2, 'Galaxy Note 20', NULL, 1),
(80, 2, 'Galaxy Note 10+', NULL, 1),
(81, 2, 'Galaxy Note 10', NULL, 1),
(110, 64, 'PlayStation 5 Slim', NULL, 1),
(111, 64, 'PlayStation 5', NULL, 1),
(112, 64, 'PlayStation 5 Digital Edition', NULL, 1),
(113, 64, 'PS4 Pro', NULL, 1),
(114, 64, 'PS4 Slim', NULL, 1),
(115, 64, 'PlayStation 4', NULL, 1),
(117, 65, 'Xbox Series X', NULL, 1),
(118, 65, 'Xbox Series S', NULL, 1),
(119, 65, 'Xbox One X', NULL, 1),
(120, 65, 'Xbox One S', NULL, 1),
(121, 65, 'Xbox One', NULL, 1),
(124, 66, 'Switch OLED', NULL, 1),
(125, 66, 'Switch', NULL, 1),
(126, 66, 'Switch Lite', NULL, 1),
(127, 56, 'Surface Pro 10', NULL, 1),
(128, 56, 'Surface Pro 9', NULL, 1),
(129, 56, 'Surface Pro 8', NULL, 1),
(130, 56, 'Surface Pro 7+', NULL, 1),
(131, 56, 'Surface Pro 7', NULL, 1),
(132, 56, 'Surface Pro X', NULL, 1),
(133, 56, 'Surface Go 4', NULL, 1),
(134, 56, 'Surface Go 3', NULL, 1),
(135, 56, 'Surface Go 2', NULL, 1),
(136, 56, 'Surface Laptop Studio 2', NULL, 1),
(137, 56, 'Surface Laptop Studio', NULL, 1),
(138, 2, 'A12', NULL, 1),
(139, 73, '128', NULL, 1),
(140, 74, '64GB', NULL, 1),
(141, 38, 'Redmi9c 128', NULL, 1),
(142, 1, 'Iphone11pro 64GB', NULL, 1),
(143, 75, '32', NULL, 1),
(144, 1, 'Iphone13 128', NULL, 1),
(145, 38, 'Xiaomimi10tlite 128', NULL, 1),
(146, 76, '64', NULL, 1),
(147, 77, '128', NULL, 1),
(148, 78, '128GB', NULL, 1),
(149, 79, '64', NULL, 1),
(150, 38, 'Redmi10c 64', NULL, 1),
(151, 38, 'Redmi12c 64', NULL, 1),
(152, 80, '128', NULL, 1),
(153, 38, 'Redmimi11t5g 128', NULL, 1),
(154, 1, 'iPhone 12 Pro Max 256GB (Rigenerato)', NULL, 1),
(155, 1, 'Iphonese2020 128', NULL, 1),
(156, 1, 'Iphone14pro 256', NULL, 1),
(157, 2, 'A12 4/64', NULL, 1),
(158, 38, 'Redmi 9C 4/128GB', NULL, 1),
(159, 1, 'Iphone 11 Pro 64GB', NULL, 1),
(160, 2, 'A03s 2/32GB', NULL, 1),
(161, 1, 'iPhone 13 128GB (Rigenerato Garanzia 6 mesi)', NULL, 1),
(162, 38, 'Mi 10T Lite 6/128GB', NULL, 1),
(163, 2, 'Note 8 4/64GB RIGENERATO', NULL, 1),
(164, 2, 'Galaxy S10 8/128GB', NULL, 1),
(165, 2, 'Galaxy A33 6/128GB', NULL, 1),
(166, 2, 'Galaxy A41 4/64GB', NULL, 1),
(167, 38, 'Redmi 10c 4/64GB', NULL, 1),
(168, 38, 'Redmi 12c 3/64GB', NULL, 1),
(169, 2, 'A15 4/128GB', NULL, 1),
(170, 38, 'Redmi MI11T 12/128GB 5G', NULL, 1),
(171, 1, 'iPhone SE 2020 128G (Rigenerato)', NULL, 1),
(172, 1, 'iPhone 14 PRO 256GB (Rigenerato)', NULL, 1),
(173, 1, 'iPhone 12 Pro Max (Rigenerato)', NULL, 1),
(174, 1, 'iPhone 14 PRO (Rigenerato)', NULL, 1),
(175, 2, 'Galaxy A12', NULL, 1);

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

--
-- Dump dei dati per la tabella `price_rules`
--

INSERT INTO `price_rules` (`id`, `device_id`, `brand_id`, `model_id`, `issue_id`, `min_price`, `max_price`, `notes`, `is_active`, `created_at`) VALUES
(1, 1, 2, NULL, 9, 35.00, NULL, 'a partire da', 1, '2025-10-27 12:15:28'),
(2, 1, 2, NULL, 6, 38.00, 38.00, 'fisso', 1, '2025-10-27 12:15:28'),
(3, 1, 2, NULL, 3, 59.00, NULL, 'a partire da', 1, '2025-10-27 12:15:28'),
(4, 1, 2, NULL, 2, 59.00, NULL, 'a partire da', 1, '2025-10-27 12:15:28'),
(5, 1, 2, NULL, 1, 119.00, NULL, 'a partire da', 1, '2025-10-27 12:15:28'),
(6, 1, 2, NULL, 7, 38.00, NULL, 'a partire da', 1, '2025-10-27 12:15:28'),
(7, 1, 2, NULL, 5, 48.00, 59.00, 'range', 1, '2025-10-27 12:15:28'),
(8, 1, 2, NULL, 4, 48.00, 59.00, 'range', 1, '2025-10-27 12:15:28'),
(9, 1, 2, NULL, 8, 35.00, 89.00, 'range', 1, '2025-10-27 12:15:28'),
(16, 2, NULL, NULL, 17, 59.00, NULL, 'a partire da', 1, '2025-10-27 12:15:28'),
(17, 2, NULL, NULL, 16, 69.00, NULL, 'a partire da', 1, '2025-10-27 12:15:28'),
(18, 2, NULL, NULL, 19, 25.00, 35.00, 'range', 1, '2025-10-27 12:15:28'),
(19, 2, NULL, NULL, 18, 48.00, 59.00, 'range', 1, '2025-10-27 12:15:28'),
(20, 2, NULL, NULL, 20, 35.00, NULL, 'a partire da', 1, '2025-10-27 12:15:28'),
(21, 2, NULL, NULL, 21, 25.00, NULL, 'a partire da', 1, '2025-10-27 12:15:28'),
(23, 3, NULL, NULL, 25, 35.00, NULL, 'a partire da', 1, '2025-10-27 12:15:28'),
(24, 3, NULL, NULL, 26, 15.00, NULL, 'a partire da', 1, '2025-10-27 12:15:28'),
(25, 3, NULL, NULL, 23, 59.00, NULL, 'a partire da', 1, '2025-10-27 12:15:28'),
(26, 3, NULL, NULL, 24, 15.00, NULL, 'a partire da', 1, '2025-10-27 12:15:28'),
(27, 3, NULL, NULL, 30, 15.00, 65.00, 'range', 1, '2025-10-27 12:15:28'),
(28, 3, NULL, NULL, 28, 119.00, NULL, 'a partire da', 1, '2025-10-27 12:15:28'),
(29, 3, NULL, NULL, 31, 35.00, 69.00, 'range', 1, '2025-10-27 12:15:28'),
(30, 3, NULL, NULL, 29, 59.00, 79.00, 'range', 1, '2025-10-27 12:15:28'),
(31, 3, NULL, NULL, 27, 15.00, NULL, 'a partire da', 1, '2025-10-27 12:15:28'),
(38, 4, NULL, NULL, 42, 89.00, NULL, 'da', 1, '2025-10-27 12:15:28'),
(39, 4, NULL, NULL, 43, 25.00, NULL, 'da', 1, '2025-10-27 12:15:28'),
(40, 4, NULL, NULL, 40, 25.00, NULL, 'da', 1, '2025-10-27 12:15:28'),
(41, 4, NULL, NULL, 44, 89.00, NULL, 'da', 1, '2025-10-27 12:15:28'),
(42, 4, NULL, NULL, 38, 69.00, NULL, 'da', 1, '2025-10-27 12:15:28'),
(43, 4, NULL, NULL, 41, 89.00, NULL, 'da', 1, '2025-10-27 12:15:28'),
(44, 4, NULL, NULL, 39, 69.00, NULL, 'da', 1, '2025-10-27 12:15:28'),
(45, 5, NULL, NULL, 53, 129.00, NULL, 'da', 1, '2025-10-27 12:15:28'),
(46, 5, NULL, NULL, 55, 129.00, NULL, 'da', 1, '2025-10-27 12:15:28'),
(47, 5, NULL, NULL, 56, 129.00, NULL, 'da', 1, '2025-10-27 12:15:28'),
(48, 5, NULL, NULL, 57, 59.00, NULL, 'da', 1, '2025-10-27 12:15:28'),
(49, 5, NULL, NULL, 58, 89.00, NULL, 'da', 1, '2025-10-27 12:15:28'),
(50, 5, NULL, NULL, 59, 35.00, NULL, 'da', 1, '2025-10-27 12:15:28'),
(52, 1, 1, 1, 1, 69.00, 69.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(53, 1, 1, 1, 2, 49.00, 49.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(54, 1, 1, 2, 1, 79.00, 79.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(55, 1, 1, 2, 2, 49.00, 49.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(56, 1, 1, 3, 1, 69.00, 69.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(57, 1, 1, 3, 2, 49.00, 49.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(58, 1, 1, 4, 1, 79.00, 79.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(59, 1, 1, 4, 2, 49.00, 49.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(60, 1, 1, 5, 1, 69.00, 69.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(61, 1, 1, 5, 2, 49.00, 49.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(62, 1, 1, 6, 1, 79.00, 79.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(63, 1, 1, 6, 2, 49.00, 49.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(64, 1, 1, 7, 1, 89.00, 89.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(65, 1, 1, 7, 2, 59.00, 59.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(66, 1, 1, 8, 1, 89.00, 89.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(67, 1, 1, 8, 2, 59.00, 59.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(68, 1, 1, 9, 1, 99.00, 99.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(69, 1, 1, 9, 2, 59.00, 59.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(70, 1, 1, 10, 1, 99.00, 139.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(71, 1, 1, 10, 2, 69.00, 99.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(72, 1, 1, 11, 1, 89.00, 119.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(73, 1, 1, 11, 2, 69.00, 99.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(74, 1, 1, 12, 1, 129.00, 189.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(75, 1, 1, 12, 2, 69.00, 99.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(76, 1, 1, 13, 1, 149.00, 219.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(77, 1, 1, 13, 2, 69.00, 99.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(78, 1, 1, 14, 1, 109.00, 149.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(79, 1, 1, 14, 2, 69.00, 99.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(80, 1, 1, 14, 3, 139.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:28'),
(81, 1, 1, 15, 1, 129.00, 179.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(82, 1, 1, 15, 2, 69.00, 99.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(83, 1, 1, 15, 3, 149.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:28'),
(84, 1, 1, 16, 1, 159.00, 219.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(85, 1, 1, 16, 2, 69.00, 99.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(86, 1, 1, 16, 3, 159.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:28'),
(87, 1, 1, 17, 1, 89.00, 89.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(88, 1, 1, 17, 2, 69.00, 69.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(89, 1, 1, 18, 1, 89.00, 89.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(90, 1, 1, 18, 2, 69.00, 69.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(91, 1, 1, 19, 1, 129.00, 189.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(92, 1, 1, 19, 2, 69.00, 119.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(93, 1, 1, 19, 3, 149.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:28'),
(94, 1, 1, 20, 1, 129.00, 199.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(95, 1, 1, 20, 2, 69.00, 119.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(96, 1, 1, 20, 3, 149.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:28'),
(97, 1, 1, 21, 1, 129.00, 199.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(98, 1, 1, 21, 2, 69.00, 119.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(99, 1, 1, 21, 3, 149.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:28'),
(100, 1, 1, 22, 1, 169.00, 249.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(101, 1, 1, 22, 2, 69.00, 119.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(102, 1, 1, 22, 3, 169.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:28'),
(103, 1, 1, 23, 1, 169.00, 249.00, 'Apple: display', 1, '2025-10-27 12:15:28'),
(104, 1, 1, 23, 2, 69.00, 119.00, 'Apple: batteria', 1, '2025-10-27 12:15:28'),
(105, 1, 1, 23, 3, 149.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:28'),
(106, 1, 1, 24, 1, 189.00, 269.00, 'Apple: display', 1, '2025-10-27 12:15:29'),
(107, 1, 1, 24, 2, 69.00, 119.00, 'Apple: batteria', 1, '2025-10-27 12:15:29'),
(108, 1, 1, 24, 3, 149.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:29'),
(109, 1, 1, 25, 1, 219.00, 319.00, 'Apple: display', 1, '2025-10-27 12:15:29'),
(110, 1, 1, 25, 2, 69.00, 119.00, 'Apple: batteria', 1, '2025-10-27 12:15:29'),
(111, 1, 1, 25, 3, 169.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:29'),
(112, 1, 1, 26, 1, 249.00, 349.00, 'Apple: display', 1, '2025-10-27 12:15:29'),
(113, 1, 1, 26, 2, 69.00, 119.00, 'Apple: batteria', 1, '2025-10-27 12:15:29'),
(114, 1, 1, 26, 3, 189.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:29'),
(115, 1, 1, 27, 1, 189.00, 269.00, 'Apple: display', 1, '2025-10-27 12:15:29'),
(116, 1, 1, 27, 2, 69.00, 119.00, 'Apple: batteria', 1, '2025-10-27 12:15:29'),
(117, 1, 1, 27, 3, 119.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:29'),
(118, 1, 1, 28, 1, 199.00, 289.00, 'Apple: display', 1, '2025-10-27 12:15:29'),
(119, 1, 1, 28, 2, 69.00, 119.00, 'Apple: batteria', 1, '2025-10-27 12:15:29'),
(120, 1, 1, 28, 3, 129.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:29'),
(121, 1, 1, 29, 1, 219.00, 369.00, 'Apple: display', 1, '2025-10-27 12:15:29'),
(122, 1, 1, 29, 2, 69.00, 119.00, 'Apple: batteria', 1, '2025-10-27 12:15:29'),
(123, 1, 1, 29, 3, 149.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:29'),
(124, 1, 1, 30, 1, 249.00, 469.00, 'Apple: display', 1, '2025-10-27 12:15:29'),
(125, 1, 1, 30, 2, 69.00, 119.00, 'Apple: batteria', 1, '2025-10-27 12:15:29'),
(126, 1, 1, 30, 3, 169.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:29'),
(127, 1, 1, 31, 1, 249.00, 339.00, 'Apple: display', 1, '2025-10-27 12:15:29'),
(128, 1, 1, 31, 2, 79.00, 119.00, 'Apple: batteria', 1, '2025-10-27 12:15:29'),
(129, 1, 1, 31, 3, 169.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:29'),
(130, 1, 1, 32, 1, 269.00, 405.00, 'Apple: display', 1, '2025-10-27 12:15:29'),
(131, 1, 1, 32, 2, 79.00, 119.00, 'Apple: batteria', 1, '2025-10-27 12:15:29'),
(132, 1, 1, 32, 3, 169.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:29'),
(133, 1, 1, 33, 1, 289.00, 405.00, 'Apple: display', 1, '2025-10-27 12:15:29'),
(134, 1, 1, 33, 2, 79.00, 119.00, 'Apple: batteria', 1, '2025-10-27 12:15:29'),
(135, 1, 1, 33, 3, 169.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:29'),
(136, 1, 1, 34, 1, 319.00, 489.00, 'Apple: display', 1, '2025-10-27 12:15:29'),
(137, 1, 1, 34, 2, 79.00, 119.00, 'Apple: batteria', 1, '2025-10-27 12:15:29'),
(138, 1, 1, 34, 3, 169.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:29'),
(139, 1, 1, 35, 1, 249.00, 339.00, 'Apple: display', 1, '2025-10-27 12:15:29'),
(140, 1, 1, 35, 2, 79.00, 119.00, 'Apple: batteria', 1, '2025-10-27 12:15:29'),
(141, 1, 1, 35, 3, 169.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:29'),
(142, 1, 1, 36, 1, 269.00, 269.00, 'Apple: display', 1, '2025-10-27 12:15:29'),
(143, 1, 1, 36, 2, 79.00, 109.00, 'Apple: batteria', 1, '2025-10-27 12:15:29'),
(144, 1, 1, 36, 3, 169.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:29'),
(145, 1, 1, 37, 1, 269.00, 405.00, 'Apple: display', 1, '2025-10-27 12:15:29'),
(146, 1, 1, 37, 2, 79.00, 119.00, 'Apple: batteria', 1, '2025-10-27 12:15:29'),
(147, 1, 1, 37, 3, 169.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:29'),
(148, 1, 1, 38, 1, 289.00, 405.00, 'Apple: display', 1, '2025-10-27 12:15:29'),
(149, 1, 1, 38, 2, 79.00, 135.00, 'Apple: batteria', 1, '2025-10-27 12:15:29'),
(150, 1, 1, 38, 3, 169.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:29'),
(151, 1, 1, 39, 1, 319.00, 489.00, 'Apple: display', 1, '2025-10-27 12:15:29'),
(152, 1, 1, 39, 2, 79.00, 135.00, 'Apple: batteria', 1, '2025-10-27 12:15:29'),
(153, 1, 1, 39, 3, 169.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:29'),
(154, 1, 1, 40, 1, 405.00, 405.00, 'Apple: display (solo orig)', 1, '2025-10-27 12:15:29'),
(155, 1, 1, 40, 2, 119.00, 119.00, 'Apple: batteria (solo orig)', 1, '2025-10-27 12:15:29'),
(156, 1, 1, 40, 3, 169.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:29'),
(157, 1, 1, 41, 1, 405.00, 405.00, 'Apple: display (solo orig)', 1, '2025-10-27 12:15:29'),
(158, 1, 1, 41, 2, 135.00, 135.00, 'Apple: batteria (solo orig)', 1, '2025-10-27 12:15:29'),
(159, 1, 1, 41, 3, 169.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:29'),
(160, 1, 1, 42, 1, 489.00, 489.00, 'Apple: display (solo orig)', 1, '2025-10-27 12:15:29'),
(161, 1, 1, 42, 2, 135.00, 135.00, 'Apple: batteria (solo orig)', 1, '2025-10-27 12:15:29'),
(162, 1, 1, 42, 3, 169.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:29'),
(163, 1, 1, 43, 1, 405.00, 405.00, 'Apple: display (solo orig)', 1, '2025-10-27 12:15:30'),
(164, 1, 1, 43, 2, 135.00, 135.00, 'Apple: batteria (solo orig)', 1, '2025-10-27 12:15:30'),
(165, 1, 1, 43, 3, 169.00, NULL, 'Apple: scocca', 1, '2025-10-27 12:15:30'),
(167, 1, NULL, NULL, 4, 48.00, 59.00, 'range', 1, '2025-10-28 18:35:50'),
(168, 1, NULL, NULL, 1, 69.00, NULL, 'a partire da', 1, '2025-10-28 18:35:50'),
(170, 1, NULL, NULL, 5, 48.00, 59.00, 'range', 1, '2025-10-28 18:35:50'),
(171, 1, NULL, NULL, 6, 38.00, 38.00, 'fisso', 1, '2025-10-28 18:35:50'),
(172, 1, NULL, NULL, 8, 35.00, 89.00, 'range', 1, '2025-10-28 18:35:50'),
(173, 1, NULL, NULL, 2, 59.00, NULL, 'a partire da', 1, '2025-10-28 18:35:50'),
(174, 1, NULL, NULL, 7, 38.00, NULL, 'a partire da', 1, '2025-10-28 18:35:50'),
(175, 1, NULL, NULL, 9, 35.00, NULL, 'a partire da', 1, '2025-10-28 18:35:50'),
(176, 3, NULL, NULL, 71, 15.00, NULL, 'a partire da', 1, '2025-10-28 18:35:50'),
(177, 1, 2, 64, 1, 119.00, NULL, NULL, 1, '2025-11-29 10:41:32');

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

--
-- Dump dei dati per la tabella `products`
--

INSERT INTO `products` (`id`, `model_id`, `sku`, `color`, `storage_gb`, `grade`, `list_price`, `price_eur`, `short_desc`, `full_desc`, `is_available`, `is_featured`, `created_at`, `updated_at`) VALUES
(1, 24, 'RIC0189', 'Blu', 256, 'A', NULL, 349.00, NULL, NULL, 1, 1, '2025-11-06 11:09:34', '2025-11-06 11:09:34'),
(2, 69, 'RIC0056', 'Nero', 256, 'A+', NULL, 159.00, '', '', 1, 1, '2025-11-06 12:28:15', '2025-11-06 12:28:15'),
(3, 50, 'RIC0069', 'Bianco', 512, 'A+', NULL, 599.00, '', '', 1, 1, '2025-11-06 12:28:57', '2025-11-06 12:28:57'),
(4, 127, 'RIC0025', 'Silver', 1024, 'A+', NULL, 799.00, '', '', 1, 1, '2025-11-06 12:30:15', '2025-11-06 12:30:15'),
(5, 14, 'RIC0118', 'Rosso', 128, 'Nuovo', 249.00, 199.00, 'Apple - iPhone 11 128GB Rosso - Grado Nuovo', 'Scopri Apple - iPhone 11 ricondizionato garantito da Key Soft Italia.\r\n        \r\nSpecifiche:\r\n- Modello: Apple - iPhone 11\r\n- Colore: Rosso\r\n- Memoria: 128 GB\r\n- Condizioni: Grado Nuovo (Testato e Funzionante al 100%)\r\n\r\nIl dispositivo è stato sottoposto a rigidi test di qualità dai nostri tecnici certificati. Include garanzia 12 mesi.', 1, 1, '2025-11-06 12:30:38', '2025-11-28 10:37:09'),
(43, 173, '21703', 'Nero', 256, 'Nuovo', 718.80, 599.00, 'iPhone 12 Pro Max 256GB (Rigenerato)', '', 1, 0, '2025-11-29 09:27:47', '2025-11-29 09:27:47'),
(44, 171, 'RIC0173', 'Nero', 0, 'Nuovo', 298.80, 249.00, 'iPhone SE 2020 128G (Rigenerato)', '', 1, 0, '2025-11-29 09:27:47', '2025-11-29 09:27:47'),
(45, 29, 'RIC0191', 'Nero', 256, 'Nuovo', 958.80, 799.00, 'Apple - iPhone 14 Pro 256GB Nero - Grado Nuovo', 'Scopri Apple - iPhone 14 Pro ricondizionato garantito da Key Soft Italia.\r\n        \r\nSpecifiche:\r\n- Modello: Apple - iPhone 14 Pro\r\n- Colore: Nero\r\n- Memoria: 256 GB\r\n- Condizioni: Grado Nuovo (Testato e Funzionante al 100%)\r\n\r\nIl dispositivo è stato sottoposto a rigidi test di qualità dai nostri tecnici certificati. Include garanzia 12 mesi.', 1, 1, '2025-11-29 09:27:48', '2025-11-29 09:34:08'),
(46, 175, '14467', 'Nero', 64, 'A+', 138.00, 115.00, 'Smartphone Samsung A12 4/64', '', 1, 0, '2025-11-29 09:43:32', '2025-11-29 09:43:32'),
(47, 15, '16814', 'Nero', 64, 'A+', 598.80, 499.00, 'Iphone 11 Pro 64GB', '', 1, 0, '2025-11-29 09:43:32', '2025-11-29 09:43:32'),
(48, 24, '18586', 'Nero', 128, 'A', 718.80, 599.00, 'iPhone 13 128GB (Rigenerato Garanzia 6 mesi)', '', 1, 0, '2025-11-29 09:43:32', '2025-11-29 09:43:32');

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

--
-- Dump dei dati per la tabella `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `path`, `alt_text`, `sort_order`, `is_cover`) VALUES
(1, 1, 'assets/img/recond/iphone13.avif', NULL, 0, 1),
(2, 5, 'assets/img/recond/6918bcd930cbf-apple-iphone-11-128gb.jpg', NULL, 0, 1);

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

--
-- Dump dei dati per la tabella `quotes`
--

INSERT INTO `quotes` (`id`, `created_at`, `device_id`, `brand_text`, `model_text`, `problems_json`, `description`, `booking_date`, `booking_time`, `est_min`, `est_max`, `first_name`, `last_name`, `email`, `phone`, `company`, `ip_address`, `user_agent`, `status`, `notes`) VALUES
(2, '2025-10-30 11:11:54', 1, 'Samsung', 'Galaxy A13', '[\"Non mi sentono (microfono)\"]', NULL, NULL, NULL, 0.00, 0.00, 'Patrizio', 'Cuscito', 'info@keysoftitalia.it', '0998293794', 'Key Soft Italia SNC', 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'pending', NULL),
(3, '2025-10-30 11:12:56', 1, 'Apple', 'iPhone 13 Pro', '[\"Display rotto\"]', NULL, NULL, NULL, 219.00, 319.00, 'Patrizio', 'Cuscito', 'info@keysoftitalia.it', '0998293794', 'Key Soft Italia SNC', 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'pending', NULL),
(4, '2025-10-30 11:31:42', 1, 'Apple', 'iPhone 14', '[\"Display rotto\"]', NULL, NULL, NULL, 189.00, 269.00, 'Patrizio', 'Cuscito', 'info@keysoftitalia.it', '0998293794', 'Key Soft Italia SNC', 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'pending', NULL),
(5, '2025-10-30 12:01:54', 1, 'Apple', 'iPhone 13', '[\"Display rotto\"]', NULL, NULL, NULL, 189.00, 269.00, 'Patrizio', 'Cuscito', 'info@keysoftitalia.it', '0998293794', 'Key Soft Italia SNC', 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'pending', NULL),
(6, '2025-10-30 12:01:56', 1, 'Apple', 'iPhone 13', '[\"Display rotto\"]', NULL, NULL, NULL, 189.00, 269.00, 'Patrizio', 'Cuscito', 'info@keysoftitalia.it', '0998293794', 'Key Soft Italia SNC', 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'pending', NULL),
(7, '2025-10-30 12:02:08', 1, 'Apple', 'iPhone 13', '[\"Display rotto\"]', NULL, NULL, NULL, 189.00, 269.00, 'Patrizio', 'Cuscito', 'info@keysoftitalia.it', '0998293794', 'Key Soft Italia SNC', 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'pending', NULL),
(8, '2025-10-30 12:02:44', 1, 'Apple', 'iPhone 13', '[\"Display rotto\"]', NULL, NULL, NULL, 189.00, 269.00, 'Patrizio', 'Cuscito', 'info@keysoftitalia.it', '0998293794', 'Key Soft Italia SNC', 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'pending', NULL),
(9, '2025-10-30 12:02:47', 1, 'Apple', 'iPhone 13', '[\"Display rotto\"]', NULL, NULL, NULL, 189.00, 269.00, 'Patrizio', 'Cuscito', 'info@keysoftitalia.it', '0998293794', 'Key Soft Italia SNC', 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'pending', NULL),
(10, '2025-10-30 12:03:45', 1, 'Apple', 'iPhone 14 Pro', '[\"Display rotto\"]', NULL, NULL, NULL, 219.00, 369.00, 'Patrizio', 'Cuscito', 'info@keysoftitalia.it', '0998293794', 'Key Soft Italia SNC', 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'pending', NULL),
(11, '2025-10-30 12:03:47', 1, 'Apple', 'iPhone 14 Pro', '[\"Display rotto\"]', NULL, NULL, NULL, 219.00, 369.00, 'Patrizio', 'Cuscito', 'info@keysoftitalia.it', '0998293794', 'Key Soft Italia SNC', 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'pending', NULL),
(12, '2025-10-30 12:04:54', 1, 'Apple', 'iPhone 14 Pro', '[\"Display rotto\"]', NULL, NULL, NULL, 219.00, 369.00, 'Patrizio', 'Cuscito', 'info@keysoftitalia.it', '0998293794', 'Key Soft Italia SNC', 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'pending', NULL),
(13, '2025-10-31 09:42:01', 1, 'Apple', 'iPhone 14 Pro', '[\"Display rotto\"]', NULL, NULL, NULL, 219.00, 369.00, 'Patrizio', 'Cuscito', 'info@keysoftitalia.it', '0998293794', 'Key Soft Italia SNC', 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'pending', NULL),
(14, '2025-10-31 09:42:55', 1, 'Apple', 'iPhone 14 Pro', '[\"Display rotto\"]', NULL, NULL, NULL, 219.00, 369.00, 'Patrizio', 'Cuscito', 'info@keysoftitalia.it', '0998293794', 'Key Soft Italia SNC', 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'pending', NULL),
(15, '2025-10-31 09:49:30', 1, 'Apple', 'iPhone 14', '[\"Display rotto\"]', NULL, NULL, NULL, 189.00, 269.00, 'Patrizio', 'Cuscito', 'info@keysoftitalia.it', '0998293794', 'Key Soft Italia SNC', 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'pending', NULL),
(16, '2025-10-31 09:50:31', 2, 'Apple', 'iPad 4', '[\"Display rotto\"]', NULL, NULL, NULL, 69.00, NULL, 'Patrizio', 'Cuscito', 'info@keysoftitalia.it', '0998293794', 'Key Soft Italia SNC', 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'pending', NULL),
(17, '2025-10-31 09:52:56', 1, 'Apple', 'iPhone 13', '[\"Display rotto\"]', NULL, NULL, NULL, 189.00, 269.00, 'Patrizio', 'Cuscito', 'info@keysoftitalia.it', '0998293794', 'Key Soft Italia SNC', 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'pending', NULL),
(18, '2025-10-31 09:53:57', 1, 'Apple', 'iPhone 13', '[\"Batteria\"]', NULL, NULL, NULL, 69.00, 119.00, 'Patrizio', 'Cuscito', 'info@keysoftitalia.it', '0998293794', 'Key Soft Italia SNC', 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'pending', NULL),
(19, '2025-10-31 18:44:00', 1, 'Apple', 'iPhone 13', '[\"Display rotto\"]', NULL, NULL, NULL, 189.00, 269.00, 'Patrizio', 'Cuscito', 'info@keysoftitalia.it', '0998293794', 'Key Soft Italia SNC', 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'pending', NULL),
(20, '2025-11-29 10:19:03', 1, 'Samsung', 'Galaxy A55', '[\"Display rotto\"]', NULL, NULL, NULL, 119.00, 0.00, 'Riso', 'Silvia', 'riso.silvia@gmail.com', '3339938918', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'rejected', '');

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

--
-- Dump dei dati per la tabella `repair_bookings`
--

INSERT INTO `repair_bookings` (`id`, `created_at`, `updated_at`, `channel`, `source`, `external_ref`, `device_type`, `device_id`, `brand_id`, `brand_name`, `model_id`, `model_name`, `problem_summary`, `notes`, `preferred_date`, `preferred_time_slot`, `dropoff_type`, `backup_done`, `tests_ok`, `customer_first_name`, `customer_last_name`, `customer_email`, `customer_phone`, `customer_company`, `contact_channel`, `privacy_accepted`, `status`) VALUES
(1, '2025-11-28 18:32:28', '2025-11-28 18:51:44', 'web', 'form_prenotazione', NULL, 'smartphone', NULL, NULL, 'Apple', NULL, 'iPhone 14 Pro', 'Non mi si vede più il display', '', '2025-11-29', 'pomeriggio', 'in_store', 0, 0, 'Patrizio', 'Cuscito', 'info@keysoftitalia.it', '3339938918', 'Key Soft Italia SNC', NULL, 1, 'completed');

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

--
-- Dump dei dati per la tabella `used_device_quotes`
--

INSERT INTO `used_device_quotes` (`id`, `created_at`, `device_type`, `device_id`, `brand_id`, `brand_name`, `model_id`, `model_name`, `device_condition`, `defects`, `accessories`, `expected_price`, `notes`, `customer_first_name`, `customer_last_name`, `customer_email`, `customer_phone`, `contact_channel`, `privacy_accepted`, `status`, `ip_address`, `user_agent`) VALUES
(1, '2025-11-26 17:47:58', 'smartphone', 1, 1, '1', 24, 'iPhone 13', 'buono', '[\"Batteria esausta\"]', '[\"Scatola\",\"Caricatore\",\"Cavo\",\"Custodia\"]', NULL, NULL, 'Patrizio', 'Cuscito', 'info@keysoftitalia.it', '0998293794', 'whatsapp', 1, 'pending', 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36'),
(2, '2025-11-26 18:53:37', 'smartphone', 1, 1, '1', 24, 'iPhone 13', 'buono', '[\"Microfono \\/ audio difettoso\"]', '[\"Scatola\"]', 150.00, '', 'Patrizio', 'Cuscito', 'info@keysoftitalia.it', '0998293794', 'form', 1, '', 0x00000000000000000000000000000001, 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1'),
(3, '2025-11-28 09:36:19', 'smartphone', 1, 1, 'Apple', 38, 'iPhone 16 Pro', 'ottimo', NULL, '[\"Scatola\",\"Cavo\"]', 700.00, NULL, 'Patrizio', 'Cuscito', 'info@keysoftitalia.it', '3339938918', 'form', 1, 'pending', 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36');

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
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'admin', '$2y$12$FtBQOuEvSdyGZVa35kFrue4n6Ek6JXZTG/sXlku7Gh12hgRBfvQea', 'admin@example.com', '2025-11-03 11:03:02');

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
-- Indici per le tabelle `dashboard_test_table`
--
ALTER TABLE `dashboard_test_table`
  ADD PRIMARY KEY (`id`);

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
-- Indici per le tabelle `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT per la tabella `dashboard_test_table`
--
ALTER TABLE `dashboard_test_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `flyers`
--
ALTER TABLE `flyers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `issues`
--
ALTER TABLE `issues`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT per la tabella `ks_store_holidays`
--
ALTER TABLE `ks_store_holidays`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT per la tabella `ks_store_hours_exceptions`
--
ALTER TABLE `ks_store_hours_exceptions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT per la tabella `ks_store_hours_weekly`
--
ALTER TABLE `ks_store_hours_weekly`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la tabella `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `models`
--
ALTER TABLE `models`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

--
-- AUTO_INCREMENT per la tabella `price_rules`
--
ALTER TABLE `price_rules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- AUTO_INCREMENT per la tabella `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT per la tabella `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `quotes`
--
ALTER TABLE `quotes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT per la tabella `repair_bookings`
--
ALTER TABLE `repair_bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `used_device_quotes`
--
ALTER TABLE `used_device_quotes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
