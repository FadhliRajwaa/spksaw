-- ========================================
-- Database: spksaw (PKH Version)
-- Sistem Pendukung Keputusan Rekomendasi Penerima Bantuan PKH
-- Metode Simple Additive Weighting (SAW)
-- ========================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
-- Struktur tabel `admin` (Simplified - removed blokir and id_session)
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
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

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_lengkap`, `level`, `alamat`, `no_telp`, `email`) VALUES
(1, 'administrator', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'Admin PKH System', 'admin', 'Kantor Dinas Sosial', '085228482669', 'admin@pkh.go.id'),
(2, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Admin Operator', 'admin', 'Kantor Kelurahan', '085228482669', 'operator@pkh.go.id');

-- --------------------------------------------------------

--
-- Struktur tabel `data_warga` (Renamed from siswa with PKH-specific columns)
--

DROP TABLE IF EXISTS `data_warga`;
CREATE TABLE IF NOT EXISTS `data_warga` (
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

--
-- Dumping data contoh untuk tabel `data_warga`
--

INSERT INTO `data_warga` (`id_warga`, `nama_lengkap`, `alamat`, `jumlah_lansia`, `jumlah_disabilitas_berat`, `jumlah_anak_sd`, `jumlah_anak_smp`, `jumlah_anak_sma`, `jumlah_balita`, `jumlah_ibu_hamil`) VALUES
(1, 'Siti Aminah', 'Jl. Merdeka No. 123, RT 01/RW 02, Kelurahan Sukamaju', 2, 1, 2, 1, 0, 1, 1),
(2, 'Budi Santoso', 'Jl. Proklamasi No. 45, RT 03/RW 01, Kelurahan Sejahtera', 1, 0, 1, 2, 1, 2, 0),
(3, 'Eka Rahayu', 'Jl. Pancasila No. 67, RT 02/RW 03, Kelurahan Makmur', 0, 2, 3, 0, 2, 0, 1),
(4, 'Ahmad Fauzi', 'Jl. Garuda No. 89, RT 04/RW 02, Kelurahan Harmoni', 1, 1, 0, 3, 1, 1, 0),
(5, 'Dewi Sartika', 'Jl. Diponegoro No. 12, RT 01/RW 04, Kelurahan Bahagia', 3, 0, 2, 1, 0, 3, 2);

-- --------------------------------------------------------

--
-- Struktur tabel `tbl_kriteria` (Simplified - removed list functionality)
--

DROP TABLE IF EXISTS `tbl_kriteria`;
CREATE TABLE IF NOT EXISTS `tbl_kriteria` (
  `id_kriteria` int(5) NOT NULL AUTO_INCREMENT,
  `kriteria` varchar(100) NOT NULL,
  `bobot` decimal(3,2) NOT NULL,
  `jenis` enum('benefit','cost') NOT NULL DEFAULT 'benefit',
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kriteria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tbl_kriteria`
--

INSERT INTO `tbl_kriteria` (`id_kriteria`, `kriteria`, `bobot`, `jenis`, `keterangan`) VALUES
(1, 'Jumlah Lansia', 0.20, 'benefit', 'Jumlah anggota keluarga lanjut usia (≥60 tahun)'),
(2, 'Jumlah Disabilitas Berat', 0.25, 'benefit', 'Jumlah anggota keluarga dengan disabilitas berat'),
(3, 'Jumlah Anak SD', 0.15, 'benefit', 'Jumlah anak usia sekolah dasar (7-12 tahun)'),
(4, 'Jumlah Anak SMP', 0.15, 'benefit', 'Jumlah anak usia sekolah menengah pertama (13-15 tahun)'),
(5, 'Jumlah Anak SMA', 0.10, 'benefit', 'Jumlah anak usia sekolah menengah atas (16-18 tahun)'),
(6, 'Jumlah Balita', 0.10, 'benefit', 'Jumlah anak balita (0-5 tahun)'),
(7, 'Jumlah Ibu Hamil', 0.05, 'benefit', 'Jumlah ibu hamil dalam keluarga');

