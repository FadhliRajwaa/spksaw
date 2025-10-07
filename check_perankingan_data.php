<?php
include 'configurasi/koneksi.php';

echo "<h3>🔍 VERIFIKASI DATA PERANKINGAN</h3>";

echo "<h4>📊 Data Warga (data_warga):</h4>";
$data_warga = mysqli_query($koneksi, "SELECT id_warga, nama_lengkap, alamat FROM data_warga ORDER BY nama_lengkap");
$total_warga = mysqli_num_rows($data_warga);
echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
echo "<tr style='background: #f0f0f0;'><th>ID</th><th>Nama</th><th>Alamat</th></tr>";
while($row = mysqli_fetch_array($data_warga)) {
    echo "<tr><td>{$row['id_warga']}</td><td>{$row['nama_lengkap']}</td><td>{$row['alamat']}</td></tr>";
}
echo "</table>";
echo "<p><strong>Total data_warga: $total_warga</strong></p>";

echo "<h4>🏆 Data Hasil SAW (tbl_hasil_saw):</h4>";
$hasil_saw = mysqli_query($koneksi, "SELECT h.id_hasil, h.id_warga, h.nama_warga, h.skor_akhir, h.ranking FROM tbl_hasil_saw h ORDER BY h.ranking");
$total_hasil = mysqli_num_rows($hasil_saw);
echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
echo "<tr style='background: #f0f0f0;'><th>ID Hasil</th><th>ID Warga</th><th>Nama</th><th>Skor</th><th>Ranking</th></tr>";
while($row = mysqli_fetch_array($hasil_saw)) {
    echo "<tr><td>{$row['id_hasil']}</td><td>{$row['id_warga']}</td><td>{$row['nama_warga']}</td><td>{$row['skor_akhir']}</td><td>{$row['ranking']}</td></tr>";
}
echo "</table>";
echo "<p><strong>Total tbl_hasil_saw: $total_hasil</strong></p>";

echo "<h4>📋 Data Klasifikasi (tbl_klasifikasi):</h4>";
$klasifikasi = mysqli_query($koneksi, "SELECT k.id_klasifikasi, k.id_warga, w.nama_lengkap FROM tbl_klasifikasi k LEFT JOIN data_warga w ON k.id_warga = w.id_warga ORDER BY w.nama_lengkap");
$total_klasifikasi = mysqli_num_rows($klasifikasi);
echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
echo "<tr style='background: #f0f0f0;'><th>ID Klasifikasi</th><th>ID Warga</th><th>Nama</th></tr>";
while($row = mysqli_fetch_array($klasifikasi)) {
    echo "<tr><td>{$row['id_klasifikasi']}</td><td>{$row['id_warga']}</td><td>{$row['nama_lengkap']}</td></tr>";
}
echo "</table>";
echo "<p><strong>Total tbl_klasifikasi: $total_klasifikasi</strong></p>";

echo "<h4>❗ ANALISIS MASALAH:</h4>";
if($total_warga > $total_hasil) {
    echo "<p style='color: red;'><strong>MASALAH DITEMUKAN!</strong></p>";
    echo "<p>Data Warga: $total_warga | Hasil SAW: $total_hasil</p>";
    echo "<p>Ada " . ($total_warga - $total_hasil) . " warga yang belum memiliki hasil SAW!</p>";
    
    // Find missing warga
    echo "<h4>👤 Warga yang hilang dari hasil SAW:</h4>";
    $missing = mysqli_query($koneksi, "
        SELECT w.id_warga, w.nama_lengkap 
        FROM data_warga w 
        LEFT JOIN tbl_hasil_saw h ON w.id_warga = h.id_warga 
        WHERE h.id_warga IS NULL
    ");
    while($row = mysqli_fetch_array($missing)) {
        echo "<p style='color: red;'>• {$row['nama_lengkap']} (ID: {$row['id_warga']})</p>";
    }
} else {
    echo "<p style='color: green;'><strong>✅ TIDAK ADA MASALAH</strong></p>";
    echo "<p>Semua data warga memiliki hasil SAW yang sesuai.</p>";
}
?>
