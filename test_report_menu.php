<?php
// Test khusus untuk menu Report
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test Menu Report Debug</h1>";

// Simulasi session admin
session_start();
$_SESSION['leveluser'] = 'admin';

include "configurasi/koneksi.php";

echo "<h2>1. Cek File report.php</h2>";
if (file_exists("administrator/report.php")) {
    echo "✅ File report.php DITEMUKAN<br><br>";
} else {
    echo "❌ File report.php TIDAK DITEMUKAN<br><br>";
}

echo "<h2>2. Output dari report.php</h2>";
echo "<div style='background: #f0f0f0; padding: 10px; border: 1px solid #ccc;'>";
ob_start();
include "administrator/report.php";
$report_output = ob_get_clean();
echo "</div>";

echo "<h3>Raw Output:</h3>";
echo "<pre>" . htmlspecialchars($report_output) . "</pre>";

echo "<h3>Rendered Output:</h3>";
echo $report_output;

echo "<h2>3. Manual Query Test</h2>";
$sql = mysqli_query($koneksi, "SELECT * FROM modul WHERE aktif='Y' AND type = 'Report' ORDER BY urutan");
echo "Query berhasil: " . ($sql ? "✅ Ya" : "❌ Tidak") . "<br>";
echo "Jumlah rows: " . mysqli_num_rows($sql) . "<br><br>";

echo "<h3>Data yang akan di-loop:</h3>";
echo "<table border='1' cellpadding='5' style='background: white; color: black;'>";
echo "<tr><th>ID</th><th>Nama Modul</th><th>Link</th><th>Type</th></tr>";
while ($m = mysqli_fetch_array($sql)) {
    echo "<tr>";
    echo "<td>{$m['id_modul']}</td>";
    echo "<td>{$m['nama_modul']}</td>";
    echo "<td>{$m['link']}</td>";
    echo "<td>{$m['type']}</td>";
    echo "</tr>";
}
echo "</table><br>";

echo "<h2>4. Simulasi Menu HTML</h2>";
echo "<div style='background: #0C1821; padding: 20px; color: white;'>";
$sql2 = mysqli_query($koneksi, "SELECT * FROM modul WHERE aktif='Y' AND type = 'Report' ORDER BY urutan");
while ($m = mysqli_fetch_array($sql2)){
    $module_name = isset($_GET['module']) ? $_GET['module'] : '';
    $active_class = '';
    
    if (strpos($m['link'], 'module=' . $module_name) !== false) {
        $active_class = 'active';
    }
    
    $icon = 'fas fa-chart-line';
    if (strpos($m['link'], 'laporan') !== false) {
        $icon = 'fas fa-calculator';
    } elseif (strpos($m['link'], 'perankingan') !== false) {
        $icon = 'fas fa-trophy';
    }
    
    echo "<div class='modern-sidebar-item' style='padding: 10px; margin: 5px 0; background: rgba(255,255,255,0.1);'>";
    echo "<a href='{$m['link']}' style='color: white; text-decoration: none;'>";
    echo "<i class='$icon'></i> ";
    echo "<span>{$m['nama_modul']}</span>";
    echo "</a>";
    echo "</div>";
}
echo "</div>";

echo "<p><a href='administrator/media_admin.php'>← Test di halaman admin sebenarnya</a></p>";
?>
