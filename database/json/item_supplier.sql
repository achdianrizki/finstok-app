-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 18, 2025 at 08:13 AM
-- Server version: 10.11.10-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u585055717_sevenacos`
--

-- --------------------------------------------------------

--
-- Table structure for table `item_supplier`
--

-- CREATE TABLE `item_supplier` (
--   `id` bigint(20) UNSIGNED NOT NULL,
--   `item_id` bigint(20) UNSIGNED NOT NULL,
--   `supplier_id` bigint(20) UNSIGNED NOT NULL,
--   `created_at` timestamp NULL DEFAULT NULL,
--   `updated_at` timestamp NULL DEFAULT NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_supplier`
--

INSERT INTO `item_supplier` (`id`, `item_id`, `supplier_id`, `created_at`, `updated_at`) VALUES
(1, 14, 1, NULL, NULL),
(2, 256, 1, NULL, NULL),
(3, 1, 1, NULL, NULL),
(4, 257, 1, NULL, NULL),
(5, 2, 1, NULL, NULL),
(6, 258, 1, NULL, NULL),
(7, 3, 1, NULL, NULL),
(8, 259, 1, NULL, NULL),
(9, 4, 1, NULL, NULL),
(10, 260, 1, NULL, NULL),
(11, 5, 1, NULL, NULL),
(12, 261, 1, NULL, NULL),
(13, 6, 1, NULL, NULL),
(14, 262, 1, NULL, NULL),
(15, 7, 1, NULL, NULL),
(16, 8, 1, NULL, NULL),
(17, 264, 1, NULL, NULL),
(18, 9, 1, NULL, NULL),
(19, 10, 1, NULL, NULL),
(20, 266, 1, NULL, NULL),
(21, 11, 1, NULL, NULL),
(22, 267, 1, NULL, NULL),
(23, 268, 1, NULL, NULL),
(24, 269, 1, NULL, NULL),
(25, 270, 1, NULL, NULL),
(26, 15, 1, NULL, NULL),
(27, 271, 1, NULL, NULL),
(28, 274, 1, NULL, NULL),
(29, 12, 1, NULL, NULL),
(30, 13, 1, NULL, NULL),
(31, 263, 1, NULL, NULL),
(32, 16, 1, NULL, NULL),
(33, 17, 1, NULL, NULL),
(34, 18, 1, NULL, NULL),
(35, 19, 1, NULL, NULL),
(36, 20, 1, NULL, NULL),
(37, 21, 1, NULL, NULL),
(38, 22, 1, NULL, NULL),
(39, 23, 1, NULL, NULL),
(40, 24, 1, NULL, NULL),
(41, 25, 1, NULL, NULL),
(42, 26, 1, NULL, NULL),
(43, 27, 1, NULL, NULL),
(44, 28, 1, NULL, NULL),
(45, 29, 1, NULL, NULL),
(46, 43, 1, NULL, NULL),
(47, 30, 1, NULL, NULL),
(48, 44, 1, NULL, NULL),
(49, 31, 1, NULL, NULL),
(50, 33, 1, NULL, NULL),
(51, 45, 1, NULL, NULL),
(52, 46, 1, NULL, NULL),
(53, 47, 1, NULL, NULL),
(54, 34, 1, NULL, NULL),
(55, 37, 1, NULL, NULL),
(56, 48, 1, NULL, NULL),
(57, 49, 1, NULL, NULL),
(58, 40, 1, NULL, NULL),
(59, 35, 1, NULL, NULL),
(60, 55, 1, NULL, NULL),
(61, 36, 1, NULL, NULL),
(62, 51, 1, NULL, NULL),
(63, 41, 1, NULL, NULL),
(64, 42, 1, NULL, NULL),
(65, 53, 1, NULL, NULL),
(66, 58, 1, NULL, NULL),
(67, 54, 1, NULL, NULL),
(68, 59, 1, NULL, NULL),
(69, 60, 1, NULL, NULL),
(70, 57, 1, NULL, NULL),
(71, 56, 1, NULL, NULL),
(72, 61, 1, NULL, NULL),
(73, 62, 1, NULL, NULL),
(74, 63, 1, NULL, NULL),
(75, 64, 1, NULL, NULL),
(76, 65, 1, NULL, NULL),
(77, 66, 1, NULL, NULL),
(78, 69, 1, NULL, NULL),
(79, 67, 1, NULL, NULL),
(80, 70, 1, NULL, NULL),
(81, 71, 1, NULL, NULL),
(82, 68, 1, NULL, NULL),
(83, 72, 1, NULL, NULL),
(84, 73, 1, NULL, NULL),
(85, 74, 1, NULL, NULL),
(86, 75, 1, NULL, NULL),
(87, 76, 1, NULL, NULL),
(88, 78, 1, NULL, NULL),
(89, 77, 1, NULL, NULL),
(90, 79, 1, NULL, NULL),
(91, 80, 1, NULL, NULL),
(92, 87, 1, NULL, NULL),
(93, 88, 1, NULL, NULL),
(94, 81, 1, NULL, NULL),
(95, 82, 1, NULL, NULL),
(96, 89, 1, NULL, NULL),
(97, 83, 1, NULL, NULL),
(98, 90, 1, NULL, NULL),
(99, 84, 1, NULL, NULL),
(100, 91, 1, NULL, NULL),
(101, 92, 1, NULL, NULL),
(102, 85, 1, NULL, NULL),
(103, 93, 1, NULL, NULL),
(104, 86, 1, NULL, NULL),
(105, 94, 1, NULL, NULL),
(106, 95, 1, NULL, NULL),
(107, 96, 1, NULL, NULL),
(108, 97, 1, NULL, NULL),
(109, 98, 1, NULL, NULL),
(110, 141, 1, NULL, NULL),
(111, 235, 1, NULL, NULL),
(112, 142, 1, NULL, NULL),
(113, 99, 1, NULL, NULL),
(114, 143, 1, NULL, NULL),
(115, 100, 1, NULL, NULL),
(116, 144, 1, NULL, NULL),
(117, 101, 1, NULL, NULL),
(118, 145, 1, NULL, NULL),
(119, 102, 1, NULL, NULL),
(120, 146, 1, NULL, NULL),
(121, 421, 1, NULL, NULL),
(122, 103, 1, NULL, NULL),
(123, 151, 1, NULL, NULL),
(124, 104, 1, NULL, NULL),
(125, 152, 1, NULL, NULL),
(126, 105, 1, NULL, NULL),
(127, 106, 1, NULL, NULL),
(128, 147, 1, NULL, NULL),
(129, 107, 1, NULL, NULL),
(130, 148, 1, NULL, NULL),
(131, 114, 1, NULL, NULL),
(132, 149, 1, NULL, NULL),
(133, 108, 1, NULL, NULL),
(134, 150, 1, NULL, NULL),
(135, 153, 1, NULL, NULL),
(136, 154, 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `item_supplier`
--
ALTER TABLE `item_supplier`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_supplier_item_id_foreign` (`item_id`),
  ADD KEY `item_supplier_supplier_id_foreign` (`supplier_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `item_supplier`
--
ALTER TABLE `item_supplier`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item_supplier`
--
ALTER TABLE `item_supplier`
  ADD CONSTRAINT `item_supplier_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_supplier_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
