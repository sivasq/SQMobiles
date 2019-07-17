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
CREATE DATABASE IF NOT EXISTS `sqindia` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */;
USE `sqindia`;

-- Dumping structure for table sqindia.branches
CREATE TABLE IF NOT EXISTS `branches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_location` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sqindia.branches: 4 rows
/*!40000 ALTER TABLE `branches` DISABLE KEYS */;
REPLACE INTO `branches` (`id`, `branch_name`, `branch_location`, `created_at`, `updated_at`) VALUES
	(1, 'WareHouse', 'Guduvancherry', '2019-07-15 11:29:41', '2019-07-15 11:29:41'),
	(2, 'SQIndia1', 'Guduvancherry', '2019-07-15 11:37:15', '2019-07-15 11:37:15'),
	(3, 'SQIndia2', 'Urabakkam', '2019-07-15 11:37:30', '2019-07-15 11:37:30'),
	(4, 'SQIndia3', 'Chengalpattu', '2019-07-15 11:37:54', '2019-07-15 11:37:54');
/*!40000 ALTER TABLE `branches` ENABLE KEYS */;

-- Dumping structure for table sqindia.brands
CREATE TABLE IF NOT EXISTS `brands` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sqindia.brands: 5 rows
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
REPLACE INTO `brands` (`id`, `brand_name`, `created_at`, `updated_at`) VALUES
	(1, 'Mi', '2019-07-16 06:05:25', '2019-07-16 06:05:25'),
	(2, 'Lenovo', '2019-07-16 06:05:31', '2019-07-16 06:05:31'),
	(3, 'Vivo', '2019-07-16 06:05:36', '2019-07-16 06:05:36'),
	(4, 'Nokia', '2019-07-16 06:05:40', '2019-07-16 06:05:40'),
	(5, 'Moto', '2019-07-16 06:05:45', '2019-07-16 06:05:45');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;

