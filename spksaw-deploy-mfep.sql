-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2025 at 03:00 PM
-- Server version: 10.4.32-MariaDB-log
-- PHP Version: 8.2.12
-- 
-- VERSION: Complete deployment with MFEP (Multi Factor Evaluation Process)
-- FEATURES: All tables with proper relational integrity + MFEP algorithm

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spksaw`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `level` enum('admin','user') NOT NULL DEFAULT 'admin',
  `alamat` text DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_lengkap`, `level`, `alamat`, `no_telp`, `email`, `created_at`, `updated_at`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator PKH', 'admin', 'Jl. Dinas Sosial No. 1', '08123456789', 'admin@pkh.go.id', '2025-09-01 00:00:00', '2025-10-17 08:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `data_warga`
-- UPDATED: Merged with klasifikasi - input kriteria langsung di sini
--

CREATE TABLE `data_warga` (
  `id_warga` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  
  -- Kriteria PKH (input langsung)
  `jumlah_lansia` int(11) DEFAULT 0 COMMENT 'C1: Jumlah Lansia (≥60 tahun)',
  `jumlah_disabilitas_berat` int(11) DEFAULT 0 COMMENT 'C2: Jumlah Penyandang Disabilitas Berat',
  `jumlah_anak_sd` int(11) DEFAULT 0 COMMENT 'C3: Jumlah Anak Usia SD (7-12 tahun)',
  `jumlah_anak_smp` int(11) DEFAULT 0 COMMENT 'C4: Jumlah Anak Usia SMP (13-15 tahun)',
  `jumlah_anak_sma` int(11) DEFAULT 0 COMMENT 'C5: Jumlah Anak Usia SMA (16-18 tahun)',
  `jumlah_balita` int(11) DEFAULT 0 COMMENT 'C6: Jumlah Balita (0-6 tahun)',
  `jumlah_ibu_hamil` int(11) DEFAULT 0 COMMENT 'C7: Jumlah Ibu Hamil/Menyusui',
  
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel Data Warga PKH dengan Kriteria Terintegrasi';

--
-- Dumping data for table `data_warga`
--

INSERT INTO `data_warga` (`id_warga`, `nama_lengkap`, `alamat`, `jumlah_lansia`, `jumlah_disabilitas_berat`, `jumlah_anak_sd`, `jumlah_anak_smp`, `jumlah_anak_sma`, `jumlah_balita`, `jumlah_ibu_hamil`, `created_at`, `updated_at`) VALUES
(1, 'Siti Aminah', 'Jl. Melati No. 15, RT 02/RW 05', 1, 0, 2, 1, 0, 1, 0, '2025-09-11 06:00:00', '2025-10-17 08:00:00'),
(2, 'Budi Santoso', 'Jl. Mawar No. 23, RT 03/RW 02', 0, 1, 1, 0, 1, 2, 1, '2025-09-11 06:05:00', '2025-10-17 08:00:00'),
(3, 'Dewi Lestari', 'Jl. Anggrek No. 8, RT 01/RW 03', 2, 0, 0, 2, 1, 0, 0, '2025-09-11 06:10:00', '2025-10-17 08:00:00'),
(4, 'Ahmad Yani', 'Jl. Kenanga No. 12, RT 04/RW 01', 0, 0, 3, 1, 0, 1, 1, '2025-09-11 06:15:00', '2025-10-17 08:00:00'),
(5, 'Ratna Sari', 'Jl. Dahlia No. 5, RT 02/RW 04', 0, 0, 1, 2, 1, 1, 0, '2025-09-11 06:20:00', '2025-10-17 08:00:00'),
(6, 'Hendra Wijaya', 'Jl. Teratai No. 19, RT 03/RW 05', 1, 1, 2, 0, 0, 2, 0, '2025-09-11 06:25:00', '2025-10-17 08:00:00'),
(7, 'Linda Kusuma', 'Jl. Flamboyan No. 7, RT 01/RW 02', 0, 0, 0, 1, 2, 1, 1, '2025-09-11 06:30:00', '2025-10-17 08:00:00'),
(8, 'Eko Prasetyo', 'Jl. Cempaka No. 14, RT 04/RW 03', 2, 0, 1, 1, 1, 0, 0, '2025-09-11 06:35:00', '2025-10-17 08:00:00'),
(9, 'Maya Indah', 'Jl. Bougenville No. 11, RT 02/RW 01', 0, 1, 2, 2, 0, 1, 0, '2025-09-11 06:40:00', '2025-10-17 08:00:00'),
(10, 'Bambang Sutrisno', 'Jl. Kamboja No. 6, RT 03/RW 04', 2, 0, 1, 0, 2, 0, 1, '2025-09-12 08:13:32', '2025-10-17 08:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `modul`
-- UPDATED: Menu labels untuk MFEP
--

CREATE TABLE `modul` (
  `id_modul` int(5) NOT NULL,
  `nama_modul` varchar(50) NOT NULL,
  `link` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'Data',
  `urutan` int(5) NOT NULL DEFAULT 1,
  `aktif` enum('Y','N') NOT NULL DEFAULT 'Y',
  `status` varchar(20) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modul`
-- NOTE: Data Klasifikasi dihapus, nama menu disesuaikan
--

INSERT INTO `modul` (`id_modul`, `nama_modul`, `link`, `type`, `urutan`, `aktif`, `status`) VALUES
(1, 'Data Warga', '?module=warga', 'Data', 1, 'Y', 'admin'),
(7, 'Data Sub Kriteria', '?module=kriteria', 'Data', 2, 'Y', 'admin'),
(8, 'Data Kriteria', '?module=pembobotan', 'Data', 3, 'Y', 'admin'),
(4, 'Laporan Hasil Perhitungan', '?module=laporan&act=analisa', 'Report', 4, 'Y', 'admin'),
(5, 'Perankingan', '?module=perankingan', 'Report', 5, 'Y', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kriteria`
-- UPDATED: Ini sekarang jadi "Data Kriteria" (formerly Pembobotan)
--

CREATE TABLE `tbl_kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `kode_kriteria` varchar(10) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `nilai` decimal(5,4) NOT NULL DEFAULT 0.0000 COMMENT 'Bobot kriteria (0-1, total=1)',
  `jenis` enum('Benefit','Cost') NOT NULL DEFAULT 'Benefit',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Data Kriteria PKH dengan Bobot';

--
-- Dumping data for table `tbl_kriteria`
--

INSERT INTO `tbl_kriteria` (`id_kriteria`, `kode_kriteria`, `keterangan`, `nilai`, `jenis`, `created_at`, `updated_at`) VALUES
(1, 'C1', 'Jumlah Lansia (≥60 tahun)', 0.1500, 'Benefit', '2025-09-10 00:00:00', '2025-10-17 08:00:00'),
(2, 'C2', 'Jumlah Penyandang Disabilitas Berat', 0.1500, 'Benefit', '2025-09-10 00:00:00', '2025-10-17 08:00:00'),
(3, 'C3', 'Jumlah Anak Usia SD (7-12 tahun)', 0.1500, 'Benefit', '2025-09-10 00:00:00', '2025-10-17 08:00:00'),
(4, 'C4', 'Jumlah Anak Usia SMP (13-15 tahun)', 0.1500, 'Benefit', '2025-09-10 00:00:00', '2025-10-17 08:00:00'),
(5, 'C5', 'Jumlah Anak Usia SMA (16-18 tahun)', 0.1500, 'Benefit', '2025-09-10 00:00:00', '2025-10-17 08:00:00'),
(6, 'C6', 'Jumlah Balita (0-6 tahun)', 0.1500, 'Benefit', '2025-09-10 00:00:00', '2025-10-17 08:00:00'),
(7, 'C7', 'Jumlah Ibu Hamil/Menyusui', 0.1500, 'Benefit', '2025-09-10 00:00:00', '2025-10-17 08:00:00'),
(8, 'C8', 'Kriteria Tambahan (Reserved)', 0.0500, 'Benefit', '2025-09-10 00:00:00', '2025-10-17 08:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hasil_mfep`
-- NEW: Hasil perhitungan MFEP (menggantikan tbl_hasil_saw)
--

CREATE TABLE `tbl_hasil_mfep` (
  `id_hasil` int(11) NOT NULL AUTO_INCREMENT,
  `id_warga` int(11) NOT NULL,
  `nama_warga` varchar(100) NOT NULL,
  
  -- Matriks Keputusan (X)
  `C1` int(11) DEFAULT 0,
  `C2` int(11) DEFAULT 0,
  `C3` int(11) DEFAULT 0,
  `C4` int(11) DEFAULT 0,
  `C5` int(11) DEFAULT 0,
  `C6` int(11) DEFAULT 0,
  `C7` int(11) DEFAULT 0,
  `C8` int(11) DEFAULT 0,
  
  -- Nilai Evaluasi Factor (E)
  `E1` decimal(5,4) DEFAULT 0.0000,
  `E2` decimal(5,4) DEFAULT 0.0000,
  `E3` decimal(5,4) DEFAULT 0.0000,
  `E4` decimal(5,4) DEFAULT 0.0000,
  `E5` decimal(5,4) DEFAULT 0.0000,
  `E6` decimal(5,4) DEFAULT 0.0000,
  `E7` decimal(5,4) DEFAULT 0.0000,
  `E8` decimal(5,4) DEFAULT 0.0000,
  
  -- Nilai Bobot Evaluasi (WE)
  `WE1` decimal(6,4) DEFAULT 0.0000,
  `WE2` decimal(6,4) DEFAULT 0.0000,
  `WE3` decimal(6,4) DEFAULT 0.0000,
  `WE4` decimal(6,4) DEFAULT 0.0000,
  `WE5` decimal(6,4) DEFAULT 0.0000,
  `WE6` decimal(6,4) DEFAULT 0.0000,
  `WE7` decimal(6,4) DEFAULT 0.0000,
  `WE8` decimal(6,4) DEFAULT 0.0000,
  
  -- Total dan Hasil
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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_himpunan`
--

CREATE TABLE `tbl_himpunan` (
  `id_himpunan` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `nilai` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Himpunan Nilai Sub Kriteria';

INSERT INTO `tbl_himpunan` (`id_himpunan`, `id_kriteria`, `keterangan`, `nilai`) VALUES
(1, 1, 'Tidak Ada', 1),
(2, 1, 'Sedikit (1-2)', 3),
(3, 1, 'Banyak (≥3)', 5),
(4, 2, 'Tidak Ada', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_log_bobot`
--

CREATE TABLE `tbl_log_bobot` (
  `id` int(11) NOT NULL,
  `id_kriteria` int(11) DEFAULT NULL,
  `kode_kriteria` varchar(10) DEFAULT NULL,
  `old_nilai` decimal(10,4) DEFAULT NULL,
  `new_nilai` decimal(10,4) DEFAULT NULL,
  `jenis` varchar(10) DEFAULT NULL,
  `aksi` varchar(20) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Audit Trail Perubahan Bobot Kriteria';

-- --------------------------------------------------------

--
-- Table structure for table `tbl_nilai_kriteria`
-- UPDATED: Sekarang jadi "Data Sub Kriteria"
--

CREATE TABLE `tbl_nilai_kriteria` (
  `id_nilai` int(11) NOT NULL,
  `id_kriteria` int(5) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `nilai` int(2) NOT NULL,
  `range_min` int(3) DEFAULT NULL,
  `range_max` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Data Sub Kriteria PKH';

INSERT INTO `tbl_nilai_kriteria` (`id_nilai`, `id_kriteria`, `keterangan`, `nilai`, `range_min`, `range_max`) VALUES
(1, 1, 'Tidak Ada', 1, 0, 0),
(2, 1, 'Sedikit (1-2)', 3, 1, 2),
(3, 1, 'Banyak (≥3)', 5, 3, 10),
(4, 2, 'Tidak Ada', 1, 0, 0),
(5, 2, 'Ada (1-2)', 4, 1, 2),
(6, 2, 'Banyak (≥3)', 5, 3, 10);

-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

ALTER TABLE `data_warga`
  ADD PRIMARY KEY (`id_warga`),
  ADD UNIQUE KEY `idx_nama_lengkap` (`nama_lengkap`);

ALTER TABLE `modul`
  ADD PRIMARY KEY (`id_modul`);

ALTER TABLE `tbl_hasil_mfep`
  ADD CONSTRAINT `fk_hasil_mfep_warga` FOREIGN KEY (`id_warga`) REFERENCES `data_warga` (`id_warga`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `tbl_himpunan`
  ADD PRIMARY KEY (`id_himpunan`),
  ADD KEY `idx_id_kriteria` (`id_kriteria`);

ALTER TABLE `tbl_kriteria`
  ADD PRIMARY KEY (`id_kriteria`),
  ADD UNIQUE KEY `kode_kriteria` (`kode_kriteria`);

ALTER TABLE `tbl_log_bobot`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_id_kriteria` (`id_kriteria`);

ALTER TABLE `tbl_nilai_kriteria`
  ADD PRIMARY KEY (`id_nilai`),
  ADD KEY `idx_id_kriteria` (`id_kriteria`);

--
-- AUTO_INCREMENT for dumped tables
--

ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `data_warga`
  MODIFY `id_warga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

ALTER TABLE `modul`
  MODIFY `id_modul` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

ALTER TABLE `tbl_hasil_mfep`
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tbl_himpunan`
  MODIFY `id_himpunan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `tbl_kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

ALTER TABLE `tbl_log_bobot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tbl_nilai_kriteria`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

-- --------------------------------------------------------

--
-- FOREIGN KEY CONSTRAINTS
--

ALTER TABLE `tbl_himpunan`
  ADD CONSTRAINT `fk_himpunan_kriteria` 
  FOREIGN KEY (`id_kriteria`) REFERENCES `tbl_kriteria` (`id_kriteria`) 
  ON DELETE CASCADE 
  ON UPDATE CASCADE;

ALTER TABLE `tbl_nilai_kriteria`
  ADD CONSTRAINT `fk_nilai_kriteria` 
  FOREIGN KEY (`id_kriteria`) REFERENCES `tbl_kriteria` (`id_kriteria`) 
  ON DELETE CASCADE 
  ON UPDATE CASCADE;

ALTER TABLE `tbl_log_bobot`
  ADD CONSTRAINT `fk_log_bobot_kriteria` 
  FOREIGN KEY (`id_kriteria`) REFERENCES `tbl_kriteria` (`id_kriteria`) 
  ON DELETE SET NULL 
  ON UPDATE CASCADE;

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
