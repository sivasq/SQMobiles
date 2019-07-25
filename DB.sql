-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.15 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             10.2.0.5610
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for sqindia
CREATE DATABASE IF NOT EXISTS `sqindia` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `sqindia`;

-- Dumping structure for table sqindia.branches
CREATE TABLE IF NOT EXISTS `branches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_name` varchar(191) NOT NULL,
  `branch_location` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Dumping data for table sqindia.branches: 8 rows
/*!40000 ALTER TABLE `branches` DISABLE KEYS */;
REPLACE INTO `branches` (`id`, `branch_name`, `branch_location`, `created_at`, `updated_at`) VALUES
	(1, 'WareHouse', 'Guduvancherry', '2019-07-15 11:29:41', '2019-07-15 11:29:41'),
	(2, 'GUD1', 'Guduvancherry', '2019-07-22 10:19:20', '2019-07-22 10:19:20'),
	(3, 'GUD2', 'Lenovo Guduvancherry', '2019-07-22 10:19:35', '2019-07-22 10:19:35'),
	(4, 'GUD3', 'Mi Guduvancherry', '2019-07-22 10:19:54', '2019-07-22 10:19:54'),
	(5, 'UPK', 'Urapakkam', '2019-07-22 10:20:43', '2019-07-22 10:20:43'),
	(6, 'MMN', 'MM Nagar', '2019-07-22 10:20:52', '2019-07-22 10:20:52'),
	(7, 'CGL1', 'Lenovo Chengalpattu', '2019-07-22 10:21:14', '2019-07-22 10:21:14'),
	(8, 'CGL2', 'Chengalpattu', '2019-07-22 10:21:27', '2019-07-22 10:21:27');
/*!40000 ALTER TABLE `branches` ENABLE KEYS */;

-- Dumping structure for table sqindia.brands
CREATE TABLE IF NOT EXISTS `brands` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- Dumping data for table sqindia.brands: 20 rows
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
REPLACE INTO `brands` (`id`, `brand_name`, `created_at`, `updated_at`) VALUES
	(1, 'Samsung', '2019-07-22 11:02:16', '2019-07-22 11:02:16'),
	(2, 'Vivo', '2019-07-22 11:02:21', '2019-07-22 11:02:21'),
	(3, 'Lava', '2019-07-22 11:02:27', '2019-07-22 11:02:27'),
	(4, 'RedMi', '2019-07-22 11:02:33', '2019-07-22 11:02:33'),
	(5, 'Oppo', '2019-07-22 11:02:43', '2019-07-22 11:02:43'),
	(6, 'RealMe', '2019-07-22 11:02:56', '2019-07-22 11:02:56'),
	(7, 'Asus', '2019-07-22 11:03:00', '2019-07-22 11:03:00'),
	(8, 'Moto', '2019-07-22 11:03:12', '2019-07-22 11:03:12'),
	(9, 'Nokia', '2019-07-22 11:03:19', '2019-07-22 11:03:19'),
	(10, 'Coolpad', '2019-07-22 11:04:01', '2019-07-22 11:04:01'),
	(11, 'MicroMax', '2019-07-22 11:04:08', '2019-07-22 11:04:08'),
	(12, 'CoolPad', '2019-07-25 03:05:04', '2019-07-25 03:05:04'),
	(13, 'Gionee', '2019-07-25 03:05:12', '2019-07-25 03:05:12'),
	(14, 'Honor', '2019-07-25 03:05:19', '2019-07-25 03:05:19'),
	(15, 'infocus', '2019-07-25 03:05:30', '2019-07-25 03:05:30'),
	(16, 'Intex', '2019-07-25 03:05:36', '2019-07-25 03:05:36'),
	(17, 'Fox', '2019-07-25 03:05:40', '2019-07-25 03:05:40'),
	(18, 'Clout', '2019-07-25 03:05:57', '2019-07-25 03:05:57'),
	(19, 'Mobistar', '2019-07-25 03:06:05', '2019-07-25 03:06:05');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;

