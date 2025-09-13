<?php
// Script untuk memperbaiki menu Data Kriteria
// Mengembalikan ke struktur menu yang benar: satu menu "Data Kriteria" saja

include "../configurasi/koneksi.php";

echo "<h2>🔧 Memperbaiki Menu Data Kriteria</h2>";

// 1. Hapus semua menu kriteria yang ada (baik yang benar maupun salah)
$delete_kriteria_menus = "DELETE FROM modul WHERE 
    nama_modul LIKE '%Kriteria%' OR 
    nama_modul LIKE '%Pembobotan%' OR 
    nama_modul LIKE '%Himpunan%'";

if (mysqli_query($koneksi, $delete_kriteria_menus)) {
    echo "✅ Menu kriteria lama berhasil dihapus<br>";
} else {
    echo "❌ Error menghapus menu lama: " . mysqli_error($koneksi) . "<br>";
}

// 2. Insert menu Data Kriteria yang benar
$insert_correct_menu = "INSERT INTO modul (nama_modul, link, status, aktif, urutan, type) 
VALUES ('Data Kriteria', '?module=kriteria', 'admin', 'Y', 2, 'Data')";

if (mysqli_query($koneksi, $insert_correct_menu)) {
    echo "✅ Menu 'Data Kriteria' berhasil ditambahkan<br>";
} else {
    echo "❌ Error menambah menu: " . mysqli_error($koneksi) . "<br>";
}

// 3. Verifikasi hasil
echo "<h3>📋 Verifikasi Menu Saat Ini:</h3>";
$check_menus = mysqli_query($koneksi, "SELECT * FROM modul ORDER BY urutan ASC");

echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
echo "<tr style='background-color: #f0f0f0;'>";
echo "<th style='padding: 8px;'>ID</th>";
echo "<th style='padding: 8px;'>Nama Menu</th>";
echo "<th style='padding: 8px;'>Link</th>";
echo "<th style='padding: 8px;'>Urutan</th>";
echo "<th style='padding: 8px;'>Status</th>";
echo "</tr>";

while ($menu = mysqli_fetch_array($check_menus)) {
    $bg_color = strpos($menu['nama_modul'], 'Kriteria') !== false ? '#e8f5e8' : 'white';
    echo "<tr style='background-color: $bg_color;'>";
    echo "<td style='padding: 8px;'>" . $menu['id_modul'] . "</td>";
    echo "<td style='padding: 8px;'><strong>" . $menu['nama_modul'] . "</strong></td>";
    echo "<td style='padding: 8px;'>" . $menu['link'] . "</td>";
    echo "<td style='padding: 8px;'>" . $menu['urutan'] . "</td>";
    echo "<td style='padding: 8px;'>" . $menu['aktif'] . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<div style='background-color: #d4edda; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
echo "<h3>✅ Perbaikan Selesai!</h3>";
echo "<p><strong>Hasil:</strong></p>";
echo "<ul>";
echo "<li>✅ Menu 'Pembobotan Kriteria' dan 'Himpunan Kriteria' dihapus</li>";
echo "<li>✅ Menu 'Data Kriteria' tunggal berhasil dibuat</li>";
echo "<li>✅ Interface terpadu sudah siap digunakan</li>";
echo "</ul>";
echo "<p><strong>Akses:</strong> <a href='../media_admin.php?module=kriteria' target='_blank'>Klik di sini untuk mengakses Data Kriteria</a></p>";
echo "</div>";

mysqli_close($koneksi);
?>
