-- Aiven-compatible SQL dump for SPK SAW/MFEP
-- All tables are created WITH primary keys inline to satisfy sql_require_primary_key=ON
-- Generated: 2025-10-18

-- Create database (optional)
CREATE DATABASE IF NOT EXISTS `spksaw` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `spksaw`;

SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;

-- Drop existing tables to allow clean import
DROP TABLE IF EXISTS `tbl_nilai_kriteria`;
DROP TABLE IF EXISTS `tbl_log_bobot`;
DROP TABLE IF EXISTS `tbl_himpunan`;
DROP TABLE IF EXISTS `tbl_hasil_mfep`;
DROP TABLE IF EXISTS `tbl_hasil_saw`;
DROP TABLE IF EXISTS `tbl_klasifikasi`;
DROP TABLE IF EXISTS `backup_tbl_klasifikasi`;
DROP TABLE IF EXISTS `backup_tbl_hasil_saw`;
DROP TABLE IF EXISTS `backup_data_warga`;
DROP TABLE IF EXISTS `data_warga`;
DROP TABLE IF EXISTS `tbl_kriteria`;
DROP TABLE IF EXISTS `modul`;
DROP TABLE IF EXISTS `admin`;

-- Table: admin
CREATE TABLE `admin` (
  `id_admin` int(3) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL DEFAULT 'administrator',
  `password` varchar(100) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `level` varchar(50) NOT NULL DEFAULT 'admin',
  `alamat` text NOT NULL,
  `no_telp` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table: backup_data_warga
CREATE TABLE `backup_data_warga` (
  `id_warga` int(11) NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `jumlah_lansia` int(11) DEFAULT 0,
  `jumlah_disabilitas_berat` int(11) DEFAULT 0,
  `jumlah_anak_sd` int(11) DEFAULT 0,
  `jumlah_anak_smp` int(11) DEFAULT 0,
  `jumlah_anak_sma` int(11) DEFAULT 0,
  `jumlah_balita` int(11) DEFAULT 0,
  `jumlah_ibu_hamil` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_warga`),
  UNIQUE KEY `idx_nama_lengkap` (`nama_lengkap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel Data Warga untuk PKH';

-- Table: backup_tbl_hasil_saw
CREATE TABLE `backup_tbl_hasil_saw` (
  `id_hasil` int(11) NOT NULL AUTO_INCREMENT,
  `id_warga` int(11) NOT NULL,
  `nama_warga` varchar(100) NOT NULL,
  `C1_norm` decimal(5,4) DEFAULT 0.0000,
  `C2_norm` decimal(5,4) DEFAULT 0.0000,
  `C3_norm` decimal(5,4) DEFAULT 0.0000,
  `C4_norm` decimal(5,4) DEFAULT 0.0000,
  `C5_norm` decimal(5,4) DEFAULT 0.0000,
  `C6_norm` decimal(5,4) DEFAULT 0.0000,
  `C7_norm` decimal(5,4) DEFAULT 0.0000,
  `C8_norm` decimal(5,4) DEFAULT 0.0000,
  `skor_akhir` decimal(6,4) DEFAULT 0.0000,
  `ranking` int(11) DEFAULT 0,
  `rekomendasi` enum('Ya','Tidak') DEFAULT 'Tidak',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_hasil`),
  KEY `idx_id_warga` (`id_warga`),
  KEY `idx_ranking` (`ranking`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel Hasil Analisis SAW';

-- Table: backup_tbl_klasifikasi
CREATE TABLE `backup_tbl_klasifikasi` (
  `id_klasifikasi` int(11) NOT NULL AUTO_INCREMENT,
  `id_warga` int(11) NOT NULL,
  `C1` int(11) DEFAULT 0 COMMENT 'Jumlah Lansia',
  `C2` int(11) DEFAULT 0 COMMENT 'Jumlah Disabilitas Berat',
  `C3` int(11) DEFAULT 0 COMMENT 'Jumlah Anak SD',
  `C4` int(11) DEFAULT 0 COMMENT 'Jumlah Anak SMP',
  `C5` int(11) DEFAULT 0 COMMENT 'Jumlah Anak SMA',
  `C6` int(11) DEFAULT 0 COMMENT 'Jumlah Balita',
  `C7` int(11) DEFAULT 0 COMMENT 'Jumlah Ibu Hamil',
  `C8` int(11) DEFAULT 0 COMMENT 'Reserved for future use',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_klasifikasi`),
  KEY `idx_id_warga` (`id_warga`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel Klasifikasi Data Warga untuk PKH';

-- Table: data_warga
CREATE TABLE `data_warga` (
  `id_warga` int(11) NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `jumlah_lansia` int(11) DEFAULT 0,
  `jumlah_disabilitas_berat` int(11) DEFAULT 0,
  `jumlah_anak_sd` int(11) DEFAULT 0,
  `jumlah_anak_smp` int(11) DEFAULT 0,
  `jumlah_anak_sma` int(11) DEFAULT 0,
  `jumlah_balita` int(11) DEFAULT 0,
  `jumlah_ibu_hamil` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_warga`),
  UNIQUE KEY `idx_nama_lengkap` (`nama_lengkap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel Data Warga untuk PKH';

-- Table: modul
CREATE TABLE `modul` (
  `id_modul` int(5) NOT NULL AUTO_INCREMENT,
  `nama_modul` varchar(50) NOT NULL,
  `link` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'Data',
  `urutan` int(5) NOT NULL DEFAULT 1,
  `aktif` enum('Y','N') NOT NULL DEFAULT 'Y',
  `status` varchar(20) NOT NULL DEFAULT 'admin',
  PRIMARY KEY (`id_modul`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table: tbl_hasil_mfep
CREATE TABLE `tbl_hasil_mfep` (
  `id_hasil` int(11) NOT NULL AUTO_INCREMENT,
  `id_warga` int(11) NOT NULL,
  `nama_warga` varchar(100) NOT NULL,
  `C1` int(11) DEFAULT 0,
  `C2` int(11) DEFAULT 0,
  `C3` int(11) DEFAULT 0,
  `C4` int(11) DEFAULT 0,
  `C5` int(11) DEFAULT 0,
  `C6` int(11) DEFAULT 0,
  `C7` int(11) DEFAULT 0,
  `C8` int(11) DEFAULT 0,
  `E1` decimal(5,4) DEFAULT 0.0000,
  `E2` decimal(5,4) DEFAULT 0.0000,
  `E3` decimal(5,4) DEFAULT 0.0000,
  `E4` decimal(5,4) DEFAULT 0.0000,
  `E5` decimal(5,4) DEFAULT 0.0000,
  `E6` decimal(5,4) DEFAULT 0.0000,
  `E7` decimal(5,4) DEFAULT 0.0000,
  `E8` decimal(5,4) DEFAULT 0.0000,
  `WE1` decimal(6,4) DEFAULT 0.0000,
  `WE2` decimal(6,4) DEFAULT 0.0000,
  `WE3` decimal(6,4) DEFAULT 0.0000,
  `WE4` decimal(6,4) DEFAULT 0.0000,
  `WE5` decimal(6,4) DEFAULT 0.0000,
  `WE6` decimal(6,4) DEFAULT 0.0000,
  `WE7` decimal(6,4) DEFAULT 0.0000,
  `WE8` decimal(6,4) DEFAULT 0.0000,
  `total_we` decimal(6,4) DEFAULT 0.0000,
  `nilai_mfep` decimal(6,4) DEFAULT 0.0000,
  `ranking` int(11) DEFAULT 0,
  `rekomendasi` enum('Ya','Tidak') DEFAULT 'Tidak',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_hasil`),
  KEY `idx_id_warga` (`id_warga`),
  KEY `idx_ranking` (`ranking`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel Hasil Perhitungan MFEP';

-- Table: tbl_hasil_saw
CREATE TABLE `tbl_hasil_saw` (
  `id_hasil` int(11) NOT NULL AUTO_INCREMENT,
  `id_warga` int(11) NOT NULL,
  `nama_warga` varchar(100) NOT NULL,
  `C1_norm` decimal(5,4) DEFAULT 0.0000,
  `C2_norm` decimal(5,4) DEFAULT 0.0000,
  `C3_norm` decimal(5,4) DEFAULT 0.0000,
  `C4_norm` decimal(5,4) DEFAULT 0.0000,
  `C5_norm` decimal(5,4) DEFAULT 0.0000,
  `C6_norm` decimal(5,4) DEFAULT 0.0000,
  `C7_norm` decimal(5,4) DEFAULT 0.0000,
  `C8_norm` decimal(5,4) DEFAULT 0.0000,
  `skor_akhir` decimal(6,4) DEFAULT 0.0000,
  `ranking` int(11) DEFAULT 0,
  `rekomendasi` enum('Ya','Tidak') DEFAULT 'Tidak',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_hasil`),
  KEY `idx_id_warga` (`id_warga`),
  KEY `idx_ranking` (`ranking`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel Hasil Analisis SAW';

-- Table: tbl_himpunan
CREATE TABLE `tbl_himpunan` (
  `id_himpunan` int(11) NOT NULL AUTO_INCREMENT,
  `id_kriteria` int(11) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `nilai` int(11) NOT NULL,
  PRIMARY KEY (`id_himpunan`),
  KEY `idx_id_kriteria` (`id_kriteria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel Himpunan Nilai Sub Kriteria';

-- Table: tbl_klasifikasi
CREATE TABLE `tbl_klasifikasi` (
  `id_klasifikasi` int(11) NOT NULL AUTO_INCREMENT,
  `id_warga` int(11) NOT NULL,
  `C1` int(11) DEFAULT 0 COMMENT 'Jumlah Lansia',
  `C2` int(11) DEFAULT 0 COMMENT 'Jumlah Disabilitas Berat',
  `C3` int(11) DEFAULT 0 COMMENT 'Jumlah Anak SD',
  `C4` int(11) DEFAULT 0 COMMENT 'Jumlah Anak SMP',
  `C5` int(11) DEFAULT 0 COMMENT 'Jumlah Anak SMA',
  `C6` int(11) DEFAULT 0 COMMENT 'Jumlah Balita',
  `C7` int(11) DEFAULT 0 COMMENT 'Jumlah Ibu Hamil',
  `C8` int(11) DEFAULT 0 COMMENT 'Reserved for future use',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_klasifikasi`),
  KEY `idx_id_warga` (`id_warga`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel Klasifikasi Data Warga untuk PKH';

-- Table: tbl_kriteria
CREATE TABLE `tbl_kriteria` (
  `id_kriteria` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kriteria` varchar(100) NOT NULL,
  `bobot` decimal(3,2) NOT NULL DEFAULT 0.00,
  `jenis` enum('benefit','cost') NOT NULL DEFAULT 'benefit',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `kode_kriteria` varchar(10) NOT NULL,
  `nilai` decimal(3,2) DEFAULT 0.50,
  PRIMARY KEY (`id_kriteria`),
  UNIQUE KEY `idx_kode_kriteria` (`kode_kriteria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel Data Kriteria PKH (formerly Pembobotan)';

-- Table: tbl_log_bobot
CREATE TABLE `tbl_log_bobot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_kriteria` int(11) DEFAULT NULL,
  `kode_kriteria` varchar(10) DEFAULT NULL,
  `old_nilai` decimal(10,4) DEFAULT NULL,
  `new_nilai` decimal(10,4) DEFAULT NULL,
  `jenis` varchar(10) DEFAULT NULL,
  `aksi` varchar(20) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_id_kriteria` (`id_kriteria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table: tbl_nilai_kriteria
CREATE TABLE `tbl_nilai_kriteria` (
  `id_nilai` int(11) NOT NULL AUTO_INCREMENT,
  `id_kriteria` int(5) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `nilai` int(2) NOT NULL,
  `range_min` int(3) DEFAULT NULL,
  `range_max` int(3) DEFAULT NULL,
  PRIMARY KEY (`id_nilai`),
  KEY `idx_id_kriteria` (`id_kriteria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel Data Sub Kriteria PKH (formerly tbl_kriteria)';

SET FOREIGN_KEY_CHECKS = 1;

-- =====================
-- Seed data
-- =====================
INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_lengkap`, `level`, `alamat`, `no_telp`, `email`, `created_at`, `updated_at`) VALUES
(1, 'administrator', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'Admin PKH System', 'admin', 'Kantor Dinas Sosial', '085228482669', 'admin@pkh.go.id', '2025-09-11 05:27:02', '2025-09-11 05:27:02'),
(2, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Admin PKH FINALL', 'admin', 'Kantor Kelurahan', '085228482669', 'operator@pkh.go.id', '2025-09-11 05:27:02', '2025-09-18 19:17:59');

INSERT INTO `backup_data_warga` (`id_warga`, `nama_lengkap`, `alamat`, `jumlah_lansia`, `jumlah_disabilitas_berat`, `jumlah_anak_sd`, `jumlah_anak_smp`, `jumlah_anak_sma`, `jumlah_balita`, `jumlah_ibu_hamil`, `created_at`, `updated_at`) VALUES
(1, 'Siti Aminah', 'Jl. Merdeka No. 123', 1, 0, 2, 1, 0, 1, 0, '2025-09-11 06:11:16', '2025-09-11 06:11:16'),
(2, 'Budi Santoso', 'Jl. Sudirman No. 45', 0, 1, 1, 0, 1, 2, 1, '2025-09-11 06:11:16', '2025-09-18 13:33:51'),
(3, 'Rina Wati', 'Jl. Ahmad Yani No. 67', 2, 0, 0, 2, 1, 0, 0, '2025-09-11 06:11:16', '2025-09-11 06:11:16'),
(5, 'Dewi Sartika', 'Jl. Kartini No. 12', 0, 0, 1, 2, 1, 1, 0, '2025-09-11 06:11:16', '2025-09-11 06:11:16'),
(11, 'dzakyy', 'gbm', 0, 0, 2, 0, 0, 0, 1, '2025-09-18 20:14:49', '2025-09-19 02:06:33');

INSERT INTO `backup_tbl_hasil_saw` (`id_hasil`, `id_warga`, `nama_warga`, `C1_norm`, `C2_norm`, `C3_norm`, `C4_norm`, `C5_norm`, `C6_norm`, `C7_norm`, `C8_norm`, `skor_akhir`, `ranking`, `rekomendasi`, `created_at`, `updated_at`) VALUES
(102, 2, 'Budi Santoso', 0.0000, 1.0000, 0.5000, 0.0000, 0.5000, 1.0000, 1.0000, 0.0000, 2.0000, 1, 'Ya', '2025-09-18 20:17:05', '2025-09-18 20:17:05'),
(103, 5, 'Dewi Sartika', 0.0000, 0.0000, 0.5000, 1.0000, 0.5000, 0.5000, 0.0000, 0.0000, 1.2500, 3, 'Tidak', '2025-09-18 20:17:05', '2025-09-18 20:17:05'),
(104, 11, 'dzaky', 0.0000, 0.0000, 1.0000, 0.0000, 0.0000, 0.0000, 1.0000, 0.0000, 1.0000, 5, 'Tidak', '2025-09-18 20:17:05', '2025-09-18 20:17:05'),
(106, 3, 'Rina Wati', 1.0000, 0.0000, 0.0000, 1.0000, 0.5000, 0.0000, 0.0000, 0.0000, 0.8000, 6, 'Tidak', '2025-09-18 20:17:05', '2025-09-18 20:17:05'),
(107, 1, 'Siti Aminah', 0.5000, 0.0000, 1.0000, 0.5000, 0.0000, 0.5000, 0.0000, 0.0000, 1.0250, 4, 'Tidak', '2025-09-18 20:17:05', '2025-09-18 20:17:05');

INSERT INTO `backup_tbl_klasifikasi` (`id_klasifikasi`, `id_warga`, `C1`, `C2`, `C3`, `C4`, `C5`, `C6`, `C7`, `C8`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0, 2, 1, 0, 1, 0, 0, '2025-09-11 06:11:16', '2025-09-11 06:11:16'),
(2, 2, 0, 1, 1, 0, 1, 2, 1, 0, '2025-09-11 06:11:16', '2025-09-14 03:07:28'),
(3, 3, 2, 0, 0, 2, 1, 0, 0, 0, '2025-09-11 06:11:16', '2025-09-11 06:11:16'),
(5, 5, 0, 0, 1, 2, 1, 1, 0, 0, '2025-09-11 06:11:16', '2025-09-11 06:11:16'),
(11, 11, 0, 0, 2, 0, 0, 0, 1, 0, '2025-09-18 20:14:49', '2025-09-19 02:06:34');

INSERT INTO `data_warga` (`id_warga`, `nama_lengkap`, `alamat`, `jumlah_lansia`, `jumlah_disabilitas_berat`, `jumlah_anak_sd`, `jumlah_anak_smp`, `jumlah_anak_sma`, `jumlah_balita`, `jumlah_ibu_hamil`, `created_at`, `updated_at`) VALUES
(1, 'Siti Aminah', 'Jl. Merdeka No. 123', 1, 0, 2, 1, 0, 1, 0, '2025-09-11 06:11:16', '2025-09-11 06:11:16'),
(3, 'Rina Wati', 'Jl. Ahmad Yani No. 67', 2, 0, 0, 2, 1, 0, 0, '2025-09-11 06:11:16', '2025-09-11 06:11:16'),
(12, 'Fadhli Rajwaa Rahmana', 'dfsf', 2, 2, 0, 3, 0, 0, 0, '2025-10-17 09:54:28', '2025-10-17 09:54:28');

INSERT INTO `modul` (`id_modul`, `nama_modul`, `link`, `type`, `urutan`, `aktif`, `status`) VALUES
(1, 'Data Warga', '?module=warga', 'Data', 1, 'Y', 'admin'),
(4, 'Laporan Hasil Perhitungan', '?module=laporan&act=analisa', 'Report', 4, 'Y', 'admin'),
(5, 'Perankingan', '?module=perankingan', 'Report', 5, 'Y', 'admin'),
(7, 'Data Sub Kriteria', '?module=kriteria', 'Data', 3, 'Y', 'admin'),
(8, 'Data Kriteria', '?module=pembobotan', 'Data', 2, 'Y', 'admin');

INSERT INTO `tbl_hasil_mfep` (`id_hasil`, `id_warga`, `nama_warga`, `C1`, `C2`, `C3`, `C4`, `C5`, `C6`, `C7`, `C8`, `E1`, `E2`, `E3`, `E4`, `E5`, `E6`, `E7`, `E8`, `WE1`, `WE2`, `WE3`, `WE4`, `WE5`, `WE6`, `WE7`, `WE8`, `total_we`, `nilai_mfep`, `ranking`, `rekomendasi`, `created_at`, `updated_at`) VALUES
(1, 12, 'Fadhli Rajwaa Rahmana', 2, 2, 0, 3, 0, 0, 0, 0, 1.0000, 1.0000, 0.0000, 1.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.6500, 0.0500, 0.0000, 0.0500, 0.0000, 0.0000, 0.0000, 0.0000, 0.7500, -2.1000, 1, 'Ya', '2025-10-17 10:13:57', '2025-10-17 10:13:57'),
(2, 1, 'Siti Aminah', 1, 0, 2, 1, 0, 1, 0, 0, 0.5000, 0.0000, 1.0000, 0.3333, 0.0000, 1.0000, 0.0000, 0.0000, 0.3250, 0.0000, 0.0500, 0.0167, 0.0000, 0.0500, 0.0000, 0.0000, 0.4417, -2.2667, 2, 'Tidak', '2025-10-17 10:13:57', '2025-10-17 10:13:57'),
(3, 3, 'Rina Wati', 2, 0, 0, 2, 1, 0, 0, 0, 1.0000, 0.0000, 0.0000, 0.6667, 1.0000, 0.0000, 0.0000, 0.0000, 0.6500, 0.0000, 0.0000, 0.0333, 0.0500, 0.0000, 0.0000, 0.0000, 0.7333, -2.4333, 3, 'Tidak', '2025-10-17 10:13:57', '2025-10-17 10:13:57');

INSERT INTO `tbl_hasil_saw` (`id_hasil`, `id_warga`, `nama_warga`, `C1_norm`, `C2_norm`, `C3_norm`, `C4_norm`, `C5_norm`, `C6_norm`, `C7_norm`, `C8_norm`, `skor_akhir`, `ranking`, `rekomendasi`, `created_at`, `updated_at`) VALUES
(106, 3, 'Rina Wati', 1.0000, 0.0000, 0.0000, 1.0000, 0.5000, 0.0000, 0.0000, 0.0000, 0.8000, 6, 'Tidak', '2025-09-18 20:17:05', '2025-09-18 20:17:05'),
(107, 1, 'Siti Aminah', 0.5000, 0.0000, 1.0000, 0.5000, 0.0000, 0.5000, 0.0000, 0.0000, 1.0250, 4, 'Tidak', '2025-09-18 20:17:05', '2025-09-18 20:17:05');

INSERT INTO `tbl_himpunan` (`id_himpunan`, `id_kriteria`, `keterangan`, `nilai`) VALUES
(1, 1, 'Sangat Baik', 6),
(2, 1, 'Baik', 4),
(3, 2, 'ppp', 4),
(4, 1, 'Cukup', 3);

INSERT INTO `tbl_klasifikasi` (`id_klasifikasi`, `id_warga`, `C1`, `C2`, `C3`, `C4`, `C5`, `C6`, `C7`, `C8`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0, 2, 1, 0, 1, 0, 0, '2025-09-11 06:11:16', '2025-09-11 06:11:16'),
(3, 3, 2, 0, 0, 2, 1, 0, 0, 0, '2025-09-11 06:11:16', '2025-09-11 06:11:16'),
(12, 12, 2, 2, 0, 3, 0, 0, 0, 0, '2025-10-17 09:54:28', '2025-10-17 09:54:28');

INSERT INTO `tbl_kriteria` (`id_kriteria`, `nama_kriteria`, `bobot`, `jenis`, `keterangan`, `created_at`, `updated_at`, `kode_kriteria`, `nilai`) VALUES
(1, 'Jumlah Lansia', 0.15, 'benefit', 'Jumlah lansia dalam keluarga', '2025-09-11 06:11:16', '2025-10-17 10:13:50', 'C1', 0.65),
(2, 'Jumlah Disabilitas Berat', 0.20, 'benefit', 'Jumlah anggota keluarga dengan disabilitas berat', '2025-09-11 06:11:16', '2025-10-17 09:53:13', 'C2', 0.05),
(3, 'Jumlah Anak SD', 0.15, 'benefit', 'Jumlah anak usia sekolah dasar', '2025-09-11 06:11:16', '2025-10-17 09:53:20', 'C3', 0.05),
(4, 'Jumlah Anak SMP', 0.10, 'benefit', 'Jumlah anak usia sekolah menengah pertama', '2025-09-11 06:11:16', '2025-10-17 09:53:27', 'C4', 0.05),
(5, 'Jumlah Anak SMA', 0.10, 'benefit', 'Jumlah anak usia sekolah menengah atas', '2025-09-11 06:11:16', '2025-10-17 09:53:35', 'C5', 0.05),
(6, 'Jumlah Balita', 0.15, 'benefit', 'Jumlah balita dalam keluargaa', '2025-09-11 06:11:16', '2025-10-17 09:53:42', 'C6', 0.05),
(7, 'Jumlah Ibu Hamil', 0.15, 'benefit', 'Jumlah ibu hamil dalam keluarga', '2025-09-11 06:11:16', '2025-10-17 09:53:47', 'C7', 0.05),
(8, 'Reserved', 0.00, 'benefit', 'Kriteria cadangan untuk pengembangan', '2025-09-11 06:11:16', '2025-10-17 09:53:51', 'C8', 0.05);

INSERT INTO `tbl_log_bobot` (`id`, `id_kriteria`, `kode_kriteria`, `old_nilai`, `new_nilai`, `jenis`, `aksi`, `username`, `created_at`) VALUES
(4, 1, 'C1', 0.0600, 0.0500, 'Benefit', 'update', 'system', '2025-09-14 03:07:40'),
(5, 6, 'C6', 0.5000, 0.5000, 'Benefit', 'update', 'system', '2025-09-21 09:27:10'),
(6, 2, 'C2', 0.5000, 0.1000, 'Benefit', 'update', 'system', '2025-10-17 09:53:03'),
(7, 2, 'C2', 0.1000, 0.0500, 'Benefit', 'update', 'system', '2025-10-17 09:53:13'),
(8, 3, 'C3', 0.5000, 0.0500, 'Benefit', 'update', 'system', '2025-10-17 09:53:20'),
(9, 4, 'C4', 0.5000, 0.0500, 'Benefit', 'update', 'system', '2025-10-17 09:53:27'),
(10, 5, 'C5', 0.5000, 0.0500, 'Benefit', 'update', 'system', '2025-10-17 09:53:35'),
(11, 6, 'C6', 0.5000, 0.0500, 'Benefit', 'update', 'system', '2025-10-17 09:53:42'),
(12, 7, 'C7', 0.5000, 0.0500, 'Benefit', 'update', 'system', '2025-10-17 09:53:47'),
(13, 8, 'C8', 0.5000, 0.0500, 'Benefit', 'update', 'system', '2025-10-17 09:53:51'),
(14, NULL, 'C9', 0.0000, 0.1000, 'Benefit', 'insert', 'system', '2025-10-17 09:54:50'),
(15, NULL, 'C9', 0.1000, 0.0000, 'benefit', 'delete', 'system', '2025-10-17 09:55:11'),
(16, 1, 'C1', 0.0500, 0.2000, 'Benefit', 'update', 'system', '2025-10-17 10:13:19'),
(17, 1, 'C1', 0.2000, 0.9000, 'Benefit', 'update', 'system', '2025-10-17 10:13:29'),
(18, 1, 'C1', 0.9000, 0.7000, 'Benefit', 'update', 'system', '2025-10-17 10:13:43'),
(19, 1, 'C1', 0.7000, 0.6500, 'Benefit', 'update', 'system', '2025-10-17 10:13:50');

INSERT INTO `tbl_nilai_kriteria` (`id_nilai`, `id_kriteria`, `keterangan`, `nilai`, `range_min`, `range_max`) VALUES
(1, 1, 'Tidak Ada', 1, 0, 0),
(2, 1, 'Sedikit (1-2)', 3, 1, 2),
(3, 1, 'Banyak (≥3)', 5, 3, 10),
(4, 2, 'Tidak Ada', 1, 0, 0),
(5, 2, 'Ada (1-2)', 4, 1, 2),
(6, 2, 'Banyak (≥3)', 5, 3, 10),
(7, 3, 'Tidak Ada', 1, 0, 0),
(8, 3, 'Sedikit (1-2)', 3, 1, 2),
(9, 3, 'Banyak (≥3)', 5, 3, 10),
(10, 4, 'Tidak Ada', 1, 0, 0),
(11, 4, 'Sedikit (1-2)', 3, 1, 2),
(12, 4, 'Banyak (≥3)', 5, 3, 10),
(13, 5, 'Tidak Ada', 1, 0, 0),
(14, 5, 'Sedikit (1-2)', 3, 1, 2),
(15, 5, 'Banyak (≥3)', 5, 3, 10),
(16, 6, 'Tidak Ada', 1, 0, 0),
(17, 6, 'Sedikit (1-2)', 3, 1, 2),
(18, 6, 'Banyak (≥3)', 5, 3, 10),
(19, 7, 'Tidak Ada', 1, 0, 0),
(20, 7, 'Ada (1-2)', 4, 1, 2),
(21, 7, 'Banyak (≥3)', 5, 3, 10);

-- Add foreign key constraints after data is present
SET FOREIGN_KEY_CHECKS = 0;
ALTER TABLE `tbl_hasil_mfep`
  ADD CONSTRAINT `fk_hasil_mfep_warga` FOREIGN KEY (`id_warga`) REFERENCES `data_warga` (`id_warga`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `tbl_hasil_saw`
  ADD CONSTRAINT `fk_hasil_saw_warga` FOREIGN KEY (`id_warga`) REFERENCES `data_warga` (`id_warga`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `tbl_himpunan`
  ADD CONSTRAINT `fk_himpunan_kriteria` FOREIGN KEY (`id_kriteria`) REFERENCES `tbl_kriteria` (`id_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `tbl_klasifikasi`
  ADD CONSTRAINT `fk_klasifikasi_warga` FOREIGN KEY (`id_warga`) REFERENCES `data_warga` (`id_warga`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `tbl_log_bobot`
  ADD CONSTRAINT `fk_log_bobot_kriteria` FOREIGN KEY (`id_kriteria`) REFERENCES `tbl_kriteria` (`id_kriteria`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `tbl_nilai_kriteria`
  ADD CONSTRAINT `fk_nilai_kriteria` FOREIGN KEY (`id_kriteria`) REFERENCES `tbl_kriteria` (`id_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS = 1;

-- Done
