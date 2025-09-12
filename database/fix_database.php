<?php
// Script untuk memperbaiki struktur database PKH
include "../configurasi/koneksi.php";

echo "<h2>Script Perbaikan Database PKH</h2>";

// 1. Drop semua tabel untuk rebuild complete
$drop_tables = [
    "DROP TABLE IF EXISTS tbl_hasil_saw",
    "DROP TABLE IF EXISTS tbl_klasifikasi", 
    "DROP TABLE IF EXISTS tbl_kriteria",
    "DROP TABLE IF EXISTS data_warga",
    "DROP TABLE IF EXISTS absensi",
    "DROP TABLE IF EXISTS alat",
    "DROP TABLE IF EXISTS berita",
    "DROP TABLE IF EXISTS download",
    "DROP TABLE IF EXISTS gallery",
    "DROP TABLE IF EXISTS guru",
    "DROP TABLE IF EXISTS halamanstatis",
    "DROP TABLE IF EXISTS hasil",
    "DROP TABLE IF EXISTS jadwalujian",
    "DROP TABLE IF EXISTS kelas",
    "DROP TABLE IF EXISTS matapelajaran",
    "DROP TABLE IF EXISTS materi",
    "DROP TABLE IF EXISTS modul",
    "DROP TABLE IF EXISTS pengumuman",
    "DROP TABLE IF EXISTS polling",
    "DROP TABLE IF EXISTS siswa",
    "DROP TABLE IF EXISTS soal",
    "DROP TABLE IF EXISTS tbl_himpunankriteria",
    "DROP TABLE IF EXISTS tbl_hasil",
    "DROP TABLE IF EXISTS ujian",
    "DROP VIEW IF EXISTS v_analisa",
    "DROP VIEW IF EXISTS v_siswa"
];

foreach($drop_tables as $sql) {
    if(mysqli_query($koneksi, $sql)) {
        echo "✓ " . substr($sql, 0, 50) . "...<br>";
    }
}

// 2. Buat tabel data_warga terlebih dahulu (parent table)
$create_warga = "
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
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_warga`),
  UNIQUE KEY `idx_nama_lengkap` (`nama_lengkap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabel Data Warga untuk PKH';
";

if(mysqli_query($koneksi, $create_warga)) {
    echo "✓ Tabel data_warga berhasil dibuat<br>";
} else {
    echo "✗ Error membuat tabel data_warga: " . mysqli_error($koneksi) . "<br>";
}

