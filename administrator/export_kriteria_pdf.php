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

// Ambil data kriteria persis seperti di aplikasi (kode_kriteria, keterangan, nilai)
$tampil_kriteria = mysqli_query($koneksi, "SELECT kode_kriteria, keterangan, nilai FROM tbl_kriteria ORDER BY id_kriteria");

// Build HTML dengan kolom: KODE | KRITERIA | BOBOT | KETERANGAN
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Penjelasan Kriteria Penilaian</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #1f2937; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .title { font-size: 16px; font-weight: 700; color: #1f4e79; margin: 0 0 2px 0; }
        .subtitle { font-size: 11px; color: #64748b; margin: 0 0 6px 0; }
        .header { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
        .meta { text-align: right; font-size: 10px; color: #6b7280; }
        .divider { height: 3px; background: #1f4e79; width: 100%; margin: 0 0 10px 0; }
        .table-wrap { border: 1px solid #cbd5e1; border-radius: 6px; padding: 0; page-break-inside: avoid; }
        table { width: 100%; border-collapse: collapse; margin: 0; page-break-inside: avoid; }
        thead { display: table-header-group; }
        tfoot { display: table-row-group; }
        tr { page-break-inside: avoid; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; }
        th { background: #1f4e79; color: #ffffff; font-weight: 700; }
        thead th:nth-child(3), tbody td:nth-child(3) { text-align: right; }
        tfoot td { background: #f8fafc; font-weight: 700; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        .notes { font-size: 11px; color: #374151; text-align: left; margin-top: 12px; }
        .notes-title { font-weight: 700; margin-bottom: 6px; }
    </style>
    </head>
<body>
    <div class="header">
        <div class="brand">
            <h3 class="title">Penjelasan Kriteria Penilaian</h3>
            <div class="subtitle">Sistem Pendukung Keputusan PKH - Metode SAW</div>
        </div>
        <div class="meta">
            <div>Tanggal: '.date('d F Y').'</div>
            <div>Dicetak: '.date('d F Y H:i').'</div>
        </div>
    </div>
    <div class="divider"></div>

    <div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th style="width:10%">KODE</th>
                <th style="width:50%">KRITERIA</th>
                <th style="width:15%">BOBOT (%)</th>
                <th style="width:25%">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>';

$totalPct = 0;
while($row = mysqli_fetch_assoc($tampil_kriteria)) {
    // Bobot diambil apa adanya dari DB agar konsisten dengan tampilan aplikasi
    $kode = htmlspecialchars($row['kode_kriteria']);
    $ket  = htmlspecialchars($row['keterangan']);
    $p = intval($row['nilai']*100);
    $totalPct += $p;
    $bobot = $p . '%'; // samakan dengan tampilan pembobotan (persen)
    $html .= '
            <tr>
                <td><strong>'.$kode.'</strong></td>
                <td>'.$ket.'</td>
                <td>'.$bobot.'</td>
                <td>Semakin tinggi nilai, semakin prioritas</td>
            </tr>';
}

$html .= '
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align:right"><strong>Total</strong></td>
                <td style="text-align:right"><strong>'.$totalPct.'%</strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    </div>

    <div class="notes">
        <div class="notes-title">Keterangan:</div>
        <ul style="margin: 0 0 0 16px; padding: 0;">
            <li>Nilai bobot sesuai dengan data di aplikasi (tanpa perubahan format).</li>
            <li>PDF ini dihasilkan otomatis oleh sistem menggunakan metode SAW.</li>
            <li>Jika tabel muat satu halaman, sistem akan menghindari pemisahan tabel.</li>
        </ul>
        <div style="margin-top:8px;">Dicetak pada: '.date('d/m/Y H:i:s').'</div>
    </div>
</body>
</html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Penjelasan_Kriteria_PKH_".date('Y-m-d').".pdf", array("Attachment" => false));
?>
