-- MIDDLEWARE DATABASE SCHEMA (The Integration Bridge)
-- Database Name Suggestion: Jabbar23
-- Created for: Unified ISP Architecture (ERPNext + Radius + InvenTree)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

-- --------------------------------------------------------

--
-- 1. TABLE: SYNC_MAPPING
-- Fungsi: Peta penghubung ID antar sistem.
-- "Customer A di ERPNext itu Username apa di Radius?"
--
CREATE TABLE `sync_mapping` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `erp_customer_id` varchar(100) NOT NULL COMMENT 'ID Customer di ERPNext (ex: CUST-2024-001)',
  `radius_username` varchar(100) NOT NULL COMMENT 'Username PPPoE didaloRADIUS',
  `inventory_device_sn` varchar(100) DEFAULT NULL COMMENT 'Serial Number Modem di InvenTree/Rumah',
  `status` enum('ACTIVE','SUSPENDED','TERMINATED') DEFAULT 'ACTIVE',
  `last_synced_at` datetime DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_map` (`erp_customer_id`,`radius_username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 2. TABLE: PARTNERS (RESELLERS)
-- Fungsi: Menyimpan data Mitra/Reseller yang tidak ada di ERPNext standar.
--
CREATE TABLE `partners` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `balance` decimal(15,2) DEFAULT 0.00 COMMENT 'Saldo Deposit Reseller',
  `commission_rate` decimal(5,2) DEFAULT 10.00 COMMENT 'Persentase Komisi (ex: 10%)',
  `erp_supplier_id` varchar(100) DEFAULT NULL COMMENT 'Link ke Vendor di ERPNext (utk pembayaran komisi)',
  `password_hash` varchar(255) NOT NULL COMMENT 'Untuk login Portal Reseller',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 3. TABLE: PARTNER_CUSTOMERS (Anak Buah Reseller)
-- Fungsi: Menandai customer mana milik reseller siapa.
--
CREATE TABLE `partner_customers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `partner_id` bigint(20) UNSIGNED NOT NULL,
  `erp_customer_id` varchar(100) NOT NULL,
  `plan_name` varchar(100) NOT NULL COMMENT 'Paket Internet yang diambil',
  `monthly_price` decimal(15,2) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `partner_idx` (`partner_id`),
  CONSTRAINT `fk_partner` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 4. TABLE: INSTALLATION_JOBS (SPK Teknisi)
-- Fungsi: Data untuk Mobile App Teknisi.
--
CREATE TABLE `installation_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `job_ticket_number` varchar(50) NOT NULL COMMENT 'No SPK dari ERPNext',
  `technician_id` varchar(100) NOT NULL COMMENT 'ID Karyawan di ERPNext',
  `customer_name` varchar(255) NOT NULL,
  `customer_address` text NOT NULL,
  `gps_coordinates` varchar(100) DEFAULT NULL,
  `status` enum('PENDING','ON_PROGRESS','COMPLETED','CANCELED') DEFAULT 'PENDING',
  `scanned_device_sn` varchar(100) DEFAULT NULL COMMENT 'Hasil Scan Barcode Modem',
  `signal_strength` varchar(50) DEFAULT NULL COMMENT 'Bukti foto/angka sinyal (dBm)',
  `completed_at` datetime DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 5. TABLE: INTEGRATION_LOGS (Audit Trail)
-- Fungsi: Merekam "Siapa ngirim apa ke siapa". Penting buat debugging kalau error.
--
CREATE TABLE `integration_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `source_system` enum('ERPNEXT','RADIUS','INVENTREE','MIDDLEWARE') NOT NULL,
  `target_system` enum('ERPNEXT','RADIUS','INVENTREE','MIDDLEWARE') NOT NULL,
  `action` varchar(100) NOT NULL COMMENT 'ex: CREATE_USER, SUSPEND_USER',
  `payload` json DEFAULT NULL COMMENT 'Data yang dikirim',
  `response` text DEFAULT NULL COMMENT 'Balasan error/success',
  `status` enum('SUCCESS','FAILED') NOT NULL,
  `executed_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 6. TABLE: PARTNER_TRANSACTIONS (Mutasi Saldo)
-- Fungsi: Catatan uang masuk/keluar Reseller.
--
CREATE TABLE `partner_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `partner_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('DEPOSIT','PURCHASE','COMMISSION_WITHDRAWAL') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `reference_id` varchar(100) DEFAULT NULL COMMENT 'No Invoice / Bukti Transfer',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_partner_trx` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
