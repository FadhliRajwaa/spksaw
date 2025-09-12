<?php
session_start();
include "../configurasi/koneksi.php";

// Check if user is logged in and is admin
if (empty($_SESSION['namauser']) || $_SESSION['leveluser'] != 'admin') {
    die("Access denied. Admin login required.");
}

// Include DOMPDF library
require_once '../vendor/autoload.php';

$options = new \Dompdf\Options();
$options->set('isRemoteEnabled', true);
$dompdf = new \Dompdf\Dompdf($options);

$tampil_kriteria = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria ORDER BY id_kriteria");

$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Kriteria PKH</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #2c3e50; }
        .header h2 { margin: 5px 0; color: #34495e; }
        .info { background-color: #ecf0f1; padding: 10px; margin: 10px 0; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #bdc3c7; padding: 8px; text-align: left; }
        th { background-color: #3498db; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        .benefit { color: #27ae60; font-weight: bold; }
        .cost { color: #e74c3c; font-weight: bold; }
        .footer { margin-top: 20px; font-size: 10px; color: #7f8c8d; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SISTEM PENDUKUNG KEPUTUSAN</h1>
        <h2>Data Kriteria Program Keluarga Harapan (PKH)</h2>
        <h3>Metode Simple Additive Weighting (SAW)</h3>
    </div>
    
    <div class="info">
        <strong>Informasi:</strong> Kriteria PKH menggunakan 8 kriteria tetap sesuai standar pemerintah untuk menentukan kelayakan penerima bantuan.
    </div>
    
    <table>
        <thead>
            <tr>
                <th width="10%">Kode</th>
                <th width="50%">Keterangan Kriteria</th>
                <th width="15%">Bobot Nilai</th>
                <th width="15%">Jenis</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>';

$no = 1;
while($kriteria = mysqli_fetch_array($tampil_kriteria)) {
    $jenis_class = ($kriteria['jenis'] == 'benefit') ? 'benefit' : 'cost';
    $html .= '
            <tr>
                <td>K'.$no.'</td>
                <td>'.$kriteria['keterangan'].'</td>
                <td>'.$kriteria['bobot'].'</td>
                <td class="'.$jenis_class.'">'.strtoupper($kriteria['jenis']).'</td>
                <td>Aktif</td>
            </tr>';
    $no++;
}

$html .= '
        </tbody>
    </table>
    
    <div class="info">
        <strong>Keterangan Jenis Kriteria:</strong><br>
        <span class="benefit">BENEFIT:</span> Semakin tinggi nilai, semakin baik (kriteria yang menguntungkan)<br>
        <span class="cost">COST:</span> Semakin rendah nilai, semakin baik (kriteria yang merugikan)
    </div>
    
    <div class="footer">
        <p>Dicetak pada: '.date('d/m/Y H:i:s').' | Sistem PKH - Dinas Sosial</p>
        <p>Total Kriteria: '.($no-1).' kriteria | Dokumen ini digenerate secara otomatis</p>
    </div>
</body>
</html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Data_Kriteria_PKH_".date('Y-m-d').".pdf", array("Attachment" => false));
?>
