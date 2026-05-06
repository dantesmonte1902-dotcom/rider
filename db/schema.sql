-- ══════════════════════════════════════════════════════════════════════════════
-- Rider — MySQL Veritabanı Şeması
-- ══════════════════════════════════════════════════════════════════════════════
--
-- phpMyAdmin'de SIFIRDAN KURULUM:
--   1. phpMyAdmin → SQL sekmesine tıklayın.
--   2. Bu dosyanın tamamını kopyalayıp yapıştırın ve "Git" deyin.
--      (Veya: Import → Dosya seç → bu dosyayı seçin → Git)
--   Tüm tablolar ve örnek veriler otomatik oluşturulur.
--
-- CLI ile kurulum:
--   mysql -u root -p < db/schema.sql
--
-- Veritabanı adını değiştirmek isterseniz:
--   Aşağıdaki "deneme" kelimelerini yeni adla değiştirin.
--   app/config.php içindeki DB_NAME sabitini de güncelleyin.
-- ══════════════════════════════════════════════════════════════════════════════

-- ── Veritabanını oluştur ve seç ───────────────────────────────────────────────
CREATE DATABASE IF NOT EXISTS `deneme`
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE `deneme`;

-- ─────────────────────────────────────────────────────────────────────────────

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

-- ── Seed: örnek şehirler ──────────────────────────────────────────────────────
INSERT IGNORE INTO `cities` (`name`) VALUES
    ('İstanbul'), ('Ankara'), ('İzmir'), ('Bursa'), ('Antalya'),
    ('Adana'), ('Konya'), ('Gaziantep'), ('Mersin'), ('Kayseri');

-- ── Seed: araç tipleri ────────────────────────────────────────────────────────
INSERT IGNORE INTO `vehicle_types` (`name`) VALUES
    ('Motosiklet'), ('Bisiklet'), ('Elektrikli Scooter'), ('Otomobil'), ('Minibüs');

-- ── Admin kullanıcısı ─────────────────────────────────────────────────────────
-- Şifre buraya YAZILMAMALIDİR.
-- Admin hesabı oluşturmak için tarayıcıda şu adresi açın:
--   http://localhost/rider/reset-admin.php?action=create&email=admin@example.com&pass=SifreniziYazin
-- Komutu çalıştırınca sayfa hash üretir ve veritabanına kaydeder.

-- ══════════════════════════════════════════════════════════════════════════════
-- MİGRASYON — Mevcut veritabanını güncellemek için
-- ══════════════════════════════════════════════════════════════════════════════
-- Daha önce bu şemayı çalıştırdıysanız ve referral_code kolonu eksikse:
--   ALTER TABLE `applications`
--       ADD COLUMN `referral_code` VARCHAR(32) NOT NULL DEFAULT '' AFTER `message`;
-- ══════════════════════════════════════════════════════════════════════════════
