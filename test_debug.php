<?php
// File debug untuk mengecek koneksi dan data
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug SPK PKH</h1>";

// Test 1: Koneksi Database
echo "<h2>1. Test Koneksi Database</h2>";
include "configurasi/koneksi.php";

if ($koneksi) {
    echo "✅ Koneksi database BERHASIL<br>";
    echo "Host: " . ($server ?? 'localhost') . "<br>";
    echo "Database: " . ($database ?? 'spksaw') . "<br>";
    echo "Port: " . ($port ?? '3306') . "<br><br>";
} else {
    echo "❌ Koneksi database GAGAL: " . mysqli_connect_error() . "<br><br>";
    die();
}

// Test 2: Cek Tabel Modul
echo "<h2>2. Cek Tabel Modul (Menu)</h2>";
$modul_data = mysqli_query($koneksi, "SELECT * FROM modul WHERE aktif='Y' ORDER BY urutan");
if ($modul_data) {
    $count = mysqli_num_rows($modul_data);
    echo "Total modul aktif: <strong>$count</strong><br><br>";
    
    if ($count > 0) {
        echo "<table border='1' cellpadding='5' style='background: white; color: black;'>";
        echo "<tr><th>No</th><th>Nama Modul</th><th>Link</th><th>Type</th><th>Urutan</th><th>Status</th></tr>";
        $no = 1;
        while ($m = mysqli_fetch_array($modul_data)) {
            echo "<tr>";
            echo "<td>$no</td>";
            echo "<td>{$m['nama_modul']}</td>";
            echo "<td>{$m['link']}</td>";
            echo "<td>{$m['type']}</td>";
            echo "<td>{$m['urutan']}</td>";
            echo "<td>{$m['status']}</td>";
            echo "</tr>";
            $no++;
        }
        echo "</table><br>";
    } else {
        echo "⚠️ Tidak ada data modul di database!<br>";
        echo "Silakan import database terlebih dahulu.<br><br>";
    }
} else {
    echo "❌ Error query modul: " . mysqli_error($koneksi) . "<br><br>";
}

// Test 3: Cek Modul Report (untuk Laporan)
echo "<h2>3. Cek Modul Report (Sidebar Laporan)</h2>";
$report_data = mysqli_query($koneksi, "SELECT * FROM modul WHERE aktif='Y' AND type='Report' ORDER BY urutan");
if ($report_data) {
    $count_report = mysqli_num_rows($report_data);
    echo "Total modul Report: <strong>$count_report</strong><br><br>";
    
    if ($count_report > 0) {
        echo "<table border='1' cellpadding='5' style='background: white; color: black;'>";
        echo "<tr><th>No</th><th>Nama Modul</th><th>Link</th><th>Type</th></tr>";
        $no = 1;
        while ($r = mysqli_fetch_array($report_data)) {
            echo "<tr>";
            echo "<td>$no</td>";
            echo "<td>{$r['nama_modul']}</td>";
            echo "<td>{$r['link']}</td>";
            echo "<td>{$r['type']}</td>";
            echo "</tr>";
            $no++;
        }
        echo "</table><br>";
    } else {
        echo "⚠️ Tidak ada modul dengan type='Report'!<br>";
        echo "Menu Laporan tidak akan muncul di sidebar.<br><br>";
    }
}

// Test 4: Cek Tabel Data Warga
echo "<h2>4. Cek Data Warga</h2>";
$warga = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM data_warga");
if ($warga) {
    $w = mysqli_fetch_array($warga);
    echo "Total data warga: <strong>{$w['total']}</strong><br><br>";
} else {
    echo "❌ Error: " . mysqli_error($koneksi) . "<br><br>";
}

// Test 5: Cek Tabel Kriteria
echo "<h2>5. Cek Data Kriteria</h2>";
$kriteria = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria ORDER BY id_kriteria");
if ($kriteria) {
    $count_k = mysqli_num_rows($kriteria);
    echo "Total kriteria: <strong>$count_k</strong><br><br>";
    
    if ($count_k > 0) {
        echo "<table border='1' cellpadding='5' style='background: white; color: black;'>";
        echo "<tr><th>Kode</th><th>Nama Kriteria</th><th>Bobot</th><th>Jenis</th></tr>";
        while ($k = mysqli_fetch_array($kriteria)) {
            echo "<tr>";
            echo "<td>{$k['kode_kriteria']}</td>";
            echo "<td>{$k['nama_kriteria']}</td>";
            echo "<td>{$k['bobot']}</td>";
            echo "<td>{$k['jenis']}</td>";
            echo "</tr>";
        }
        echo "</table><br>";
    }
}

// Test 6: Cek Session
echo "<h2>6. Test Session</h2>";
session_start();
echo "Session ID: " . session_id() . "<br>";
echo "Session aktif: " . (session_status() == PHP_SESSION_ACTIVE ? "✅ Ya" : "❌ Tidak") . "<br><br>";

// Test 7: Cek Admin
echo "<h2>7. Cek Data Admin</h2>";
$admin = mysqli_query($koneksi, "SELECT * FROM admin");
if ($admin) {
    $count_admin = mysqli_num_rows($admin);
    echo "Total admin: <strong>$count_admin</strong><br><br>";
    
    if ($count_admin > 0) {
        echo "<table border='1' cellpadding='5' style='background: white; color: black;'>";
        echo "<tr><th>ID</th><th>Username</th><th>Nama Lengkap</th><th>Level</th></tr>";
        while ($a = mysqli_fetch_array($admin)) {
            echo "<tr>";
            echo "<td>{$a['id_admin']}</td>";
            echo "<td>{$a['username']}</td>";
            echo "<td>{$a['nama_lengkap']}</td>";
            echo "<td>{$a['level']}</td>";
            echo "</tr>";
        }
        echo "</table><br>";
        
        echo "<p><strong>Login credentials:</strong><br>";
        echo "Username: admin<br>";
        echo "Password: admin<br></p>";
    }
}

echo "<hr>";
echo "<h2>Kesimpulan</h2>";
echo "<ul>";
echo "<li>Jika semua data di atas kosong → <strong>Database belum diimport</strong></li>";
echo "<li>Jika modul Report kosong → <strong>Menu Laporan tidak akan muncul</strong></li>";
echo "<li>Jika konten tidak terlihat di halaman admin → <strong>Masalah CSS</strong></li>";
echo "</ul>";

echo "<p><a href='administrator/'>← Kembali ke Halaman Admin</a></p>";
?>
