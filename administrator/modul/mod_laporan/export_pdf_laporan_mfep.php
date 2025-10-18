<?php
session_start();
if (empty($_SESSION['namauser']) && empty($_SESSION['passuser'])) {
    die('Unauthorized access');
}

// Bersihkan buffer agar tidak mengganggu header PDF
if (function_exists('ob_get_level') && ob_get_level()) { @ob_end_clean(); }

require_once('../../../configurasi/koneksi.php');
require_once('../../../vendor/autoload.php');

try {
    // Ambil data kriteria aktif (nilai > 0) urut berdasarkan kode (C1..C8)
    $qKrit = mysqli_query($koneksi, "SELECT kode_kriteria, keterangan, nilai FROM tbl_kriteria WHERE nilai > 0 ORDER BY kode_kriteria");
    $kriteria = [];
    while ($k = mysqli_fetch_assoc($qKrit)) { $kriteria[] = $k; }

    // Ambil hasil MFEP (sudah disimpan saat hitung)
    $qHasil = mysqli_query($koneksi, "SELECT * FROM tbl_hasil_mfep ORDER BY ranking ASC");
    if (!$qHasil) { throw new Exception('Query hasil MFEP gagal: '.mysqli_error($koneksi)); }
    $rows = [];
    while ($r = mysqli_fetch_assoc($qHasil)) { $rows[] = $r; }

    $total = count($rows);
    if ($total === 0) {
        // Tidak ada data, buat PDF kecil berisi info
        $htmlEmpty = '<html><body style="font-family:DejaVu Sans, Arial, sans-serif; padding:24px;">'
            .'<h2>Belum Ada Data Perhitungan MFEP</h2>'
            .'<p>Silakan lakukan perhitungan MFEP terlebih dahulu di menu Laporan Hasil Data Perhitungan.</p>'
            .'</body></html>';
        $opt = new \Dompdf\Options();
        $opt->set('defaultFont', 'DejaVu Sans');
        $dom = new \Dompdf\Dompdf($opt);
        $dom->loadHtml($htmlEmpty);
        $dom->setPaper('A4');
        $dom->render();
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="Laporan_MFEP_Kosong.pdf"');
        echo $dom->output();
        exit;
    }

    // Ringkasan
    $stat = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT 
            COUNT(*) total,
            SUM(CASE WHEN rekomendasi='Ya' THEN 1 ELSE 0 END) layak,
            SUM(CASE WHEN rekomendasi='Tidak' THEN 1 ELSE 0 END) tidak_layak,
            MAX(nilai_mfep) mfep_max,
            AVG(nilai_mfep) mfep_avg
        FROM tbl_hasil_mfep
    "));

    $tanggal = date('d F Y, H:i:s').' WIB';

    // Utility untuk membuat header dinamis kolom berdasarkan kriteria
    $buildHeader = function(string $prefix) use ($kriteria) {
        $html = '';
        foreach ($kriteria as $k) {
            $idx = (int)substr($k['kode_kriteria'], 1);
            $label = $prefix . $idx . ' - ' . htmlspecialchars($k['keterangan']);
            $extra = '';
            if ($prefix === 'WE') {
                $extra = '<br><span style="background:#e5e7eb;color:#111;padding:2px 4px;border-radius:3px;">W='.(number_format((float)$k['nilai'], 2)).'</span>';
            }
            $html .= '<th class="text-center">'.$label.$extra.'</th>';
        }
        return $html;
    };

    // Utility untuk membuat sel data dinamis berdasarkan kode C/E/WE
    $buildRowCells = function(array $row, string $prefix) use ($kriteria) {
        $html = '';
        foreach ($kriteria as $k) {
            $idx = (int)substr($k['kode_kriteria'], 1);
            $key = ($prefix === 'X') ? ('C'.$idx) : ($prefix.$idx); // X=raw C#, E#, WE#
            $val = isset($row[$key]) ? $row[$key] : 0;
            if ($prefix === 'X') {
                $html .= '<td class="text-center">'.htmlspecialchars((string)$val).'</td>';
            } else {
                $html .= '<td class="text-center">'.number_format((float)$val, 4).'</td>';
            }
        }
        return $html;
    };

    // HTML modern & informatif (5 bagian)
    $html = "<!DOCTYPE html><html><head><meta charset='UTF-8'>
        <title>Laporan Hasil Data Perhitungan (MFEP)</title>
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
            .section{margin-top:14px}
            .section h3{color:#0f172a;font-weight:700;margin:0 0 8px}
            table{width:100%;border-collapse:collapse;margin-top:6px}
            th,td{border:1px solid #e5e7eb;padding:7px}
            th{background:#1d4ed8;color:#fff;text-transform:uppercase;font-size:9px;letter-spacing:.4px}
            td{font-size:10px}
            .text-center{text-align:center}
            .label{display:inline-block;padding:2px 6px;border-radius:4px;font-size:9px;color:#fff}
            .label-primary{background:#3b82f6}
            .label-success{background:#16a34a}
            .label-info{background:#0ea5e9}
            .label-warning{background:#f59e0b}
            .label-default{background:#6b7280}
            .thead-repeat thead{display:table-header-group}
            .formula{font-size:9px;color:#475569}
            .footer{margin-top:16px;border-top:1px solid #e5e7eb;padding-top:10px;color:#64748b;font-size:9px}
        </style>
    </head><body>
        <div class='header'>
            <h1>Sistem Pendukung Keputusan PKH</h1>
            <h2>Laporan Hasil Data Perhitungan (MFEP)</h2>
            <p>Tanggal Cetak: {$tanggal}</p>
        </div>

        <div class='grid'>
            <div class='card'><div class='val'>".(int)$stat['total']."</div><div class='lbl'>Total Warga</div></div>
            <div class='card'><div class='val'>".(int)$stat['layak']."</div><div class='lbl'>Rekomendasi Ya</div></div>
            <div class='card'><div class='val'>".number_format((float)$stat['mfep_max'],4)."</div><div class='lbl'>Nilai Tertinggi</div></div>
            <div class='card'><div class='val'>".number_format((float)$stat['mfep_avg'],4)."</div><div class='lbl'>Rata-rata MFEP</div></div>
        </div>

        <div class='section'>
            <h3>1. Matriks Keputusan (X)</h3>
            <table class='thead-repeat'>
                <thead>
                    <tr>
                        <th width='5%'>No</th>
                        <th width='25%'>Nama Warga</th>
                        ".$buildHeader('X')."
                    </tr>
                </thead>
                <tbody>";

    $no = 1;
    foreach ($rows as $r) {
        $html .= "<tr>
            <td class='text-center'>".$no."</td>
            <td><strong>".htmlspecialchars($r['nama_warga'])."</strong></td>
            ".$buildRowCells($r, 'X')."
        </tr>";
        $no++;
    }

    $html .= "</tbody></table>
        </div>

        <div class='section'>
            <h3>2. Nilai Evaluasi Factor (E)</h3>
            <div class='formula'>E = X / X<sub>max</sub></div>
            <table class='thead-repeat'>
                <thead>
                    <tr>
                        <th width='5%'>No</th>
                        <th width='25%'>Nama Warga</th>
                        ".$buildHeader('E')."
                    </tr>
                </thead>
                <tbody>";

    $no = 1;
    foreach ($rows as $r) {
        $html .= "<tr>
            <td class='text-center'>".$no."</td>
            <td><strong>".htmlspecialchars($r['nama_warga'])."</strong></td>
            ".$buildRowCells($r, 'E')."
        </tr>";
        $no++;
    }

    $html .= "</tbody></table>
        </div>

        <div class='section'>
            <h3>3. Nilai Bobot Evaluasi (WE)</h3>
            <div class='formula'>WE = Bobot × E</div>
            <table class='thead-repeat'>
                <thead>
                    <tr>
                        <th width='5%'>No</th>
                        <th width='20%'>Nama Warga</th>
                        ".$buildHeader('WE')."
                        <th width='10%'>∑WE</th>
                    </tr>
                </thead>
                <tbody>";

    $no = 1;
    foreach ($rows as $r) {
        $html .= "<tr>
            <td class='text-center'>".$no."</td>
            <td><strong>".htmlspecialchars($r['nama_warga'])."</strong></td>
            ".$buildRowCells($r, 'WE')."
            <td class='text-center'><span class='label label-primary'>".number_format((float)$r['total_we'], 4)."</span></td>
        </tr>";
        $no++;
    }

    $html .= "</tbody></table>
        </div>

        <div class='section'>
            <h3>4. Nilai Total Evaluasi (∑WE)</h3>
            <table>
                <thead>
                    <tr>
                        <th width='8%'>No</th>
                        <th>Nama Warga</th>
                        <th width='18%'>Total WE</th>
                        <th>Rincian (WE1 + WE2 + ...)</th>
                    </tr>
                </thead>
                <tbody>";

    $no = 1;
    foreach ($rows as $r) {
        $parts = [];
        // Rangkai WE1..WEn berdasarkan kriteria aktif
        foreach ($kriteria as $k) {
            $idx = (int)substr($k['kode_kriteria'], 1);
            $parts[] = number_format((float)$r['WE'.$idx], 4);
        }
        $html .= "<tr>
            <td class='text-center'>".$no."</td>
            <td><strong>".htmlspecialchars($r['nama_warga'])."</strong></td>
            <td class='text-center'><span class='label label-success'>".number_format((float)$r['total_we'],4)."</span></td>
            <td class='formula'>".implode(' + ', $parts)."</td>
        </tr>";
        $no++;
    }

    $html .= "</tbody></table>
        </div>

        <div class='section'>
            <h3>5. Ranking Akhir dan Daftar Ranking</h3>
            <table class='thead-repeat'>
                <thead>
                    <tr>
                        <th width='8%'>Ranking</th>
                        <th>Nama Warga</th>
                        <th width='15%'>Total WE</th>
                        <th width='15%'>Nilai MFEP</th>
                        <th width='15%'>Rekomendasi</th>
                        <th width='20%'>Status</th>
                    </tr>
                </thead>
                <tbody>";

    foreach ($rows as $r) {
        $status = 'Kurang Layak';
        if ($r['ranking'] <= ceil($total*0.3)) $status = 'Sangat Layak';
        elseif ($r['ranking'] <= ceil($total*0.6)) $status = 'Layak';
        elseif ($r['ranking'] <= ceil($total*0.8)) $status = 'Cukup Layak';

        $html .= "<tr>
            <td class='text-center'><strong>#{$r['ranking']}</strong></td>
            <td><strong>".htmlspecialchars($r['nama_warga'])."</strong></td>
            <td class='text-center'><span class='label label-primary'>".number_format((float)$r['total_we'], 4)."</span></td>
            <td class='text-center'><span class='label label-success'>".number_format((float)$r['nilai_mfep'],4)."</span></td>
            <td class='text-center'><span class='label ".($r['rekomendasi']=='Ya'?'label-success':'label-default')."'>{$r['rekomendasi']}</span></td>
            <td class='text-center'>{$status}</td>
        </tr>";
    }

    $html .= "</tbody></table>
        </div>

        <div class='footer'>Dokumen ini dibuat otomatis oleh Sistem SPK MFEP PKH. Semakin tinggi nilai MFEP, semakin prioritas penerima bantuan.</div>
    </body></html>";

    // Render PDF
    $options = new \Dompdf\Options();
    $options->set('isRemoteEnabled', true);
    $options->set('isHtml5ParserEnabled', true);
    $options->set('defaultFont', 'DejaVu Sans');
    $dompdf = new \Dompdf\Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    $filename = 'Laporan_MFEP_'.date('Y-m-d_H-i-s').'.pdf';
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    echo $dompdf->output();
    exit;

} catch (Throwable $e) {
    if (function_exists('ob_get_level') && ob_get_level()) { @ob_end_clean(); }
    header('Content-Type: text/plain');
    echo 'Error generating PDF (Laporan MFEP): '.$e->getMessage();
    exit;
}
