# ⚡ QUICK START GUIDE - SPK MFEP v3.0

**🚀 Deploy dalam 5 Menit!**

---

## 🎯 **UNTUK APA INI?**

Kalau Tuan Fadhli mau **langsung deploy dan test** sistem MFEP tanpa baca dokumentasi panjang, ikuti guide ini!

---

## ✅ **LANGKAH 1: BACKUP DATABASE (30 detik)**

```bash
# Buka Command Prompt / Terminal
cd e:\Xampp\htdocs\spksaw

# Backup database lama (PENTING!)
e:\Xampp\mysql\bin\mysqldump -u root -p spksaw > backup_before_mfep.sql

# Enter password MySQL (biasanya kosong, tinggal Enter)
```

**✅ Selesai!** File backup ada di folder project.

---

## ✅ **LANGKAH 2: RUN MIGRATION (1 menit)**

### **Pilih salah satu:**

### **Option A: Via phpMyAdmin (MUDAH)** ⭐ RECOMMENDED
```
1. Buka http://localhost/phpmyadmin
2. Pilih database "spksaw"
3. Tab "Import"
4. Choose file: migration_saw_to_mfep.sql
5. Klik "Go"
6. Tunggu sampai success! ✅
```

### **Option B: Via Command Line**
```bash
e:\Xampp\mysql\bin\mysql -u root -p spksaw < database/migration_saw_to_mfep.sql
```

**✅ Selesai!** Database sudah updated ke MFEP!

---

## ✅ **LANGKAH 3: CEK FILE PHP (10 detik)**

Verify 3 file baru sudah ada:

```
📂 e:\Xampp\htdocs\spksaw\administrator\modul\
   📂 mod_laporan\
      ✅ aksi_laporan_mfep.php      (NEW - 298 lines)
      ✅ laporan_mfep.php            (NEW - 390 lines)
   📂 mod_perankingan\
      ✅ perankingan_mfep.php        (NEW - 285 lines)
```

**Kalau belum ada?** Copy dari hasil generate Jarvis sebelumnya!

---

## ✅ **LANGKAH 4: UPDATE ROUTING (SUDAH SELESAI!)** ✅

File `content_admin.php` **SUDAH DIUPDATE** oleh Jarvis!

Verify aja:
```php
// Buka: administrator/content_admin.php
// Line 536: Should be laporan_mfep.php ✅
// Line 543: Should be perankingan_mfep.php ✅
```

**✅ Selesai!** Routing sudah correct!

---

## ✅ **LANGKAH 5: TEST SISTEM (2 menit)**

### **Test 1: Login** (10 detik)
```
1. Buka: http://localhost/spksaw/administrator/
2. Login: admin / admin
3. Check dashboard → Should see "MFEP" text ✅
```

### **Test 2: Menu** (10 detik)
```
Check menu sidebar:
✅ Data Warga
✅ Data Sub Kriteria  
✅ Data Kriteria
✅ Laporan Hasil Perhitungan
✅ Perankingan
❌ Data Klasifikasi (should NOT exist)
```

### **Test 3: Input Data Warga** (30 detik)
```
1. Klik "Data Warga"
2. Klik "Tambah Data Warga"
3. Check form ada input:
   ✅ Nama Lengkap
   ✅ Alamat
   ✅ Jumlah Lansia (C1)
   ✅ Disabilitas Berat (C2)
   ✅ Anak SD, SMP, SMA (C3-C5)
   ✅ Balita (C6)
   ✅ Ibu Hamil (C7)

4. Input test data:
   Nama: Test MFEP 1
   Alamat: Jl. Test
   Lansia: 2
   Disabilitas: 1
   Anak SD: 2
   Anak SMP: 1
   Anak SMA: 1
   Balita: 1
   Ibu Hamil: 0

5. Klik "Simpan Data"
6. Should success! ✅
```

### **Test 4: Hitung MFEP** (30 detik)
```
1. Klik menu "Laporan Hasil Perhitungan"
2. Klik button hijau "Hitung MFEP"
3. Wait for alert...
4. Should show: "✅ Perhitungan MFEP Berhasil!"
5. Page refresh otomatis
```

### **Test 5: Lihat 5 Tabel** (30 detik)
```
Scroll down, should see 5 tables:

1️⃣ Matriks Keputusan (X)
   ✅ Shows raw data C1-C8
   ✅ Nama kriteria visible

2️⃣ Nilai Evaluasi Factor (E)
   ✅ Shows normalized values 0-1
   ✅ Formula E = X/Xmax displayed

3️⃣ Nilai Bobot Evaluasi (WE)
   ✅ Shows weighted values
   ✅ Bobot displayed in header

4️⃣ Nilai Total Evaluasi (∑WE)
   ✅ Shows sum of WE
   ✅ Formula breakdown

5️⃣ Ranking Akhir
   ✅ Shows ranking list
   ✅ Status kelayakan
   ✅ Medals for top 3 🏆
```

### **Test 6: Lihat Perankingan** (10 detik)
```
1. Klik menu "Perankingan"
2. Check table shows:
   ✅ Ranking column
   ✅ Total WE (bukan "Total Nilai")
   ✅ Nilai MFEP (bukan "Nilai SAW")
   ✅ Rekomendasi
   ✅ Status kelayakan
```

---

## 🎉 **SELESAI!**

**Kalau semua test ✅ PASS, berarti sistem MFEP sudah siap!**

