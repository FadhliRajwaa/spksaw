<?php
// Script update menu PKH sesuai permintaan client
include "../configurasi/koneksi.php";

echo "<h2>Update Menu PKH Sesuai Permintaan Client</h2>";

// 1. Buat tabel modul jika belum ada
$create_modul = "
CREATE TABLE IF NOT EXISTS `modul` (
  `id_modul` int(5) NOT NULL AUTO_INCREMENT,
  `nama_modul` varchar(50) NOT NULL,
  `link` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'Data',
  `urutan` int(5) NOT NULL DEFAULT 1,
  `aktif` enum('Y','N') NOT NULL DEFAULT 'Y',
  `status` varchar(20) NOT NULL DEFAULT 'admin',
  PRIMARY KEY (`id_modul`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if(mysqli_query($koneksi, $create_modul)) {
    echo "✓ Tabel modul berhasil dibuat/sudah ada<br>";
}

// 2. Hapus semua modul lama
$delete_old = "DELETE FROM modul";
if(mysqli_query($koneksi, $delete_old)) {
    echo "✓ Semua modul lama berhasil dihapus<br>";
}

// 3. Insert modul PKH baru sesuai permintaan client
$insert_modules = [
    // DATA MASTER
    [
        'nama_modul' => 'Data Warga',
        'link' => '?module=warga',
        'type' => 'Data',
        'urutan' => 1,
        'aktif' => 'Y',
        'status' => 'admin'
    ],
    [
        'nama_modul' => 'Data Kriteria',
        'link' => '?module=kriteria',
        'type' => 'Data',
        'urutan' => 2,
        'aktif' => 'Y',
        'status' => 'admin'
    ],
    [
        'nama_modul' => 'Data Klasifikasi',
        'link' => '?module=klasifikasi',
        'type' => 'Data',
        'urutan' => 3,
        'aktif' => 'Y',
        'status' => 'admin'
    ],
    
    // LAPORAN
    [
        'nama_modul' => 'Laporan Hasil Analisa',
        'link' => '?module=laporan&act=analisa',
        'type' => 'Report',
        'urutan' => 1,
        'aktif' => 'Y',
        'status' => 'admin'
    ],
    [
        'nama_modul' => 'Perankingan',
        'link' => '?module=perankingan',
        'type' => 'Report',
        'urutan' => 2,
        'aktif' => 'Y',
        'status' => 'admin'
    ]
];

foreach($insert_modules as $module) {
    $sql = "INSERT INTO modul (nama_modul, link, type, urutan, aktif, status) VALUES 
            ('{$module['nama_modul']}', '{$module['link']}', '{$module['type']}', 
             {$module['urutan']}, '{$module['aktif']}', '{$module['status']}')";
    
    if(mysqli_query($koneksi, $sql)) {
        echo "✓ Modul '{$module['nama_modul']}' berhasil ditambahkan<br>";
    } else {
        echo "✗ Error menambah modul '{$module['nama_modul']}': " . mysqli_error($koneksi) . "<br>";
    }
}

// 4. Hapus kolom blokir dan id_session dari tabel admin sesuai permintaan
$alter_admin = [
    "ALTER TABLE admin DROP COLUMN IF EXISTS blokir",
    "ALTER TABLE admin DROP COLUMN IF EXISTS id_session"
];

foreach($alter_admin as $sql) {
    if(mysqli_query($koneksi, $sql)) {
        echo "✓ " . substr($sql, 0, 50) . "... berhasil<br>";
    }
}

// 4. Pastikan struktur tabel sesuai permintaan client
echo "<br><h3>Verifikasi Struktur Database PKH:</h3>";

// Cek struktur data_warga
$check_warga = mysqli_query($koneksi, "DESCRIBE data_warga");
echo "<strong>Tabel data_warga:</strong><br>";
while($column = mysqli_fetch_array($check_warga)) {
    echo "- {$column['Field']} ({$column['Type']})<br>";
}

// Cek total data
$count_warga = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM data_warga");
$total = mysqli_fetch_array($count_warga);
echo "<br><strong>Total Data Warga:</strong> {$total['total']} data<br>";

$count_kriteria = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tbl_kriteria");
$total_kriteria = mysqli_fetch_array($count_kriteria);
echo "<strong>Total Kriteria PKH:</strong> {$total_kriteria['total']} kriteria<br>";

$count_klasifikasi = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tbl_klasifikasi");
$total_klasifikasi = mysqli_fetch_array($count_klasifikasi);
echo "<strong>Total Data Klasifikasi:</strong> {$total_klasifikasi['total']} data<br>";

echo "<br><h3>✅ Update Menu PKH Selesai!</h3>";
echo "<p><strong>Menu yang aktif sekarang:</strong></p>";
echo "<ul>";
echo "<li><strong>Data Master:</strong> Data Warga, Data Kriteria, Data Klasifikasi</li>";
echo "<li><strong>Laporan:</strong> Laporan Hasil Analisa, Perankingan</li>";
echo "</ul>";

echo "<p><strong>Dihapus sesuai permintaan:</strong></p>";
echo "<ul>";
echo "<li>❌ Data Kelas</li>";
echo "<li>❌ Menu Pengaturan</li>";
echo "<li>❌ Kolom blokir & id_session di tabel admin</li>";
echo "</ul>";

echo "<p><a href='../administrator/media_admin.php?module=home' class='btn btn-primary'>Kembali ke Dashboard</a></p>";
?>
