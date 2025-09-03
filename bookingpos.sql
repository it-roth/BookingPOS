-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 01, 2025 at 08:45 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookingpos`
--
CREATE DATABASE IF NOT EXISTS `bookingpos` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `bookingpos`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `profile_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_username_unique` (`username`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `name`, `email`, `password`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`, `profile_image`) VALUES
(2, 'admin', 'Luffy', 'admin@gmail.com', '$2y$10$swd4irNm2Y1ziYrl0JXWPeTIbGISzexcWuMX4xV090.xMMGd8R.uu', 'admin', 1, NULL, NULL, '2025-05-28 14:33:56', 'profile-images/1756693472_luffy.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_number` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `movie_hall_id` bigint UNSIGNED NOT NULL,
  `customer_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_booking_number_unique` (`booking_number`),
  KEY `bookings_movie_hall_id_foreign` (`movie_hall_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_number`, `movie_hall_id`, `customer_name`, `customer_email`, `customer_phone`, `total_amount`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(21, 'BKG-20250520-EAF8440', 9, 'Saroth', 'saroth@gmail.com', '0963097092', 11.00, 'confirmed', NULL, '2025-05-20 12:47:00', '2025-09-01 04:46:27'),
(20, 'BKG-20250509-VHO4739', 9, 'Saroth', 'saroth@gmail.com', '0963097092', 9.00, 'confirmed', NULL, '2025-05-09 12:14:45', '2025-05-09 12:14:45'),
(19, 'BKG-20250421-OSA5550', 7, 'Saroth', 'saroth@gmail.com', '0963097092', 3.00, 'confirmed', NULL, '2025-04-21 12:57:39', '2025-04-21 12:57:39'),
(18, 'BKG-20250418-DPK1041', 7, 'Saroth', 'saroth@gmail.com', '0963097092', 5.00, 'confirmed', NULL, '2025-04-18 12:48:15', '2025-04-18 12:48:15'),
(22, 'BKG-20250528-YUW2068', 9, 'dsafesdfsdf', 'asdf', 'dfd', 3.00, 'confirmed', NULL, '2025-05-28 12:39:05', '2025-05-28 12:39:05'),
(23, 'BKG-20250529-QOE8823', 9, 'saroth', 'saroth@gmail.com', '124242356', 3.00, 'confirmed', NULL, '2025-05-29 11:41:00', '2025-05-29 11:41:00');

-- --------------------------------------------------------

--
-- Table structure for table `booking_items`
--

DROP TABLE IF EXISTS `booking_items`;
CREATE TABLE IF NOT EXISTS `booking_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` bigint UNSIGNED NOT NULL,
  `item_type` enum('ticket','food','drink') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_id` bigint UNSIGNED DEFAULT NULL,
  `item_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(8,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_booking_item` (`booking_id`,`item_type`,`item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_items`
--

INSERT INTO `booking_items` (`id`, `booking_id`, `item_type`, `item_id`, `item_name`, `quantity`, `unit_price`, `subtotal`, `created_at`, `updated_at`) VALUES
(29, 21, 'ticket', 63, 'Seat E3 (regular)', 1, 11.00, 11.00, '2025-05-20 12:47:00', '2025-09-01 04:46:27'),
(28, 20, 'food', 3, 'Hot Dog', 1, 1.00, 1.00, '2025-05-09 12:14:45', '2025-05-09 12:14:45'),
(27, 20, 'ticket', 136, 'Seat H9 (premium)', 1, 3.00, 3.00, '2025-05-09 12:14:45', '2025-05-09 12:14:45'),
(26, 20, 'ticket', 125, 'Seat F8 (premium)', 1, 3.00, 3.00, '2025-05-09 12:14:45', '2025-05-09 12:14:45'),
(25, 20, 'ticket', 123, 'Seat F6 (vip)', 1, 2.00, 2.00, '2025-05-09 12:14:45', '2025-05-09 12:14:45'),
(24, 19, 'ticket', 136, 'Seat H9 (premium)', 1, 3.00, 3.00, '2025-04-21 12:57:39', '2025-04-21 12:57:39'),
(23, 18, 'drink', 1, 'Coca-Cola', 1, 1.00, 1.00, '2025-04-18 12:48:15', '2025-04-18 12:48:15'),
(22, 18, 'ticket', 139, 'Seat G5 (vip)', 1, 2.00, 2.00, '2025-04-18 12:48:15', '2025-04-18 12:48:15'),
(21, 18, 'ticket', 128, 'Seat G6 (vip)', 1, 2.00, 2.00, '2025-04-18 12:48:15', '2025-04-18 12:48:15'),
(18, 15, 'ticket', 68, 'Seat E8 (regular)', 1, 1.00, 1.00, '2025-04-18 12:43:08', '2025-04-18 12:43:08'),
(19, 16, 'ticket', 52, 'Seat D2 (regular)', 1, 1.00, 1.00, '2025-04-18 12:45:02', '2025-04-18 12:45:02'),
(20, 17, 'food', 2, 'PopCorn', 1, 2.00, 2.00, '2025-04-18 12:46:44', '2025-04-18 12:46:44'),
(30, 22, 'ticket', 132, 'Seat G10 (premium)', 1, 3.00, 3.00, '2025-05-28 12:39:05', '2025-05-28 12:39:05'),
(31, 23, 'ticket', 122, 'Seat H4 (premium)', 1, 3.00, 3.00, '2025-05-29 11:41:00', '2025-05-29 11:41:00');

-- --------------------------------------------------------

--
-- Table structure for table `drinks`
--

DROP TABLE IF EXISTS `drinks`;
CREATE TABLE IF NOT EXISTS `drinks` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `size` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'regular',
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drinks`
--

INSERT INTO `drinks` (`id`, `name`, `description`, `category`, `image`, `price`, `size`, `is_available`, `created_at`, `updated_at`) VALUES
(1, 'Coca-Cola', NULL, 'Soft Drinks', 'drink-images/1745493918_cocacola_PNG10.png', 1.00, 'regular', 1, '2025-04-11 17:17:39', '2025-04-24 11:25:18'),
(2, 'Sting', NULL, 'Soft Drinks', 'drink-images/1745234476_sting.png', 1.00, 'regular', 1, '2025-04-21 11:19:04', '2025-04-21 11:21:16'),
(3, 'Pepsi', NULL, 'Soft Drinks', 'drink-images/1745234496_pepsi.png', 1.00, 'regular', 1, '2025-04-21 11:21:36', '2025-04-21 11:21:36');

-- --------------------------------------------------------

--
-- Table structure for table `food_items`
--

DROP TABLE IF EXISTS `food_items`;
CREATE TABLE IF NOT EXISTS `food_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `preparation_time` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `food_items`
--

INSERT INTO `food_items` (`id`, `name`, `description`, `category`, `image`, `price`, `is_available`, `preparation_time`, `created_at`, `updated_at`) VALUES
(2, 'PopCorn', NULL, 'Snacks', 'food-images/1745493886_Popcorn.png', 2.00, 1, 2, '2025-04-11 17:17:16', '2025-04-24 11:24:46'),
(3, 'Hot Dog', NULL, 'Fast Food', 'food-images/1745234920_hotdog.png', 1.00, 1, 2, '2025-04-21 11:28:40', '2025-04-21 11:28:40'),
(4, 'Fried Chicken', NULL, 'Fast Food', 'food-images/1745235358_Fried Chicken.png', 5.50, 1, 5, '2025-04-21 11:35:58', '2025-04-21 11:35:58');

-- --------------------------------------------------------

--
-- Table structure for table `halls`
--

DROP TABLE IF EXISTS `halls`;
CREATE TABLE IF NOT EXISTS `halls` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` int NOT NULL,
  `hall_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `halls`
--

INSERT INTO `halls` (`id`, `name`, `capacity`, `hall_type`, `is_active`, `description`, `created_at`, `updated_at`) VALUES
(2, 'Hall 1', 100, 'Regular', 1, 'A comfortable, air-conditioned hall with big screens, surround sound, and cozy seats — perfect for enjoying your favorite movies with friends and family.', '2025-04-11 15:50:46', '2025-04-11 15:50:46'),
(4, 'VIP Hall', 50, 'VIP', 1, 'VIP seats are designed for maximum comfort and luxury. They are usually larger, made with high-quality materials like leather or plush fabric, and offer extra legroom. Many VIP seats are recliners with adjustable backrests and footrests. Some theaters also provide in-seat service, such as food and beverage delivery. VIP seating is often located in the best viewing areas of the auditorium for an optimal movie experience, making it perfect for those who want a more relaxed and upscale environment.', '2025-05-29 11:18:12', '2025-05-29 11:18:12');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2024_03_10_000000_create_admins_table', 1),
(3, '2025_04_04_173617_create_movies_table', 1),
(4, '2025_04_04_173744_create_halls_table', 1),
(5, '2025_04_04_173807_create_seats_table', 1),
(6, '2025_04_04_173908_create_food_items_table', 1),
(7, '2025_04_04_174012_create_drinks_table', 1),
(8, '2025_04_06_085027_create_bookings_table', 1),
(9, '2025_04_06_085249_create_booking_items_table', 1),
(10, '2025_04_06_085259_create_movie_halls_table', 1),
(11, '2025_04_12_000000_modify_profile_image_in_users_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

DROP TABLE IF EXISTS `movies`;
CREATE TABLE IF NOT EXISTS `movies` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `genre` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` int NOT NULL,
  `image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `release_date` date NOT NULL,
  `is_showing` tinyint(1) NOT NULL DEFAULT '0',
  `price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `description`, `genre`, `duration`, `image`, `release_date`, `is_showing`, `price`, `created_at`, `updated_at`) VALUES
(2, 'Ne Zha (2019)', 'A boy born with a dark destiny must choose between destruction and becoming a hero. As fate pushes him toward chaos, Ne Zha fights to change his path in this epic, action-packed animated adventure.', 'Animation, Action, Fantasy', 110, '/images/movies/1745493939_NeZha.jpg', '2025-04-12', 1, 0.00, '2025-04-11 15:49:32', '2025-04-24 11:25:41'),
(4, 'Spider-Man: No Way Home', 'With Spider-Man\'s identity now revealed, Peter Parker asks Doctor Strange for help. When a spell goes wrong, dangerous foes from other universes start to appear, forcing Peter to discover what it truly means to be Spider-Man. Featuring characters from previous Spider-Man franchises, this multiverse crossover event redefines Peter’s path.', 'Action, Adventure, Sci-Fi, Superhero', 148, 'images/movies/1748516312_spider_man.jpg', '2025-06-01', 1, 0.00, '2025-05-29 10:58:32', '2025-05-29 10:58:32');

-- --------------------------------------------------------

--
-- Table structure for table `movie_halls`
--

DROP TABLE IF EXISTS `movie_halls`;
CREATE TABLE IF NOT EXISTS `movie_halls` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `movie_id` bigint UNSIGNED NOT NULL,
  `hall_id` bigint UNSIGNED NOT NULL,
  `showtime` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `movie_halls_movie_id_hall_id_showtime_unique` (`movie_id`,`hall_id`,`showtime`),
  KEY `movie_halls_hall_id_foreign` (`hall_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `movie_halls`
--

INSERT INTO `movie_halls` (`id`, `movie_id`, `hall_id`, `showtime`, `is_active`, `created_at`, `updated_at`) VALUES
(9, 2, 2, '2025-06-06 18:03:00', 1, '2025-05-08 11:00:18', '2025-05-08 11:00:18'),
(8, 2, 2, '2025-05-08 19:00:00', 1, '2025-05-08 11:00:18', '2025-05-08 11:00:18'),
(7, 2, 2, '2025-05-01 20:00:00', 1, '2025-04-11 17:16:02', '2025-04-11 17:16:02'),
(10, 4, 2, '2025-06-01 22:00:00', 1, '2025-05-29 11:00:14', '2025-05-29 11:00:14'),
(11, 2, 4, '2025-06-07 12:00:00', 1, '2025-05-29 11:43:36', '2025-05-29 11:43:36');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

DROP TABLE IF EXISTS `seats`;
CREATE TABLE IF NOT EXISTS `seats` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `hall_id` bigint UNSIGNED NOT NULL,
  `row` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` int NOT NULL,
  `type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'regular',
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `additional_charge` decimal(8,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seats_hall_id_row_number_unique` (`hall_id`,`row`,`number`)
) ENGINE=MyISAM AUTO_INCREMENT=191 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`id`, `hall_id`, `row`, `number`, `type`, `is_available`, `additional_charge`, `created_at`, `updated_at`) VALUES
(40, 2, 'B', 10, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(39, 2, 'B', 9, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(38, 2, 'B', 8, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(37, 2, 'B', 7, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(36, 2, 'B', 6, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(35, 2, 'B', 5, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(34, 2, 'B', 4, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(33, 2, 'B', 3, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(32, 2, 'B', 2, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(31, 2, 'B', 1, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(30, 2, 'A', 10, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(29, 2, 'A', 9, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(28, 2, 'A', 8, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(27, 2, 'A', 7, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(26, 2, 'A', 6, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(25, 2, 'A', 5, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(24, 2, 'A', 4, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(23, 2, 'A', 3, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(22, 2, 'A', 2, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(21, 2, 'A', 1, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(41, 2, 'C', 1, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(42, 2, 'C', 2, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(43, 2, 'C', 3, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(44, 2, 'C', 4, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(45, 2, 'C', 5, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(46, 2, 'C', 6, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(47, 2, 'C', 7, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(48, 2, 'C', 8, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(49, 2, 'C', 9, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(50, 2, 'C', 10, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(51, 2, 'D', 1, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(52, 2, 'D', 2, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(53, 2, 'D', 3, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(54, 2, 'D', 4, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(55, 2, 'D', 5, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(56, 2, 'D', 6, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(57, 2, 'D', 7, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(58, 2, 'D', 8, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(59, 2, 'D', 9, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(60, 2, 'D', 10, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(61, 2, 'E', 1, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(62, 2, 'E', 2, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(63, 2, 'E', 3, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(64, 2, 'E', 4, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(65, 2, 'E', 5, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(66, 2, 'E', 6, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(67, 2, 'E', 7, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(68, 2, 'E', 8, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(69, 2, 'E', 9, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(70, 2, 'E', 10, 'regular', 1, 1.00, '2025-04-11 17:07:14', '2025-04-11 17:07:14'),
(110, 2, 'J', 10, 'vip', 1, 2.00, '2025-04-11 17:11:04', '2025-04-11 17:11:04'),
(109, 2, 'J', 9, 'vip', 1, 2.00, '2025-04-11 17:11:04', '2025-04-11 17:11:04'),
(108, 2, 'J', 8, 'vip', 1, 2.00, '2025-04-11 17:11:04', '2025-04-11 17:11:04'),
(107, 2, 'J', 7, 'vip', 1, 2.00, '2025-04-11 17:11:04', '2025-04-11 17:11:04'),
(106, 2, 'J', 6, 'vip', 1, 2.00, '2025-04-11 17:11:04', '2025-04-11 17:11:04'),
(105, 2, 'J', 5, 'vip', 1, 2.00, '2025-04-11 17:11:04', '2025-04-11 17:11:04'),
(104, 2, 'J', 4, 'vip', 1, 2.00, '2025-04-11 17:11:04', '2025-04-11 17:11:04'),
(103, 2, 'J', 3, 'vip', 1, 2.00, '2025-04-11 17:11:04', '2025-04-11 17:11:04'),
(102, 2, 'J', 2, 'vip', 1, 2.00, '2025-04-11 17:11:04', '2025-04-11 17:11:04'),
(101, 2, 'J', 1, 'vip', 1, 2.00, '2025-04-11 17:11:04', '2025-04-11 17:11:04'),
(100, 2, 'I', 10, 'vip', 1, 2.00, '2025-04-11 17:11:04', '2025-04-11 17:11:04'),
(99, 2, 'I', 9, 'vip', 1, 2.00, '2025-04-11 17:11:04', '2025-04-11 17:11:04'),
(98, 2, 'I', 8, 'vip', 1, 2.00, '2025-04-11 17:11:04', '2025-04-11 17:11:04'),
(97, 2, 'I', 7, 'vip', 1, 2.00, '2025-04-11 17:11:04', '2025-04-11 17:11:04'),
(96, 2, 'I', 6, 'vip', 1, 2.00, '2025-04-11 17:11:04', '2025-04-11 17:11:04'),
(95, 2, 'I', 5, 'vip', 1, 2.00, '2025-04-11 17:11:04', '2025-04-11 17:11:04'),
(94, 2, 'I', 4, 'vip', 1, 2.00, '2025-04-11 17:11:04', '2025-04-11 17:11:04'),
(93, 2, 'I', 3, 'vip', 1, 2.00, '2025-04-11 17:11:04', '2025-04-11 17:11:04'),
(92, 2, 'I', 2, 'vip', 1, 2.00, '2025-04-11 17:11:04', '2025-04-11 17:11:04'),
(91, 2, 'I', 1, 'vip', 1, 2.00, '2025-04-11 17:11:04', '2025-04-11 17:11:04'),
(111, 2, 'F', 1, 'premium', 1, 3.00, '2025-04-11 17:12:11', '2025-04-11 17:12:11'),
(112, 2, 'F', 2, 'premium', 1, 3.00, '2025-04-11 17:12:11', '2025-04-11 17:12:11'),
(113, 2, 'F', 3, 'premium', 1, 3.00, '2025-04-11 17:12:11', '2025-04-11 17:12:11'),
(114, 2, 'F', 4, 'premium', 1, 3.00, '2025-04-11 17:12:11', '2025-04-11 17:12:11'),
(115, 2, 'G', 1, 'premium', 1, 3.00, '2025-04-11 17:12:11', '2025-04-11 17:12:11'),
(116, 2, 'G', 2, 'premium', 1, 3.00, '2025-04-11 17:12:11', '2025-04-11 17:12:11'),
(117, 2, 'G', 3, 'premium', 1, 3.00, '2025-04-11 17:12:11', '2025-04-11 17:12:11'),
(118, 2, 'G', 4, 'premium', 1, 3.00, '2025-04-11 17:12:11', '2025-04-11 17:12:11'),
(119, 2, 'H', 1, 'premium', 1, 3.00, '2025-04-11 17:12:11', '2025-04-11 17:12:11'),
(120, 2, 'H', 2, 'premium', 1, 3.00, '2025-04-11 17:12:11', '2025-04-11 17:12:11'),
(121, 2, 'H', 3, 'premium', 1, 3.00, '2025-04-11 17:12:11', '2025-04-11 17:12:11'),
(122, 2, 'H', 4, 'premium', 1, 3.00, '2025-04-11 17:12:11', '2025-04-11 17:12:11'),
(123, 2, 'F', 6, 'vip', 1, 2.00, '2025-04-11 17:12:52', '2025-04-11 17:14:25'),
(124, 2, 'F', 7, 'premium', 1, 3.00, '2025-04-11 17:12:52', '2025-04-11 17:12:52'),
(125, 2, 'F', 8, 'premium', 1, 3.00, '2025-04-11 17:12:52', '2025-04-11 17:12:52'),
(126, 2, 'F', 9, 'premium', 1, 3.00, '2025-04-11 17:12:52', '2025-04-11 17:12:52'),
(127, 2, 'F', 10, 'premium', 1, 3.00, '2025-04-11 17:12:52', '2025-04-11 17:12:52'),
(128, 2, 'G', 6, 'vip', 1, 2.00, '2025-04-11 17:12:52', '2025-04-11 17:14:50'),
(129, 2, 'G', 7, 'premium', 1, 3.00, '2025-04-11 17:12:52', '2025-04-11 17:12:52'),
(130, 2, 'G', 8, 'premium', 1, 3.00, '2025-04-11 17:12:52', '2025-04-11 17:12:52'),
(131, 2, 'G', 9, 'premium', 1, 3.00, '2025-04-11 17:12:52', '2025-04-11 17:12:52'),
(132, 2, 'G', 10, 'premium', 1, 3.00, '2025-04-11 17:12:52', '2025-04-11 17:12:52'),
(133, 2, 'H', 6, 'vip', 1, 2.00, '2025-04-11 17:12:52', '2025-04-11 17:15:10'),
(134, 2, 'H', 7, 'premium', 1, 3.00, '2025-04-11 17:12:52', '2025-04-11 17:12:52'),
(135, 2, 'H', 8, 'premium', 1, 3.00, '2025-04-11 17:12:52', '2025-04-11 17:12:52'),
(136, 2, 'H', 9, 'premium', 1, 3.00, '2025-04-11 17:12:52', '2025-04-11 17:12:52'),
(137, 2, 'H', 10, 'premium', 1, 3.00, '2025-04-11 17:12:52', '2025-04-11 17:12:52'),
(138, 2, 'F', 5, 'vip', 1, 2.00, '2025-04-11 17:13:53', '2025-04-11 17:13:53'),
(139, 2, 'G', 5, 'vip', 1, 2.00, '2025-04-11 17:13:53', '2025-04-11 17:13:53'),
(140, 2, 'H', 5, 'vip', 1, 2.00, '2025-04-11 17:13:53', '2025-04-11 17:13:53'),
(141, 4, 'A', 1, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(142, 4, 'A', 2, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(143, 4, 'A', 3, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(144, 4, 'A', 4, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(145, 4, 'A', 5, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(146, 4, 'A', 6, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(147, 4, 'A', 7, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(148, 4, 'A', 8, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(149, 4, 'A', 9, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(150, 4, 'A', 10, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(151, 4, 'B', 1, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(152, 4, 'B', 2, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(153, 4, 'B', 3, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(154, 4, 'B', 4, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(155, 4, 'B', 5, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(156, 4, 'B', 6, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(157, 4, 'B', 7, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(158, 4, 'B', 8, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(159, 4, 'B', 9, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(160, 4, 'B', 10, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(161, 4, 'C', 1, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(162, 4, 'C', 2, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(163, 4, 'C', 3, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(164, 4, 'C', 4, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(165, 4, 'C', 5, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(166, 4, 'C', 6, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(167, 4, 'C', 7, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(168, 4, 'C', 8, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(169, 4, 'C', 9, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(170, 4, 'C', 10, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(171, 4, 'D', 1, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(172, 4, 'D', 2, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(173, 4, 'D', 3, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(174, 4, 'D', 4, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(175, 4, 'D', 5, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(176, 4, 'D', 6, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(177, 4, 'D', 7, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(178, 4, 'D', 8, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(179, 4, 'D', 9, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(180, 4, 'D', 10, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(181, 4, 'E', 1, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(182, 4, 'E', 2, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(183, 4, 'E', 3, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(184, 4, 'E', 4, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(185, 4, 'E', 5, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(186, 4, 'E', 6, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(187, 4, 'E', 7, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(188, 4, 'E', 8, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(189, 4, 'E', 9, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02'),
(190, 4, 'E', 10, 'vip', 1, 5.00, '2025-05-29 11:19:02', '2025-05-29 11:19:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `role` varchar(191) NOT NULL DEFAULT 'admin',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `profile_image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `password`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`, `profile_image`) VALUES
(3, 'saroth', 'Leng Saroth', 'lengsaroth9@gmail.com', '$2y$10$5WU7ggPmBcfx0mbt9bHzmOPLb2z7jCROlEIQllH5.DceDNuWrZVkq', 'user', 1, NULL, '2025-04-17 14:23:15', '2025-09-01 02:22:23', 'profile-images/1748516177_saroth.PNG'),
(9, 'nainai', 'Thong Chornai', 'thongchornai7@gmail.com', '$2y$10$NSVLL/u8Q1WJNeJQ.mLdlOFXkBry5Utl2QNQ.bLO1E8op.fi4PjJe', 'user', 1, NULL, '2025-09-01 02:17:51', '2025-09-01 02:33:37', 'profile-images/1756694017_zoro.jpg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
