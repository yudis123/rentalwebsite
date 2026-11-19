-- Database website rental mobil (instalasi baru)
-- Impor: mysql -uroot < database/rentalwebsite.sql
-- Setelah impor, buat akun superadmin pertama:
--   php database/create_admin.php <username> <password>

CREATE DATABASE IF NOT EXISTS rentalwebsite
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE rentalwebsite;

-- Mobil --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS cars (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name          VARCHAR(100) NOT NULL,
  slug          VARCHAR(120) NOT NULL,
  category      VARCHAR(30)  NOT NULL,            -- City Car, MPV, SUV, Premium
  seats         TINYINT UNSIGNED NOT NULL DEFAULT 5,
  transmission  ENUM('Manual','Matic') NOT NULL DEFAULT 'Manual',
  fuel          VARCHAR(20)  NOT NULL DEFAULT 'Bensin',
  price_per_day INT UNSIGNED NOT NULL,            -- Rupiah per hari
  description   VARCHAR(500) NOT NULL DEFAULT '',
  image         VARCHAR(190) NOT NULL,            -- path relatif, mis. assets/uploads/cars/xxx.jpg
  status        ENUM('ready','rented') NOT NULL DEFAULT 'ready',
  sort_order    INT NOT NULL DEFAULT 0,
  created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_cars_slug (slug),
  KEY idx_cars_status (status),
  KEY idx_cars_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User admin ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name          VARCHAR(100) NOT NULL,
  username      VARCHAR(30)  NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role          VARCHAR(20)  NOT NULL DEFAULT 'superadmin',
  is_active     TINYINT(1)   NOT NULL DEFAULT 1,
  last_login_at DATETIME     NULL,
  created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Catatan percobaan login gagal (untuk pembatasan) -------------------------
CREATE TABLE IF NOT EXISTS login_attempts (
  id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  throttle_key CHAR(40) NOT NULL,
  attempted_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY idx_attempts_key (throttle_key, attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Konten situs yang diubah dari admin (hero, footer) -----------------------
-- Kunci yang belum ada di sini memakai nilai bawaan application/config/site.php
CREATE TABLE IF NOT EXISTS settings (
  name  VARCHAR(60) NOT NULL,
  value TEXT NOT NULL,
  PRIMARY KEY (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data contoh (harga hanya ilustrasi, silakan sesuaikan)
INSERT IGNORE INTO cars
  (name, slug, category, seats, transmission, fuel, price_per_day, image, status, sort_order)
VALUES
  ('Daihatsu Ayla',          'daihatsu-ayla',          'City Car', 5, 'Manual', 'Bensin',  275000, 'assets/img/cars/daihatsu-ayla.svg',          'ready',  10),
  ('Honda Brio',             'honda-brio',             'City Car', 5, 'Matic',  'Bensin',  300000, 'assets/img/cars/honda-brio.svg',             'ready',  20),
  ('Daihatsu Xenia',         'daihatsu-xenia',         'MPV',      7, 'Manual', 'Bensin',  325000, 'assets/img/cars/daihatsu-xenia.svg',         'ready',  30),
  ('Toyota Avanza',          'toyota-avanza',          'MPV',      7, 'Matic',  'Bensin',  350000, 'assets/img/cars/toyota-avanza.svg',          'ready',  40),
  ('Mitsubishi Xpander',     'mitsubishi-xpander',     'MPV',      7, 'Matic',  'Bensin',  400000, 'assets/img/cars/mitsubishi-xpander.svg',     'rented', 50),
  ('Toyota Innova Zenix',    'toyota-innova-zenix',    'MPV',      7, 'Matic',  'Hybrid',  650000, 'assets/img/cars/toyota-innova-zenix.svg',    'ready',  60),
  ('Mitsubishi Pajero Sport','mitsubishi-pajero-sport','SUV',      7, 'Matic',  'Diesel', 1100000, 'assets/img/cars/mitsubishi-pajero-sport.svg','ready',  70),
  ('Toyota Fortuner',        'toyota-fortuner',        'SUV',      7, 'Matic',  'Diesel', 1200000, 'assets/img/cars/toyota-fortuner.svg',        'rented', 80),
  ('Toyota Alphard',         'toyota-alphard',         'Premium',  7, 'Matic',  'Bensin', 2500000, 'assets/img/cars/toyota-alphard.svg',         'ready',  90);
