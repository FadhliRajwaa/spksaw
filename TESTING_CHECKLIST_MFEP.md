# ✅ TESTING CHECKLIST - SPK MFEP v3.0

## 📅 **Date:** October 17, 2025
## 👤 **Tester:** _______________
## 🎯 **Version:** 3.0.0 (MFEP Migration Complete)

---

## 🚀 **PRE-DEPLOYMENT CHECKLIST**

### **1. Database Preparation**
- [ ] Backup database existing: `mysqldump -u root -p spksaw > backup_$(date +%Y%m%d).sql`
- [ ] Create test database: `CREATE DATABASE spksaw_test;`
- [ ] Import deployment SQL: `mysql -u root -p spksaw_test < spksaw-deploy-mfep.sql`
- [ ] Verify tables created:
  ```sql
  SHOW TABLES;
  -- Expected: admin, data_warga, tbl_kriteria, tbl_hasil_mfep, 
  --           tbl_himpunan, tbl_log_bobot, tbl_nilai_kriteria, modul
  ```
- [ ] Check data warga structure:
  ```sql
  DESC data_warga;
  -- Should have: jumlah_lansia, jumlah_disabilitas_berat, jumlah_anak_sd, etc.
  ```

### **2. File Upload Verification**
- [ ] Upload `aksi_laporan_mfep.php` → `administrator/modul/mod_laporan/`
- [ ] Upload `laporan_mfep.php` → `administrator/modul/mod_laporan/`
- [ ] Upload `perankingan_mfep.php` → `administrator/modul/mod_perankingan/`
- [ ] Verify `content_admin.php` updated (routing changed)
- [ ] Check file permissions (755 for directories, 644 for files)

---

## 🧪 **FUNCTIONAL TESTING**

### **TEST 1: Login & Dashboard** ✅
- [ ] Open: `http://localhost/spksaw/administrator/`
- [ ] Login dengan: username `admin`, password `admin`
- [ ] Verify dashboard loads
- [ ] Check welcome text: "**MFEP (Multi Factor Evaluation Process)**" visible
- [ ] **Status:** ⬜ PASS ⬜ FAIL
- [ ] **Notes:** _______________________________________

---

### **TEST 2: Menu Navigation** ✅
- [ ] Verify menu structure:
  - ✅ Data Warga (urutan 1)
  - ✅ Data Sub Kriteria (urutan 2)
  - ✅ Data Kriteria (urutan 3)
  - ✅ Laporan Hasil Perhitungan (urutan 4)
  - ✅ Perankingan (urutan 5)
- [ ] Check Data Klasifikasi NOT visible (deprecated)
- [ ] **Status:** ⬜ PASS ⬜ FAIL
- [ ] **Notes:** _______________________________________

---

### **TEST 3: Data Warga - Input Terintegrasi** ✅
- [ ] Click menu "**Data Warga**"
- [ ] Click "**Tambah Data Warga**"
- [ ] Verify form fields visible:
  - [ ] Nama Lengkap
  - [ ] Alamat
  - [ ] **Jumlah Lansia** (C1)
  - [ ] **Disabilitas Berat** (C2)
  - [ ] **Anak SD** (C3)
  - [ ] **Anak SMP** (C4)
  - [ ] **Anak SMA** (C5)
  - [ ] **Balita** (C6)
  - [ ] **Ibu Hamil** (C7)

#### **Test Data Sample:**
```
Nama: Test Warga MFEP 1
Alamat: Jl. Testing No. 123
Lansia: 2
Disabilitas: 1
Anak SD: 3
Anak SMP: 2
Anak SMA: 1
Balita: 1
Ibu Hamil: 1
```

- [ ] Submit form
- [ ] Verify data tersimpan di tabel
- [ ] **Status:** ⬜ PASS ⬜ FAIL
- [ ] **Notes:** _______________________________________

---

### **TEST 4: Data Kriteria (Pembobotan)** ✅
- [ ] Click menu "**Data Kriteria**"
- [ ] Verify tabel kriteria tampil dengan kolom:
  - Kode (C1-C8)
  - Keterangan (Nama kriteria lengkap)
  - Bobot (Nilai 0-1)
  - Jenis (Benefit/Cost)
  - Aksi (Edit/Delete)

#### **Verify Default Weights:**
```
C1: Lansia          - 0.15 (15%)
C2: Disabilitas     - 0.15 (15%)
C3: Anak SD         - 0.15 (15%)
C4: Anak SMP        - 0.15 (15%)
C5: Anak SMA        - 0.15 (15%)
C6: Balita          - 0.15 (15%)
C7: Ibu Hamil       - 0.15 (15%)
C8: Tambahan        - 0.05 (5%)
-----------------------------------
TOTAL:              - 1.00 (100%) ✅
```

- [ ] Edit satu kriteria (test)
- [ ] Verify total bobot tetap = 1.0
- [ ] **Status:** ⬜ PASS ⬜ FAIL
- [ ] **Notes:** _______________________________________

---

