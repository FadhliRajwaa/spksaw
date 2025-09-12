<?php
include('../configurasi/koneksi.php');

echo "<h2>Update Kriteria Kode Values</h2>";

// Update kode_kriteria values
$updates = [
    1 => 'C1',
    2 => 'C2', 
    3 => 'C3',
    4 => 'C4',
    5 => 'C5',
    6 => 'C6',
    7 => 'C7',
    8 => 'C8'
];

foreach ($updates as $id => $kode) {
    $update = mysqli_query($koneksi, "UPDATE tbl_kriteria SET kode_kriteria = '$kode' WHERE id_kriteria = $id");
    if ($update) {
        echo "✅ Updated kriteria $id with kode $kode<br>";
    } else {
        echo "❌ Error updating kriteria $id: " . mysqli_error($koneksi) . "<br>";
    }
}

echo "<br><h3>Current Kriteria Data:</h3>";
$check = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria ORDER BY id_kriteria");
while ($r = mysqli_fetch_array($check)) {
    echo "- {$r['kode_kriteria']}: {$r['keterangan']} (Bobot: {$r['nilai']}, Jenis: {$r['jenis']})<br>";
}

echo "<br><a href='../administrator/media_admin.php?module=kriteria'>Back to Kriteria Module</a>";
?>
