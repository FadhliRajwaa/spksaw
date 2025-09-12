<?php
require __DIR__ . '/../vendor/autoload.php'; // sesuaikan path-nya
use Dompdf\Dompdf;

include "../configurasi/koneksi.php";

// Logo dan tanggal cetak
$logo = 'logo.png'; // sesuaikan path logo
$tanggal = date('d-m-Y H:i');

// Ambil data kriteria
$tampil_kriteria = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria");

// Ambil data siswa dan klasifikasi
$tampil_klasifikasi = mysqli_query($koneksi, "SELECT * FROM tbl_klasifikasi GROUP BY id_siswa");

// Mulai HTML
$html = '
<style>
body { font-family: Arial, sans-serif; font-size: 12px; }
h2, h3 { text-align: center; margin: 0; }
table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
th, td { border: 1px solid #000; padding: 5px; text-align: center; }
.header { text-align: center; margin-bottom: 20px; }
</style>

<div class="header">
    <img src="'.$logo.'" style="width:60px; float:left;">
    <h2>Laporan Hasil Perhitungan SAW</h2>
    <p>Tanggal Cetak: '.$tanggal.'</p>
</div>
';

// ==================== MATRIK AWAL ====================
$html .= '<h3>Matrik Awal</h3><table><thead><tr>
<th>No</th><th>NIM</th><th>Nama</th>';
$a = 1;
mysqli_data_seek($tampil_kriteria, 0);
while($f = mysqli_fetch_array($tampil_kriteria)){
    $html .= "<th>C$a</th>";
    $a++;
}
$html .= '</tr></thead><tbody>';

$no = 1;
mysqli_data_seek($tampil_klasifikasi, 0);
while ($r = mysqli_fetch_array($tampil_klasifikasi)) {
    $h = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa ='$r[id_siswa]'"));
    $html .= "<tr><td>$no</td><td>$h[nim]</td><td>$h[nama_lengkap]</td>";
    $klasifikasi = mysqli_query($koneksi, "SELECT * FROM tbl_klasifikasi WHERE id_siswa = '$r[id_siswa]'");
    while ($n = mysqli_fetch_array($klasifikasi)) {
        $himpunankriteria = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM tbl_himpunankriteria WHERE id_hk ='$n[id_hk]'"));
        $html .= "<td>$himpunankriteria[nama]</td>";
    }
    $html .= "</tr>";
    $no++;
}
$html .= '</tbody></table>';

// ==================== NORMALISASI ====================
$tampil_kriteria = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria");
$html .= '<h3>Normalisasi</h3><table><thead><tr>
<th>No</th><th>NIM</th><th>Nama</th>';
$a = 1;
while($f = mysqli_fetch_array($tampil_kriteria)){
    $html .= "<th>C$a</th>";
    $a++;
}
$html .= '</tr></thead><tbody>';

$no = 1;
mysqli_data_seek($tampil_klasifikasi, 0);
while ($r = mysqli_fetch_array($tampil_klasifikasi)) {
    $h = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa ='$r[id_siswa]'"));
    $html .= "<tr><td>$no</td><td>$h[nim]</td><td>$h[nama_lengkap]</td>";
    $klasifikasi = mysqli_query($koneksi, "SELECT * FROM v_analisa WHERE id_siswa = '$r[id_siswa]'");
    while ($n = mysqli_fetch_array($klasifikasi)) {
        $crmax = mysqli_fetch_array(mysqli_query($koneksi, "SELECT max(nilai) as nilaimax FROM v_analisa WHERE id_kriteria='$n[id_kriteria]'"));
        $himpunankriteria = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM tbl_himpunankriteria WHERE id_hk ='$n[id_hk]'"));
        $nilaiok = $himpunankriteria['nilai'] / $crmax['nilaimax'];
        $html .= "<td>".round($nilaiok, 4)."</td>";
    }
    $html .= "</tr>";
    $no++;
}
$html .= '</tbody></table>';

// ==================== RANGKING ====================
$html .= '<h3>Rangking</h3><table><thead><tr>
<th>No</th><th>NIM</th><th>Nama</th><th>Total Nilai</th></tr></thead><tbody>';

$no = 1;
mysqli_data_seek($tampil_klasifikasi, 0);
while ($r = mysqli_fetch_array($tampil_klasifikasi)) {
    $h = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa ='$r[id_siswa]'"));
    $html .= "<tr><td>$no</td><td>$h[nim]</td><td>$h[nama_lengkap]</td>";
    $klasifikasi = mysqli_query($koneksi, "SELECT * FROM v_analisa WHERE id_siswa = '$r[id_siswa]'");
    $totalnilai = 0;
    while ($n = mysqli_fetch_array($klasifikasi)) {
        $crmax = mysqli_fetch_array(mysqli_query($koneksi, "SELECT max(nilai) as nilaimax FROM v_analisa WHERE id_kriteria='$n[id_kriteria]'"));
        $himpunankriteria = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM tbl_himpunankriteria WHERE id_hk ='$n[id_hk]'"));
        $bobot = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM tbl_kriteria WHERE id = '$n[id_kriteria]'"));
        $nilaiok = $himpunankriteria['nilai'] / $crmax['nilaimax'];
        $rank = $nilaiok * $bobot['bobot'];                                    
        $totalnilai += $rank;
    }
    $html .= "<td>".round($totalnilai, 4)."</td></tr>";
    $no++;
}
$html .= '</tbody></table>';

// Buat PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("laporan_saw.pdf", array("Attachment" => false));
?>