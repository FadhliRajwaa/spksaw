<?php
$server = "localhost";
$user = "root";
$password = "";
$database = "spksaw";
set_time_limit(1800);

$koneksi = mysqli_connect($server, $user, $password, $database);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>