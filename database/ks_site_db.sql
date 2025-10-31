-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Ott 31, 2025 alle 12:11
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
(56, 2, 'Microsoft Surface', 1),
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
(72, 5, 'TCL', 1);

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
(137, 56, 'Surface Laptop Studio', NULL, 1);

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
(5, 1, 2, NULL, 1, 69.00, NULL, 'a partire da', 1, '2025-10-27 12:15:28'),
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
(176, 3, NULL, NULL, 71, 15.00, NULL, 'a partire da', 1, '2025-10-28 18:35:50');

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
  `est_min` decimal(10,2) DEFAULT NULL,
  `est_max` decimal(10,2) DEFAULT NULL,
  `first_name` varchar(80) NOT NULL,
  `last_name` varchar(80) NOT NULL,
  `email` varchar(140) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `company` varchar(120) DEFAULT NULL,
  `ip_address` varbinary(16) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL
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
-- Indici per le tabelle `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_device_issue` (`device_id`,`label`);

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
-- Indici per le tabelle `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT per la tabella `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `issues`
--
ALTER TABLE `issues`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT per la tabella `models`
--
ALTER TABLE `models`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT per la tabella `price_rules`
--
ALTER TABLE `price_rules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT per la tabella `quotes`
--
ALTER TABLE `quotes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
