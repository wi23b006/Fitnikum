-- phpMyAdmin SQL Dump – Fitnikum Sprint 4 (schlanke Version)
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET NAMES utf8mb4 */;

-- =========================================================
-- Datenbank: fitnikum
-- =========================================================
DROP DATABASE IF EXISTS `fitnikum`;
CREATE DATABASE `fitnikum` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `fitnikum`;


-- ---------------------------------------------------------
-- Kategorien (Spec 3a: Produkte je Kategorie)
-- ---------------------------------------------------------
CREATE TABLE `categories` (
  `id`   INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Proteine'),
(2, 'Vitamine'),
(3, 'Zubehör');


-- ---------------------------------------------------------
-- User
-- Felder laut Spec 1)b: Anrede, Vorname, Nachname, Adresse,
-- PLZ, Ort, E-Mail, Username, Passwort.
-- role = 'user' oder 'admin' (Spec 1)e und 2)d).
-- active = aktiv/inaktiv (Matrix VIII Kunden verwalten).
-- remember_token = "Login merken" Cookie (Matrix III).
-- ---------------------------------------------------------
CREATE TABLE `users` (
  `id`             INT(11)      NOT NULL AUTO_INCREMENT,
  `salutation`     VARCHAR(20)  DEFAULT NULL,
  `firstname`      VARCHAR(100) DEFAULT NULL,
  `lastname`       VARCHAR(100) DEFAULT NULL,
  `username`       VARCHAR(100) NOT NULL UNIQUE,
  `email`          VARCHAR(150) NOT NULL UNIQUE,
  `password_hash`  VARCHAR(255) NOT NULL,
  `address`        VARCHAR(255) DEFAULT NULL,
  `postal_code`    VARCHAR(20)  DEFAULT NULL,
  `city`           VARCHAR(100) DEFAULT NULL,
  `role`           VARCHAR(20)  NOT NULL DEFAULT 'user',
  `active`         TINYINT(1)   NOT NULL DEFAULT 1,
  `remember_token` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Passwort-Hash für "test1234" (Beispiel) – bitte mit gen_hash.php
-- selbst erzeugen und unten ersetzen, sonst funktioniert der Login nicht.
INSERT INTO `users`
  (`id`, `salutation`, `firstname`, `lastname`, `username`, `email`,
   `password_hash`, `address`, `postal_code`, `city`, `role`) VALUES
(1, 'Herr', 'Admin', 'Fitnikum', 'admin', 'admin@fitnikum.at',
    'HIER_HASH_EINFUEGEN', NULL, NULL, NULL, 'admin'),
(2, 'Herr', 'Max', 'Mustermann', 'maxm', 'max@test.at',
    'HIER_HASH_EINFUEGEN', 'Teststraße 1', '1010', 'Wien', 'user');


-- ---------------------------------------------------------
-- Zahlungsmethoden (Spec 6a, Matrix VI: hinzufügen)
-- ---------------------------------------------------------
CREATE TABLE `payment_methods` (
  `id`      INT(11)      NOT NULL AUTO_INCREMENT,
  `user_id` INT(11)      NOT NULL,
  `type`    VARCHAR(50)  NOT NULL,
  `details` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ---------------------------------------------------------
-- Produkte (Spec Admin 1a: Name, Beschreibung, Bewertung, Preis, Foto)
-- ---------------------------------------------------------
CREATE TABLE `products` (
  `id`          INT(11)        NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(150)   NOT NULL,
  `description` TEXT           DEFAULT NULL,
  `category_id` INT(11)        NOT NULL,
  `price`       DECIMAL(10,2)  NOT NULL,
  `rating`      DECIMAL(2,1)   NOT NULL DEFAULT 0,
  `image`       VARCHAR(255)   DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `products` (`name`, `description`, `category_id`, `price`, `rating`, `image`) VALUES
('Whey Protein',        'Protein Pulver für Muskelaufbau und Regeneration.', 1, 24.99, 4.6, 'whey-protein.jpg'),
('Proteinriegel',       'Ein einfacher Snack mit hohem Proteingehalt.',      1,  2.49, 4.2, 'proteinriegel.jpg'),
('Veganes Protein',     'Pflanzliches Protein als Alternative zu Whey.',     1, 27.99, 4.4, 'veganes-protein.jpg'),
('Vitamin D',           'Unterstützt Knochen und Immunsystem.',              2,  9.99, 4.5, 'vitamin-d.jpg'),
('Vitamin C',           'Klassisches Vitaminprodukt für den Alltag.',        2,  7.99, 4.3, 'vitamin-c.jpg'),
('Multivitamin',        'Mehrere Vitamine in einem Produkt.',                2, 14.99, 4.4, 'multivitamin.jpg'),
('Shaker',              'Ein einfacher Shaker für Proteinshakes.',           3,  6.99, 4.7, 'shaker.jpg'),
('Trainingshandschuhe', 'Handschuhe für besseren Griff beim Training.',      3, 12.99, 4.1, 'trainingshandschuhe.jpg'),
('Sporttasche',         'Praktische Tasche für Gym und Freizeit.',           3, 29.99, 4.5, 'sporttasche.jpg');


-- ---------------------------------------------------------
-- Bestellungen
-- created_at: Spec 6c "nach Datum sortiert"
-- payment_method: Matrix V "Auswahl Zahlungsmöglichkeit"
-- voucher_code + voucher_used_amount: Matrix V "Restwert bleibt erhalten"
-- invoice_number: Spec 6c "Rechnungsnummer wird generiert"
-- ---------------------------------------------------------
CREATE TABLE `orders` (
  `id`                  INT(11)       NOT NULL AUTO_INCREMENT,
  `user_id`             INT(11)       NOT NULL,
  `total_price`         DECIMAL(10,2) NOT NULL,
  `payment_method`      VARCHAR(50)   DEFAULT NULL,
  `voucher_code`        VARCHAR(10)   DEFAULT NULL,
  `voucher_used_amount` DECIMAL(10,2) DEFAULT 0,
  `invoice_number`      VARCHAR(50)   DEFAULT NULL,
  `created_at`          DATETIME      DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ---------------------------------------------------------
-- Bestellpositionen
-- visible: Matrix VIII "Admin entfernt Produkt aus Bestellung,
-- für Kunde nicht mehr sichtbar"
-- ---------------------------------------------------------
CREATE TABLE `order_items` (
  `id`           INT(11)       NOT NULL AUTO_INCREMENT,
  `order_id`     INT(11)       NOT NULL,
  `product_id`   INT(11)       DEFAULT NULL,
  `product_name` VARCHAR(255)  NOT NULL,
  `price`        DECIMAL(10,2) NOT NULL,
  `quantity`     INT(11)       NOT NULL,
  `visible`      TINYINT(1)    NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ---------------------------------------------------------
-- Gutscheine
-- Spec Admin 3a: 5-stelliger alphanum. Code, Wert, Ablaufdatum
-- Matrix V: Restwert → remaining_value
-- ---------------------------------------------------------
CREATE TABLE `vouchers` (
  `id`              INT(11)        NOT NULL AUTO_INCREMENT,
  `code`            VARCHAR(10)    NOT NULL UNIQUE,
  `value`           DECIMAL(10,2)  NOT NULL,
  `remaining_value` DECIMAL(10,2)  NOT NULL,
  `expires_at`      DATE           NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `vouchers` (`code`, `value`, `remaining_value`, `expires_at`) VALUES
('TEST1', 20.00, 20.00, '2027-12-31'),
('EXP01',  5.00,  5.00, '2025-01-01');

COMMIT;