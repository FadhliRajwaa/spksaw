<?php
include('configurasi/koneksi.php');

echo "=== DEBUG DETAIL PERANKINGAN ===\n";

// Check data for id_hasil = 97
echo "\n--- Checking data for id_hasil = 97 ---\n";
$detail = mysqli_query($koneksi, "
    SELECT h.*, w.alamat, w.no_kk, w.no_ktp 
    FROM tbl_hasil_saw h 
    JOIN data_warga w ON h.id_warga = w.id_warga 
    WHERE h.id_hasil = '97'
");

if($data = mysqli_fetch_array($detail)) {
    echo "Data found:\n";
    echo "- Nama: " . $data['nama_warga'] . "\n";
    echo "- ID Warga: " . $data['id_warga'] . "\n";
    echo "- Ranking: " . $data['ranking'] . "\n";
    echo "- Skor: " . $data['skor_akhir'] . "\n";
    echo "- Rekomendasi: " . $data['rekomendasi'] . "\n";
} else {
    echo "No data found for id_hasil = 97\n";
    echo "MySQL Error: " . mysqli_error($koneksi) . "\n";
}

echo "\n--- All results from tbl_hasil_saw ---\n";
$all = mysqli_query($koneksi, "SELECT * FROM tbl_hasil_saw ORDER BY ranking");
while($row = mysqli_fetch_array($all)) {
    echo "ID: " . $row['id_hasil'] . " - " . $row['nama_warga'] . " - Rank: " . $row['ranking'] . "\n";
}

echo "\n--- Check tbl_klasifikasi for id_warga ---\n";
if(isset($data) && $data) {
    $klasifikasi = mysqli_query($koneksi, "SELECT * FROM tbl_klasifikasi WHERE id_warga = '{$data['id_warga']}'");
    if($klasif = mysqli_fetch_array($klasifikasi)) {
        echo "Klasifikasi data found for id_warga: " . $data['id_warga'] . "\n";
        echo "C1: " . $klasif['C1'] . ", C2: " . $klasif['C2'] . ", C3: " . $klasif['C3'] . "\n";
    } else {
        echo "No klasifikasi data found\n";
    }
}

echo "\n--- Check kriteria names ---\n";
$kriteria = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria ORDER BY id_kriteria");
while($k = mysqli_fetch_array($kriteria)) {
    echo $k['kode_kriteria'] . ": " . $k['keterangan'] . "\n";
}
?>
