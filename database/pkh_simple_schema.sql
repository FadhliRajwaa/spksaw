-- ========================================
-- Simple PKH Database Schema (Without Stored Procedures)
-- Sistem Pendukung Keputusan Rekomendasi Penerima Bantuan PKH
-- ========================================

-- ========================================
-- Table: admin (Simplified)
-- ========================================

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id_admin` int(3) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL DEFAULT 'administrator',
  `password` varchar(100) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `level` varchar(50) NOT NULL DEFAULT 'admin',
  `alamat` text NOT NULL,
  `no_telp` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_lengkap`, `level`, `alamat`, `no_telp`, `email`) VALUES
(1, 'administrator', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'Admin PKH System', 'admin', 'Kantor Dinas Sosial', '085228482669', 'admin@pkh.go.id'),
(2, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Admin Operator', 'admin', 'Kantor Kelurahan', '085228482669', 'operator@pkh.go.id');

-- ========================================
-- Table: data_warga (PKH-specific data)
-- ========================================

DROP TABLE IF EXISTS `data_warga`;
CREATE TABLE `data_warga` (
  `id_warga` int(9) NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(100) NOT NULL,
  `alamat` varchar(200) NOT NULL,
  `jumlah_lansia` int(3) NOT NULL DEFAULT 0,
  `jumlah_disabilitas_berat` int(3) NOT NULL DEFAULT 0,
  `jumlah_anak_sd` int(3) NOT NULL DEFAULT 0,
  `jumlah_anak_smp` int(3) NOT NULL DEFAULT 0,
  `jumlah_anak_sma` int(3) NOT NULL DEFAULT 0,
  `jumlah_balita` int(3) NOT NULL DEFAULT 0,
  `jumlah_ibu_hamil` int(3) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_warga`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `data_warga` (`nama_lengkap`, `alamat`, `jumlah_lansia`, `jumlah_disabilitas_berat`, `jumlah_anak_sd`, `jumlah_anak_smp`, `jumlah_anak_sma`, `jumlah_balita`, `jumlah_ibu_hamil`) VALUES
('Siti Aminah', 'Jl. Merdeka No. 123, RT 01/RW 02, Kelurahan Sukamaju', 2, 1, 2, 1, 0, 1, 1),
('Budi Santoso', 'Jl. Proklamasi No. 45, RT 03/RW 01, Kelurahan Sejahtera', 1, 0, 1, 2, 1, 2, 0),
('Eka Rahayu', 'Jl. Pancasila No. 67, RT 02/RW 03, Kelurahan Makmur', 0, 2, 3, 0, 2, 0, 1),
('Ahmad Fauzi', 'Jl. Garuda No. 89, RT 04/RW 02, Kelurahan Harmoni', 1, 1, 0, 3, 1, 1, 0),
('Dewi Sartika', 'Jl. Diponegoro No. 12, RT 01/RW 04, Kelurahan Bahagia', 3, 0, 2, 1, 0, 3, 2);

-- ========================================
-- Table: tbl_kriteria (PKH criteria)
-- ========================================

DROP TABLE IF EXISTS `tbl_kriteria`;
CREATE TABLE `tbl_kriteria` (
  `id_kriteria` int(5) NOT NULL AUTO_INCREMENT,
  `kriteria` varchar(100) NOT NULL,
  `bobot` decimal(3,2) NOT NULL,
  `jenis` enum('benefit','cost') NOT NULL DEFAULT 'benefit',
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kriteria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tbl_kriteria` (`id_kriteria`, `kriteria`, `bobot`, `jenis`, `keterangan`) VALUES
(1, 'Jumlah Lansia', 0.20, 'benefit', 'Jumlah anggota keluarga lanjut usia (≥60 tahun)'),
(2, 'Jumlah Disabilitas Berat', 0.25, 'benefit', 'Jumlah anggota keluarga dengan disabilitas berat'),
(3, 'Jumlah Anak SD', 0.15, 'benefit', 'Jumlah anak usia sekolah dasar (7-12 tahun)'),
(4, 'Jumlah Anak SMP', 0.15, 'benefit', 'Jumlah anak usia sekolah menengah pertama (13-15 tahun)'),
(5, 'Jumlah Anak SMA', 0.10, 'benefit', 'Jumlah anak usia sekolah menengah atas (16-18 tahun)'),
(6, 'Jumlah Balita', 0.10, 'benefit', 'Jumlah anak balita (0-5 tahun)'),
(7, 'Jumlah Ibu Hamil', 0.05, 'benefit', 'Jumlah ibu hamil dalam keluarga');

-- ========================================
-- Table: tbl_nilai_kriteria (Criteria values)
-- ========================================

DROP TABLE IF EXISTS `tbl_nilai_kriteria`;
CREATE TABLE `tbl_nilai_kriteria` (
  `id_nilai` int(11) NOT NULL AUTO_INCREMENT,
  `id_kriteria` int(5) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `nilai` int(2) NOT NULL,
  `range_min` int(3) DEFAULT NULL,
  `range_max` int(3) DEFAULT NULL,
  PRIMARY KEY (`id_nilai`),
  KEY `fk_kriteria_nilai` (`id_kriteria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tbl_nilai_kriteria` (`id_kriteria`, `keterangan`, `nilai`, `range_min`, `range_max`) VALUES
(1, 'Tidak Ada', 1, 0, 0),
(1, 'Sedikit (1-2)', 3, 1, 2),
(1, 'Banyak (≥3)', 5, 3, 10),
(2, 'Tidak Ada', 1, 0, 0),
(2, 'Ada (1-2)', 4, 1, 2),
(2, 'Banyak (≥3)', 5, 3, 10),
(3, 'Tidak Ada', 1, 0, 0),
(3, 'Sedikit (1-2)', 3, 1, 2),
(3, 'Banyak (≥3)', 5, 3, 10),
(4, 'Tidak Ada', 1, 0, 0),
(4, 'Sedikit (1-2)', 3, 1, 2),
(4, 'Banyak (≥3)', 5, 3, 10),
(5, 'Tidak Ada', 1, 0, 0),
(5, 'Sedikit (1-2)', 3, 1, 2),
(5, 'Banyak (≥3)', 5, 3, 10),
(6, 'Tidak Ada', 1, 0, 0),
(6, 'Sedikit (1-2)', 3, 1, 2),
(6, 'Banyak (≥3)', 5, 3, 10),
(7, 'Tidak Ada', 1, 0, 0),
(7, 'Ada (1-2)', 4, 1, 2),
(7, 'Banyak (≥3)', 5, 3, 10);

-- ========================================
-- Table: tbl_klasifikasi (Auto-populated classification)
-- ========================================

DROP TABLE IF EXISTS `tbl_klasifikasi`;
CREATE TABLE `tbl_klasifikasi` (
  `id_klasifikasi` int(11) NOT NULL AUTO_INCREMENT,
  `id_warga` int(9) NOT NULL,
  `id_kriteria` int(5) NOT NULL,
  `id_nilai` int(11) NOT NULL,
  `nilai_input` int(3) NOT NULL,
  `nilai_konversi` int(2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_klasifikasi`),
  KEY `fk_warga_klasifikasi` (`id_warga`),
  KEY `fk_kriteria_klasifikasi` (`id_kriteria`),
  KEY `fk_nilai_klasifikasi` (`id_nilai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- Table: tbl_hasil_saw (SAW calculation results)
-- ========================================

DROP TABLE IF EXISTS `tbl_hasil_saw`;
CREATE TABLE `tbl_hasil_saw` (
  `id_hasil` int(11) NOT NULL AUTO_INCREMENT,
  `id_warga` int(9) NOT NULL,
  `total_nilai` decimal(10,6) NOT NULL,
  `ranking` int(5) NOT NULL,
  `status` enum('layak','tidak_layak') DEFAULT 'tidak_layak',
  `tanggal_perhitungan` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_hasil`),
  KEY `fk_warga_hasil` (`id_warga`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- Table: modul (Updated menu structure)
-- ========================================

DROP TABLE IF EXISTS `modul`;
CREATE TABLE `modul` (
  `id_modul` int(5) NOT NULL AUTO_INCREMENT,
  `nama_modul` varchar(50) NOT NULL,
  `link` varchar(100) NOT NULL,
  `static_content` text,
  `gambar` varchar(100) DEFAULT NULL,
  `publish` enum('Y','N') NOT NULL DEFAULT 'Y',
  `status` enum('admin') NOT NULL DEFAULT 'admin',
  `aktif` enum('Y','N') NOT NULL DEFAULT 'Y',
  `urutan` int(5) NOT NULL,
  `link_seo` varchar(50) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_modul`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `modul` (`nama_modul`, `link`, `publish`, `status`, `aktif`, `urutan`, `link_seo`, `type`) VALUES
('Data Warga', '?module=warga', 'Y', 'admin', 'Y', 1, 'data-warga', ''),
('Data Kriteria', '?module=kriteria', 'Y', 'admin', 'Y', 2, 'data-kriteria', ''),
('Data Klasifikasi', '?module=klasifikasi', 'Y', 'admin', 'Y', 3, 'data-klasifikasi', ''),
('Laporan Hasil Analisa', '?module=laporan&act=analisa', 'Y', 'admin', 'Y', 4, 'laporan-analisa', 'Report'),
('Perankingan', '?module=perankingan', 'Y', 'admin', 'Y', 5, 'perankingan', 'Report');
