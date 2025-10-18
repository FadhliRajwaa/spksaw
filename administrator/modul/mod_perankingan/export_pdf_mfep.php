<?php
session_start();
if (empty($_SESSION['namauser']) && empty($_SESSION['passuser'])) {
    die('Unauthorized access');
}

// Bersihkan buffer agar header tidak korup
if (function_exists('ob_get_level') && ob_get_level()) { @ob_end_clean(); }

require_once('../../../configurasi/koneksi.php');
require_once('../../../vendor/autoload.php');

try {
    // Ambil hasil MFEP + alamat untuk informasi baris
    $q = mysqli_query($koneksi, "
        SELECT h.*, w.alamat,
               w.jumlah_lansia, w.jumlah_disabilitas_berat, w.jumlah_anak_sd,
               w.jumlah_anak_smp, w.jumlah_anak_sma, w.jumlah_balita, w.jumlah_ibu_hamil
        FROM tbl_hasil_mfep h
        JOIN data_warga w ON w.id_warga = h.id_warga
        ORDER BY h.ranking ASC
    ");
    if (!$q) { throw new Exception('Query data MFEP gagal: '.mysqli_error($koneksi)); }

    $rows = [];
    while ($r = mysqli_fetch_assoc($q)) { $rows[] = $r; }

    // Statistik
    $stat = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT COUNT(*) total,
               SUM(CASE WHEN rekomendasi='Ya' THEN 1 ELSE 0 END) layak,
               SUM(CASE WHEN rekomendasi='Tidak' THEN 1 ELSE 0 END) tidak_layak,
               MAX(nilai_mfep) mfep_max,
               AVG(nilai_mfep) mfep_avg
        FROM tbl_hasil_mfep
    "));

    // Informasi kriteria
    $krit = mysqli_query($koneksi, "SELECT kode_kriteria, keterangan, nilai FROM tbl_kriteria WHERE nilai>0 ORDER BY kode_kriteria");
    $kriteria = [];
    while ($k = mysqli_fetch_assoc($krit)) { $kriteria[] = $k; }

    $tanggal = date('d F Y, H:i:s').' WIB';

    // HTML modern & informatif
    $html = "<!DOCTYPE html><html><head><meta charset='UTF-8'>
        <title>Laporan Perankingan PKH - MFEP</title>
        <style>
            *{box-sizing:border-box}
            body{font-family:'DejaVu Sans',Arial,sans-serif;font-size:11px;margin:0;padding:24px;background:#fff}
            .header{border-bottom:3px solid #2563eb;padding-bottom:12px;margin-bottom:16px;text-align:center}
            .header h1{margin:0 0 6px;color:#2563eb;font-size:18px;text-transform:uppercase}
            .header h2{margin:0 0 6px;color:#111;font-size:14px}
            .header p{margin:0;color:#555;font-size:10px}
            .grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin:14px 0}
            .card{background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;padding:12px;text-align:center}
            .card .val{font-weight:700;font-size:20px;color:#1d4ed8;margin-bottom:2px}
            .card .lbl{font-size:10px;color:#475569;text-transform:uppercase;letter-spacing:.3px}
            table{width:100%;border-collapse:collapse;margin-top:14px}
            th,td{border:1px solid #e5e7eb;padding:7px}
            th{background:#1d4ed8;color:#fff;text-transform:uppercase;font-size:9px;letter-spacing:.4px}
            td{font-size:10px}
            .text-center{text-align:center}
            .badge{padding:3px 7px;border-radius:4px;color:#fff;font-weight:700;font-size:9px}
            .badge-yes{background:#16a34a}
            .badge-no{background:#6b7280}
            .rank{background:#0ea5e9;color:#fff;border-radius:9999px;padding:4px 7px;font-weight:700;font-size:10px}
            .foot{margin-top:16px;border-top:1px solid #e5e7eb;padding-top:10px;color:#64748b;font-size:9px}
            .section-title{color:#0f172a;font-weight:700;margin:16px 0 6px}
            .criteria-table thead{display:table-header-group}
        </style>
    </head><body>
        <div class='header'>
            <h1>Sistem Pendukung Keputusan PKH</h1>
            <h2>Perankingan Penerima Bantuan - Metode MFEP</h2>
            <p>Tanggal Cetak: {$tanggal}</p>
        </div>

        <div class='grid'>
            <div class='card'><div class='val'>".(int)$stat['total']."</div><div class='lbl'>Total Warga</div></div>
            <div class='card'><div class='val'>".(int)$stat['layak']."</div><div class='lbl'>Rekomendasi Ya</div></div>
            <div class='card'><div class='val'>".number_format((float)$stat['mfep_max'],4)."</div><div class='lbl'>Nilai Tertinggi</div></div>
            <div class='card'><div class='val'>".number_format((float)$stat['mfep_avg'],4)."</div><div class='lbl'>Rata-rata MFEP</div></div>
        </div>

        <table>
            <thead>
                <tr>
                    <th width='7%'>Rank</th>
                    <th width='23%'>Nama Warga</th>
                    <th width='28%'>Alamat</th>
                    <th width='12%'>Total WE</th>
                    <th width='12%'>Nilai MFEP</th>
                    <th width='8%'>Rek.</th>
                    <th width='10%'>Status</th>
                </tr>
            </thead>
            <tbody>";

    foreach ($rows as $r) {
        // Status kelayakan konsisten dengan halaman web
        $status = 'Kurang Layak';
        $total = (int)$stat['total'];
        if ($r['ranking'] <= ceil($total*0.3)) $status = 'Sangat Layak';
        elseif ($r['ranking'] <= ceil($total*0.6)) $status = 'Layak';
        elseif ($r['ranking'] <= ceil($total*0.8)) $status = 'Cukup Layak';

        $html .= "<tr>
            <td class='text-center'><span class='rank'>#{$r['ranking']}</span></td>
            <td><strong>{$r['nama_warga']}</strong></td>
            <td>".htmlspecialchars($r['alamat'])."</td>
            <td class='text-center'>".number_format((float)$r['total_we'],4)."</td>
            <td class='text-center'>".number_format((float)$r['nilai_mfep'],4)."</td>
            <td class='text-center'><span class='badge ".($r['rekomendasi']=='Ya'?'badge-yes':'badge-no')."'>{$r['rekomendasi']}</span></td>
            <td class='text-center'>{$status}</td>
        </tr>";
    }

    $html .= "</tbody></table>

        <div class='section-title'>Bobot & Nama Kriteria</div>
        <table class='criteria-table'>
            <thead>
                <tr>
                    <th width='12%'>Kode</th>
                    <th>Nama Kriteria</th>
                    <th width='15%'>Bobot (%)</th>
                </tr>
            </thead>
            <tbody>";
    foreach ($kriteria as $k) {
        $html .= "<tr>
            <td class='text-center'><strong>{$k['kode_kriteria']}</strong></td>
            <td>".htmlspecialchars($k['keterangan'])."</td>
            <td class='text-center'>".(int)($k['nilai']*100)."</td>
        </tr>";
    }
    $html .= "</tbody></table>

        <div class='foot'>
            Dokumen ini dibuat otomatis oleh Sistem SPK MFEP PKH. Semakin tinggi nilai MFEP, semakin prioritas penerima bantuan.
        </div>
    </body></html>";

    // Render PDF
    $options = new \Dompdf\Options();
    $options->set('isRemoteEnabled', true);
    $options->set('isHtml5ParserEnabled', true);
    $options->set('defaultFont', 'DejaVu Sans');
    $dompdf = new \Dompdf\Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $filename = 'Perankingan_MFEP_'.date('Y-m-d_H-i-s').'.pdf';
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    echo $dompdf->output();
    exit;

} catch (Throwable $e) {
    if (function_exists('ob_get_level') && ob_get_level()) { @ob_end_clean(); }
    header('Content-Type: text/plain');
    echo 'Error generating PDF (MFEP): '.$e->getMessage();
    exit;
}
