CREATE DATABASE IF NOT EXISTS `villa_mercedes_resort`;
USE `villa_mercedes_resort`;

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
) AUTO_INCREMENT=1000;

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
) AUTO_INCREMENT=2000;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) AUTO_INCREMENT=3000;

INSERT INTO `users` (`username`, `password_hash`, `full_name`, `created_at`) VALUES
('admin', '$2y$10$b9QpSp198ciPycL4itqJ4.09ng.twSXPhpjRA0cyV1i8ELi32iYz6', 'Administrator', '2026-05-09 02:52:36');
