-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.8 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for tabulation_system
DROP DATABASE IF EXISTS `valorant_database`;
CREATE DATABASE IF NOT EXISTS `tabulation_system` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `tabulation_system`;

-- Dumping structure for table tabulation_system.cache
DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tabulation_system.cache: ~0 rows (approximately)

-- Dumping structure for table tabulation_system.cache_locks
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tabulation_system.cache_locks: ~0 rows (approximately)

-- Dumping structure for table tabulation_system.contestants
DROP TABLE IF EXISTS `contestants`;
CREATE TABLE IF NOT EXISTS `contestants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contest_id` bigint unsigned NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `performance_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `team` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contestants_contest_id_foreign` (`contest_id`),
  CONSTRAINT `contestants_contest_id_foreign` FOREIGN KEY (`contest_id`) REFERENCES `contests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tabulation_system.contestants: ~1 rows (approximately)
INSERT INTO `contestants` (`id`, `contest_id`, `number`, `name`, `performance_url`, `team`, `gender`, `photo`, `is_active`, `created_at`, `updated_at`) VALUES
	(68, 4, 'M67', 'Jao', NULL, 'jhwjdhwj', 'male', 'contestants/zdYLNmjOXqlzclUvNCZH8Oenvg5pAvvp2TuTHqHd.jpg', 1, '2026-07-20 03:20:38', '2026-07-20 03:20:38');

-- Dumping structure for table tabulation_system.contests
DROP TABLE IF EXISTS `contests`;
CREATE TABLE IF NOT EXISTS `contests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('single','double','group') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'single',
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `tabulator_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','active','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contests_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tabulation_system.contests: ~6 rows (approximately)
INSERT INTO `contests` (`id`, `uuid`, `name`, `type`, `logo`, `description`, `tabulator_name`, `status`, `created_at`, `updated_at`) VALUES
	(1, '927865b7-ff33-4dbd-a2c2-4735f28e2b36', 'MR. & MS. CCDI 2024', 'double', NULL, 'The annual search for the next ambassadors of CCDI.', 'Juan Dela Cruz', 'completed', '2026-07-12 21:43:21', '2026-07-20 01:54:30'),
	(2, 'f51750b7-9e92-4edd-9961-8636711e7ad5', 'wdwjhgj', 'double', NULL, 'gywgyw', NULL, 'pending', '2026-07-12 23:49:17', '2026-07-12 23:49:17'),
	(3, '6c53efd6-f28f-4eb8-b141-2332281dd99d', 'Jao Contest', 'double', 'logos/rjLK4keRkhHcOQG8Nx5yEOKqUGoXy8CfCzDjBGLO.jpg', 'dnmnfdnfd', NULL, 'pending', '2026-07-20 00:44:32', '2026-07-20 01:37:12'),
	(4, 'c5cfb007-d917-4df7-a0d7-00decea40456', 'JaoMer', 'double', 'logos/8H91lHfhMT5V7m17NmoU2v9Kamxql6EEeTPFjzV7.jpg', 'dghdgf', 'Jao', 'completed', '2026-07-20 01:54:16', '2026-07-21 05:47:34'),
	(5, '839c7ebe-efe9-4e1c-ba2a-ed2806fea637', 'fgdfgd', 'double', NULL, 'cvcgc', NULL, 'completed', '2026-07-21 05:47:27', '2026-07-21 05:58:57'),
	(6, '7f8e4e2e-dfad-41ad-b99a-50dc2a83d667', 'Singing Contest', 'double', NULL, 'This contest is for singing contest', NULL, 'active', '2026-07-21 05:58:51', '2026-07-21 05:58:57');

-- Dumping structure for table tabulation_system.criteria
DROP TABLE IF EXISTS `criteria`;
CREATE TABLE IF NOT EXISTS `criteria` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `exposure_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `percentage` decimal(5,2) NOT NULL,
  `min_score` decimal(8,2) NOT NULL DEFAULT '0.00',
  `max_score` decimal(8,2) NOT NULL DEFAULT '100.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `criteria_exposure_id_foreign` (`exposure_id`),
  CONSTRAINT `criteria_exposure_id_foreign` FOREIGN KEY (`exposure_id`) REFERENCES `exposures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tabulation_system.criteria: ~8 rows (approximately)
INSERT INTO `criteria` (`id`, `exposure_id`, `name`, `percentage`, `min_score`, `max_score`, `created_at`, `updated_at`) VALUES
	(1, 3, 'Poise & Elegance', 30.00, 0.00, 100.00, '2026-07-12 21:43:21', '2026-07-12 21:43:21'),
	(2, 3, 'Gown & Styling', 30.00, 0.00, 100.00, '2026-07-12 21:43:21', '2026-07-12 21:43:21'),
	(3, 3, 'Stage Presence', 20.00, 0.00, 100.00, '2026-07-12 21:43:21', '2026-07-12 21:43:21'),
	(4, 3, 'Overall Impact', 20.00, 0.00, 100.00, '2026-07-12 21:43:21', '2026-07-12 21:43:21'),
	(5, 6, 'AudienceImpact', 30.00, 20.00, 25.00, '2026-07-20 03:43:19', '2026-07-20 03:43:19'),
	(6, 6, 'yu', 70.00, 0.00, 100.00, '2026-07-20 19:28:58', '2026-07-20 19:28:58'),
	(7, 5, 'Accurateness', 50.00, 0.00, 100.00, '2026-07-21 03:23:04', '2026-07-21 03:23:04'),
	(8, 5, 'Impact', 50.00, 0.00, 100.00, '2026-07-21 03:23:16', '2026-07-21 03:23:16');

-- Dumping structure for table tabulation_system.exposures
DROP TABLE IF EXISTS `exposures`;
CREATE TABLE IF NOT EXISTS `exposures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contest_id` bigint unsigned NOT NULL,
  `previous_exposure_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('preliminary','final') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'preliminary',
  `order` int NOT NULL DEFAULT '1',
  `weight` decimal(5,2) NOT NULL DEFAULT '100.00',
  `top_n` int DEFAULT NULL,
  `carry_over` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('locked','active','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'locked',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exposures_contest_id_foreign` (`contest_id`),
  KEY `exposures_previous_exposure_id_foreign` (`previous_exposure_id`),
  CONSTRAINT `exposures_contest_id_foreign` FOREIGN KEY (`contest_id`) REFERENCES `contests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exposures_previous_exposure_id_foreign` FOREIGN KEY (`previous_exposure_id`) REFERENCES `exposures` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tabulation_system.exposures: ~7 rows (approximately)
INSERT INTO `exposures` (`id`, `contest_id`, `previous_exposure_id`, `name`, `type`, `order`, `weight`, `top_n`, `carry_over`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, NULL, 'Casual Wear', 'preliminary', 1, 20.00, NULL, 0, 'completed', '2026-07-12 21:43:21', '2026-07-12 21:43:21'),
	(2, 1, NULL, 'Talent Competition', 'preliminary', 2, 30.00, NULL, 0, 'completed', '2026-07-12 21:43:21', '2026-07-12 21:43:21'),
	(3, 1, NULL, 'Evening Gown', 'final', 3, 50.00, 10, 1, 'active', '2026-07-12 21:43:21', '2026-07-12 21:43:21'),
	(4, 1, NULL, 'Q & A', 'final', 4, 50.00, 5, 1, 'locked', '2026-07-12 21:43:21', '2026-07-12 21:43:21'),
	(5, 4, 6, 'Q&A', 'preliminary', 2, 100.00, NULL, 0, 'completed', '2026-07-20 03:35:15', '2026-07-21 03:55:09'),
	(6, 4, NULL, 'Talent', 'preliminary', 1, 100.00, NULL, 0, 'completed', '2026-07-20 03:35:33', '2026-07-21 03:56:31'),
	(7, 6, NULL, 'Singing Contest', 'preliminary', 1, 100.00, NULL, 0, 'locked', '2026-07-21 06:01:02', '2026-07-21 06:01:02');

-- Dumping structure for table tabulation_system.failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tabulation_system.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table tabulation_system.jobs
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tabulation_system.jobs: ~0 rows (approximately)

-- Dumping structure for table tabulation_system.job_batches
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tabulation_system.job_batches: ~0 rows (approximately)

-- Dumping structure for table tabulation_system.judges
DROP TABLE IF EXISTS `judges`;
CREATE TABLE IF NOT EXISTS `judges` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contest_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_online` tinyint(1) NOT NULL DEFAULT '0',
  `last_activity` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `judges_access_code_unique` (`access_code`),
  KEY `judges_contest_id_foreign` (`contest_id`),
  CONSTRAINT `judges_contest_id_foreign` FOREIGN KEY (`contest_id`) REFERENCES `contests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tabulation_system.judges: ~23 rows (approximately)
INSERT INTO `judges` (`id`, `contest_id`, `name`, `access_code`, `is_online`, `last_activity`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Judge One', 'C5JKUG', 1, NULL, '2026-07-12 21:43:21', '2026-07-12 21:43:21'),
	(2, 1, 'Judge Two', 'CKNB6C', 0, NULL, '2026-07-12 21:43:21', '2026-07-12 21:43:21'),
	(3, 1, 'Judge Three', 'HAVUEY', 1, NULL, '2026-07-12 21:43:21', '2026-07-12 21:43:21'),
	(4, 1, 'Judge Four', 'AVFWV9', 1, NULL, '2026-07-12 21:43:21', '2026-07-12 21:43:21'),
	(5, 1, 'Judge Five', 'DHONLC', 0, NULL, '2026-07-12 21:43:21', '2026-07-12 21:43:21'),
	(6, 3, 'Judge 01', 'NEEJOQ', 0, NULL, '2026-07-20 00:44:32', '2026-07-20 00:44:32'),
	(7, 3, 'Judge 02', 'OGPTWZ', 0, NULL, '2026-07-20 00:44:32', '2026-07-20 00:44:32'),
	(8, 3, 'Judge 03', 'FRDRQR', 0, NULL, '2026-07-20 00:44:32', '2026-07-20 00:44:32'),
	(9, 3, 'Judge 04', 'ZXU8IL', 0, NULL, '2026-07-20 00:44:32', '2026-07-20 00:44:32'),
	(10, 3, 'Judge 05', 'YINUKB', 0, NULL, '2026-07-20 00:44:32', '2026-07-20 00:44:32'),
	(12, 4, 'Judge 090', 'MCCUNC', 0, NULL, '2026-07-20 01:54:16', '2026-07-20 19:33:57'),
	(13, 4, 'Judge 03', '4DNSIS', 0, NULL, '2026-07-20 01:54:16', '2026-07-20 01:54:16'),
	(14, 4, 'Judge 04', 'R1GBT1', 0, NULL, '2026-07-20 01:54:16', '2026-07-20 01:54:16'),
	(15, 4, 'Judge 05', 'V6JCYZ', 0, NULL, '2026-07-20 01:54:16', '2026-07-20 01:54:16'),
	(16, 4, 'JAo', '0TN1IO', 1, '2026-07-21 03:54:44', '2026-07-20 19:34:25', '2026-07-21 03:54:44'),
	(17, 3, 'Jeresano', 'LMRXL7', 1, '2026-07-20 20:02:45', '2026-07-20 19:57:11', '2026-07-20 20:02:45'),
	(18, 5, 'Judge 01', 'CICERT', 0, NULL, '2026-07-21 05:47:27', '2026-07-21 05:47:27'),
	(19, 5, 'Judge 02', 'LY5IWJ', 0, NULL, '2026-07-21 05:47:27', '2026-07-21 05:47:27'),
	(20, 5, 'Judge 03', 'F71ZOO', 0, NULL, '2026-07-21 05:47:27', '2026-07-21 05:47:27'),
	(21, 5, 'Judge 04', 'CD9O2D', 0, NULL, '2026-07-21 05:47:27', '2026-07-21 05:47:27'),
	(22, 5, 'Judge 05', 'YZ9DII', 0, NULL, '2026-07-21 05:47:27', '2026-07-21 05:47:27'),
	(23, 6, 'Judge 01', '4X9P5S', 0, NULL, '2026-07-21 05:58:51', '2026-07-21 05:58:51'),
	(24, 6, 'Judge 02', 'O91ZE5', 0, NULL, '2026-07-21 05:58:51', '2026-07-21 05:58:51');

-- Dumping structure for table tabulation_system.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tabulation_system.migrations: ~13 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_07_13_053201_create_contests_table', 1),
	(5, '2026_07_13_053203_create_exposures_table', 1),
	(6, '2026_07_13_053204_create_criteria_table', 1),
	(7, '2026_07_13_053205_create_contestants_table', 1),
	(8, '2026_07_13_053206_create_judges_table', 1),
	(9, '2026_07_13_053207_create_scores_table', 1),
	(10, '2026_07_20_110000_add_previous_exposure_id_to_exposures_table', 2),
	(11, '2026_07_21_034321_add_performance_url_to_contestants_table', 3),
	(12, '2026_07_21_035944_add_uuid_to_contests_table', 4),
	(13, '2026_07_21_113311_add_comment_and_hash_to_scores_table', 5);

-- Dumping structure for table tabulation_system.password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tabulation_system.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table tabulation_system.scores
DROP TABLE IF EXISTS `scores`;
CREATE TABLE IF NOT EXISTS `scores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `judge_id` bigint unsigned NOT NULL,
  `contestant_id` bigint unsigned NOT NULL,
  `criterion_id` bigint unsigned NOT NULL,
  `exposure_id` bigint unsigned NOT NULL,
  `score` decimal(8,2) NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `is_final` tinyint(1) NOT NULL DEFAULT '0',
  `ballot_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `change_count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_score` (`judge_id`,`contestant_id`,`criterion_id`,`exposure_id`),
  KEY `scores_contestant_id_foreign` (`contestant_id`),
  KEY `scores_criterion_id_foreign` (`criterion_id`),
  KEY `scores_exposure_id_foreign` (`exposure_id`),
  CONSTRAINT `scores_contestant_id_foreign` FOREIGN KEY (`contestant_id`) REFERENCES `contestants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `scores_criterion_id_foreign` FOREIGN KEY (`criterion_id`) REFERENCES `criteria` (`id`) ON DELETE CASCADE,
  CONSTRAINT `scores_exposure_id_foreign` FOREIGN KEY (`exposure_id`) REFERENCES `exposures` (`id`) ON DELETE CASCADE,
  CONSTRAINT `scores_judge_id_foreign` FOREIGN KEY (`judge_id`) REFERENCES `judges` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tabulation_system.scores: ~4 rows (approximately)
INSERT INTO `scores` (`id`, `judge_id`, `contestant_id`, `criterion_id`, `exposure_id`, `score`, `comment`, `is_final`, `ballot_hash`, `change_count`, `created_at`, `updated_at`) VALUES
	(1, 16, 68, 5, 6, 20.00, NULL, 1, NULL, 0, '2026-07-20 20:22:45', '2026-07-20 20:25:03'),
	(2, 16, 68, 6, 6, 80.00, NULL, 1, NULL, 0, '2026-07-20 20:22:45', '2026-07-20 20:25:03'),
	(3, 16, 68, 7, 5, 51.00, NULL, 1, 'DD205D9E', 3, '2026-07-21 03:26:02', '2026-07-21 03:54:44'),
	(4, 16, 68, 8, 5, 70.00, NULL, 1, 'DD205D9E', 3, '2026-07-21 03:26:02', '2026-07-21 03:54:44');

-- Dumping structure for table tabulation_system.sessions
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tabulation_system.sessions: ~1 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('uvMRfQ3VKirEqhxRWVeUtv8d6z63EJQynPyYK2Jm', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', 'eyJfdG9rZW4iOiJNY0RXbEFYUEtBbTUxbWZNbW5EWjluakpOazlTWlRUQWZuNlkxdmFkIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvcmVzdWx0c1wvNCIsInJvdXRlIjoicmVzdWx0cy5zaG93In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwidXJsIjpbXSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsImp1ZGdlX2lkIjoxNn0=', 1784642865);

-- Dumping structure for table tabulation_system.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tabulation_system.users: ~0 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Admin Operator', 'admin@ccdi.edu.ph', '2026-07-12 21:43:21', '$2y$12$guiL1Iv6rDRmSTWYmu17duZ/4cdICcUrvnyJeVtMFGYrDB4zll4IC', 'lvaGhMPRhz', '2026-07-12 21:43:21', '2026-07-12 21:43:21');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