### **TEST 5: Hitung MFEP** ✅
- [ ] Click menu "**Laporan Hasil Perhitungan**"
- [ ] Click button "**Hitung MFEP**" (hijau)
- [ ] Wait for calculation...
- [ ] Verify alert success: "✅ Perhitungan MFEP Berhasil!"
- [ ] Check alert shows:
  - Total data
  - Ranking #1 name
  - Nilai MFEP
- [ ] **Status:** ⬜ PASS ⬜ FAIL
- [ ] **Notes:** _______________________________________

---

### **TEST 6: Laporan Hasil Perhitungan - 5 Tables** ✅

#### **Table 1: Matriks Keputusan (X)**
- [ ] Header shows: "1. Matriks Keputusan (X)"
- [ ] Columns: No, Nama Warga, C1-C8 dengan nama kriteria
- [ ] Data shows RAW values (integer)
- [ ] **Status:** ⬜ PASS ⬜ FAIL

#### **Table 2: Nilai Evaluasi Factor (E)**
- [ ] Header shows: "2. Nilai Evaluasi Factor (E)"
- [ ] Formula visible: E = X / X_max
- [ ] Columns: No, Nama Warga, E1-E8 dengan nama kriteria
- [ ] Data shows NORMALIZED values (0-1, 4 decimals)
- [ ] **Status:** ⬜ PASS ⬜ FAIL

#### **Table 3: Nilai Bobot Evaluasi (WE)**
- [ ] Header shows: "3. Nilai Bobot Evaluasi (WE)"
- [ ] Formula visible: WE = Bobot × E
- [ ] Columns: No, Nama, WE1-WE8 dengan bobot, ∑WE
- [ ] Data shows WEIGHTED values (4 decimals)
- [ ] Column ∑WE shows sum of all WE
- [ ] **Status:** ⬜ PASS ⬜ FAIL

#### **Table 4: Nilai Total Evaluasi (∑WE)**
- [ ] Header shows: "4. Nilai Total Evaluasi (∑WE)"
- [ ] Columns: No, Nama, Total WE, Formula
- [ ] Formula breakdown visible (WE1 + WE2 + ... + WE8)
- [ ] **Status:** ⬜ PASS ⬜ FAIL

#### **Table 5: Ranking Akhir**
- [ ] Header shows: "5. Ranking Akhir dan Daftar Ranking"
- [ ] Columns: Ranking, Nama, Total WE, Nilai MFEP, Rekomendasi, Status
- [ ] Data sorted by Nilai MFEP (descending)
- [ ] Status kelayakan visible:
  - Top 30%: "Sangat Layak" (green)
  - 31-60%: "Layak" (blue)
  - 61-80%: "Cukup Layak" (yellow)
  - 81-100%: "Kurang Layak" (red)
- [ ] **Status:** ⬜ PASS ⬜ FAIL

- [ ] **Overall Test 6 Status:** ⬜ PASS ⬜ FAIL
- [ ] **Notes:** _______________________________________

---

### **TEST 7: Perankingan** ✅
- [ ] Click menu "**Perankingan**"
- [ ] Verify table shows:
  - Ranking column (with medals 🏆 for top 3)
  - Nama Warga
  - **Total WE (∑WE)** - not "Total Nilai"
  - **Nilai MFEP** - not "Nilai SAW"
  - Rekomendasi (Ya/Tidak)
  - Status (Sangat Layak/Layak/Cukup/Kurang)
- [ ] Check info boxes below:
  - Total Warga
  - Layak PKH
  - Nilai Tertinggi
  - Rata-rata MFEP
- [ ] **Status:** ⬜ PASS ⬜ FAIL
- [ ] **Notes:** _______________________________________

---

### **TEST 8: Data Validation** ✅

#### **Check Database Tables:**
```sql
-- 1. Verify tbl_hasil_mfep structure
DESC tbl_hasil_mfep;
-- Expected columns: C1-C8, E1-E8, WE1-WE8, total_we, nilai_mfep, ranking

-- 2. Check data in tbl_hasil_mfep
SELECT id_hasil, nama_warga, nilai_mfep, ranking 
FROM tbl_hasil_mfep 
ORDER BY ranking 
LIMIT 5;

-- 3. Verify calculations
SELECT 
    nama_warga,
    C1, C2, C3, C4, C5, C6, C7, C8,
    E1, E2, E3, E4, E5, E6, E7, E8,
    WE1, WE2, WE3, WE4, WE5, WE6, WE7, WE8,
    total_we,
    nilai_mfep,
    ranking
FROM tbl_hasil_mfep
ORDER BY ranking
LIMIT 3;
```

- [ ] All 26 columns present
- [ ] Data types correct (INT for C, DECIMAL for E/WE/MFEP)
- [ ] Ranking sequential (1, 2, 3, ...)
- [ ] **Status:** ⬜ PASS ⬜ FAIL
- [ ] **Notes:** _______________________________________

---

### **TEST 9: Edge Cases** ⚠️

#### **Test 9.1: Bobot Invalid**
- [ ] Set total bobot ≠ 1.0 (e.g., 0.95)
- [ ] Try "Hitung MFEP"
- [ ] Verify error alert: "Total bobot harus sama dengan 1.0"
- [ ] **Status:** ⬜ PASS ⬜ FAIL

