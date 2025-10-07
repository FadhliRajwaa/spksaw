<?php
include('configurasi/koneksi.php');

echo "=== CHECK TABLE STRUCTURE ===\n";

echo "\n--- data_warga table structure ---\n";
$result = mysqli_query($koneksi, "DESCRIBE data_warga");
while($row = mysqli_fetch_array($result)) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}

echo "\n--- tbl_hasil_saw table structure ---\n";
$result = mysqli_query($koneksi, "DESCRIBE tbl_hasil_saw");
while($row = mysqli_fetch_array($result)) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}

echo "\n--- Sample data from data_warga ---\n";
$result = mysqli_query($koneksi, "SELECT * FROM data_warga LIMIT 1");
while($row = mysqli_fetch_array($result)) {
    foreach($row as $key => $value) {
        if(!is_numeric($key)) {
            echo "$key: $value\n";
        }
    }
}
?>