-- --------------------------------------------------------

--
-- Struktur tabel `tbl_nilai_kriteria` (New simplified structure)
--

DROP TABLE IF EXISTS `tbl_nilai_kriteria`;
CREATE TABLE IF NOT EXISTS `tbl_nilai_kriteria` (
  `id_nilai` int(11) NOT NULL AUTO_INCREMENT,
  `id_kriteria` int(5) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `nilai` int(2) NOT NULL,
  `range_min` int(3) DEFAULT NULL,
  `range_max` int(3) DEFAULT NULL,
  PRIMARY KEY (`id_nilai`),
  KEY `fk_kriteria_nilai` (`id_kriteria`),
  CONSTRAINT `fk_kriteria_nilai` FOREIGN KEY (`id_kriteria`) REFERENCES `tbl_kriteria` (`id_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tbl_nilai_kriteria`
--

INSERT INTO `tbl_nilai_kriteria` (`id_nilai`, `id_kriteria`, `keterangan`, `nilai`, `range_min`, `range_max`) VALUES
-- Kriteria Jumlah Lansia
(1, 1, 'Tidak Ada', 1, 0, 0),
(2, 1, 'Sedikit (1-2)', 3, 1, 2),
(3, 1, 'Banyak (≥3)', 5, 3, 10),

-- Kriteria Jumlah Disabilitas Berat
(4, 2, 'Tidak Ada', 1, 0, 0),
(5, 2, 'Ada (1-2)', 4, 1, 2),
(6, 2, 'Banyak (≥3)', 5, 3, 10),

-- Kriteria Jumlah Anak SD
(7, 3, 'Tidak Ada', 1, 0, 0),
(8, 3, 'Sedikit (1-2)', 3, 1, 2),
(9, 3, 'Banyak (≥3)', 5, 3, 10),

-- Kriteria Jumlah Anak SMP
(10, 4, 'Tidak Ada', 1, 0, 0),
(11, 4, 'Sedikit (1-2)', 3, 1, 2),
(12, 4, 'Banyak (≥3)', 5, 3, 10),

-- Kriteria Jumlah Anak SMA
(13, 5, 'Tidak Ada', 1, 0, 0),
(14, 5, 'Sedikit (1-2)', 3, 1, 2),
(15, 5, 'Banyak (≥3)', 5, 3, 10),

-- Kriteria Jumlah Balita
(16, 6, 'Tidak Ada', 1, 0, 0),
(17, 6, 'Sedikit (1-2)', 3, 1, 2),
(18, 6, 'Banyak (≥3)', 5, 3, 10),

-- Kriteria Jumlah Ibu Hamil
(19, 7, 'Tidak Ada', 1, 0, 0),
(20, 7, 'Ada (1-2)', 4, 1, 2),
(21, 7, 'Banyak (≥3)', 5, 3, 10);

-- --------------------------------------------------------

--
-- Struktur tabel `tbl_klasifikasi` (Auto-populated from data_warga)
--

DROP TABLE IF EXISTS `tbl_klasifikasi`;
CREATE TABLE IF NOT EXISTS `tbl_klasifikasi` (
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
  KEY `fk_nilai_klasifikasi` (`id_nilai`),
  CONSTRAINT `fk_warga_klasifikasi` FOREIGN KEY (`id_warga`) REFERENCES `data_warga` (`id_warga`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_kriteria_klasifikasi` FOREIGN KEY (`id_kriteria`) REFERENCES `tbl_kriteria` (`id_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_nilai_klasifikasi` FOREIGN KEY (`id_nilai`) REFERENCES `tbl_nilai_kriteria` (`id_nilai`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur tabel `tbl_hasil_saw` (Store SAW calculation results)
--

DROP TABLE IF EXISTS `tbl_hasil_saw`;
CREATE TABLE IF NOT EXISTS `tbl_hasil_saw` (
  `id_hasil` int(11) NOT NULL AUTO_INCREMENT,
  `id_warga` int(9) NOT NULL,
  `total_nilai` decimal(10,6) NOT NULL,
  `ranking` int(5) NOT NULL,
  `status` enum('layak','tidak_layak') DEFAULT 'tidak_layak',
  `tanggal_perhitungan` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_hasil`),
  KEY `fk_warga_hasil` (`id_warga`),
  CONSTRAINT `fk_warga_hasil` FOREIGN KEY (`id_warga`) REFERENCES `data_warga` (`id_warga`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur tabel `modul` (Updated menu structure)
--

DROP TABLE IF EXISTS `modul`;
CREATE TABLE IF NOT EXISTS `modul` (
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

--
-- Dumping data untuk tabel `modul` (Updated PKH menu structure)
--

INSERT INTO `modul` (`id_modul`, `nama_modul`, `link`, `static_content`, `gambar`, `publish`, `status`, `aktif`, `urutan`, `link_seo`, `type`) VALUES
(1, 'Data Warga', '?module=warga', '', '', 'Y', 'admin', 'Y', 1, 'data-warga', ''),
(2, 'Data Kriteria', '?module=kriteria', '', '', 'Y', 'admin', 'Y', 2, 'data-kriteria', ''),
(3, 'Data Klasifikasi', '?module=klasifikasi', '', '', 'Y', 'admin', 'Y', 3, 'data-klasifikasi', ''),
(4, 'Laporan Hasil Analisa', '?module=laporan&act=analisa', '', '', 'Y', 'admin', 'Y', 4, 'laporan-analisa', 'Report'),
(5, 'Perankingan', '?module=perankingan', '', '', 'Y', 'admin', 'Y', 5, 'perankingan', 'Report');

-- --------------------------------------------------------

--
-- Views untuk analisa SAW
--

-- View untuk melihat data klasifikasi lengkap
DROP VIEW IF EXISTS `v_klasifikasi_lengkap`;
CREATE VIEW `v_klasifikasi_lengkap` AS
SELECT 
    k.id_klasifikasi,
    k.id_warga,
    w.nama_lengkap,
    w.alamat,
    k.id_kriteria,
    kr.kriteria,
    kr.bobot,
    k.nilai_input,
    k.nilai_konversi,
    nk.keterangan as nilai_keterangan
FROM tbl_klasifikasi k
JOIN data_warga w ON k.id_warga = w.id_warga
JOIN tbl_kriteria kr ON k.id_kriteria = kr.id_kriteria
JOIN tbl_nilai_kriteria nk ON k.id_nilai = nk.id_nilai;

-- View untuk melihat matriks keputusan
DROP VIEW IF EXISTS `v_matriks_keputusan`;
CREATE VIEW `v_matriks_keputusan` AS
SELECT 
    w.id_warga,
    w.nama_lengkap,
    MAX(CASE WHEN k.id_kriteria = 1 THEN k.nilai_konversi END) as lansia,
    MAX(CASE WHEN k.id_kriteria = 2 THEN k.nilai_konversi END) as disabilitas,
    MAX(CASE WHEN k.id_kriteria = 3 THEN k.nilai_konversi END) as anak_sd,
    MAX(CASE WHEN k.id_kriteria = 4 THEN k.nilai_konversi END) as anak_smp,
    MAX(CASE WHEN k.id_kriteria = 5 THEN k.nilai_konversi END) as anak_sma,
    MAX(CASE WHEN k.id_kriteria = 6 THEN k.nilai_konversi END) as balita,
    MAX(CASE WHEN k.id_kriteria = 7 THEN k.nilai_konversi END) as ibu_hamil
FROM data_warga w
LEFT JOIN tbl_klasifikasi k ON w.id_warga = k.id_warga
GROUP BY w.id_warga, w.nama_lengkap;

-- --------------------------------------------------------

--
-- Stored Procedures untuk otomatisasi
--

DELIMITER //

-- Procedure untuk auto-populate klasifikasi dari data warga
DROP PROCEDURE IF EXISTS sp_auto_populate_klasifikasi//
CREATE PROCEDURE sp_auto_populate_klasifikasi(IN p_id_warga INT)
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_id_kriteria INT;
    DECLARE v_nilai_input INT;
    DECLARE v_id_nilai INT;
    DECLARE v_nilai_konversi INT;
    
    -- Delete existing klasifikasi for this warga
    DELETE FROM tbl_klasifikasi WHERE id_warga = p_id_warga;
    
    -- Get warga data
    SELECT 
        jumlah_lansia, jumlah_disabilitas_berat, jumlah_anak_sd, 
        jumlah_anak_smp, jumlah_anak_sma, jumlah_balita, jumlah_ibu_hamil
    INTO @lansia, @disabilitas, @anak_sd, @anak_smp, @anak_sma, @balita, @ibu_hamil
    FROM data_warga WHERE id_warga = p_id_warga;
    
    -- Insert klasifikasi for each criteria
    -- Lansia
    SELECT id_nilai, nilai INTO v_id_nilai, v_nilai_konversi
    FROM tbl_nilai_kriteria 
    WHERE id_kriteria = 1 AND @lansia BETWEEN COALESCE(range_min, 0) AND COALESCE(range_max, 999)
    ORDER BY range_max DESC LIMIT 1;
    
    INSERT INTO tbl_klasifikasi (id_warga, id_kriteria, id_nilai, nilai_input, nilai_konversi)
    VALUES (p_id_warga, 1, v_id_nilai, @lansia, v_nilai_konversi);
    
    -- Disabilitas
    SELECT id_nilai, nilai INTO v_id_nilai, v_nilai_konversi
    FROM tbl_nilai_kriteria 
    WHERE id_kriteria = 2 AND @disabilitas BETWEEN COALESCE(range_min, 0) AND COALESCE(range_max, 999)
    ORDER BY range_max DESC LIMIT 1;
    
    INSERT INTO tbl_klasifikasi (id_warga, id_kriteria, id_nilai, nilai_input, nilai_konversi)
    VALUES (p_id_warga, 2, v_id_nilai, @disabilitas, v_nilai_konversi);
    
    -- Anak SD
    SELECT id_nilai, nilai INTO v_id_nilai, v_nilai_konversi
    FROM tbl_nilai_kriteria 
    WHERE id_kriteria = 3 AND @anak_sd BETWEEN COALESCE(range_min, 0) AND COALESCE(range_max, 999)
    ORDER BY range_max DESC LIMIT 1;
    
    INSERT INTO tbl_klasifikasi (id_warga, id_kriteria, id_nilai, nilai_input, nilai_konversi)
    VALUES (p_id_warga, 3, v_id_nilai, @anak_sd, v_nilai_konversi);
    
    -- Anak SMP
    SELECT id_nilai, nilai INTO v_id_nilai, v_nilai_konversi
    FROM tbl_nilai_kriteria 
    WHERE id_kriteria = 4 AND @anak_smp BETWEEN COALESCE(range_min, 0) AND COALESCE(range_max, 999)
    ORDER BY range_max DESC LIMIT 1;
    
    INSERT INTO tbl_klasifikasi (id_warga, id_kriteria, id_nilai, nilai_input, nilai_konversi)
    VALUES (p_id_warga, 4, v_id_nilai, @anak_smp, v_nilai_konversi);
    
    -- Anak SMA
    SELECT id_nilai, nilai INTO v_id_nilai, v_nilai_konversi
    FROM tbl_nilai_kriteria 
    WHERE id_kriteria = 5 AND @anak_sma BETWEEN COALESCE(range_min, 0) AND COALESCE(range_max, 999)
    ORDER BY range_max DESC LIMIT 1;
    
    INSERT INTO tbl_klasifikasi (id_warga, id_kriteria, id_nilai, nilai_input, nilai_konversi)
    VALUES (p_id_warga, 5, v_id_nilai, @anak_sma, v_nilai_konversi);
    
    -- Balita
    SELECT id_nilai, nilai INTO v_id_nilai, v_nilai_konversi
    FROM tbl_nilai_kriteria 
    WHERE id_kriteria = 6 AND @balita BETWEEN COALESCE(range_min, 0) AND COALESCE(range_max, 999)
    ORDER BY range_max DESC LIMIT 1;
    
    INSERT INTO tbl_klasifikasi (id_warga, id_kriteria, id_nilai, nilai_input, nilai_konversi)
    VALUES (p_id_warga, 6, v_id_nilai, @balita, v_nilai_konversi);
    
    -- Ibu Hamil
    SELECT id_nilai, nilai INTO v_id_nilai, v_nilai_konversi
    FROM tbl_nilai_kriteria 
    WHERE id_kriteria = 7 AND @ibu_hamil BETWEEN COALESCE(range_min, 0) AND COALESCE(range_max, 999)
    ORDER BY range_max DESC LIMIT 1;
    
    INSERT INTO tbl_klasifikasi (id_warga, id_kriteria, id_nilai, nilai_input, nilai_konversi)
    VALUES (p_id_warga, 7, v_id_nilai, @ibu_hamil, v_nilai_konversi);
END//

-- Procedure untuk menghitung SAW
DROP PROCEDURE IF EXISTS sp_hitung_saw//
CREATE PROCEDURE sp_hitung_saw()
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_id_warga INT;
    DECLARE v_total_nilai DECIMAL(10,6);
    DECLARE v_ranking INT DEFAULT 1;
    
    DECLARE cur_warga CURSOR FOR 
        SELECT id_warga FROM data_warga ORDER BY id_warga;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    -- Clear previous results
    DELETE FROM tbl_hasil_saw;
    
    -- Calculate for each warga
    OPEN cur_warga;
    read_loop: LOOP
        FETCH cur_warga INTO v_id_warga;
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        -- Calculate weighted score
        SELECT 
            SUM((k.nilai_konversi / 
                (SELECT MAX(nilai_konversi) FROM tbl_klasifikasi k2 
                 JOIN tbl_kriteria kr2 ON k2.id_kriteria = kr2.id_kriteria 
                 WHERE k2.id_kriteria = k.id_kriteria)) * kr.bobot)
        INTO v_total_nilai
        FROM tbl_klasifikasi k
        JOIN tbl_kriteria kr ON k.id_kriteria = kr.id_kriteria
        WHERE k.id_warga = v_id_warga;
        
        -- Insert result
        INSERT INTO tbl_hasil_saw (id_warga, total_nilai, ranking, status)
        VALUES (v_id_warga, COALESCE(v_total_nilai, 0), 0, 'tidak_layak');
        
    END LOOP;
    CLOSE cur_warga;
    
    -- Update rankings
    SET @rank = 0;
    UPDATE tbl_hasil_saw 
    SET ranking = (@rank := @rank + 1)
    ORDER BY total_nilai DESC;
    
    -- Update status (top 50% get 'layak')
    UPDATE tbl_hasil_saw 
    SET status = 'layak' 
    WHERE ranking <= (SELECT COUNT(*) / 2 FROM tbl_hasil_saw);
    
END//

DELIMITER ;

-- --------------------------------------------------------

-- Drop unused tables
DROP TABLE IF EXISTS `absensi`;
DROP TABLE IF EXISTS `pengajar`;
DROP TABLE IF EXISTS `registrasi_siswa`;
DROP TABLE IF EXISTS `kelas`;
DROP TABLE IF EXISTS `siswa`;
DROP TABLE IF EXISTS `tbl_himpunankriteria`;

-- --------------------------------------------------------

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
