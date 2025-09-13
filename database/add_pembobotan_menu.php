<?php
include "../configurasi/koneksi.php";
// Tambah menu Pembobotan Kriteria jika belum ada
$cek = mysqli_query($koneksi, "SELECT id_modul FROM modul WHERE link='?module=pembobotan' LIMIT 1");
if(mysqli_num_rows($cek)==0){
    mysqli_query($koneksi, "INSERT INTO modul (nama_modul, link, status, aktif, urutan, type) VALUES ('Pembobotan Kriteria','?module=pembobotan','admin','Y',2,'Data')");
    echo "✓ Menu Pembobotan Kriteria ditambahkan.<br>";
} else {
    echo "Menu sudah ada.<br>";
}
// Pastikan Data Kriteria ada
$cek2 = mysqli_query($koneksi, "SELECT id_modul FROM modul WHERE link='?module=kriteria' LIMIT 1");
if(mysqli_num_rows($cek2)==0){
    mysqli_query($koneksi, "INSERT INTO modul (nama_modul, link, status, aktif, urutan, type) VALUES ('Data Kriteria','?module=kriteria','admin','Y',3,'Data')");
    echo "✓ Menu Data Kriteria ditambahkan.<br>";
}

echo "Selesai. <a href='../administrator/media_admin.php?module=pembobotan' target='_blank'>Buka Pembobotan</a> | <a href='../administrator/media_admin.php?module=kriteria' target='_blank'>Buka Data Kriteria</a>";
