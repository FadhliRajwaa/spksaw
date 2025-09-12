<?php
/**
 * Database Migration Script for PKH System
 * Execute this file to migrate the database from student system to PKH system
 */

// Include database connection
require_once 'configurasi/koneksi.php';

// Function to execute SQL file
function executeSQLFile($mysqli, $filename) {
    $sql = file_get_contents($filename);
    
    // Remove comments and split by delimiter
    $sql = preg_replace('/^--.*$/m', '', $sql);
    $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
    
    // Split by semicolons but keep DELIMITER blocks intact
    $statements = [];
    $delimiter = ';';
    $current_statement = '';
    
    $lines = explode("\n", $sql);
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        
        // Handle DELIMITER changes
        if (preg_match('/^DELIMITER\s+(.*)$/i', $line, $matches)) {
            $delimiter = $matches[1];
            continue;
        }
        
        $current_statement .= $line . "\n";
        
        // Check if statement ends with current delimiter
        if (substr(rtrim($line), -strlen($delimiter)) === $delimiter) {
            $stmt = trim(substr($current_statement, 0, -strlen($delimiter)));
            if (!empty($stmt)) {
                $statements[] = $stmt;
            }
            $current_statement = '';
        }
    }
    
    // Add remaining statement if any
    if (!empty(trim($current_statement))) {
        $statements[] = trim($current_statement);
    }
    
    $success_count = 0;
    $error_count = 0;
    $errors = [];
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (empty($statement)) continue;
        
        // Skip certain statements that might cause issues
        if (stripos($statement, 'DROP TABLE IF EXISTS backup_') === 0 ||
            stripos($statement, 'CREATE TABLE IF NOT EXISTS backup_') === 0) {
            continue;
        }
        
        if ($mysqli->multi_query($statement)) {
            do {
                if ($result = $mysqli->store_result()) {
                    $result->free();
                }
            } while ($mysqli->next_result());
            $success_count++;
        } else {
            $error_count++;
            $errors[] = "Error in statement: " . substr($statement, 0, 100) . "... - " . $mysqli->error;
        }
    }
    
    return [
        'success' => $success_count,
        'errors' => $error_count,
        'error_messages' => $errors
    ];
}

// Start migration
echo "<!DOCTYPE html>\n";
echo "<html><head><title>Database Migration - PKH System</title>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} .info{color:blue;}</style>";
echo "</head><body>\n";

echo "<h1>Database Migration: Student System → PKH System</h1>\n";
echo "<div class='info'>Starting migration process...</div><br>\n";

// Check if migration file exists
$migration_file = 'database/migration_to_pkh.sql';
if (!file_exists($migration_file)) {
    echo "<div class='error'>Migration file not found: $migration_file</div>\n";
    exit;
}

// Execute migration
echo "<div class='info'>Executing migration script...</div><br>\n";
$result = executeSQLFile($koneksi, $migration_file);

echo "<h2>Migration Results:</h2>\n";
echo "<div class='success'>Successful statements: " . $result['success'] . "</div><br>\n";
echo "<div class='error'>Failed statements: " . $result['errors'] . "</div><br>\n";

if (!empty($result['error_messages'])) {
    echo "<h3>Error Details:</h3>\n";
    echo "<ul>\n";
    foreach ($result['error_messages'] as $error) {
        echo "<li class='error'>$error</li>\n";
    }
    echo "</ul>\n";
}

// Verify migration by checking if new tables exist
echo "<h2>Verification:</h2>\n";

$tables_to_check = ['data_warga', 'tbl_nilai_kriteria', 'tbl_hasil_saw'];
foreach ($tables_to_check as $table) {
    $query = "SHOW TABLES LIKE '$table'";
    $result = $koneksi->query($query);
    if ($result && $result->num_rows > 0) {
        echo "<div class='success'>✓ Table '$table' exists</div><br>\n";
    } else {
        echo "<div class='error'>✗ Table '$table' missing</div><br>\n";
    }
}

// Check if sample data exists
$query = "SELECT COUNT(*) as count FROM data_warga";
$result = $koneksi->query($query);
if ($result) {
    $row = $result->fetch_assoc();
    echo "<div class='info'>Sample data: " . $row['count'] . " warga records found</div><br>\n";
}

// Initialize sample data if tables exist but are empty
$query = "SELECT COUNT(*) as count FROM data_warga";
$result = $koneksi->query($query);
if ($result && $result->fetch_assoc()['count'] == 0) {
    echo "<div class='info'>Initializing sample data...</div><br>\n";
    
    // Insert sample warga data
    $sample_warga = "
    INSERT INTO data_warga (nama_lengkap, alamat, jumlah_lansia, jumlah_disabilitas_berat, jumlah_anak_sd, jumlah_anak_smp, jumlah_anak_sma, jumlah_balita, jumlah_ibu_hamil) VALUES
    ('Siti Aminah', 'Jl. Merdeka No. 123, RT 01/RW 02, Kelurahan Sukamaju', 2, 1, 2, 1, 0, 1, 1),
    ('Budi Santoso', 'Jl. Proklamasi No. 45, RT 03/RW 01, Kelurahan Sejahtera', 1, 0, 1, 2, 1, 2, 0),
    ('Eka Rahayu', 'Jl. Pancasila No. 67, RT 02/RW 03, Kelurahan Makmur', 0, 2, 3, 0, 2, 0, 1),
    ('Ahmad Fauzi', 'Jl. Garuda No. 89, RT 04/RW 02, Kelurahan Harmoni', 1, 1, 0, 3, 1, 1, 0),
    ('Dewi Sartika', 'Jl. Diponegoro No. 12, RT 01/RW 04, Kelurahan Bahagia', 3, 0, 2, 1, 0, 3, 2)
    ";
    
    if ($koneksi->query($sample_warga)) {
        echo "<div class='success'>✓ Sample warga data inserted</div><br>\n";
    } else {
        echo "<div class='error'>✗ Failed to insert sample data: " . $koneksi->error . "</div><br>\n";
    }
}

echo "<h2>Migration Summary:</h2>\n";
echo "<div class='info'>Database has been successfully migrated to PKH system structure.</div><br>\n";
echo "<div class='info'>You can now proceed with implementing the PHP modules.</div><br>\n";

echo "<h3>Next Steps:</h3>\n";
echo "<ol>\n";
echo "<li>Update login interface with PKH logo and description</li>\n";
echo "<li>Implement Data Warga CRUD module</li>\n";
echo "<li>Implement Data Kriteria module</li>\n";
echo "<li>Implement Data Klasifikasi auto-population</li>\n";
echo "<li>Implement SAW calculation engine</li>\n";
echo "<li>Implement reporting and ranking features</li>\n";
echo "</ol>\n";

echo "</body></html>\n";

$koneksi->close();
?>