---

## 🐛 **TROUBLESHOOTING CEPAT**

### **Problem 1: Error 500 / Blank Page**
```
Solution:
1. Check error di: e:\Xampp\apache\logs\error.log
2. Verify file PHP ada di folder yang benar
3. Clear browser cache (Ctrl+F5)
```

### **Problem 2: "Perhitungan MFEP gagal!"**
```
Solution:
1. Check total bobot kriteria = 1.0
2. Pastikan ada data warga di database
3. Check query error di phpMyAdmin
```

### **Problem 3: Menu "Data Klasifikasi" masih muncul**
```
Solution:
1. Re-run migration script
2. Clear browser cache
3. Check modul table di database
```

### **Problem 4: Routing tidak berubah**
```
Solution:
1. Verify content_admin.php updated
2. Line 536: include "modul/mod_laporan/laporan_mfep.php";
3. Line 543: include "modul/mod_perankingan/perankingan_mfep.php";
4. Save and refresh
```

### **Problem 5: Tabel tidak muncul setelah hitung**
```
Solution:
1. Check tbl_hasil_mfep di database
2. SELECT * FROM tbl_hasil_mfep;
3. If empty, re-run "Hitung MFEP"
4. Check bobot kriteria valid
```

---

## 🆘 **ROLLBACK KE SAW (Emergency)**

**Kalau ada masalah CRITICAL:**

```bash
# Step 1: Stop XAMPP

# Step 2: Restore backup
e:\Xampp\mysql\bin\mysql -u root -p spksaw < backup_before_mfep.sql

# Step 3: Revert routing
# Edit content_admin.php:
# Line 536: include "modul/mod_laporan/laporan.php";
# Line 543: include "modul/mod_perankingan/perankingan.php";

# Step 4: Start XAMPP

# Step 5: Test old system
```

---

## 📞 **NEED HELP?**

### **Check These Files:**
1. `MIGRATION_GUIDE_SAW_TO_MFEP.md` - Detailed migration steps
2. `TESTING_CHECKLIST_MFEP.md` - Complete testing procedures
3. `COMPLETE_SUMMARY_MFEP.md` - Full project documentation

### **Database Verification:**
```sql
-- Check tables exist
SHOW TABLES;

-- Check tbl_hasil_mfep structure
DESC tbl_hasil_mfep;

-- Check sample data
SELECT * FROM tbl_hasil_mfep LIMIT 5;

-- Check modul menu
SELECT * FROM modul ORDER BY urutan;
```

---

## ✅ **CHECKLIST FINAL**

Sebelum deploy ke production, pastikan:

- [ ] Database di-backup ✅
- [ ] Migration script sukses ✅
- [ ] 3 file PHP baru ada ✅
- [ ] Routing updated ✅
- [ ] Login works ✅
- [ ] Menu structure correct ✅
- [ ] Data Warga input works ✅
- [ ] Hitung MFEP works ✅
- [ ] 5 tabel displayed ✅
- [ ] Perankingan displayed ✅

**Semua ✅? SIAP PRODUCTION!** 🚀

---

## 🎯 **NEXT ACTION**

```
IF all tests PASS:
   ✅ Deploy to production
   ✅ Train users
   ✅ Monitor for 1 week
   
IF any test FAIL:
   ⚠️ Check TROUBLESHOOTING section
   ⚠️ Run TESTING_CHECKLIST_MFEP.md
   ⚠️ Contact developer if needed
```

---

## 📊 **QUICK REFERENCE**

### **File Locations:**
```
Database Scripts:
📄 database/migration_saw_to_mfep.sql
📄 spksaw-deploy-mfep.sql

PHP Files:
📄 administrator/modul/mod_laporan/aksi_laporan_mfep.php
📄 administrator/modul/mod_laporan/laporan_mfep.php
📄 administrator/modul/mod_perankingan/perankingan_mfep.php
📄 administrator/content_admin.php (MODIFIED)

Documentation:
📄 README.md (UPDATED)
📄 MIGRATION_GUIDE_SAW_TO_MFEP.md
📄 TESTING_CHECKLIST_MFEP.md
📄 COMPLETE_SUMMARY_MFEP.md
📄 QUICK_START_MFEP.md (this file)
```

### **Database Tables:**
```
Main Tables:
✅ tbl_hasil_mfep (new)
✅ data_warga (updated)
✅ tbl_kriteria
✅ tbl_nilai_kriteria
✅ modul (updated)

Backup Tables:
⚠️ backup_tbl_hasil_saw
⚠️ backup_tbl_klasifikasi
⚠️ backup_data_warga
```

### **Key URLs:**
```
🌐 Admin Panel: http://localhost/spksaw/administrator/
🌐 phpMyAdmin: http://localhost/phpmyadmin
🌐 Data Warga: ?module=warga
🌐 Laporan: ?module=laporan&act=analisa
🌐 Perankingan: ?module=perankingan
```

---

<div align="center">
  <h2>🎊 GOOD LUCK! 🎊</h2>
  <p><strong>Version 3.0.0 - MFEP</strong></p>
  <p><em>Deployment Made Easy</em></p>
</div>

---

**📅 Created:** October 17, 2025  
**👤 By:** Jarvis AI Assistant  
**🎯 For:** Tuan Fadhli  
**⏱️ Time:** 5 minutes deployment  

**🚀 Status: READY TO DEPLOY!**
