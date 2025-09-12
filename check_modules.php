<?php
require_once 'configurasi/koneksi.php';

echo "<h3>Current Modules in Database:</h3>";
$result = mysqli_query($koneksi, "SELECT id_modul, nama_modul, link, type, aktif, urutan FROM modul ORDER BY urutan");

if($result) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nama Modul</th><th>Link</th><th>Type</th><th>Aktif</th><th>Urutan</th></tr>";
    while($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td>{$row['id_modul']}</td>";
        echo "<td>{$row['nama_modul']}</td>";
        echo "<td>{$row['link']}</td>";
        echo "<td>{$row['type']}</td>";
        echo "<td>{$row['aktif']}</td>";
        echo "<td>{$row['urutan']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . mysqli_error($koneksi);
}
?>
