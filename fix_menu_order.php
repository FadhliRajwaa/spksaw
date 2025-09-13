<?php
// Script to fix menu order - Pembobotan Kriteria should come before Data Kriteria
include "configurasi/koneksi.php";

echo "<h2>🔧 Perbaikan Urutan Menu</h2>";

// Update menu order
echo "<h3>📋 Mengatur Urutan Menu...</h3>";

// Set Pembobotan Kriteria to order 3 (after Data Warga which is likely 2)
$update1 = mysqli_query($koneksi, "UPDATE modul SET urutan = 3 WHERE nama_modul = 'Pembobotan Kriteria'");
echo $update1 ? "✅ Pembobotan Kriteria diset ke urutan 3<br>" : "❌ Gagal update Pembobotan Kriteria<br>";

// Set Data Kriteria to order 4 (after Pembobotan)
$update2 = mysqli_query($koneksi, "UPDATE modul SET urutan = 4 WHERE nama_modul = 'Data Kriteria'");
echo $update2 ? "✅ Data Kriteria diset ke urutan 4<br>" : "❌ Gagal update Data Kriteria<br>";

// Set Data Klasifikasi to order 5
$update3 = mysqli_query($koneksi, "UPDATE modul SET urutan = 5 WHERE nama_modul = 'Data Klasifikasi'");
echo $update3 ? "✅ Data Klasifikasi diset ke urutan 5<br>" : "❌ Gagal update Data Klasifikasi<br>";

echo "<h3>📊 Status Menu Saat Ini:</h3>";
$result = mysqli_query($koneksi, "SELECT nama_modul, link, urutan FROM modul ORDER BY urutan");
echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
echo "<tr style='background: #f0f0f0;'><th>Urutan</th><th>Nama Modul</th><th>Link</th></tr>";
while($row = mysqli_fetch_array($result)) {
    echo "<tr><td>{$row['urutan']}</td><td>{$row['nama_modul']}</td><td>{$row['link']}</td></tr>";
}
echo "</table>";

echo "<br><strong>✅ Urutan menu telah diperbaiki!</strong><br>";
echo "<a href='administrator/media_admin.php?module=home' style='background: #007bff; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px;'>🏠 Kembali ke Admin</a>";
?>
