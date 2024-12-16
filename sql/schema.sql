-- Adatbázis létrehozása megfelelő karakterkészlettel és kollációval
CREATE DATABASE IF NOT EXISTS `crypto_app` DEFAULT CHARACTER SET utf8mb4 DEFAULT COLLATE utf8mb4_unicode_ci;

USE `crypto_app`;

-- Users tábla
CREATE TABLE `users` (
  `id` int AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci UNIQUE,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci UNIQUE,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Cryptocurrencies tábla (módosított icon_url oszlop)
CREATE TABLE `cryptocurrencies` (
  `id` int AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `symbol` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci UNIQUE,
  `current_price` decimal(20, 8),
  `market_cap` bigint,
  `volume_24h` bigint,
  `icon_url` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_update` datetime,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Favorites tábla
CREATE TABLE `favorites` (
  `id` int AUTO_INCREMENT PRIMARY KEY,
  `user_id` int,
  `cryptocurrency_id` int,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (cryptocurrency_id) REFERENCES cryptocurrencies(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Notifications tábla
CREATE TABLE `notifications` (
  `id` int AUTO_INCREMENT PRIMARY KEY,
  `user_id` int,
  `cryptocurrency_id` int,
  `trigger_price` decimal(20, 8),
  `notification_sent` boolean DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (cryptocurrency_id) REFERENCES cryptocurrencies(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE utf8mb4_unicode_ci;
