-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for villa_mercedes_resort
CREATE DATABASE IF NOT EXISTS `villa_mercedes_resort` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `villa_mercedes_resort`;

-- Dumping structure for table villa_mercedes_resort.bookings
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `guest_name` varchar(255) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `facility_id` int NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `notes` text,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_amount` decimal(10,2) DEFAULT '0.00',
  `payment_status` varchar(50) DEFAULT 'Unpaid',
  PRIMARY KEY (`id`),
  KEY `facility_id` (`facility_id`),
  CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table villa_mercedes_resort.bookings: ~0 rows (approximately)
INSERT INTO `bookings` (`id`, `guest_name`, `phone_number`, `facility_id`, `date_start`, `date_end`, `notes`, `status`, `created_at`, `payment_amount`, `payment_status`) VALUES
	(22, 'Juan Dela Cruz', '09123456789', 10, '2026-05-16', '2026-05-17', '0', 'Confirmed', '2026-05-16 10:23:43', 500.00, 'Paid'),
	(23, 'Marione Andrell Manapul', '09123456789', 12, '2026-05-16', '2026-05-17', '0', 'Confirmed', '2026-05-16 10:24:23', 750.00, 'Paid'),
	(27, 'Jimuel Josh Timoteo', '09056638453', 9, '2026-05-16', '2026-05-17', '0', 'Confirmed', '2026-05-16 10:52:46', 250.00, 'Paid');

-- Dumping structure for table villa_mercedes_resort.facilities
CREATE TABLE IF NOT EXISTS `facilities` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_filename` varchar(255) DEFAULT NULL,
  `capacity` int NOT NULL,
  `rate_amount` decimal(10,2) NOT NULL,
  `rate_unit` enum('Per Day','Per Session') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table villa_mercedes_resort.facilities: ~4 rows (approximately)
INSERT INTO `facilities` (`id`, `name`, `description`, `image_filename`, `capacity`, `rate_amount`, `rate_unit`, `created_at`) VALUES
	(9, 'Swimming Pool', '', 'villa_mercedes_pool.jpg', 25, 500.00, 'Per Day', '2026-05-09 07:48:06'),
	(10, 'Chapel', '', 'chapel.jpg', 120, 750.00, 'Per Day', '2026-05-09 07:49:12'),
	(12, 'Event Place', '', 'event place.jpg', 120, 700.00, 'Per Day', '2026-05-09 15:50:23'),
	(17, 'Basketball Court', 'dhfjsdge', 'basketballcourt.jpg', 75, 200.00, 'Per Session', '2026-05-16 10:54:59');

-- Dumping structure for table villa_mercedes_resort.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table villa_mercedes_resort.users: ~0 rows (approximately)
INSERT INTO `users` (`id`, `username`, `password_hash`, `full_name`, `created_at`) VALUES
	(1, 'admin', '$2y$10$b9QpSp198ciPycL4itqJ4.09ng.twSXPhpjRA0cyV1i8ELi32iYz6', 'Administrator', '2026-05-09 02:52:36');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
