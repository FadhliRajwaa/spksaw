<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
    die('Unauthorized access');
}

// Clear any previous output
ob_clean();

include('../../../configurasi/koneksi.php');
require_once('../../../vendor/autoload.php');

try {
    // Get data with detailed information
    $hasil = mysqli_query($koneksi, "
        SELECT h.*, w.alamat, w.jumlah_lansia, w.jumlah_disabilitas_berat, 
               w.jumlah_anak_sd, w.jumlah_anak_smp, w.jumlah_anak_sma, 
               w.jumlah_balita, w.jumlah_ibu_hamil
        FROM tbl_hasil_saw h 
        JOIN data_warga w ON h.id_warga = w.id_warga 
        ORDER BY h.ranking ASC
    ");

    if (!$hasil) {
        throw new Exception("Query failed: " . mysqli_error($koneksi));
    }

    // Fetch all rows into an array so we can log and safely iterate later
    $rows = [];
    while ($r = mysqli_fetch_assoc($hasil)) {
        $rows[] = $r;
    }

    // Log export details (timestamp, generated filename, total rows, exported names)
    $logDir = __DIR__ . '/../../../logs';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0777, true);
    }
    $exportFilename = 'Laporan_Perankingan_PKH_' . date('Y-m-d_H-i-s') . '.pdf';
    $logFile = rtrim($logDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'export_log.txt';
    $exportNames = array_column($rows, 'nama_warga');
    $logEntry = "[" . date('Y-m-d H:i:s') . "] export_file={$exportFilename}; total=" . count($rows) . "; names=" . implode(', ', $exportNames) . PHP_EOL;
    @file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);

    $stats = mysqli_query($koneksi, "
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN rekomendasi = 'Ya' THEN 1 ELSE 0 END) as layak,
            SUM(CASE WHEN rekomendasi = 'Tidak' THEN 1 ELSE 0 END) as tidak_layak
        FROM tbl_hasil_saw
    ");
    $stat = mysqli_fetch_array($stats);
    
    // Get criteria information (use exact columns from app schema)
    $kriteria = mysqli_query($koneksi, "
        SELECT kode_kriteria, keterangan, nilai 
        FROM tbl_kriteria 
        ORDER BY id_kriteria
    ");
    $criteria_info = [];
    while($k = mysqli_fetch_array($kriteria)) {
        $criteria_info[] = $k;
    }

    $html = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Laporan Perankingan Penerima PKH</title>
        <style>
            body { 
                font-family: 'DejaVu Sans', Arial, sans-serif; 
                font-size: 11px; 
                margin: 0;
                padding: 20px;
            }
            .header { 
                text-align: center; 
                margin-bottom: 25px; 
                border-bottom: 3px solid #2c5aa0;
                padding-bottom: 15px;
            }
            .header h1 { 
                margin: 8px 0; 
                font-size: 18px; 
                color: #2c5aa0;
                text-transform: uppercase;
            }
            .header h2 { 
                margin: 5px 0; 
                font-size: 14px; 
                color: #333;
            }
            .header p { 
                margin: 3px 0; 
                color: #666;
                font-size: 10px;
            }
            .stats { 
                margin-bottom: 20px; 
                background: #f8f9fa;
                padding: 15px;
                border-radius: 5px;
            }
            .stats table { 
                width: 100%; 
                border-collapse: collapse; 
            }
            .stats td { 
                padding: 10px; 
                text-align: center; 
                font-weight: bold;
                color: #2c5aa0;
                font-size: 12px;
            }
            table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-bottom: 20px; 
            }
            .criteria-table {
                page-break-inside: avoid;
            }
            .criteria-table thead { display: table-header-group; }
            .criteria-table tr { page-break-inside: avoid; }
            .criteria-section { page-break-inside: avoid; }
            .criteria-title {
                color: #2c5aa0;
                text-align: left;
                font-weight: bold;
                font-size: 14px;
                margin: 0 0 6px 0;
                padding: 0 0 5px 0;
                border-bottom: 2px solid #2c5aa0;
            }
            th, td { 
                padding: 8px; 
                border: 1px solid #ddd; 
                text-align: left; 
                font-size: 10px;
            }
            th { 
                /* Fallback solid color for PDF renderers that don't support gradients */
                background-color: #2c5aa0;
                background-image: linear-gradient(135deg, #2c5aa0 0%, #1e3a8a 100%);
                color: #ffffff; 
                text-align: center; 
                vertical-align: middle;
                font-weight: bold;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                /* Ensure header does not get clipped when spanning pages */
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .text-center { text-align: center; }
            .rank-1 { background-color: #fff3cd; border-left: 4px solid #ffc107; }
            .rank-2 { background-color: #d4edda; border-left: 4px solid #28a745; }
            .rank-3 { background-color: #cce7ff; border-left: 4px solid #007bff; }
            .layak { 
                color: #fff; 
                background: #28a745;
                padding: 3px 8px;
                border-radius: 3px;
                font-weight: bold;
                font-size: 9px;
            }
            .tidak-layak { 
                color: #fff; 
                background: #dc3545;
                padding: 3px 8px;
                border-radius: 3px;
                font-weight: bold;
                font-size: 9px;
            }
            .ranking-badge {
                background: #2c5aa0;
                color: white;
                padding: 5px 8px;
                border-radius: 50%;
                font-weight: bold;
                font-size: 10px;
            }
            .footer { 
                margin-top: 30px; 
                text-align: left; 
                font-size: 9px; 
                color: #666;
                border-top: 1px solid #ddd;
                padding-top: 15px;
            }
            .summary-grid {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                gap: 20px;
                text-align: center;
            }
            /* Force criteria section to start on a new page */
            .page-break-before { page-break-before: always; }
            .summary-item {
                background: white;
                padding: 15px;
                border-radius: 8px;
                border: 2px solid #e9ecef;
            }
            .summary-value {
                font-size: 24px;
                font-weight: bold;
                color: #2c5aa0;
                margin-bottom: 5px;
            }
            .summary-label {
                font-size: 11px;
                color: #666;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
        </style>
    </head>
    <body>
        <div class='header'>
            <h1>Sistem Pendukung Keputusan PKH</h1>
            <h2>Laporan Perankingan Penerima Program Keluarga Harapan</h2>
            <p>Metode Simple Additive Weighting (SAW) | Dinas Sosial Republik Indonesia</p>
            <p>Tanggal Cetak: " . date('d F Y, H:i:s') . " WIB</p>
        </div>
        
        <div class='stats'>
            <div class='summary-grid'>
                <div class='summary-item'>
                    <div class='summary-value'>{$stat['total']}</div>
                    <div class='summary-label'>Total Warga Dianalisis</div>
                </div>
                <div class='summary-item'>
                    <div class='summary-value'>{$stat['layak']}</div>
                    <div class='summary-label'>Layak Menerima PKH</div>
                </div>
                <div class='summary-item'>
                    <div class='summary-value'>{$stat['tidak_layak']}</div>
                    <div class='summary-label'>Tidak Layak</div>
                </div>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th width='5%'>Rank</th>
                    <th width='18%'>Nama Warga</th>
                    <th width='22%'>Alamat</th>
                    <th width='20%'>Detail Keluarga</th>
                    <th width='15%'>Skor SAW</th>
                    <th width='10%'>Status</th>
                    <th width='10%'>Rekomendasi</th>
                </tr>
            </thead>
            <tbody>";

    foreach($rows as $row) {
        $row_class = '';
        if($row['ranking'] <= 3) {
            $row_class = 'rank-' . $row['ranking'];
        }

        $rekomendasi_class = $row['rekomendasi'] == 'Ya' ? 'layak' : 'tidak-layak';

        // Build family details
        $family_details = [];
        if($row['jumlah_lansia'] > 0) $family_details[] = "Lansia: {$row['jumlah_lansia']}";
        if($row['jumlah_disabilitas_berat'] > 0) $family_details[] = "Disabilitas: {$row['jumlah_disabilitas_berat']}";
        if($row['jumlah_anak_sd'] > 0) $family_details[] = "Anak SD: {$row['jumlah_anak_sd']}";
        if($row['jumlah_anak_smp'] > 0) $family_details[] = "Anak SMP: {$row['jumlah_anak_smp']}";
        if($row['jumlah_anak_sma'] > 0) $family_details[] = "Anak SMA: {$row['jumlah_anak_sma']}";
        if($row['jumlah_balita'] > 0) $family_details[] = "Balita: {$row['jumlah_balita']}";
        if($row['jumlah_ibu_hamil'] > 0) $family_details[] = "Ibu Hamil: {$row['jumlah_ibu_hamil']}";
        
        $family_info = empty($family_details) ? 'Tidak ada dependan' : implode(', ', $family_details);
        
        // Determine eligibility status
        $status_kelayakan = $row['skor_akhir'] >= 1.5 ? 'Prioritas Tinggi' : ($row['skor_akhir'] >= 1.0 ? 'Prioritas Sedang' : 'Prioritas Rendah');
        
        $html .= "<tr class='$row_class'>
                    <td class='text-center'>
                        <span class='ranking-badge'>#{$row['ranking']}</span>
                    </td>
                    <td><strong>{$row['nama_warga']}</strong></td>
                    <td>{$row['alamat']}</td>
                    <td style='font-size: 9px;'>{$family_info}</td>
                    <td class='text-center'>" . number_format($row['skor_akhir'], 4) . "</td>
                    <td class='text-center' style='font-size: 9px;'>{$status_kelayakan}</td>
                    <td class='text-center'>
                        <span class='$rekomendasi_class'>{$row['rekomendasi']}</span>
                    </td>
                  </tr>";
    }

    $html .= "</tbody>
            </table>
            
            <div class='criteria-section page-break-before' style='margin-top: 0;'>
                <h3 class='criteria-title'>Penjelasan Kriteria Penilaian</h3>
                <table class='criteria-table' style='margin-top: 0;'>
                    <thead>
                        <tr>
                            <th width='15%'>KODE</th>
                            <th width='45%'>KRITERIA</th>
                            <th width='15%'>BOBOT (%)</th>
                            <th width='25%'>KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody>";
    
    // Add criteria explanation
    foreach($criteria_info as $k) {
        $html .= "<tr>
                    <td class='text-center'><strong>{$k['kode_kriteria']}</strong></td>
                    <td>{$k['keterangan']}</td>
                    <td class='text-center'>" . intval($k['nilai']*100) . "</td>
                    <td style='font-size: 9px;'>Semakin tinggi nilai, semakin prioritas</td>
                  </tr>";
    }
    
    $html .= "</tbody>
                </table>
            </div>
            
            <div class='footer'>
                <p><strong>Keterangan:</strong></p>
                <p>• Perankingan dilakukan menggunakan metode Simple Additive Weighting (SAW)</p>
                <p>• Skor tertinggi menunjukkan prioritas utama penerima bantuan PKH</p>
                <p>• Detail keluarga menunjukkan jumlah anggota keluarga dalam kategori prioritas</p>
                <p>• Status Prioritas: Tinggi (≥1.5), Sedang (1.0-1.49), Rendah (&lt;1.0)</p>
                <p>• Dokumen ini dibuat secara otomatis oleh Sistem Pendukung Keputusan PKH</p>
                <hr style='margin: 10px 0; border: none; border-top: 1px solid #ddd;'>
                <p>© " . date('Y') . " Dinas Sosial Republik Indonesia - Sistem PKH SAW</p>
            </div>
        </body>
        </html>";

    // Create PDF
    $options = new \Dompdf\Options();
    $options->set('isRemoteEnabled', true);
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);
    $options->set('defaultFont', 'DejaVu Sans');
    
    $dompdf = new \Dompdf\Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Set proper headers for PDF download
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $exportFilename . '"');
    // Prevent caching of the generated PDF
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
    header('Expires: 0');

    // Clear any previous output
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    // Output PDF
    echo $dompdf->output();
    
} catch (Exception $e) {
    // Clear any output
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    // Return error as plain text
    header('Content-Type: text/plain');
    echo "Error generating PDF: " . $e->getMessage();
}

exit();
?>