-- Dumping structure for table sqindia.inventories
CREATE TABLE IF NOT EXISTS `inventories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventories_supplier_id_foreign` (`supplier_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sqindia.inventories: 1 rows
/*!40000 ALTER TABLE `inventories` DISABLE KEYS */;
REPLACE INTO `inventories` (`id`, `invoice_number`, `supplier_id`, `created_at`, `updated_at`) VALUES
	(1, 'gfgd', 1, '2019-07-16 07:42:05', '2019-07-16 07:42:05'),
	(2, 'gfgdkl', 1, '2019-07-16 07:42:05', '2019-07-16 07:42:05');
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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sqindia.inventory_products: 1 rows
/*!40000 ALTER TABLE `inventory_products` DISABLE KEYS */;
REPLACE INTO `inventory_products` (`id`, `inventory_id`, `product_id`, `inventory_product_qty`, `purchase_price_per_qty`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 2, 0, '2019-07-16 07:42:05', '2019-07-16 07:42:05'),
	(2, 1, 2, 2, 0, '2019-07-16 07:42:05', '2019-07-16 07:42:05'),
	(3, 2, 1, 2, 0, '2019-07-16 07:42:05', '2019-07-16 07:42:05'),
	(4, 2, 2, 2, 0, '2019-07-16 07:42:05', '2019-07-16 07:42:05');
/*!40000 ALTER TABLE `inventory_products` ENABLE KEYS */;

-- Dumping structure for table sqindia.inventory_product_details
CREATE TABLE IF NOT EXISTS `inventory_product_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `inventory_product_id` int(11) NOT NULL,
  `imei_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` int(11) NOT NULL,
  `sales_invoice` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_at` timestamp NULL DEFAULT NULL,
  `sale_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_product_details_inventory_product_id_foreign` (`inventory_product_id`),
  KEY `inventory_product_details_branch_id_foreign` (`branch_id`),
  KEY `inventory_product_details_user_id_foreign` (`sale_by`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sqindia.inventory_product_details: 2 rows
/*!40000 ALTER TABLE `inventory_product_details` DISABLE KEYS */;
REPLACE INTO `inventory_product_details` (`id`, `inventory_product_id`, `imei_number`, `branch_id`, `sales_invoice`, `sales_at`, `sale_by`, `created_at`, `updated_at`) VALUES
	(1, 1, 'gfdg', 1, 'sdfds', NULL, NULL, NULL, NULL),
	(2, 1, 'gdfg', 1, NULL, NULL, NULL, NULL, NULL),
	(3, 2, 'erere', 1, NULL, NULL, NULL, NULL, NULL),
	(4, 2, 'fgdfgfd', 1, NULL, NULL, NULL, NULL, NULL),
	(5, 3, 'gfdgll', 1, NULL, NULL, NULL, NULL, NULL),
	(6, 3, 'gdfgjo', 1, NULL, NULL, NULL, NULL, NULL),
	(7, 4, 'ggjoi', 1, NULL, NULL, NULL, NULL, NULL),
	(8, 4, 'ggkkjoi', 1, NULL, NULL, NULL, NULL, NULL);
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sqindia.inventory_product_detail_txn_histories: 0 rows
/*!40000 ALTER TABLE `inventory_product_detail_txn_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_product_detail_txn_histories` ENABLE KEYS */;

-- Dumping structure for table sqindia.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `product_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_brand_id_foreign` (`brand_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sqindia.products: 10 rows
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
REPLACE INTO `products` (`id`, `product_name`, `brand_id`, `created_at`, `updated_at`) VALUES
	(1, 'Note 7', 1, '2019-07-16 06:11:02', '2019-07-16 06:11:02'),
	(2, 'A2', 1, '2019-07-16 06:11:45', '2019-07-16 06:11:45'),
	(3, 'K6 power', 2, '2019-07-16 06:11:55', '2019-07-16 06:11:55'),
	(4, 'k7 Note', 2, '2019-07-16 06:12:06', '2019-07-16 06:12:06'),
	(5, 'v15 pro', 3, '2019-07-16 06:12:13', '2019-07-16 06:12:13'),
	(6, 'v9 note', 3, '2019-07-16 06:12:21', '2019-07-16 06:12:21'),
	(7, '6.1 plus', 4, '2019-07-16 06:12:52', '2019-07-16 06:12:52'),
	(8, '5.1 plus', 4, '2019-07-16 06:12:59', '2019-07-16 06:12:59'),
	(9, 'G4 plus', 5, '2019-07-16 06:13:06', '2019-07-16 06:13:06'),
	(10, 'G7 note', 5, '2019-07-16 06:13:14', '2019-07-16 06:13:14');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

-- Dumping structure for table sqindia.suppliers
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sqindia.suppliers: 5 rows
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
REPLACE INTO `suppliers` (`id`, `supplier_name`, `created_at`, `updated_at`) VALUES
	(1, 'Mi Supplier', '2019-07-16 06:04:01', '2019-07-16 06:04:01'),
	(2, 'Nokia Supplier', '2019-07-16 06:04:08', '2019-07-16 06:04:08'),
	(3, 'Moto Supplier', '2019-07-16 06:04:14', '2019-07-16 06:04:14'),
	(4, 'Lenovo Supplier', '2019-07-16 06:04:28', '2019-07-16 06:04:28'),
	(5, 'Vivo Supplier', '2019-07-16 06:04:51', '2019-07-16 06:04:51');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;

-- Dumping structure for table sqindia.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` int(11) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_mobile_unique` (`mobile`),
  KEY `users_branch_id_foreign` (`branch_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sqindia.users: 4 rows
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
REPLACE INTO `users` (`id`, `name`, `email`, `mobile`, `password`, `roles`, `branch_id`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Admin', 'admin@sqindia.net', '1234567890', '$2y$10$AaV2OQoCX9vo9Dz/uUjYT.vlGiir7Zji97wdJJT5c68bxHv4DorT2', 'admin', 1, NULL, NULL, '2019-07-15 16:24:46', '2019-07-15 16:24:48'),
	(2, 'user1', 'user1@sqindia.net', '123456794', '$2y$10$ztofk9KMWoUr6qTgTfvmUuSXcEt6Wml65.ffHLDGTfVOeioVxS.aW', 'user', 2, NULL, NULL, '2019-07-16 06:00:02', '2019-07-16 06:00:02'),
	(3, 'user2', 'user2@sqindia.net', '7894651', '$2y$10$kirRQGfCBomhme79l6D8Tut5PWl1tXdSnKPfbnp/2QpRxqB3TLLqi', 'user', 3, NULL, NULL, '2019-07-16 06:01:24', '2019-07-16 06:01:24'),
	(4, 'user3', 'user3@sqindia.net', '45678133', '$2y$10$/BSXDQ0yxbTvP.VsLCUF7uck6IcxBc1OAz/Jgnb547kifmFhSFPu6', 'user', 4, NULL, NULL, '2019-07-16 06:01:52', '2019-07-16 06:01:52');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
