<?php
ob_start(); // Start output buffering to prevent headers already sent
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

// Get klasifikasi data with normalized values
$tampil_klasifikasi = mysqli_query($koneksi, "
    SELECT k.*, w.nama_lengkap, w.alamat,
           kr1.keterangan as k1, kr2.keterangan as k2, kr3.keterangan as k3,
           kr4.keterangan as k4, kr5.keterangan as k5, kr6.keterangan as k6,
           kr7.keterangan as k7, kr8.keterangan as k8
    FROM tbl_klasifikasi k
    LEFT JOIN data_warga w ON k.id_warga = w.id_warga
    LEFT JOIN tbl_kriteria kr1 ON kr1.id_kriteria = 1
    LEFT JOIN tbl_kriteria kr2 ON kr2.id_kriteria = 2
    LEFT JOIN tbl_kriteria kr3 ON kr3.id_kriteria = 3
    LEFT JOIN tbl_kriteria kr4 ON kr4.id_kriteria = 4
    LEFT JOIN tbl_kriteria kr5 ON kr5.id_kriteria = 5
    LEFT JOIN tbl_kriteria kr6 ON kr6.id_kriteria = 6
    LEFT JOIN tbl_kriteria kr7 ON kr7.id_kriteria = 7
    LEFT JOIN tbl_kriteria kr8 ON kr8.id_kriteria = 8
    ORDER BY w.nama_lengkap
");

$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Klasifikasi PKH</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #2c3e50; font-size: 16px; }
        .header h2 { margin: 5px 0; color: #34495e; font-size: 14px; }
        .info { background-color: #ecf0f1; padding: 10px; margin: 10px 0; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #bdc3c7; padding: 6px; text-align: center; }
        th { background-color: #3498db; color: white; font-weight: bold; font-size: 9px; }
        .nama-col { text-align: left; font-weight: bold; }
        .alamat-col { text-align: left; font-size: 9px; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        .footer { margin-top: 20px; font-size: 8px; color: #7f8c8d; }
        .nilai-tinggi { background-color: #d5f4e6; color: #27ae60; font-weight: bold; }
        .nilai-sedang { background-color: #fef9e7; color: #f39c12; }
        .nilai-rendah { background-color: #fadbd8; color: #e74c3c; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SISTEM PENDUKUNG KEPUTUSAN</h1>
        <h2>Data Klasifikasi Program Keluarga Harapan (PKH)</h2>
        <h3>Metode Simple Additive Weighting (SAW)</h3>
    </div>
    
    <div class="info">
        <strong>Informasi:</strong> Data klasifikasi menunjukkan nilai setiap warga untuk setiap kriteria PKH yang telah dinormalisasi menggunakan metode SAW.
    </div>
    
    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="15%">Nama Warga</th>
                <th width="15%">Alamat</th>
                <th width="8%">Lansia</th>
                <th width="8%">Disabilitas</th>
                <th width="8%">Anak SD</th>
                <th width="8%">Anak SMP</th>
                <th width="8%">Anak SMA</th>
                <th width="8%">Balita</th>
                <th width="8%">Ibu Hamil</th>
                <th width="8%">Cadangan</th>
                <th width="10%">Total Skor</th>
            </tr>
        </thead>
        <tbody>';

$no = 1;
while($klasifikasi = mysqli_fetch_array($tampil_klasifikasi)) {
    // Calculate total score using correct column names
    $total_skor = $klasifikasi['C1'] + $klasifikasi['C2'] + $klasifikasi['C3'] + 
                  $klasifikasi['C4'] + $klasifikasi['C5'] + $klasifikasi['C6'] + 
                  $klasifikasi['C7'] + $klasifikasi['C8'];
    
    // Determine color class based on total score
    $skor_class = '';
    if ($total_skor >= 4) $skor_class = 'nilai-tinggi';
    elseif ($total_skor >= 2) $skor_class = 'nilai-sedang';
    else $skor_class = 'nilai-rendah';
    
    $html .= '
            <tr>
                <td>'.$no.'</td>
                <td class="nama-col">'.$klasifikasi['nama_lengkap'].'</td>
                <td class="alamat-col">'.$klasifikasi['alamat'].'</td>
                <td>'.number_format($klasifikasi['C1'], 3).'</td>
                <td>'.number_format($klasifikasi['C2'], 3).'</td>
                <td>'.number_format($klasifikasi['C3'], 3).'</td>
                <td>'.number_format($klasifikasi['C4'], 3).'</td>
                <td>'.number_format($klasifikasi['C5'], 3).'</td>
                <td>'.number_format($klasifikasi['C6'], 3).'</td>
                <td>'.number_format($klasifikasi['C7'], 3).'</td>
                <td>'.number_format($klasifikasi['C8'], 3).'</td>
                <td class="'.$skor_class.'">'.number_format($total_skor, 3).'</td>
            </tr>';
    $no++;
}

$html .= '
        </tbody>
    </table>
    
    <div class="info">
        <strong>Keterangan Skor:</strong><br>
        <span class="nilai-tinggi">■ Skor Tinggi (≥ 4.0):</span> Prioritas Utama untuk menerima bantuan PKH<br>
        <span class="nilai-sedang">■ Skor Sedang (2.0 - 3.9):</span> Prioritas Menengah untuk menerima bantuan PKH<br>
        <span class="nilai-rendah">■ Skor Rendah (< 2.0):</span> Prioritas Rendah untuk menerima bantuan PKH
    </div>
    
    <div class="footer">
        <p>Dicetak pada: '.date('d/m/Y H:i:s').' | Sistem PKH - Dinas Sosial</p>
        <p>Total Data: '.($no-1).' warga | Dokumen ini digenerate secara otomatis</p>
        <p>Nilai telah dinormalisasi menggunakan metode Simple Additive Weighting (SAW)</p>
    </div>
</body>
</html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("Data_Klasifikasi_PKH_".date('Y-m-d').".pdf", array("Attachment" => false));
?>
