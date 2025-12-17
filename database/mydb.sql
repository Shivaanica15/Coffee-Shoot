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
INSERT IGNORE INTO `registered_user`
  (`Username`,`password`,`mobileNo`,`phone`,`email`,`type`,`studio_name`,`profile_picture`)
VALUES
  ('Admin','admin123',NULL,NULL,'admin@coffeeshoot.local','admin',NULL,'unkown.png'),
  ('Demo Client','client123','0770000000',NULL,'client@coffeeshoot.local','client',NULL,'unkown.png'),
  ('Demo Studio','studio123',NULL,'0770000001','studio@coffeeshoot.local','studio','Demo Studio','unkown.png');
