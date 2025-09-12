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

$tampil_warga = mysqli_query($koneksi, "SELECT * FROM data_warga ORDER BY nama_lengkap");

$html = '
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
        .subtitle { font-size: 14px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 10px; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        .no { text-align: center; width: 5%; }
        .nama { width: 20%; }
        .alamat { width: 25%; }
        .criteria { width: 7%; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LAPORAN DATA WARGA PKH</div>
        <div class="subtitle">Sistem Pendukung Keputusan Rekomendasi Penerima Bantuan PKH</div>
        <div>Tanggal: ' . date('d F Y') . '</div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th class="no">No</th>
                <th class="nama">Nama Lengkap</th>
                <th class="alamat">Alamat</th>
                <th class="criteria">Lansia</th>
                <th class="criteria">Disabilitas</th>
                <th class="criteria">Anak SD</th>
                <th class="criteria">Anak SMP</th>
                <th class="criteria">Anak SMA</th>
                <th class="criteria">Balita</th>
                <th class="criteria">Ibu Hamil</th>
            </tr>
        </thead>
        <tbody>';

$no = 1;
while($data = mysqli_fetch_array($tampil_warga)) {
    $html .= '
            <tr>
                <td class="no">' . $no . '</td>
                <td class="nama">' . htmlspecialchars($data['nama_lengkap']) . '</td>
                <td class="alamat">' . htmlspecialchars($data['alamat']) . '</td>
                <td class="criteria">' . $data['jumlah_lansia'] . '</td>
                <td class="criteria">' . $data['jumlah_disabilitas_berat'] . '</td>
                <td class="criteria">' . $data['jumlah_anak_sd'] . '</td>
                <td class="criteria">' . $data['jumlah_anak_smp'] . '</td>
                <td class="criteria">' . $data['jumlah_anak_sma'] . '</td>
                <td class="criteria">' . $data['jumlah_balita'] . '</td>
                <td class="criteria">' . $data['jumlah_ibu_hamil'] . '</td>
            </tr>';
    $no++;
}

$html .= '
        </tbody>
    </table>
    
    <div style="margin-top: 30px; font-size: 10px;">
        <p><strong>Keterangan Kriteria PKH:</strong></p>
        <ul>
            <li>Lansia: Jumlah anggota keluarga berusia 60 tahun ke atas</li>
            <li>Disabilitas: Jumlah anggota keluarga dengan disabilitas berat</li>
            <li>Anak SD: Jumlah anak usia sekolah dasar (6-12 tahun)</li>
            <li>Anak SMP: Jumlah anak usia sekolah menengah pertama (13-15 tahun)</li>
            <li>Anak SMA: Jumlah anak usia sekolah menengah atas (16-18 tahun)</li>
            <li>Balita: Jumlah anak usia 0-5 tahun</li>
            <li>Ibu Hamil: Jumlah ibu hamil dalam keluarga</li>
        </ul>
        <p style="margin-top: 20px;">
            <strong>Total Data Warga: ' . ($no - 1) . ' keluarga</strong><br>
            Dicetak pada: ' . date('d F Y H:i:s') . '
        </p>
    </div>
</body>
</html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$filename = 'Laporan_Data_Warga_PKH_' . date('Y-m-d') . '.pdf';
$dompdf->stream($filename, array('Attachment' => true));
?>
