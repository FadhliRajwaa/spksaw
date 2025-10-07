<?php
include('configurasi/koneksi.php');

echo "<h2>Data Comparison Test</h2>";

echo "<h3>1. Web Display Query (perankingan.php)</h3>";
$hasil = mysqli_query($koneksi, "
    SELECT h.*, w.alamat 
    FROM tbl_hasil_saw h 
    JOIN data_warga w ON h.id_warga = w.id_warga 
    ORDER BY h.ranking ASC
");

echo "<table border='1'>";
echo "<tr><th>Ranking</th><th>Nama</th><th>Alamat</th><th>Skor</th><th>Rekomendasi</th></tr>";
while($row = mysqli_fetch_array($hasil)) {
    echo "<tr>";
    echo "<td>{$row['ranking']}</td>";
    echo "<td>{$row['nama_warga']}</td>";
    echo "<td>{$row['alamat']}</td>";
    echo "<td>" . number_format($row['skor_akhir'], 4) . "</td>";
    echo "<td>{$row['rekomendasi']}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h3>2. Export PDF Query (export_pdf.php)</h3>";
$hasil = mysqli_query($koneksi, "
    SELECT h.*, w.alamat, w.jumlah_lansia, w.jumlah_disabilitas_berat, 
           w.jumlah_anak_sd, w.jumlah_anak_smp, w.jumlah_anak_sma, 
           w.jumlah_balita, w.jumlah_ibu_hamil
    FROM tbl_hasil_saw h 
    JOIN data_warga w ON h.id_warga = w.id_warga 
    ORDER BY h.ranking ASC
");

echo "<table border='1'>";
echo "<tr><th>Ranking</th><th>Nama</th><th>Alamat</th><th>Skor</th><th>Rekomendasi</th><th>Details</th></tr>";
while($row = mysqli_fetch_array($hasil)) {
    // Build family details like in export_pdf.php
    $family_details = [];
    if($row['jumlah_lansia'] > 0) $family_details[] = "Lansia: {$row['jumlah_lansia']}";
    if($row['jumlah_disabilitas_berat'] > 0) $family_details[] = "Disabilitas: {$row['jumlah_disabilitas_berat']}";
    if($row['jumlah_anak_sd'] > 0) $family_details[] = "Anak SD: {$row['jumlah_anak_sd']}";
    if($row['jumlah_anak_smp'] > 0) $family_details[] = "Anak SMP: {$row['jumlah_anak_smp']}";
    if($row['jumlah_anak_sma'] > 0) $family_details[] = "Anak SMA: {$row['jumlah_anak_sma']}";
    if($row['jumlah_balita'] > 0) $family_details[] = "Balita: {$row['jumlah_balita']}";
    if($row['jumlah_ibu_hamil'] > 0) $family_details[] = "Ibu Hamil: {$row['jumlah_ibu_hamil']}";
    
    $family_info = empty($family_details) ? 'Tidak ada dependan' : implode(', ', $family_details);
    
    echo "<tr>";
    echo "<td>{$row['ranking']}</td>";
    echo "<td>{$row['nama_warga']}</td>";
    echo "<td>{$row['alamat']}</td>";
    echo "<td>" . number_format($row['skor_akhir'], 4) . "</td>";
    echo "<td>{$row['rekomendasi']}</td>";
    echo "<td>{$family_info}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h3>3. Field Name Check</h3>";
echo "<p>tbl_hasil_saw contains nama_warga field</p>";
echo "<p>data_warga contains nama_lengkap field</p>";

echo "<h3>4. Check for Name Field Issue</h3>";
$hasil = mysqli_query($koneksi, "
    SELECT h.nama_warga as hasil_nama, w.nama_lengkap as warga_nama, h.ranking, h.skor_akhir, h.rekomendasi
    FROM tbl_hasil_saw h 
    JOIN data_warga w ON h.id_warga = w.id_warga 
    ORDER BY h.ranking ASC
");

echo "<table border='1'>";
echo "<tr><th>Ranking</th><th>Hasil.nama_warga</th><th>Warga.nama_lengkap</th><th>Match?</th><th>Skor</th><th>Rekomendasi</th></tr>";
while($row = mysqli_fetch_array($hasil)) {
    $match = ($row['hasil_nama'] == $row['warga_nama']) ? 'YES' : 'NO';
    echo "<tr>";
    echo "<td>{$row['ranking']}</td>";
    echo "<td>{$row['hasil_nama']}</td>";
    echo "<td>{$row['warga_nama']}</td>";
    echo "<td><strong>{$match}</strong></td>";
    echo "<td>" . number_format($row['skor_akhir'], 4) . "</td>";
    echo "<td>{$row['rekomendasi']}</td>";
    echo "</tr>";
}
echo "</table>";
?>
