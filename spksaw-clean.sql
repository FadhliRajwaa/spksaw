-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 12, 2025 at 01:25 AM
-- Server version: 10.4.32-MariaDB-log
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

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
  `id_admin` int(3) NOT NULL,
  `username` varchar(100) NOT NULL DEFAULT 'administrator',
  `password` varchar(100) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `level` varchar(50) NOT NULL DEFAULT 'admin',
  `alamat` text NOT NULL,
  `no_telp` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_lengkap`, `level`, `alamat`, `no_telp`, `email`, `created_at`, `updated_at`) VALUES
(1, 'administrator', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'Admin PKH System', 'admin', 'Kantor Dinas Sosial', '085228482669', 'admin@pkh.go.id', '2025-09-11 05:27:02', '2025-09-11 05:27:02'),
(2, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Admin Operator', 'admin', 'Kantor Kelurahan', '085228482669', 'operator@pkh.go.id', '2025-09-11 05:27:02', '2025-09-11 05:27:02');

-- --------------------------------------------------------

--
-- Table structure for table `data_warga`
--

CREATE TABLE `data_warga` (
  `id_warga` int(11) NOT NULL,
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel Data Warga untuk PKH';

--
-- Dumping data for table `data_warga`
--

INSERT INTO `data_warga` (`id_warga`, `nama_lengkap`, `alamat`, `jumlah_lansia`, `jumlah_disabilitas_berat`, `jumlah_anak_sd`, `jumlah_anak_smp`, `jumlah_anak_sma`, `jumlah_balita`, `jumlah_ibu_hamil`, `created_at`, `updated_at`) VALUES
(1, 'Siti Aminah', 'Jl. Merdeka No. 123', 1, 0, 2, 1, 0, 1, 0, '2025-09-11 06:11:16', '2025-09-11 06:11:16'),
(2, 'Budi Santoso', 'Jl. Sudirman No. 45', 0, 1, 1, 0, 1, 2, 1, '2025-09-11 06:11:16', '2025-09-11 06:11:16'),
(3, 'Rina Wati', 'Jl. Ahmad Yani No. 67', 2, 0, 0, 2, 1, 0, 0, '2025-09-11 06:11:16', '2025-09-11 06:11:16'),
(4, 'Ahmad Fauzi', 'Jl. Diponegoro No. 89', 1, 1, 3, 0, 0, 1, 1, '2025-09-11 06:11:16', '2025-09-11 06:11:16'),
(5, 'Dewi Sartika', 'Jl. Kartini No. 12', 0, 0, 1, 2, 1, 1, 0, '2025-09-11 06:11:16', '2025-09-11 06:11:16'),
(8, 'Fadhli Rajwaa Rahmanaa', 'gbm', 0, 0, 0, 0, 2, 0, 0, '2025-09-11 07:24:56', '2025-09-11 07:25:29');

-- --------------------------------------------------------

--
-- Table structure for table `modul`
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
--

INSERT INTO `modul` (`id_modul`, `nama_modul`, `link`, `type`, `urutan`, `aktif`, `status`) VALUES
(1, 'Data Warga', '?module=warga', 'Data', 1, 'Y', 'admin'),
(2, 'Data Kriteria', '?module=kriteria', 'Data', 2, 'Y', 'admin'),
(3, 'Data Klasifikasi', '?module=klasifikasi', 'Data', 3, 'Y', 'admin'),
(4, 'Laporan Hasil Analisa', '?module=laporan&act=analisa', 'Report', 1, 'Y', 'admin'),
(5, 'Perankingan', '?module=perankingan', 'Report', 2, 'Y', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hasil_saw`
--