// 3. Buat tabel tbl_kriteria
$create_kriteria = "
CREATE TABLE `tbl_kriteria` (
  `id_kriteria` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kriteria` varchar(100) NOT NULL,
  `bobot` decimal(3,2) NOT NULL DEFAULT 0.00,
  `jenis` enum('benefit','cost') NOT NULL DEFAULT 'benefit',
  `keterangan` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kriteria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabel Kriteria PKH';
";

if(mysqli_query($koneksi, $create_kriteria)) {
    echo "✓ Tabel tbl_kriteria berhasil dibuat<br>";
} else {
    echo "✗ Error membuat tabel tbl_kriteria: " . mysqli_error($koneksi) . "<br>";
}

// 4. Buat tabel tbl_klasifikasi (child table dengan foreign key)
$create_klasifikasi = "
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
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_klasifikasi`),
  KEY `idx_id_warga` (`id_warga`),
  FOREIGN KEY (`id_warga`) REFERENCES `data_warga`(`id_warga`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabel Klasifikasi Data Warga untuk PKH';
";

if(mysqli_query($koneksi, $create_klasifikasi)) {
    echo "✓ Tabel tbl_klasifikasi berhasil dibuat<br>";
} else {
    echo "✗ Error membuat tabel tbl_klasifikasi: " . mysqli_error($koneksi) . "<br>";
}

// 5. Buat tabel tbl_hasil_saw
$create_hasil = "
CREATE TABLE IF NOT EXISTS `tbl_hasil_saw` (
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
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_hasil`),
  KEY `idx_id_warga` (`id_warga`),
  KEY `idx_ranking` (`ranking`),
  FOREIGN KEY (`id_warga`) REFERENCES `data_warga`(`id_warga`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabel Hasil Analisis SAW';
";

if(mysqli_query($koneksi, $create_hasil)) {
    echo "✓ Tabel tbl_hasil_saw berhasil dibuat<br>";
} else {
    echo "✗ Error membuat tabel tbl_hasil_saw: " . mysqli_error($koneksi) . "<br>";
}

// 6. Insert data kriteria default
$insert_kriteria = "
INSERT IGNORE INTO `tbl_kriteria` (`nama_kriteria`, `bobot`, `jenis`, `keterangan`) VALUES
('Jumlah Lansia', 0.15, 'benefit', 'Jumlah lansia dalam keluarga'),
('Jumlah Disabilitas Berat', 0.20, 'benefit', 'Jumlah anggota keluarga dengan disabilitas berat'),
('Jumlah Anak SD', 0.15, 'benefit', 'Jumlah anak usia sekolah dasar'),
('Jumlah Anak SMP', 0.10, 'benefit', 'Jumlah anak usia sekolah menengah pertama'),
('Jumlah Anak SMA', 0.10, 'benefit', 'Jumlah anak usia sekolah menengah atas'),
('Jumlah Balita', 0.15, 'benefit', 'Jumlah balita dalam keluarga'),
('Jumlah Ibu Hamil', 0.15, 'benefit', 'Jumlah ibu hamil dalam keluarga'),
('Reserved', 0.00, 'benefit', 'Kriteria cadangan untuk pengembangan')
";

if(mysqli_query($koneksi, $insert_kriteria)) {
    echo "✓ Data kriteria default berhasil diinsert<br>";
} else {
    echo "✗ Error insert data kriteria: " . mysqli_error($koneksi) . "<br>";
}

// 7. Insert sample data warga
$insert_sample = "
INSERT IGNORE INTO `data_warga` (`nama_lengkap`, `alamat`, `jumlah_lansia`, `jumlah_disabilitas_berat`, `jumlah_anak_sd`, `jumlah_anak_smp`, `jumlah_anak_sma`, `jumlah_balita`, `jumlah_ibu_hamil`) VALUES
('Siti Aminah', 'Jl. Merdeka No. 123', 1, 0, 2, 1, 0, 1, 0),
('Budi Santoso', 'Jl. Sudirman No. 45', 0, 1, 1, 0, 1, 2, 1),
('Rina Wati', 'Jl. Ahmad Yani No. 67', 2, 0, 0, 2, 1, 0, 0),
('Ahmad Fauzi', 'Jl. Diponegoro No. 89', 1, 1, 3, 0, 0, 1, 1),
('Dewi Sartika', 'Jl. Kartini No. 12', 0, 0, 1, 2, 1, 1, 0)
";

if(mysqli_query($koneksi, $insert_sample)) {
    echo "✓ Sample data warga berhasil diinsert<br>";
} else {
    echo "✗ Error insert sample data: " . mysqli_error($koneksi) . "<br>";
}

// 8. Generate klasifikasi otomatis dari data warga
$query_warga = "SELECT * FROM data_warga";
$result_warga = mysqli_query($koneksi, $query_warga);

while($warga = mysqli_fetch_array($result_warga)) {
    $insert_klasifikasi_data = "
    INSERT IGNORE INTO `tbl_klasifikasi` 
    (`id_warga`, `C1`, `C2`, `C3`, `C4`, `C5`, `C6`, `C7`, `C8`) 
    VALUES 
    ({$warga['id_warga']}, {$warga['jumlah_lansia']}, {$warga['jumlah_disabilitas_berat']}, 
     {$warga['jumlah_anak_sd']}, {$warga['jumlah_anak_smp']}, {$warga['jumlah_anak_sma']}, 
     {$warga['jumlah_balita']}, {$warga['jumlah_ibu_hamil']}, 0)
    ";
    
    if(mysqli_query($koneksi, $insert_klasifikasi_data)) {
        echo "✓ Klasifikasi untuk {$warga['nama_lengkap']} berhasil dibuat<br>";
    }
}

// 9. Update admin password untuk konsistensi
$update_admin = "UPDATE admin SET nama_lengkap='Admin PKH System', password='200ceb26807d6bf99fd6f4f0d1ca54d4' WHERE username='administrator'";
if(mysqli_query($koneksi, $update_admin)) {
    echo "✓ Admin data berhasil diupdate<br>";
}

echo "<br><h3>✅ Perbaikan Database Selesai!</h3>";
echo "<p>Database PKH sudah siap digunakan dengan struktur yang benar.</p>";
echo "<p><a href='../administrator/media_admin.php?module=home'>Kembali ke Dashboard</a></p>";
?>
