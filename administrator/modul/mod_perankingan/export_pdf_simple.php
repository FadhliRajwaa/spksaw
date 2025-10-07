<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
    die('Unauthorized access');
}

include('../../../configurasi/koneksi.php');

// Query data
$hasil = mysqli_query($koneksi, "
    SELECT h.*, w.alamat 
    FROM tbl_hasil_saw h 
    JOIN data_warga w ON h.id_warga = w.id_warga 
    ORDER BY h.ranking ASC
");

$stats = mysqli_query($koneksi, "
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN rekomendasi = 'Ya' THEN 1 ELSE 0 END) as layak,
        SUM(CASE WHEN rekomendasi = 'Tidak' THEN 1 ELSE 0 END) as tidak_layak
    FROM tbl_hasil_saw
");
$stat = mysqli_fetch_array($stats);

// Use Dompdf (single implementation)
require_once('../../../vendor/autoload.php');
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('chroot', realpath(__DIR__ . '/../../../'));
$dompdf = new Dompdf($options);

$filename = 'Perankingan_PKH_SAW_' . date('Y-m-d') . '.pdf';

// Build HTML
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Perankingan PKH SAW</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #222; }
        h2 { text-align: center; color: #2c5aa0; margin: 0 0 6px; }
        h3 { text-align: center; color: #666; margin: 0 0 10px; }
        .meta { text-align: center; font-size: 10px; color: #555; margin-bottom: 16px; }
        .stats { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .stats td { border: 1px solid #ddd; padding: 6px 8px; }
        .stats tr { background: #f8f9fa; }
        table.data { width: 100%; border-collapse: collapse; }
        .data th, .data td { border: 1px solid #ddd; padding: 6px 8px; }
        .data thead th { background: #2c5aa0; color: #fff; text-align: center; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h2>LAPORAN PERANKINGAN PENERIMA PKH</h2>
    <h3>Metode Simple Additive Weighting (SAW)</h3>
    <div class="meta">Tanggal: <?= date('d F Y'); ?></div>

    <table class="stats">
        <tr>
            <td><strong>Total Warga</strong></td>
            <td><strong><?= (int)$stat['total']; ?> orang</strong></td>
            <td><strong>Layak PKH</strong></td>
            <td><strong><?= (int)$stat['layak']; ?> orang</strong></td>
            <td><strong>Tidak Layak</strong></td>
            <td><strong><?= (int)$stat['tidak_layak']; ?> orang</strong></td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th width="10%">Ranking</th>
                <th width="35%">Nama Warga</th>
                <th width="35%">Alamat</th>
                <th width="10%">Total Nilai</th>
                <th width="10%">Rekomendasi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_array($hasil)): ?>
                <tr>
                    <td class="text-center"><strong>#<?= htmlspecialchars($row['ranking']); ?></strong></td>
                    <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                    <td><?= htmlspecialchars($row['alamat']); ?></td>
                    <td class="text-center"><?= number_format((float)$row['skor_akhir'], 4); ?></td>
                    <td class="text-center"><strong><?= htmlspecialchars($row['rekomendasi']); ?></strong></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <p style="font-size:10px; color:#666; margin-top:12px;">
        Laporan ini dibuat secara otomatis oleh Sistem Pendukung Keputusan PKH menggunakan metode SAW (Simple Additive Weighting).
    </p>
</body>
</html>
<?php
$html = ob_get_clean();

$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream($filename, [ 'Attachment' => true ]);
exit;
?>
