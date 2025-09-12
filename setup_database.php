<?php
/**
 * Database Setup Script for PKH System
 * This script creates the database and imports the complete schema
 */

// Database configuration
$server = "localhost";
$user = "root";
$password = "";
$database = "spksaw";

echo "<!DOCTYPE html>\n";
echo "<html><head><title>Database Setup - PKH System</title>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} .info{color:blue;}</style>";
echo "</head><body>\n";

echo "<h1>Database Setup: PKH System</h1>\n";

// Connect without database first
$koneksi = new mysqli($server, $user, $password);

if ($koneksi->connect_error) {
    echo "<div class='error'>Connection failed: " . $koneksi->connect_error . "</div>";
    exit;
}

echo "<div class='success'>✓ Connected to MySQL server</div><br>\n";

// Create database if it doesn't exist
$query = "CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
if ($koneksi->query($query)) {
    echo "<div class='success'>✓ Database '$database' created/verified</div><br>\n";
} else {
    echo "<div class='error'>✗ Error creating database: " . $koneksi->error . "</div><br>\n";
}

// Select database
if ($koneksi->select_db($database)) {
    echo "<div class='success'>✓ Database '$database' selected</div><br>\n";
} else {
    echo "<div class='error'>✗ Error selecting database: " . $koneksi->error . "</div><br>\n";
    exit;
}

// Now execute the PKH schema
echo "<div class='info'>Creating PKH database structure...</div><br>\n";

// Disable foreign key checks first
$koneksi->query("SET FOREIGN_KEY_CHECKS = 0");

// Read and execute the complete PKH schema
$schema_file = 'database/pkh_simple_schema.sql';
if (!file_exists($schema_file)) {
    echo "<div class='error'>Schema file not found: $schema_file</div>\n";
    exit;
}

$sql = file_get_contents($schema_file);

// Remove comments and split statements
$sql = preg_replace('/^--.*$/m', '', $sql);
$sql = preg_replace('/\/\*.*?\*\//s', '', $sql);

// Split by semicolons and execute
$statements = explode(';', $sql);
$success_count = 0;
$error_count = 0;

foreach ($statements as $statement) {
    $statement = trim($statement);
    if (empty($statement)) continue;
    
    // Skip certain MySQL-specific statements
    if (stripos($statement, '/*!40') === 0 || 
        stripos($statement, 'SET SQL_MODE') === 0 ||
        stripos($statement, 'SET time_zone') === 0) {
        continue;
    }
    
    if ($koneksi->query($statement)) {
        $success_count++;
    } else {
        $error_count++;
        if (strlen($statement) > 100) {
            $statement_preview = substr($statement, 0, 100) . "...";
        } else {
            $statement_preview = $statement;
        }
        echo "<div class='error'>Error in: $statement_preview<br>MySQL Error: " . $koneksi->error . "</div><br>\n";
    }
}

// Re-enable foreign key checks
$koneksi->query("SET FOREIGN_KEY_CHECKS = 1");

echo "<h2>Schema Import Results:</h2>\n";
echo "<div class='success'>Successful statements: $success_count</div><br>\n";
echo "<div class='error'>Failed statements: $error_count</div><br>\n";

// Verify tables were created
echo "<h2>Table Verification:</h2>\n";
$expected_tables = [
    'admin', 'data_warga', 'tbl_kriteria', 'tbl_nilai_kriteria', 
    'tbl_klasifikasi', 'tbl_hasil_saw', 'modul'
];

foreach ($expected_tables as $table) {
    $result = $koneksi->query("SHOW TABLES LIKE '$table'");
    if ($result && $result->num_rows > 0) {
        echo "<div class='success'>✓ Table '$table' created</div><br>\n";
    } else {
        echo "<div class='error'>✗ Table '$table' missing</div><br>\n";
    }
}

// Check data
echo "<h2>Data Verification:</h2>\n";
$data_checks = [
    ['table' => 'admin', 'desc' => 'Admin users'],
    ['table' => 'data_warga', 'desc' => 'Sample warga'],
    ['table' => 'tbl_kriteria', 'desc' => 'PKH criteria'],
    ['table' => 'tbl_nilai_kriteria', 'desc' => 'Criteria values'],
    ['table' => 'modul', 'desc' => 'Menu modules']
];

foreach ($data_checks as $check) {
    $result = $koneksi->query("SELECT COUNT(*) as count FROM {$check['table']}");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "<div class='info'>{$check['desc']}: {$row['count']} records</div><br>\n";
    }
}

echo "<h2>Setup Complete!</h2>\n";
echo "<div class='success'>✓ PKH Database has been successfully created and configured</div><br>\n";
echo "<div class='info'>You can now access the admin panel and start using the PKH system.</div><br>\n";

echo "<h3>Default Login Credentials:</h3>\n";
echo "<ul>\n";
echo "<li><strong>Username:</strong> administrator</li>\n";
echo "<li><strong>Password:</strong> administrator</li>\n";
echo "</ul>\n";

echo "<h3>Next Steps:</h3>\n";
echo "<ol>\n";
echo "<li>Update the login interface with PKH branding</li>\n";
echo "<li>Test the data entry modules</li>\n";
echo "<li>Verify SAW calculations work correctly</li>\n";
echo "</ol>\n";

echo "</body></html>\n";

$koneksi->close();
?>
