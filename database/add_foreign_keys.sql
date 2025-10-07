-- ============================================
-- SCRIPT UNTUK MENAMBAHKAN FOREIGN KEY RELATIONSHIPS
-- SPK SAW Database - Table Relations
-- Created: 2025-09-14
-- ============================================

USE spksaw;

-- Disable foreign key checks temporarily for smooth execution
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- 1. ADD FOREIGN KEY: tbl_klasifikasi -> data_warga
-- ============================================
ALTER TABLE `tbl_klasifikasi` 
ADD CONSTRAINT `fk_klasifikasi_warga` 
FOREIGN KEY (`id_warga`) REFERENCES `data_warga` (`id_warga`) 
ON DELETE CASCADE 
ON UPDATE CASCADE;

-- ============================================
-- 2. ADD FOREIGN KEY: tbl_hasil_saw -> data_warga  
-- ============================================
ALTER TABLE `tbl_hasil_saw` 
ADD CONSTRAINT `fk_hasil_saw_warga` 
FOREIGN KEY (`id_warga`) REFERENCES `data_warga` (`id_warga`) 
ON DELETE CASCADE 
ON UPDATE CASCADE;

-- ============================================
-- 3. ADD FOREIGN KEY: tbl_himpunan -> tbl_kriteria
-- ============================================
ALTER TABLE `tbl_himpunan` 
ADD CONSTRAINT `fk_himpunan_kriteria` 
FOREIGN KEY (`id_kriteria`) REFERENCES `tbl_kriteria` (`id_kriteria`) 
ON DELETE CASCADE 
ON UPDATE CASCADE;

-- ============================================
-- 4. ADD FOREIGN KEY: tbl_nilai_kriteria -> tbl_kriteria
-- ============================================
ALTER TABLE `tbl_nilai_kriteria` 
ADD CONSTRAINT `fk_nilai_kriteria` 
FOREIGN KEY (`id_kriteria`) REFERENCES `tbl_kriteria` (`id_kriteria`) 
ON DELETE CASCADE 
ON UPDATE CASCADE;

-- ============================================
-- 5. CLEAN ORPHANED DATA BEFORE ADDING FOREIGN KEY
-- ============================================

-- Check orphaned data in tbl_log_bobot
SELECT 'Checking orphaned data in tbl_log_bobot...' as Status;

SELECT lb.id, lb.id_kriteria, lb.kode_kriteria, lb.aksi
FROM tbl_log_bobot lb
LEFT JOIN tbl_kriteria k ON lb.id_kriteria = k.id_kriteria
WHERE lb.id_kriteria IS NOT NULL 
AND k.id_kriteria IS NULL;

-- Delete orphaned records (id_kriteria not exists in tbl_kriteria)
DELETE lb FROM tbl_log_bobot lb
LEFT JOIN tbl_kriteria k ON lb.id_kriteria = k.id_kriteria
WHERE lb.id_kriteria IS NOT NULL 
AND k.id_kriteria IS NULL;

SELECT 'Orphaned data cleaned successfully!' as Status;

-- ============================================
-- 5. ADD FOREIGN KEY: tbl_log_bobot -> tbl_kriteria
-- ============================================
ALTER TABLE `tbl_log_bobot` 
ADD CONSTRAINT `fk_log_bobot_kriteria` 
FOREIGN KEY (`id_kriteria`) REFERENCES `tbl_kriteria` (`id_kriteria`) 
ON DELETE SET NULL 
ON UPDATE CASCADE;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- VERIFICATION: Check all foreign keys
-- ============================================
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME,
    UPDATE_RULE,
    DELETE_RULE
FROM 
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE 
    REFERENCED_TABLE_SCHEMA = 'spksaw' 
    AND REFERENCED_TABLE_NAME IS NOT NULL
ORDER BY TABLE_NAME, COLUMN_NAME;

-- ============================================
-- SUCCESS MESSAGE
-- ============================================
SELECT 'Foreign Key Relationships berhasil ditambahkan!' as Status;
