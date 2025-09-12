<?php
$dir = "C:/xampp7.4/htdocs/saw"; // lokasi project
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($rii as $file) {
    if ($file->isDir()) continue;
    if (pathinfo($file->getFilename(), PATHINFO_EXTENSION) != "php") continue;

    $content = file_get_contents($file->getPathname());

    // Ganti fungsi mysql_* jadi mysqli_*
    $content = str_replace("mysqli_connect", "mysqli_connect", $content);
    $content = str_replace("mysqli_query($koneksi, ", "mysqli_query(\$koneksi, ", $content);
    $content = str_replace("mysqli_real_escape_string($koneksi, ", "mysqli_real_escape_string(\$koneksi, ", $content);
    $content = str_replace("mysqli_fetch_array", "mysqli_fetch_array", $content);
    $content = str_replace("mysqli_num_rows", "mysqli_num_rows", $content);
    $content = str_replace("// mysql_select_db dihapus karena mysqli_connect sudah pilih DB", "// // mysql_select_db dihapus karena mysqli_connect sudah pilih DB dihapus karena mysqli_connect sudah pilih DB", $content);

    file_put_contents($file->getPathname(), $content);
    echo "Converted: " . $file->getPathname() . "\n";
}

echo "Selesai konversi!";