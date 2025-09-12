<?php
include('../configurasi/koneksi.php');

echo "<h2>Create SAW Results Table</h2>";

// Create tbl_hasil_saw table
$create_table = "
CREATE TABLE IF NOT EXISTS tbl_hasil_saw (
    id_hasil INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_warga INT(11) NOT NULL,
    nilai_saw DECIMAL(6,4) NOT NULL,
    ranking INT(11) DEFAULT NULL,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_warga (id_warga),
    INDEX idx_ranking (ranking),
    FOREIGN KEY (id_warga) REFERENCES data_warga(id_warga) ON DELETE CASCADE
)";

if (mysqli_query($koneksi, $create_table)) {
    echo "✅ Table tbl_hasil_saw created successfully<br>";
} else {
    echo "❌ Error creating table: " . mysqli_error($koneksi) . "<br>";
}

// Check table structure
$check_table = mysqli_query($koneksi, "DESCRIBE tbl_hasil_saw");
if ($check_table) {
    echo "<h3>Table Structure:</h3>";
    while($col = mysqli_fetch_array($check_table)) {
        echo "- {$col['Field']} ({$col['Type']})<br>";
    }
} else {
    echo "❌ Error checking table: " . mysqli_error($koneksi) . "<br>";
}

echo "<br><a href='../administrator/media_admin.php?module=laporan&act=analisa'>Test Laporan Module</a>";
?>
