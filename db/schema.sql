-- ══════════════════════════════════════════════════════════════════════════════
-- Rider — MySQL Database Schema
-- ══════════════════════════════════════════════════════════════════════════════
-- Usage (XAMPP / phpMyAdmin):
--   1. Create a database called `deneme` (or change the name in app/config.php).
--   2. Import this file via phpMyAdmin → Import, or run via CLI:
--        mysql -u root deneme < db/schema.sql
-- ══════════════════════════════════════════════════════════════════════════════

SET NAMES utf8mb4;
SET foreign_key_checks = 0;

-- ── Admin users ───────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `admin_users` (
    `id`            INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `email`         VARCHAR(255)     NOT NULL,
    `password_hash` VARCHAR(255)     NOT NULL,
    `created_at`    TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_admin_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Cities ────────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `cities` (
    `id`   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_city_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Vehicle types ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `vehicle_types` (
    `id`   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_vtype_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Applications ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `applications` (
    `id`            INT UNSIGNED                                NOT NULL AUTO_INCREMENT,
    `name`          VARCHAR(255)                                NOT NULL,
    `email`         VARCHAR(255)                                NOT NULL,
    `phone`         VARCHAR(50)                                 NOT NULL,
    `city`          VARCHAR(100)                                NOT NULL DEFAULT '',
    `vehicle_type`  VARCHAR(100)                                NOT NULL DEFAULT '',
    `message`       TEXT                                        NOT NULL DEFAULT '',
    `referral_code` VARCHAR(32)                                 NOT NULL DEFAULT '',
    `status`        ENUM('pending','approved','rejected')       NOT NULL DEFAULT 'pending',
    `created_at`    TIMESTAMP                                   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET foreign_key_checks = 1;

-- ── Seed: sample cities ───────────────────────────────────────────────────────
INSERT IGNORE INTO `cities` (`name`) VALUES
    ('İstanbul'), ('Ankara'), ('İzmir'), ('Bursa'), ('Antalya'),
    ('Adana'), ('Konya'), ('Gaziantep'), ('Mersin'), ('Kayseri');

-- ── Seed: sample vehicle types ────────────────────────────────────────────────
INSERT IGNORE INTO `vehicle_types` (`name`) VALUES
    ('Motosiklet'), ('Bisiklet'), ('Elektrikli Scooter'), ('Otomobil'), ('Minibüs');

-- ── NOTE: Admin user ──────────────────────────────────────────────────────────
-- Do NOT store a plain-text password here.
-- Use reset-admin.php to create/reset your admin account:
--   http://localhost/rider/reset-admin.php?action=create&email=admin@example.com&pass=YourPass

-- ── Migration: add referral_code column to existing databases ─────────────────
-- Run this if you already have the applications table without referral_code:
--   ALTER TABLE `applications` ADD COLUMN `referral_code` VARCHAR(32) NOT NULL DEFAULT '' AFTER `message`;
