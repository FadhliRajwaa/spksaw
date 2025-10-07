<?php
include('configurasi/koneksi.php');

echo "=== CHECKING AHMAD FAUZI REFERENCES ===\n";

// Check data_warga
echo "\n--- data_warga table ---\n";
$result = mysqli_query($koneksi, "SELECT * FROM data_warga");
echo "Total records: " . mysqli_num_rows($result) . "\n";
while($row = mysqli_fetch_array($result)) {
    echo "ID: " . $row['id_warga'] . " - " . $row['nama_lengkap'] . "\n";
}

// Check tbl_hasil_saw
echo "\n--- tbl_hasil_saw table ---\n";
$result = mysqli_query($koneksi, "SELECT * FROM tbl_hasil_saw");
echo "Total records: " . mysqli_num_rows($result) . "\n";
while($row = mysqli_fetch_array($result)) {
    echo "ID: " . $row['id_warga'] . " - " . $row['nama_warga'] . " - Rank: " . $row['ranking'] . "\n";
}

// Check for specific Ahmad Fauzi
echo "\n--- Searching for Ahmad Fauzi ---\n";
$ahmad_check = mysqli_query($koneksi, "SELECT * FROM data_warga WHERE nama_lengkap LIKE '%Ahmad%' OR nama_lengkap LIKE '%Fauzi%'");
if(mysqli_num_rows($ahmad_check) > 0) {
    while($row = mysqli_fetch_array($ahmad_check)) {
        echo "Found Ahmad/Fauzi in data_warga: " . $row['nama_lengkap'] . "\n";
    }
} else {
    echo "No Ahmad Fauzi found in data_warga\n";
}

$ahmad_hasil = mysqli_query($koneksi, "SELECT * FROM tbl_hasil_saw WHERE nama_warga LIKE '%Ahmad%' OR nama_warga LIKE '%Fauzi%'");
if(mysqli_num_rows($ahmad_hasil) > 0) {
    while($row = mysqli_fetch_array($ahmad_hasil)) {
        echo "Found Ahmad/Fauzi in tbl_hasil_saw: " . $row['nama_warga'] . "\n";
    }
} else {
    echo "No Ahmad Fauzi found in tbl_hasil_saw\n";
}
?>
