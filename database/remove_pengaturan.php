<?php
include('../configurasi/koneksi.php');

echo "<h2>Remove Pengaturan Menu</h2>";

// Delete any Pengaturan/Setting related menus
$delete_sql = "DELETE FROM modul WHERE nama_modul LIKE '%Pengaturan%' OR nama_modul LIKE '%Setting%' OR link LIKE '%config%' OR link LIKE '%setting%'";

if(mysqli_query($koneksi, $delete_sql)) {
    $affected = mysqli_affected_rows($koneksi);
    echo "✅ $affected menu pengaturan berhasil dihapus<br>";
} else {
    echo "❌ Error: " . mysqli_error($koneksi) . "<br>";
}

// Show current menu structure
echo "<br><h3>Menu PKH yang Aktif:</h3>";
$query = mysqli_query($koneksi, "SELECT * FROM modul ORDER BY type, urutan");
while($menu = mysqli_fetch_array($query)) {
    echo "- {$menu['nama_modul']} ({$menu['type']})<br>";
}

echo "<br><a href='../administrator/media_admin.php'>Kembali ke Dashboard</a>";
?>