#### **Test 9.2: No Data Warga**
- [ ] Delete all warga (backup first!)
- [ ] Try "Hitung MFEP"
- [ ] Verify error: "Tidak ada data warga"
- [ ] **Status:** ⬜ PASS ⬜ FAIL

#### **Test 9.3: Zero Values**
- [ ] Add warga with all criteria = 0
- [ ] Hitung MFEP
- [ ] Verify warga appears in ranking (last position)
- [ ] **Status:** ⬜ PASS ⬜ FAIL

#### **Test 9.4: Klasifikasi Redirect**
- [ ] Try access: `?module=klasifikasi` (if old link exists)
- [ ] Verify redirect to Data Warga
- [ ] Verify alert: "Fitur Data Klasifikasi sudah tidak digunakan"
- [ ] **Status:** ⬜ PASS ⬜ FAIL

- [ ] **Overall Test 9 Status:** ⬜ PASS ⬜ FAIL
- [ ] **Notes:** _______________________________________

---

### **TEST 10: Performance** ⚡

#### **Load Testing:**
- [ ] Add 10 warga → Hitung MFEP
  - **Time:** _____ seconds
  - **Status:** ⬜ PASS (< 5s) ⬜ FAIL
  
- [ ] Add 50 warga → Hitung MFEP
  - **Time:** _____ seconds
  - **Status:** ⬜ PASS (< 15s) ⬜ FAIL

- [ ] Add 100 warga → Hitung MFEP
  - **Time:** _____ seconds
  - **Status:** ⬜ PASS (< 30s) ⬜ FAIL

- [ ] **Overall Test 10 Status:** ⬜ PASS ⬜ FAIL
- [ ] **Notes:** _______________________________________

---

### **TEST 11: UI/UX Check** 🎨
- [ ] Dashboard displays MFEP text (not SAW)
- [ ] All buttons responsive
- [ ] Tables scrollable horizontally (if needed)
- [ ] Labels clear and descriptive
- [ ] No broken images/icons
- [ ] Dark theme consistent
- [ ] Mobile responsive (test on phone)
- [ ] **Status:** ⬜ PASS ⬜ FAIL
- [ ] **Notes:** _______________________________________

---

### **TEST 12: Export Functions** 📄

#### **PDF Export (If Implemented):**
- [ ] Click "Export PDF" button
- [ ] Verify PDF downloads
- [ ] Check PDF contains:
  - Header: "MFEP (Multi Factor Evaluation Process)"
  - All 5 tables
  - Proper formatting
- [ ] **Status:** ⬜ PASS ⬜ FAIL ⬜ N/A

#### **Print Function:**
- [ ] Click "Print" button
- [ ] Verify print preview shows correctly
- [ ] Action buttons hidden in print view
- [ ] **Status:** ⬜ PASS ⬜ FAIL

- [ ] **Overall Test 12 Status:** ⬜ PASS ⬜ FAIL ⬜ N/A
- [ ] **Notes:** _______________________________________

---

## 🐛 **BUG TRACKING**

### **Critical Bugs:** 🔴
| # | Description | Steps to Reproduce | Status |
|---|-------------|-------------------|--------|
| 1 | | | ⬜ Open ⬜ Fixed |
| 2 | | | ⬜ Open ⬜ Fixed |

### **Minor Bugs:** 🟡
| # | Description | Steps to Reproduce | Status |
|---|-------------|-------------------|--------|
| 1 | | | ⬜ Open ⬜ Fixed |
| 2 | | | ⬜ Open ⬜ Fixed |

---

## 📊 **TEST SUMMARY**

### **Overall Results:**
```
Total Tests: 12
Passed:      _____ / 12
Failed:      _____ / 12
N/A:         _____ / 12

Pass Rate:   _____ %
```

### **Critical Issues Found:**
- [ ] None ✅
- [ ] Yes (list below):
  1. _______________________________________
  2. _______________________________________

### **Ready for Production?**
- [ ] ✅ YES - All tests passed
- [ ] ⚠️ YES with minor issues (list above)
- [ ] ❌ NO - Critical bugs found

---

## 🎯 **DEPLOYMENT DECISION**

### **Approved By:**
- **Tester Name:** _______________________
- **Date:** _____________________________
- **Signature:** _________________________

### **Deployment Status:**
- [ ] ✅ APPROVED - Deploy to production
- [ ] ⚠️ APPROVED - Deploy with monitoring
- [ ] ❌ REJECTED - Fix issues first

### **Notes:**
```
_________________________________________________________
_________________________________________________________
_________________________________________________________
```

---

## 📞 **SUPPORT CONTACTS**

**Developer:** Jarvis AI Assistant  
**Version:** 3.0.0 (MFEP)  
**Date:** October 17, 2025  

**Emergency Rollback:**
```bash
# Restore backup
mysql -u root -p spksaw < backup_YYYYMMDD.sql

# Revert routing in content_admin.php
# Change back to laporan.php and perankingan.php
```

---

**🎊 GOOD LUCK WITH TESTING!** 🎊
