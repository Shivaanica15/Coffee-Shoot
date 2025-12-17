-- CoffeeShoot database bootstrap (MariaDB/MySQL)
-- Creates schema `mydb` and the tables used by the PHP pages.

CREATE DATABASE IF NOT EXISTS `mydb` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `mydb`;

-- Users (clients/admin/studios)
CREATE TABLE IF NOT EXISTS `registered_user` (
  `User_ID` INT NOT NULL AUTO_INCREMENT,
  `Username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `mobileNo` VARCHAR(50) NULL,
  `phone` VARCHAR(50) NULL,
  `email` VARCHAR(255) NOT NULL,
  `type` VARCHAR(20) NOT NULL DEFAULT 'client',
  `studio_name` VARCHAR(255) NULL,
  `profile_picture` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`User_ID`),
  UNIQUE KEY `uniq_registered_user_email` (`email`),
  KEY `idx_registered_user_type` (`type`),
  KEY `idx_registered_user_username` (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Studio package/event details
CREATE TABLE IF NOT EXISTS `event_details` (
  `packageNo` VARCHAR(10) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `email` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`packageNo`),
  KEY `idx_event_details_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Client bookings
CREATE TABLE IF NOT EXISTS `booking` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `event` VARCHAR(255) NOT NULL,
  `location` VARCHAR(255) NOT NULL,
  `date` DATE NOT NULL,
  `time` TIME NOT NULL,
  `studioName` VARCHAR(255) NULL,
  `package_type` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_booking_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Some pages refer to `bookings` (plural). Keep a compatible table to avoid runtime errors.
CREATE TABLE IF NOT EXISTS `bookings` LIKE `booking`;

-- Contact form submissions
CREATE TABLE IF NOT EXISTS `Inquiries` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(255) NOT NULL,
  `Email` VARCHAR(255) NOT NULL,
  `Message` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_inquiries_email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Studio uploaded media
CREATE TABLE IF NOT EXISTS `media` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `media_name` VARCHAR(255) NOT NULL,
  `media_path` VARCHAR(500) NOT NULL,
  `media_type` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_media_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed minimal accounts so login/explore works immediately.
INSERT INTO `registered_user`
  (`Username`,`password`,`phone`,`mobileNo`,`email`,`type`,`studio_name`,`profile_picture`)
VALUES
  ('System Admin', '$2y$10$P9463sLVOTZeat0kCIyNuureJIkcJidQVjhTFJHlQIBeRoINQ2NW2', '0712345678', '0712345678', 'admin@coffeeshoot.local', 'admin', NULL, 'unkown.png')
ON DUPLICATE KEY UPDATE
  `Username`=VALUES(`Username`),
  `password`=VALUES(`password`),
  `phone`=VALUES(`phone`),
  `mobileNo`=VALUES(`mobileNo`),
  `type`=VALUES(`type`),
  `studio_name`=VALUES(`studio_name`),
  `profile_picture`=VALUES(`profile_picture`);

INSERT INTO `registered_user`
  (`Username`,`password`,`phone`,`mobileNo`,`email`,`type`,`studio_name`,`profile_picture`)
VALUES
  ('Amaya Silva', '$2y$10$UZTBT5y2RuvpebZwH3F7T.LCRQpgFjqtxIkx6ccSp//WSo01ZL9xu', '0771122334', '0771122334', 'amaya.silva@example.com', 'client', NULL, 'unkown.png'),
  ('Kasun Perera', '$2y$10$UZTBT5y2RuvpebZwH3F7T.LCRQpgFjqtxIkx6ccSp//WSo01ZL9xu', '0719988776', '0719988776', 'kasun.perera@example.com', 'client', NULL, 'unkown.png')
ON DUPLICATE KEY UPDATE
  `Username`=VALUES(`Username`),
  `password`=VALUES(`password`),
  `phone`=VALUES(`phone`),
  `mobileNo`=VALUES(`mobileNo`),
  `type`=VALUES(`type`),
  `studio_name`=VALUES(`studio_name`),
  `profile_picture`=VALUES(`profile_picture`);

INSERT INTO `registered_user`
  (`Username`,`password`,`phone`,`mobileNo`,`email`,`type`,`studio_name`,`profile_picture`)
VALUES
  ('Nethmi Jayasinghe', '$2y$10$nUjNqBEdSi9zfgyJ7rMto.jQ9KN6NQIPO/pN1K7rERImD0b842Ijq', '0785566778', '0785566778', 'studio@coffeeshoot.local', 'studio', 'LensCraft Studio', 'unkown.png')
ON DUPLICATE KEY UPDATE
  `Username`=VALUES(`Username`),
  `password`=VALUES(`password`),
  `phone`=VALUES(`phone`),
  `mobileNo`=VALUES(`mobileNo`),
  `type`=VALUES(`type`),
  `studio_name`=VALUES(`studio_name`),
  `profile_picture`=VALUES(`profile_picture`);

-- Keep legacy demo client account (used by older pages/configs)
INSERT INTO `registered_user`
  (`Username`,`password`,`phone`,`mobileNo`,`email`,`type`,`studio_name`,`profile_picture`)
VALUES
  ('Demo Client', '$2y$10$UZTBT5y2RuvpebZwH3F7T.LCRQpgFjqtxIkx6ccSp//WSo01ZL9xu', '0770000000', '0770000000', 'client@coffeeshoot.local', 'client', NULL, 'unkown.png')
ON DUPLICATE KEY UPDATE
  `Username`=VALUES(`Username`),
  `password`=VALUES(`password`),
  `phone`=VALUES(`phone`),
  `mobileNo`=VALUES(`mobileNo`),
  `type`=VALUES(`type`),
  `studio_name`=VALUES(`studio_name`),
  `profile_picture`=VALUES(`profile_picture`);

-- Dummy bookings (3+)
INSERT INTO `booking` (`email`,`event`,`location`,`date`,`time`,`studioName`,`package_type`) VALUES
  ('amaya.silva@example.com','Wedding','Colombo','2026-01-10','10:30:00','LensCraft Studio','Wedding'),
  ('kasun.perera@example.com','Birthday','Kandy','2026-02-05','15:00:00','LensCraft Studio','Birthday'),
  ('amaya.silva@example.com','Outdoor','Galle','2026-03-18','07:30:00','LensCraft Studio','Outdoor');

-- Dummy inquiries (3+)
INSERT INTO `Inquiries` (`Name`,`Email`,`Message`) VALUES
  ('Amaya Silva','amaya.silva@example.com','Hi, can I change my booking date if needed?'),
  ('Kasun Perera','kasun.perera@example.com','Do you offer a birthday package with 2 photographers?'),
  ('Nethmi Jayasinghe','studio@coffeeshoot.local','Please help me update my studio profile details.');
