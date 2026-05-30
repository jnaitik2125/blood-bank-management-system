-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 27, 2026 at 11:52 AM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blood`
--

-- --------------------------------------------------------

--
-- Table structure for table `blood_units`
--

DROP TABLE IF EXISTS `blood_units`;
CREATE TABLE IF NOT EXISTS `blood_units` (
  `id` int NOT NULL AUTO_INCREMENT,
  `blood_group` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(8,2) NOT NULL,
  `collection_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `status` enum('available','used','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blood_units`
--

INSERT INTO `blood_units` (`id`, `blood_group`, `quantity`, `collection_date`, `expiry_date`, `status`) VALUES
(1, 'B+', 0.00, '2026-03-01', '2026-04-05', 'used'),
(2, 'O+', 1.50, '2026-03-02', '2026-04-06', 'available'),
(3, 'B+', 0.00, '2026-03-27', '2026-05-01', 'used'),
(4, 'AB+', 2.00, '2026-03-27', '2026-05-01', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

DROP TABLE IF EXISTS `donations`;
CREATE TABLE IF NOT EXISTS `donations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `donor_id` int NOT NULL,
  `blood_group` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(8,2) NOT NULL,
  `collection_staff_id` int NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_donation_donor` (`donor_id`),
  KEY `fk_donation_staff` (`collection_staff_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `donor_id`, `blood_group`, `quantity`, `collection_staff_id`, `date`) VALUES
(1, 1, 'B+', 1.00, 3, '2026-03-01'),
(2, 2, 'O+', 1.00, 3, '2026-03-02'),
(3, 4, 'B+', 1.00, 3, '2026-03-27'),
(4, 5, 'AB+', 2.00, 3, '2026-03-27');

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

DROP TABLE IF EXISTS `donors`;
CREATE TABLE IF NOT EXISTS `donors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` int NOT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `blood_group` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `last_donation_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `donors`
--

INSERT INTO `donors` (`id`, `name`, `age`, `gender`, `blood_group`, `contact`, `address`, `last_donation_date`) VALUES
(1, 'Rahul Sharma', 28, 'male', 'B+', '9876543210', 'Delhi', '2026-01-15'),
(2, 'Sneha Verma', 31, 'female', 'O+', '9876543211', 'Noida', '2026-02-10'),
(5, 'Naitik1', 18, 'male', 'AB+', '+916388903338', '', '2026-03-27'),
(4, 'Naitik', 18, 'male', 'A+', '+916388903337', '', '2026-03-27');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_logs`
--

DROP TABLE IF EXISTS `inventory_logs`;
CREATE TABLE IF NOT EXISTS `inventory_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `action` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blood_group` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(8,2) NOT NULL,
  `performed_by` int NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_log_user` (`performed_by`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_logs`
--

INSERT INTO `inventory_logs` (`id`, `action`, `blood_group`, `quantity`, `performed_by`, `timestamp`) VALUES
(1, 'seed_stock', 'B+', 2.00, 1, '2026-03-27 16:40:10'),
(2, 'seed_stock', 'O+', 1.50, 1, '2026-03-27 16:40:10'),
(3, 'donation_added', 'B+', 1.00, 1, '2026-03-27 11:13:56'),
(4, 'donation_added', 'AB+', 2.00, 1, '2026-03-27 11:15:57'),
(5, 'request_approved_stock_deducted', 'B+', 3.00, 1, '2026-03-27 11:16:32');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

DROP TABLE IF EXISTS `requests`;
CREATE TABLE IF NOT EXISTS `requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `patient_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blood_group` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(8,2) NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `requested_by` int NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_request_user` (`requested_by`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `patient_name`, `blood_group`, `quantity`, `status`, `requested_by`, `date`) VALUES
(1, 'Patient A', 'B+', 1.00, 'pending', 2, '2026-03-10'),
(2, 'Patient B', 'O+', 0.50, 'approved', 2, '2026-03-11'),
(3, 'Mahek', 'B+', 1.00, 'pending', 1, '2026-03-27'),
(4, 'Aascharya', 'B+', 3.00, 'approved', 1, '2026-03-27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','receptionist','collection_staff') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`, `created_at`) VALUES
(1, 'Admin User', 'admin@gmail.com', '$2y$10$ko7fjFBlEH95GpbEDfLCi.kx7Wj1JKGFm8CYfq0BiFkSbdqG1gzhO', 'admin', 1, '2026-03-27 16:40:10'),
(2, 'Receptionist', 'reception@gmail.com', '$2y$10$IehcCTf/bdUWTJmXPFaT6urlHIo82747nFXhLGSaMJikwSFx0huKu', 'receptionist', 1, '2026-03-27 16:40:10'),
(3, 'Lab Assistant', 'lab@gmail.com', '$2y$10$pgEh/NAE0QtdcMk1N7KC/Oxts24pNxdeJK4dKZESNtEWC9.HYUyO6', 'collection_staff', 1, '2026-03-27 16:40:10');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