CREATE TABLE `tbl_hasil_saw` (
  `id_hasil` int(11) NOT NULL,
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel Hasil Analisis SAW';

--
-- Dumping data for table `tbl_hasil_saw`
--

INSERT INTO `tbl_hasil_saw` (`id_hasil`, `id_warga`, `nama_warga`, `C1_norm`, `C2_norm`, `C3_norm`, `C4_norm`, `C5_norm`, `C6_norm`, `C7_norm`, `C8_norm`, `skor_akhir`, `ranking`, `rekomendasi`, `created_at`, `updated_at`) VALUES
(91, 4, 'Ahmad Fauzi', 0.5000, 1.0000, 1.0000, 0.0000, 0.0000, 0.5000, 1.0000, 0.0000, 2.0000, 1, 'Ya', '2025-09-11 22:13:29', '2025-09-11 22:13:29'),
(92, 2, 'Budi Santoso', 0.0000, 1.0000, 0.3333, 0.0000, 0.5000, 1.0000, 1.0000, 0.0000, 1.9167, 2, 'Ya', '2025-09-11 22:13:29', '2025-09-11 22:13:29'),
(93, 5, 'Dewi Sartika', 0.0000, 0.0000, 0.3333, 1.0000, 0.5000, 0.5000, 0.0000, 0.0000, 1.1667, 4, 'Tidak', '2025-09-11 22:13:29', '2025-09-11 22:13:29'),
(94, 8, 'Fadhli Rajwaa Rahmanaa', 0.0000, 0.0000, 0.0000, 0.0000, 1.0000, 0.0000, 0.0000, 0.0000, 0.5000, 6, 'Tidak', '2025-09-11 22:13:29', '2025-09-11 22:13:29'),
(95, 3, 'Rina Wati', 1.0000, 0.0000, 0.0000, 1.0000, 0.5000, 0.0000, 0.0000, 0.0000, 1.2500, 3, 'Tidak', '2025-09-11 22:13:29', '2025-09-11 22:13:29'),
(96, 1, 'Siti Aminah', 0.5000, 0.0000, 0.6667, 0.5000, 0.0000, 0.5000, 0.0000, 0.0000, 1.0833, 5, 'Tidak', '2025-09-11 22:13:29', '2025-09-11 22:13:29');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_klasifikasi`
--

CREATE TABLE `tbl_klasifikasi` (
  `id_klasifikasi` int(11) NOT NULL,
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel Klasifikasi Data Warga untuk PKH';

--
-- Dumping data for table `tbl_klasifikasi`
--

INSERT INTO `tbl_klasifikasi` (`id_klasifikasi`, `id_warga`, `C1`, `C2`, `C3`, `C4`, `C5`, `C6`, `C7`, `C8`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0, 2, 1, 0, 1, 0, 0, '2025-09-11 06:11:16', '2025-09-11 06:11:16'),
(2, 2, 0, 1, 1, 0, 1, 2, 1, 0, '2025-09-11 06:11:16', '2025-09-11 06:11:16'),
(3, 3, 2, 0, 0, 2, 1, 0, 0, 0, '2025-09-11 06:11:16', '2025-09-11 06:11:16'),
(4, 4, 1, 1, 3, 0, 0, 1, 1, 0, '2025-09-11 06:11:16', '2025-09-11 06:11:16'),
(5, 5, 0, 0, 1, 2, 1, 1, 0, 0, '2025-09-11 06:11:16', '2025-09-11 06:11:16'),
(8, 8, 0, 0, 0, 0, 2, 0, 0, 0, '2025-09-11 07:24:56', '2025-09-11 07:25:29');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kriteria`
--

CREATE TABLE `tbl_kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `nama_kriteria` varchar(100) NOT NULL,
  `bobot` decimal(3,2) NOT NULL DEFAULT 0.00,
  `jenis` enum('benefit','cost') NOT NULL DEFAULT 'benefit',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `kode_kriteria` varchar(10) NOT NULL,
  `nilai` decimal(3,2) DEFAULT 0.50
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel Kriteria PKH';

--
-- Dumping data for table `tbl_kriteria`
--

INSERT INTO `tbl_kriteria` (`id_kriteria`, `nama_kriteria`, `bobot`, `jenis`, `keterangan`, `created_at`, `updated_at`, `kode_kriteria`, `nilai`) VALUES
(1, 'Jumlah Lansia', 0.15, 'benefit', 'Jumlah lansia dalam keluarga', '2025-09-11 06:11:16', '2025-09-11 07:44:07', 'C1', 0.50),
(2, 'Jumlah Disabilitas Berat', 0.20, 'benefit', 'Jumlah anggota keluarga dengan disabilitas berat', '2025-09-11 06:11:16', '2025-09-11 07:44:07', 'C2', 0.50),
(3, 'Jumlah Anak SD', 0.15, 'benefit', 'Jumlah anak usia sekolah dasar', '2025-09-11 06:11:16', '2025-09-11 07:44:07', 'C3', 0.50),
(4, 'Jumlah Anak SMP', 0.10, 'benefit', 'Jumlah anak usia sekolah menengah pertama', '2025-09-11 06:11:16', '2025-09-11 07:44:07', 'C4', 0.50),
(5, 'Jumlah Anak SMA', 0.10, 'benefit', 'Jumlah anak usia sekolah menengah atas', '2025-09-11 06:11:16', '2025-09-11 07:44:07', 'C5', 0.50),
(6, 'Jumlah Balita', 0.15, 'benefit', 'Jumlah balita dalam keluarga', '2025-09-11 06:11:16', '2025-09-11 07:44:07', 'C6', 0.50),
(7, 'Jumlah Ibu Hamil', 0.15, 'benefit', 'Jumlah ibu hamil dalam keluarga', '2025-09-11 06:11:16', '2025-09-11 07:44:07', 'C7', 0.50),
(8, 'Reserved', 0.00, 'benefit', 'Kriteria cadangan untuk pengembangan', '2025-09-11 06:11:16', '2025-09-11 07:44:07', 'C8', 0.50);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_nilai_kriteria`
--

CREATE TABLE `tbl_nilai_kriteria` (
  `id_nilai` int(11) NOT NULL,
  `id_kriteria` int(5) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `nilai` int(2) NOT NULL,
  `range_min` int(3) DEFAULT NULL,
  `range_max` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_nilai_kriteria`
--

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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `data_warga`
--
ALTER TABLE `data_warga`
  ADD PRIMARY KEY (`id_warga`),
  ADD UNIQUE KEY `idx_nama_lengkap` (`nama_lengkap`);

--
-- Indexes for table `modul`
--
ALTER TABLE `modul`
  ADD PRIMARY KEY (`id_modul`);

--
-- Indexes for table `tbl_hasil_saw`
--
ALTER TABLE `tbl_hasil_saw`
  ADD PRIMARY KEY (`id_hasil`),
  ADD KEY `idx_id_warga` (`id_warga`),
  ADD KEY `idx_ranking` (`ranking`);

--
-- Indexes for table `tbl_klasifikasi`
--
ALTER TABLE `tbl_klasifikasi`
  ADD PRIMARY KEY (`id_klasifikasi`),
  ADD KEY `idx_id_warga` (`id_warga`);

--
-- Indexes for table `tbl_kriteria`
--
ALTER TABLE `tbl_kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `tbl_nilai_kriteria`
--
ALTER TABLE `tbl_nilai_kriteria`
  ADD PRIMARY KEY (`id_nilai`),
  ADD KEY `fk_kriteria_nilai` (`id_kriteria`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `data_warga`
--
ALTER TABLE `data_warga`
  MODIFY `id_warga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `modul`
--
ALTER TABLE `modul`
  MODIFY `id_modul` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_hasil_saw`
--
ALTER TABLE `tbl_hasil_saw`
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `tbl_klasifikasi`
--
ALTER TABLE `tbl_klasifikasi`
  MODIFY `id_klasifikasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_kriteria`
--
ALTER TABLE `tbl_kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_nilai_kriteria`
--
ALTER TABLE `tbl_nilai_kriteria`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_hasil_saw`
--
ALTER TABLE `tbl_hasil_saw`
  ADD CONSTRAINT `tbl_hasil_saw_ibfk_1` FOREIGN KEY (`id_warga`) REFERENCES `data_warga` (`id_warga`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_klasifikasi`
--
ALTER TABLE `tbl_klasifikasi`
  ADD CONSTRAINT `tbl_klasifikasi_ibfk_1` FOREIGN KEY (`id_warga`) REFERENCES `data_warga` (`id_warga`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
