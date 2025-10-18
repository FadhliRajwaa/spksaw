-- ============================================
-- MIGRATION SCRIPT: SAW to MFEP
-- Date: 2025-10-17
-- Description: Convert SPK-SAW to SPK-MFEP
-- ============================================

-- BACKUP WARNING: Please backup your database before running this script!

-- Set safe mode
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- STEP 1: Backup existing data
-- ============================================
CREATE TABLE IF NOT EXISTS `backup_tbl_hasil_saw` LIKE `tbl_hasil_saw`;
INSERT INTO `backup_tbl_hasil_saw` SELECT * FROM `tbl_hasil_saw`;

CREATE TABLE IF NOT EXISTS `backup_tbl_klasifikasi` LIKE `tbl_klasifikasi`;
INSERT INTO `backup_tbl_klasifikasi` SELECT * FROM `tbl_klasifikasi`;

CREATE TABLE IF NOT EXISTS `backup_data_warga` LIKE `data_warga`;
INSERT INTO `backup_data_warga` SELECT * FROM `data_warga`;

-- ============================================
-- STEP 2: Merge tbl_klasifikasi into data_warga
-- ============================================
-- Columns C1-C8 sudah ada di data_warga, jadi tidak perlu ALTER
-- Hanya update data from tbl_klasifikasi if needed

-- ============================================
-- STEP 3: Rename tbl_hasil_saw to tbl_hasil_mfep
-- ============================================
-- Drop if exists
DROP TABLE IF EXISTS `tbl_hasil_mfep`;

-- Create new table for MFEP results
CREATE TABLE `tbl_hasil_mfep` (
  `id_hasil` int(11) NOT NULL AUTO_INCREMENT,
  `id_warga` int(11) NOT NULL,
  `nama_warga` varchar(100) NOT NULL,
  
  -- Matriks Keputusan (X) - Raw values
  `C1` int(11) DEFAULT 0,
  `C2` int(11) DEFAULT 0,
  `C3` int(11) DEFAULT 0,
  `C4` int(11) DEFAULT 0,
  `C5` int(11) DEFAULT 0,
  `C6` int(11) DEFAULT 0,
  `C7` int(11) DEFAULT 0,
  `C8` int(11) DEFAULT 0,
  
  -- Nilai Evaluasi Factor (E) - Normalized 0-1
  `E1` decimal(5,4) DEFAULT 0.0000,
  `E2` decimal(5,4) DEFAULT 0.0000,
  `E3` decimal(5,4) DEFAULT 0.0000,
  `E4` decimal(5,4) DEFAULT 0.0000,
  `E5` decimal(5,4) DEFAULT 0.0000,
  `E6` decimal(5,4) DEFAULT 0.0000,
  `E7` decimal(5,4) DEFAULT 0.0000,
  `E8` decimal(5,4) DEFAULT 0.0000,
  
  -- Nilai Bobot Evaluasi (WE) - Weight × Factor
  `WE1` decimal(6,4) DEFAULT 0.0000,
  `WE2` decimal(6,4) DEFAULT 0.0000,
  `WE3` decimal(6,4) DEFAULT 0.0000,
  `WE4` decimal(6,4) DEFAULT 0.0000,
  `WE5` decimal(6,4) DEFAULT 0.0000,
  `WE6` decimal(6,4) DEFAULT 0.0000,
  `WE7` decimal(6,4) DEFAULT 0.0000,
  `WE8` decimal(6,4) DEFAULT 0.0000,
  
  -- Nilai Total Evaluasi
  `total_we` decimal(6,4) DEFAULT 0.0000,
  
  -- MFEP Score (WE - WP)
  `nilai_mfep` decimal(6,4) DEFAULT 0.0000,
  
  -- Ranking
  `ranking` int(11) DEFAULT 0,
  `rekomendasi` enum('Ya','Tidak') DEFAULT 'Tidak',
  
  -- Timestamps
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  
  PRIMARY KEY (`id_hasil`),
  KEY `idx_id_warga` (`id_warga`),
  KEY `idx_ranking` (`ranking`),
  CONSTRAINT `fk_hasil_mfep_warga` FOREIGN KEY (`id_warga`) REFERENCES `data_warga` (`id_warga`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel Hasil Perhitungan MFEP';

-- ============================================
-- STEP 4: Update modul menu
-- ============================================
-- SAFER: rename by link to avoid double-rename collision
UPDATE `modul` SET `nama_modul` = 'Data Kriteria'      
WHERE `link` LIKE '%module=pembobotan%';

UPDATE `modul` SET `nama_modul` = 'Data Sub Kriteria'  
WHERE `link` LIKE '%module=kriteria%';

UPDATE `modul` SET `nama_modul` = 'Laporan Hasil Perhitungan' 
WHERE `nama_modul` = 'Laporan Hasil Analisa';

-- Delete Data Klasifikasi menu
DELETE FROM `modul` WHERE `nama_modul` = 'Data Klasifikasi';

-- Order: 1 Data Warga, 2 Data Kriteria, 3 Data Sub Kriteria, 4 Laporan, 5 Perankingan
UPDATE `modul` SET `urutan` = 2 WHERE `link` LIKE '%module=pembobotan%';
UPDATE `modul` SET `urutan` = 3 WHERE `link` LIKE '%module=kriteria%';
UPDATE `modul` SET `urutan` = 4 WHERE `nama_modul` = 'Laporan Hasil Perhitungan';
UPDATE `modul` SET `urutan` = 5 WHERE `nama_modul` = 'Perankingan';

-- ============================================
-- STEP 5: Drop tbl_klasifikasi (optional - after data merged)
-- ============================================
-- WARNING: Only run this after confirming data is safely merged!
-- DROP TABLE IF EXISTS `tbl_klasifikasi`;

-- ============================================
-- STEP 6: Update tbl_kriteria comments
-- ============================================
ALTER TABLE `tbl_kriteria` COMMENT='Tabel Data Kriteria PKH (formerly Pembobotan)';
ALTER TABLE `tbl_nilai_kriteria` COMMENT='Tabel Data Sub Kriteria PKH (formerly tbl_kriteria)';
ALTER TABLE `tbl_himpunan` COMMENT='Tabel Himpunan Nilai Sub Kriteria';

-- ============================================
-- STEP 7: Clean up old SAW table (optional)
-- ============================================
-- WARNING: Only run this after confirming MFEP works correctly!
-- DROP TABLE IF EXISTS `tbl_hasil_saw`;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- MIGRATION COMPLETE
-- ============================================
-- Please verify:
-- 1. Data integrity in data_warga
-- 2. Menu labels updated correctly
-- 3. MFEP calculation working
-- 4. Backup tables created successfully
-- 
-- To rollback:
-- 1. Restore from backup_* tables
-- 2. Run rollback script
-- ============================================

SELECT 'Migration script executed successfully!' AS Status;
SELECT 'Please test MFEP calculation before dropping backup tables' AS Warning;