-- Dumping structure for table sqindia.inventories
CREATE TABLE IF NOT EXISTS `inventories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(191) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventories_supplier_id_foreign` (`supplier_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table sqindia.inventories: 0 rows
/*!40000 ALTER TABLE `inventories` DISABLE KEYS */;
REPLACE INTO `inventories` (`id`, `invoice_number`, `supplier_id`, `created_at`, `updated_at`) VALUES
	(1, 'sddfd', 1, '2019-07-25 09:40:49', '2019-07-25 09:40:49'),
	(2, 'dsd', 2, '2019-07-25 09:43:56', '2019-07-25 09:43:56'),
	(3, 'gfgfd', 2, '2019-07-25 09:46:20', '2019-07-25 09:46:20'),
	(4, 'fdsf', 2, '2019-07-25 12:03:35', '2019-07-25 12:03:35');
/*!40000 ALTER TABLE `inventories` ENABLE KEYS */;

-- Dumping structure for table sqindia.inventory_products
CREATE TABLE IF NOT EXISTS `inventory_products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `inventory_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `inventory_product_qty` int(11) NOT NULL,
  `purchase_price_per_qty` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_products_inventory_id_foreign` (`inventory_id`),
  KEY `inventory_products_product_id_foreign` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table sqindia.inventory_products: 0 rows
/*!40000 ALTER TABLE `inventory_products` DISABLE KEYS */;
REPLACE INTO `inventory_products` (`id`, `inventory_id`, `product_id`, `inventory_product_qty`, `purchase_price_per_qty`, `created_at`, `updated_at`) VALUES
	(1, 1, 99, 2, 454, '2019-07-25 09:40:49', '2019-07-25 09:40:49'),
	(2, 2, 100, 2, 45, '2019-07-25 09:43:56', '2019-07-25 09:43:56'),
	(3, 3, 96, 2, 5, '2019-07-25 09:46:20', '2019-07-25 09:46:20'),
	(4, 4, 94, 2, 0, '2019-07-25 12:03:35', '2019-07-25 12:03:35');
/*!40000 ALTER TABLE `inventory_products` ENABLE KEYS */;

-- Dumping structure for table sqindia.inventory_product_details
CREATE TABLE IF NOT EXISTS `inventory_product_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `inventory_product_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `imei_number` varchar(191) NOT NULL,
  `imei_qty` int(11) DEFAULT '1',
  `branch_id` int(11) NOT NULL,
  `sales_invoice` varchar(191) DEFAULT NULL,
  `sales_at` timestamp NULL DEFAULT NULL,
  `sale_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_product_details_inventory_product_id_foreign` (`inventory_product_id`),
  KEY `inventory_product_details_branch_id_foreign` (`branch_id`),
  KEY `inventory_product_details_user_id_foreign` (`sale_by`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table sqindia.inventory_product_details: 0 rows
/*!40000 ALTER TABLE `inventory_product_details` DISABLE KEYS */;
REPLACE INTO `inventory_product_details` (`id`, `inventory_product_id`, `product_id`, `imei_number`, `imei_qty`, `branch_id`, `sales_invoice`, `sales_at`, `sale_by`, `created_at`, `updated_at`) VALUES
	(1, 3, 96, 'rfgfdg', 1, 1, 'fg', '2019-07-25 16:35:03', 15, NULL, NULL),
	(2, 3, 96, 'gfdg', 1, 4, NULL, NULL, NULL, NULL, NULL),
	(3, 4, 94, 'ererer', 1, 1, NULL, NULL, NULL, NULL, NULL),
	(4, 4, 94, 'ghjgj67', 1, 1, NULL, NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `inventory_product_details` ENABLE KEYS */;

-- Dumping structure for table sqindia.inventory_product_detail_txn_histories
CREATE TABLE IF NOT EXISTS `inventory_product_detail_txn_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `inventory_product_detail_id` int(11) NOT NULL,
  `txn_from` int(11) NOT NULL,
  `txn_to` int(11) NOT NULL,
  `txn_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inv_prt_detail_id_foreign` (`inventory_product_detail_id`),
  KEY `inventory_product_detail_txn_histories_txn_from_foreign` (`txn_from`),
  KEY `inventory_product_detail_txn_histories_txn_to_foreign` (`txn_to`),
  KEY `inventory_product_detail_txn_histories_txn_by_foreign` (`txn_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table sqindia.inventory_product_detail_txn_histories: 0 rows
/*!40000 ALTER TABLE `inventory_product_detail_txn_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_product_detail_txn_histories` ENABLE KEYS */;

-- Dumping structure for table sqindia.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Dumping data for table sqindia.migrations: 9 rows
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2019_07_12_131532_create_suppliers_table', 1),
	(3, '2019_07_12_131820_create_brands_table', 1),
	(4, '2019_07_12_131830_create_products_table', 1),
	(5, '2019_07_12_131907_create_branches_table', 1),
	(6, '2019_07_13_064222_create_inventories_table', 1),
	(7, '2019_07_13_114005_create_inventory_products_table', 1),
	(8, '2019_07_13_114102_create_inventory_product_details_table', 1),
	(9, '2019_07_15_092722_create_inventory_product_detail_txn_histories_table', 1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Dumping structure for table sqindia.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_name` varchar(191) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_brand_id_foreign` (`brand_id`)
) ENGINE=MyISAM AUTO_INCREMENT=126 DEFAULT CHARSET=utf8;

-- Dumping data for table sqindia.products: 107 rows
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
REPLACE INTO `products` (`id`, `product_name`, `brand_id`, `created_at`, `updated_at`) VALUES
	(1, 'A10', 1, '2019-07-22 11:05:15', '2019-07-25 07:34:31'),
	(2, 'A2 CORE 1GB/16GB', 1, '2019-07-22 11:05:33', '2019-07-22 11:05:33'),
	(3, 'A20', 1, '2019-07-22 11:05:41', '2019-07-22 11:05:41'),
	(4, 'A30 4GB/64GB', 1, '2019-07-22 11:05:57', '2019-07-22 11:05:57'),
	(5, 'A50 4GB/64GB', 1, '2019-07-22 11:06:18', '2019-07-22 11:06:18'),
	(6, 'A50 6GB/64GB', 1, '2019-07-22 11:06:50', '2019-07-22 11:06:50'),
	(7, 'A7 4GB 64GB', 1, '2019-07-22 11:07:13', '2019-07-22 11:07:13'),
	(8, 'A70', 1, '2019-07-22 11:07:23', '2019-07-22 11:07:23'),
	(9, 'GURU FM+', 1, '2019-07-22 11:07:42', '2019-07-22 11:07:42'),
	(10, 'GURU Music 2', 1, '2019-07-22 11:07:52', '2019-07-22 11:07:52'),
	(11, 'GURU 1200', 1, '2019-07-22 11:08:04', '2019-07-22 11:08:04'),
	(12, 'J2 Core', 1, '2019-07-22 11:08:14', '2019-07-22 11:08:14'),
	(13, 'J2[2018]', 1, '2019-07-22 11:08:26', '2019-07-22 11:08:26'),
	(14, 'J6 4GB/64GB', 1, '2019-07-22 11:08:57', '2019-07-22 11:08:57'),
	(15, 'J6 3GB/32GB', 1, '2019-07-22 11:09:11', '2019-07-22 11:09:11'),
	(16, 'J6 PLUS', 1, '2019-07-22 11:09:27', '2019-07-22 11:09:27'),
	(17, 'METRO SM-B313', 1, '2019-07-22 11:09:42', '2019-07-22 11:09:42'),
	(18, 'METRO SM-B350', 1, '2019-07-22 11:09:57', '2019-07-22 11:09:57'),
	(19, 'V15 6/64', 2, '2019-07-22 11:11:59', '2019-07-22 11:11:59'),
	(20, 'V15 PRO 6/128 GB', 2, '2019-07-22 11:12:28', '2019-07-22 11:12:28'),
	(21, 'Y12 3/64GB', 2, '2019-07-22 11:12:41', '2019-07-22 11:12:41'),
	(22, 'V11', 2, '2019-07-22 11:12:47', '2019-07-22 11:12:47'),
	(23, 'Y12 4/32 GB', 2, '2019-07-22 11:13:11', '2019-07-22 11:13:11'),
	(24, 'Y15 4/6 GB', 2, '2019-07-22 11:13:32', '2019-07-22 11:13:32'),
	(25, 'Y91i 6GB', 2, '2019-07-22 11:14:02', '2019-07-22 11:14:02'),
	(26, 'Y91i 32GB', 2, '2019-07-22 11:14:13', '2019-07-22 11:14:13'),
	(27, 'Y93 [3+64]', 2, '2019-07-22 11:15:12', '2019-07-22 11:15:12'),
	(28, 'Y93 [4+32]', 2, '2019-07-22 11:15:30', '2019-07-22 11:15:30'),
	(29, 'Y17 4/128 GB', 2, '2019-07-22 11:15:55', '2019-07-22 11:15:55'),
	(30, 'Y91 3/32 GB', 2, '2019-07-22 11:16:09', '2019-07-22 11:16:09'),
	(31, 'A1K 2/32GB', 5, '2019-07-22 11:17:20', '2019-07-22 11:17:20'),
	(32, 'A83 3GB', 5, '2019-07-22 11:17:34', '2019-07-22 11:17:34'),
	(33, 'A83 4GB', 5, '2019-07-22 11:17:49', '2019-07-22 11:17:49'),
	(34, 'A83 2GB', 5, '2019-07-22 11:18:20', '2019-07-22 11:18:20'),
	(35, '1201', 5, '2019-07-22 11:18:45', '2019-07-22 11:18:45'),
	(36, 'A33F', 5, '2019-07-22 11:18:57', '2019-07-22 11:18:57'),
	(37, 'A37', 5, '2019-07-22 11:19:05', '2019-07-22 11:19:05'),
	(38, 'A3S 2GB', 5, '2019-07-22 11:19:28', '2019-07-22 11:19:28'),
	(39, 'A3S 3GB', 5, '2019-07-22 11:19:42', '2019-07-22 11:19:42'),
	(40, 'A5 4/32GB', 5, '2019-07-22 11:20:05', '2019-07-22 11:20:05'),
	(41, 'A5 4/64GB', 5, '2019-07-22 11:20:19', '2019-07-22 11:20:19'),
	(42, 'A5S 4/64', 5, '2019-07-22 11:20:45', '2019-07-22 11:20:45'),
	(43, 'A5S 2/32GB', 5, '2019-07-22 11:21:18', '2019-07-22 11:21:18'),
	(44, 'A5S 3/32GB', 5, '2019-07-22 11:21:44', '2019-07-22 11:21:44'),
	(45, 'A7 4/64GB', 5, '2019-07-22 11:22:04', '2019-07-22 11:22:04'),
	(46, 'A7 3/64GB', 5, '2019-07-22 11:22:21', '2019-07-22 11:22:21'),
	(47, 'A71 3/16GB', 5, '2019-07-22 11:22:37', '2019-07-22 11:22:37'),
	(48, 'F11 4/128GB', 5, '2019-07-22 11:22:50', '2019-07-22 11:22:50'),
	(49, 'F11 PRO 6GB', 5, '2019-07-22 11:23:04', '2019-07-22 11:23:04'),
	(50, '3.2 3/32GB', 9, '2019-07-22 11:24:46', '2019-07-22 11:24:46'),
	(51, '4.2 3/32GB', 9, '2019-07-22 11:24:58', '2019-07-22 11:24:58'),
	(52, '1 1/8GB', 9, '2019-07-22 11:25:18', '2019-07-22 11:25:18'),
	(53, '105 DS RM 11345[NEW]', 9, '2019-07-22 11:26:00', '2019-07-22 11:26:00'),
	(54, '106 DS', 9, '2019-07-22 11:26:13', '2019-07-22 11:26:13'),
	(55, '130 DS RM NEW', 9, '2019-07-22 11:26:39', '2019-07-22 11:26:39'),
	(56, '150 DS RM 1190', 9, '2019-07-22 11:27:07', '2019-07-22 11:27:07'),
	(57, '2.1', 9, '2019-07-22 11:27:13', '2019-07-22 11:27:13'),
	(58, '2.2 2/16GB', 9, '2019-07-22 11:27:30', '2019-07-22 11:27:30'),
	(59, '2.2 3/32GB', 9, '2019-07-22 11:27:41', '2019-07-22 11:27:41'),
	(60, '216 DS RM-1187', 9, '2019-07-22 11:27:58', '2019-07-22 11:27:58'),
	(61, 'D105 SSRM 1134[NEW]', 9, '2019-07-22 11:28:17', '2019-07-22 11:28:17'),
	(62, 'NOTE 6 PRO 6/64GB', 4, '2019-07-22 11:45:56', '2019-07-22 11:45:56'),
	(63, 'NOTE 6 PRO 4/64GB', 4, '2019-07-22 11:46:06', '2019-07-22 11:46:06'),
	(64, 'POCO F1 [6+128]', 4, '2019-07-22 11:46:48', '2019-07-22 11:46:48'),
	(65, 'POCO F1 [8+128]', 4, '2019-07-22 11:47:05', '2019-07-22 11:47:05'),
	(66, 'POCO F1 [6+64]', 4, '2019-07-22 11:47:20', '2019-07-22 11:47:20'),
	(67, '5 [4+64GB]', 4, '2019-07-22 11:48:04', '2019-07-22 11:48:04'),
	(68, '6 [3+32GB]', 4, '2019-07-22 11:48:26', '2019-07-22 11:48:26'),
	(69, '6 [3+64GB]', 4, '2019-07-22 11:48:52', '2019-07-22 11:48:52'),
	(70, '6A [2+16]', 4, '2019-07-22 11:49:15', '2019-07-22 11:49:15'),
	(71, '6A[2+32GB]', 4, '2019-07-22 11:50:01', '2019-07-22 11:50:01'),
	(72, '7[2+32GB]', 4, '2019-07-22 11:50:11', '2019-07-22 11:50:11'),
	(73, '7[3+32GB]', 4, '2019-07-22 11:50:25', '2019-07-22 11:50:25'),
	(74, 'A1 [4+64]', 4, '2019-07-22 11:50:43', '2019-07-22 11:50:43'),
	(75, 'A2 6/128', 4, '2019-07-22 11:51:19', '2019-07-22 11:51:19'),
	(76, 'A2 4/64', 4, '2019-07-22 11:51:44', '2019-07-22 11:51:44'),
	(77, 'GO [1+8]', 4, '2019-07-22 11:52:02', '2019-07-22 11:52:02'),
	(78, 'MIX 2[6+128]', 4, '2019-07-22 11:52:33', '2019-07-22 11:52:33'),
	(79, 'NOTE 5 [3+32}', 4, '2019-07-22 11:52:57', '2019-07-22 11:52:57'),
	(80, 'NOTE 5 PRO [4+64]', 4, '2019-07-22 11:53:36', '2019-07-22 11:53:36'),
	(81, 'NOTE 5 PRO 6GB', 4, '2019-07-22 11:54:28', '2019-07-22 11:54:28'),
	(82, 'NOTE 6 PRO [3/32]', 4, '2019-07-22 11:54:44', '2019-07-22 11:54:44'),
	(83, 'NOTE 7 [3+32]', 4, '2019-07-22 11:56:26', '2019-07-22 11:56:26'),
	(84, 'NOTE 7 [4+64]', 4, '2019-07-22 11:56:45', '2019-07-22 11:56:45'),
	(85, 'NOTE 7 PRO [4+64]', 4, '2019-07-22 11:57:06', '2019-07-22 11:57:06'),
	(86, 'NOTE 7 PRO [6+128]', 4, '2019-07-22 11:57:27', '2019-07-22 11:57:27'),
	(87, 'NOTE 7S[3+32]', 4, '2019-07-22 11:57:40', '2019-07-22 11:57:40'),
	(88, 'NOTE 7S [4+64]', 4, '2019-07-22 11:57:59', '2019-07-22 11:57:59'),
	(89, 'Y2 [3+32]', 4, '2019-07-22 11:58:18', '2019-07-22 11:58:18'),
	(90, 'Y2 [4+64]', 4, '2019-07-22 11:58:33', '2019-07-22 11:58:33'),
	(91, 'Y3 [3+32]', 4, '2019-07-22 11:58:41', '2019-07-22 11:58:41'),
	(92, 'Y3 [4+64]', 4, '2019-07-22 11:58:54', '2019-07-22 11:58:54'),
	(93, '5A', 10, '2019-07-25 03:06:49', '2019-07-25 03:06:49'),
	(94, 'P5L', 13, '2019-07-25 03:07:06', '2019-07-25 03:07:06'),
	(95, '9i', 14, '2019-07-25 03:07:16', '2019-07-25 03:07:16'),
	(96, '9n', 14, '2019-07-25 03:07:30', '2019-07-25 03:07:30'),
	(97, 'Selfie C1', 15, '2019-07-25 03:07:55', '2019-07-25 03:07:55'),
	(98, 'Selfie C2', 15, '2019-07-25 03:08:12', '2019-07-25 03:08:12'),
	(99, 'G1', 16, '2019-07-25 03:08:28', '2019-07-25 03:08:28'),
	(100, 'Style+', 17, '2019-07-25 03:08:50', '2019-07-25 03:08:50'),
	(101, 'X428', 18, '2019-07-25 03:09:11', '2019-07-25 03:09:11'),
	(102, 'E1', 19, '2019-07-25 03:09:27', '2019-07-25 03:09:27'),
	(103, 'C2 2/16GB', 6, '2019-07-25 03:18:19', '2019-07-25 03:18:19'),
	(104, 'U1 4/64GB', 6, '2019-07-25 03:18:35', '2019-07-25 03:18:35'),
	(105, 'RealMe 2 3/32GB', 6, '2019-07-25 03:19:13', '2019-07-25 03:19:13'),
	(106, 'RealMe 2 4/64GB', 6, '2019-07-25 03:19:34', '2019-07-25 03:19:34'),
	(107, 'RealMe 3 Pro 6/128GB', 6, '2019-07-25 03:20:13', '2019-07-25 03:25:13'),
	(125, 'tgh', 2, '2019-07-25 07:46:37', '2019-07-25 07:46:37'),
	(124, 'A10', 2, '2019-07-25 07:36:58', '2019-07-25 07:36:58'),
	(123, 'A10f', 2, '2019-07-25 07:22:59', '2019-07-25 07:22:59'),
	(122, 'gfdgf', 2, '2019-07-25 07:22:44', '2019-07-25 07:22:44'),
	(121, 'fdsf', 2, '2019-07-25 07:18:46', '2019-07-25 07:18:46'),
	(120, 'fdsf', 1, '2019-07-25 07:18:43', '2019-07-25 07:18:43');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

-- Dumping structure for table sqindia.suppliers
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Dumping data for table sqindia.suppliers: 8 rows
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
REPLACE INTO `suppliers` (`id`, `supplier_name`, `created_at`, `updated_at`) VALUES
	(1, 'SAIRAJ TRADERS PVT LTD', '2019-07-22 10:44:52', '2019-07-22 10:44:52'),
	(2, 'SG Marketing', '2019-07-22 10:45:14', '2019-07-22 10:45:14'),
	(3, 'VEETEE Trading Pvt Ltd', '2019-07-22 10:46:02', '2019-07-22 10:46:02'),
	(4, 'Kreative Enterprise', '2019-07-22 10:46:17', '2019-07-22 10:46:17'),
	(5, 'TV Corp Solutions Pvt Ltd', '2019-07-22 10:46:48', '2019-07-22 10:46:48'),
	(6, 'Prakash Agencies', '2019-07-22 10:47:02', '2019-07-22 10:47:02'),
	(7, 'Mobile Point', '2019-07-22 10:47:11', '2019-07-22 10:47:11'),
	(8, 'Supreme Computer India Pvt Ltd', '2019-07-22 10:51:37', '2019-07-22 10:51:37');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;

-- Dumping structure for table sqindia.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `mobile` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `roles` varchar(191) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `api_token` varchar(200) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_mobile_unique` (`mobile`),
  KEY `users_branch_id_foreign` (`branch_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- Dumping data for table sqindia.users: 8 rows
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
REPLACE INTO `users` (`id`, `name`, `email`, `mobile`, `password`, `roles`, `branch_id`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`, `api_token`, `deleted_at`) VALUES
	(1, 'Admin', 'admin@sqindia.net', '1234567890', '$2y$10$AaV2OQoCX9vo9Dz/uUjYT.vlGiir7Zji97wdJJT5c68bxHv4DorT2', 'admin', 1, NULL, NULL, '2019-07-15 16:24:46', '2019-07-15 16:24:48', NULL, NULL),
	(11, 'Basha', 'bashadisha@sqindia.net', '9791181406', '$2y$10$J6vW4kjHx2julDhZIpXFO.s.6sUY5eHzn7pS3Q2QIBKKjNN9tku96', 'user', 5, NULL, NULL, '2019-07-22 10:36:50', '2019-07-22 10:36:50', NULL, NULL),
	(10, 'Vella Durai', 'velladurai@sqindia.net', '9698931609', '$2y$10$ssLC3aS9Z2eGD1BPv9hBee3P/2jFx5FfamJdcWqPss93/dTMA2l.i', 'user', 7, NULL, NULL, '2019-07-22 10:35:38', '2019-07-22 10:35:38', NULL, NULL),
	(9, 'Madan', 'madan@sqindia.net', '7845796770', '$2y$10$pjKAuXqMKWEq4YpJihIk4OzTx9Xvo.wvhPjvCvtDD/FOYnHwYEFJ2', 'user', 8, NULL, NULL, '2019-07-22 10:34:30', '2019-07-22 10:34:30', NULL, NULL),
	(8, 'Rabeek', 'rabeek@sqindia.net', '9952811128', '$2y$10$IRCfh8/wQf8LcWD8OJJDguExZDSVDmWPI46bVHpWNFWwgnlu0ANqi', 'user', 6, NULL, NULL, '2019-07-22 10:29:44', '2019-07-22 10:29:44', NULL, NULL),
	(7, 'Ghouse Basha', 'ghousebasha@sqindia.net', '9710201819', '$2y$10$gxH4z5irvIxJKTnTjcxBherVhJ3bi0JTYdh2sW1WpjHITsb1f.KIC', 'user', 2, NULL, NULL, '2019-07-22 10:26:08', '2019-07-22 10:30:42', NULL, NULL),
	(12, 'Ashiq', 'ashiq@sqindia.net', '9894240015', '$2y$10$n7RJpex4/iGAjQqTIZ8y6OrMTVhJtdzaPEVy6pYJBoG41hh2SUPEm', 'user', 3, NULL, NULL, '2019-07-22 10:37:51', '2019-07-22 10:37:51', NULL, NULL),
	(13, 'vinoth', 'vinoth@sqindia.net', '7010212503', '$2y$10$AaV2OQoCX9vo9Dz/uUjYT.vlGiir7Zji97wdJJT5c68bxHv4DorT2', 'user', 4, NULL, NULL, '2019-07-22 10:41:14', '2019-07-25 12:28:22', '7d2317e9ae280b1798ff09e19fbd32650c857150e7e4a398bd5259be03e7ed95', NULL),
	(15, 'vinoth', 'vinoth1@sqindia.net', '7010212504', '$2y$10$AaV2OQoCX9vo9Dz/uUjYT.vlGiir7Zji97wdJJT5c68bxHv4DorT2', 'user', 4, NULL, NULL, '2019-07-22 10:41:14', '2019-07-25 11:05:15', 'f9bdc1021b45163da201612d4979cc7bec78a35e7ce9a44239934dce8b12290d', '2019-07-25 11:05:15');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
