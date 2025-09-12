<?php
include('../configurasi/koneksi.php');

echo "<h2>Fix Kriteria Database Structure</h2>";

// Check current structure
$check_columns = mysqli_query($koneksi, "DESCRIBE tbl_kriteria");
if ($check_columns) {
    echo "<h3>Current tbl_kriteria Structure:</h3>";
    while($col = mysqli_fetch_array($check_columns)) {
        echo "- {$col['Field']} ({$col['Type']})<br>";
    }
} else {
    echo "❌ Error checking table structure: " . mysqli_error($koneksi) . "<br>";
}

// Check if we need to add columns
$columns_needed = [
    'id_kriteria' => 'INT(11) NOT NULL AUTO_INCREMENT',
    'kode_kriteria' => 'VARCHAR(10) NOT NULL',
    'keterangan' => 'TEXT NOT NULL', 
    'nilai' => 'DECIMAL(3,2) DEFAULT 0.5',
    'jenis' => 'ENUM("Benefit","Cost") DEFAULT "Benefit"'
];

echo "<br><h3>Adding Missing Columns:</h3>";

// Check if id_kriteria exists, if not add it as primary key
$check_id = mysqli_query($koneksi, "SHOW COLUMNS FROM tbl_kriteria LIKE 'id_kriteria'");
if (mysqli_num_rows($check_id) == 0) {
    $add_id = mysqli_query($koneksi, "ALTER TABLE tbl_kriteria ADD COLUMN id_kriteria INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");
    if ($add_id) {
        echo "✅ Added id_kriteria column<br>";
    } else {
        echo "❌ Error adding id_kriteria: " . mysqli_error($koneksi) . "<br>";
    }
}

// Check and add other columns
foreach ($columns_needed as $column => $definition) {
    if ($column == 'id_kriteria') continue; // Already handled above
    
    $check_col = mysqli_query($koneksi, "SHOW COLUMNS FROM tbl_kriteria LIKE '$column'");
    if (mysqli_num_rows($check_col) == 0) {
        $add_col = mysqli_query($koneksi, "ALTER TABLE tbl_kriteria ADD COLUMN $column $definition");
        if ($add_col) {
            echo "✅ Added $column column<br>";
        } else {
            echo "❌ Error adding $column: " . mysqli_error($koneksi) . "<br>";
        }
    } else {
        echo "ℹ️ Column $column already exists<br>";
    }
}

// Insert default PKH criteria if table is empty
$count_criteria = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tbl_kriteria");
$count_result = mysqli_fetch_array($count_criteria);

if ($count_result['total'] == 0) {
    echo "<br><h3>Inserting Default PKH Criteria:</h3>";
    
    $default_criteria = [
        ['C1', 'Jumlah lansia dalam keluarga', 0.15, 'Benefit'],
        ['C2', 'Jumlah anggota keluarga dengan disabilitas berat', 0.20, 'Benefit'],
        ['C3', 'Jumlah anak usia sekolah dasar', 0.20, 'Benefit'],
        ['C4', 'Jumlah anak usia sekolah menengah pertama', 0.15, 'Benefit'],
        ['C5', 'Jumlah anak usia sekolah menengah atas', 0.10, 'Benefit'],
        ['C6', 'Jumlah balita dalam keluarga', 0.15, 'Benefit'],
        ['C7', 'Jumlah ibu hamil dalam keluarga', 0.05, 'Benefit'],
        ['C8', 'Kriteria cadangan untuk pengembangan', 0.00, 'Benefit']
    ];
    
    foreach ($default_criteria as $index => $criteria) {
        $kode = $criteria[0];
        $keterangan = $criteria[1];
        $nilai = $criteria[2];
        $jenis = $criteria[3];
        
        $insert = mysqli_query($koneksi, "
            INSERT INTO tbl_kriteria (id_kriteria, kode_kriteria, keterangan, nilai, jenis) 
            VALUES (".($index + 1).", '$kode', '$keterangan', $nilai, '$jenis')
        ");
        
        if ($insert) {
            echo "✅ Added: $kode - $keterangan (Bobot: $nilai)<br>";
        } else {
            echo "❌ Error adding $kode: " . mysqli_error($koneksi) . "<br>";
        }
    }
} else {
    echo "<br>ℹ️ Criteria data already exists ($count_result[total] records)<br>";
}

// Fix tbl_klasifikasi structure if needed
echo "<br><h3>Checking tbl_klasifikasi Structure:</h3>";
$check_klasifikasi = mysqli_query($koneksi, "DESCRIBE tbl_klasifikasi");
if ($check_klasifikasi) {
    $has_id_warga = false;
    $has_c_columns = false;
    
    while($col = mysqli_fetch_array($check_klasifikasi)) {
        echo "- {$col['Field']} ({$col['Type']})<br>";
        if ($col['Field'] == 'id_warga') $has_id_warga = true;
        if (preg_match('/^C[1-8]$/', $col['Field'])) $has_c_columns = true;
    }
    
    if (!$has_id_warga || !$has_c_columns) {
        echo "<br>🔄 Need to update tbl_klasifikasi structure...<br>";
        
        // Create new structure for PKH
        $alter_klasifikasi = "ALTER TABLE tbl_klasifikasi ";
        $alterations = [];
        
        if (!$has_id_warga) {
            $alterations[] = "ADD COLUMN id_warga INT(11) NOT NULL";
        }
        
        for ($i = 1; $i <= 8; $i++) {
            $check_c = mysqli_query($koneksi, "SHOW COLUMNS FROM tbl_klasifikasi LIKE 'C$i'");
            if (mysqli_num_rows($check_c) == 0) {
                $alterations[] = "ADD COLUMN C$i INT(11) DEFAULT 0";
            }
        }
        
        $check_created = mysqli_query($koneksi, "SHOW COLUMNS FROM tbl_klasifikasi LIKE 'created_at'");
        if (mysqli_num_rows($check_created) == 0) {
            $alterations[] = "ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
        }
        
        if (!empty($alterations)) {
            $alter_sql = $alter_klasifikasi . implode(", ", $alterations);
            $result = mysqli_query($koneksi, $alter_sql);
            
            if ($result) {
                echo "✅ Updated tbl_klasifikasi structure<br>";
            } else {
                echo "❌ Error updating tbl_klasifikasi: " . mysqli_error($koneksi) . "<br>";
            }
        }
    } else {
        echo "✅ tbl_klasifikasi structure is correct<br>";
    }
}

echo "<br><h3>Final Status:</h3>";
echo "<a href='../administrator/media_admin.php?module=kriteria' class='btn btn-primary'>Check Kriteria Module</a><br>";
echo "<a href='../administrator/media_admin.php?module=klasifikasi' class='btn btn-secondary'>Check Klasifikasi Module</a><br>";
?>
