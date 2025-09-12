<?php
require '../vendor/autoload.php'; // pastikan dompdf sudah di-install via composer

use Dompdf\Dompdf;
use Dompdf\Options;

include "../configurasi/koneksi.php";
include "../configurasi/fungsi_indotgl.php";

if (!isset($_GET['id'])) {
    die("ID Siswa tidak ditemukan.");
}

$id_siswa = intval($_GET['id']);

// Ambil data siswa
$detail = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa='$id_siswa'");
$siswa = mysqli_fetch_array($detail);
if (!$siswa) {
    die("Data siswa tidak ditemukan.");
}

// Ambil data kelas
$get_kelas = mysqli_query($koneksi, "SELECT * FROM kelas WHERE id_kelas = '{$siswa['id_kelas']}'");
$kelas = mysqli_fetch_array($get_kelas);

// Hitung jumlah teman sekelas
$friends = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_kelas='{$siswa['id_kelas']}'"));

// Format tanggal
$tgl_lahir = tgl_indo($siswa['tgl_lahir']);

// Konfigurasi DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// HTML untuk PDF
$html = '
<style>
    body { font-family: Arial, sans-serif; font-size: 12px; }
    .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 20px; }
    .header img { float: left; height: 60px; }
    h2 { margin: 0; }
    table { border-collapse: collapse; width: 100%; margin-top: 10px; }
    table td { padding: 6px; vertical-align: top; }
    .label { font-weight: bold; width: 150px; }
</style>

<div class="header">
   
    <h2>Detail Peserta Didik</h2>
    <small>Tanggal Cetak: '.tgl_indo(date("Y-m-d")).'</small>
</div>

<table border="1">
    <tr>
        <td class="label">Nama Lengkap</td>
        <td>'.$siswa['nama_lengkap'].'</td>
    </tr>
    <tr>
        <td class="label">NIM</td>
        <td>'.$siswa['nim'].'</td>
    </tr>
    <tr>
        <td class="label">Username</td>
        <td>'.$siswa['username_login'].'</td>
    </tr>
    <tr>
        <td class="label">Kelas</td>
        <td>'.$kelas['nama'].'</td>
    </tr>
    <tr>
        <td class="label">Jumlah Teman</td>
        <td>'.$friends.'</td>
    </tr>
    <tr>
        <td class="label">Alamat</td>
        <td>'.$siswa['alamat'].'</td>
    </tr>
    <tr>
        <td class="label">Tempat Lahir</td>
        <td>'.$siswa['tempat_lahir'].'</td>
    </tr>
    <tr>
        <td class="label">Tanggal Lahir</td>
        <td>'.$tgl_lahir.'</td>
    </tr>
    <tr>
        <td class="label">Jenis Kelamin</td>
        <td>'.($siswa['jenis_kelamin']=='P' ? 'Perempuan' : 'Laki-laki').'</td>
    </tr>
    <tr>
        <td class="label">Agama</td>
        <td>'.$siswa['agama'].'</td>
    </tr>
    <tr>
        <td class="label">Tahun Masuk</td>
        <td>'.$siswa['th_masuk'].'</td>
    </tr>
    <tr>
        <td class="label">Email</td>
        <td>'.$siswa['email'].'</td>
    </tr>
    <tr>
        <td class="label">No. HP</td>
        <td>'.$siswa['no_telp'].'</td>
    </tr>
    <tr>
        <td class="label">Blokir</td>
        <td>'.$siswa['blokir'].'</td>
    </tr>
    <tr>
        <td class="label">Nama Ayah</td>
        <td>'.$siswa['nama_ayah'].'</td>
    </tr>
    <tr>
        <td class="label">Nama Ibu</td>
        <td>'.$siswa['nama_ibu'].'</td>
    </tr>
</table>
';

// Load HTML
$dompdf->loadHtml($html);

// Atur ukuran kertas
$dompdf->setPaper('A4', 'portrait');

// Render dan output PDF
$dompdf->render();
$dompdf->stream("detail_siswa_{$siswa['nim']}.pdf", ["Attachment" => false]);