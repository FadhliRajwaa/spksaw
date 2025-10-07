<?php
session_start();
include "../configurasi/koneksi.php";

// Check if user is logged in and is admin
if (empty($_SESSION['namauser']) || $_SESSION['leveluser'] != 'admin') {
    die("Access denied. Admin login required.");
}

// Include DOMPDF library
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Compute aggregates for summary and total rows
$totals = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT
    COUNT(*) AS total_keluarga,
    COALESCE(SUM(jumlah_lansia),0) AS total_lansia,
    COALESCE(SUM(jumlah_disabilitas_berat),0) AS total_disabilitas,
    COALESCE(SUM(jumlah_anak_sd),0) AS total_anak_sd,
    COALESCE(SUM(jumlah_anak_smp),0) AS total_anak_smp,
    COALESCE(SUM(jumlah_anak_sma),0) AS total_anak_sma,
    COALESCE(SUM(jumlah_balita),0) AS total_balita,
    COALESCE(SUM(jumlah_ibu_hamil),0) AS total_ibu_hamil
  FROM data_warga"));

// Limit rows to keep PDF single-page and compact
$maxRows = 40; // change if you want more rows (may force multiple pages)
$tampil_warga = mysqli_query($koneksi, "SELECT * FROM data_warga ORDER BY nama_lengkap LIMIT " . intval($maxRows));

// Logo (use file:// absolute path if available)
$logoPath = '';
$candidate = realpath(__DIR__ . '/logo_lp3i.png');
if ($candidate && file_exists($candidate)) {
    $logoPath = 'file://' . $candidate;
}

// Build HTML
$html = '<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8" />
<style>
    @page { margin: 8mm 8mm; }
    body { font-family: DejaVu Sans, Arial, sans-serif; color: #0f1724; font-size: 9px; -webkit-print-color-adjust: exact; print-color-adjust: exact; }

    .header { display:flex; align-items:center; gap:12px; margin-bottom:8px; }
    .brand .title { font-size:14px; font-weight:700; color: #0f1724; }
    .brand .subtitle { font-size:10px; color: #6b7280; }
    .meta { margin-left:auto; text-align:right; font-size:9px; color:#6b7280; }

    .summary { display:flex; gap:6px; margin:8px 0 10px 0; }
    .card { background: #ffffff; border-radius:6px; padding:6px 8px; box-shadow: none; border:1px solid #e5e7eb; flex:1; }
    .card .k { font-size:10px; color:#6b7280; }
    .card .v { font-size:12px; font-weight:700; color:#0f1724; margin-top:4px; }

    /* Legend chips for criteria */
    .legend { display:flex; flex-wrap:wrap; gap:8px; margin:6px 0 4px 0; page-break-inside: avoid; }
    .chip { display:inline-flex; align-items:center; gap:6px; border:1px solid #e5e7eb; border-radius:999px; padding:2px 8px; font-size:9px; color:#111827; background:#ffffff; }
    .chip .dot { width:10px; height:10px; border-radius:50%; display:inline-block; }
    .dot.blue { background:#2563eb; }
    .dot.orange { background:#f59e0b; }
    .dot.green { background:#10b981; }
    .dot.red { background:#ef4444; }

    table { width:100%; border-collapse:collapse; margin-top:6px; font-size:9px; border:1px solid #cbd5e1; }
    thead { display: table-header-group; }
    thead th { background: #f1f5f9; color:#0f1724 !important; font-weight:700; padding:7px 6px; border:1px solid #cbd5e1; border-bottom:2px solid #cbd5e1; text-transform: uppercase; letter-spacing: .3px; }
    tbody td { padding:4px 6px; border:1px solid #e5e7eb; vertical-align:middle; color: #111827; }
    tbody tr:nth-child(even) { background: #f8fafc; }

    .no { width:3%; text-align:center; }
    .nama { width:24%; }
    .alamat { width:36%; }
    .criteria { width:5%; text-align:center; }

    .badge { display:inline-block; padding:2px 6px; border-radius:999px; font-size:9px; color:#fff; }
    .badge.blue { background: #2563eb; }
    .badge.green { background: #10b981; }
    .badge.red { background: #ef4444; }
    .badge.orange { background: #f59e0b; }

    .footer-note { margin-top:10px; font-size:9.5px; color:#6b7280; }
    .note-list { margin:4px 0 0 14px; padding:0; }
    .footer { position: fixed; bottom: 6mm; left: 8mm; right: 8mm; font-size:9px; color:#6b7280; display:flex; justify-content:space-between; }

    /* Force content to try fit a single page: small paddings and compact typography */
    .modern-compact { line-height:1.05; }

    /* Modern table wrapper and caption */
    .table-wrap { border:1px solid #cbd5e1; border-radius:6px; page-break-inside: avoid; }
    .table-title { caption-side: top; text-align:left; font-weight:700; color:#1f4e79; padding:4px 6px 6px 6px; border-bottom:2px solid #cbd5e1; font-size:11px; }
    tfoot td { background:#f8fafc; font-weight:700; }
</style>
</head>
<body>
    <div class="header">';

// Logo dihilangkan sesuai permintaan klien

$html .= '<div class="brand">'
      . '<div class="title">LAPORAN DATA WARGA PKH</div>'
      . '<div class="subtitle">Sistem Pendukung Keputusan - Metode SAW</div>'
    . '</div>';

$html .= '<div class="meta">'
      . '<div>Tanggal: ' . date('d F Y') . '</div>'
      . '<div>Dicetak: ' . date('d F Y H:i') . '</div>'
    . '</div>';

$html .= '</div>'; // header end

// Summary cards
$html .= '<div class="summary">'
      . '<div class="card"><div class="k">Total Keluarga</div><div class="v">' . number_format($totals['total_keluarga']) . '</div></div>'
      . '<div class="card"><div class="k">Total Lansia</div><div class="v">' . number_format($totals['total_lansia']) . '</div></div>'
      . '<div class="card"><div class="k">Total Disabilitas</div><div class="v">' . number_format($totals['total_disabilitas']) . '</div></div>'
      . '<div class="card"><div class="k">Total Balita</div><div class="v">' . number_format($totals['total_balita']) . '</div></div>'
    . '</div>';

// Legend chips (keterangan tabel) tepat di atas tabel
$html .= '<div class="legend">'
      . '<span class="chip"><span class="dot blue"></span> Lansia</span>'
      . '<span class="chip"><span class="dot orange"></span> Disabilitas Berat</span>'
      . '<span class="chip"><span class="dot green"></span> Anak SD</span>'
      . '<span class="chip"><span class="dot green"></span> Anak SMP</span>'
      . '<span class="chip"><span class="dot green"></span> Anak SMA</span>'
      . '<span class="chip"><span class="dot red"></span> Balita</span>'
      . '<span class="chip"><span class="dot red"></span> Ibu Hamil</span>'
    . '</div>';

// Table header
$html .= '<table>'
       . '<thead>'
       . '<tr>'
       . '<th class="no">No</th>'
       . '<th class="nama">Nama Lengkap</th>'
       . '<th class="alamat">Alamat</th>'
       . '<th class="criteria">Lansia</th>'
       . '<th class="criteria">Disabilitas</th>'
       . '<th class="criteria">SD</th>'
       . '<th class="criteria">SMP</th>'
       . '<th class="criteria">SMA</th>'
       . '<th class="criteria">Balita</th>'
       . '<th class="criteria">Ibu Hamil</th>'
       . '</tr>'
       . '</thead>'
       . '<tbody>';

$no = 1;
while($data = mysqli_fetch_assoc($tampil_warga)) {
    // Prepare badges for visual emphasis
    $lansia = '<span class="badge blue">' . intval($data['jumlah_lansia']) . '</span>';
    $disab = '<span class="badge orange">' . intval($data['jumlah_disabilitas_berat']) . '</span>';
    $sd = '<span class="badge green">' . intval($data['jumlah_anak_sd']) . '</span>';
    $smp = '<span class="badge green">' . intval($data['jumlah_anak_smp']) . '</span>';
    $sma = '<span class="badge green">' . intval($data['jumlah_anak_sma']) . '</span>';
    $balita = '<span class="badge red">' . intval($data['jumlah_balita']) . '</span>';
    $ibu = '<span class="badge red">' . intval($data['jumlah_ibu_hamil']) . '</span>';

    $html .= '<tr>'
           . '<td class="no">' . $no . '</td>'
           . '<td class="nama"><strong>' . htmlspecialchars($data['nama_lengkap']) . '</strong></td>'
           . '<td class="alamat">' . htmlspecialchars($data['alamat']) . '</td>'
           . '<td class="criteria">' . $lansia . '</td>'
           . '<td class="criteria">' . $disab . '</td>'
           . '<td class="criteria">' . $sd . '</td>'
           . '<td class="criteria">' . $smp . '</td>'
           . '<td class="criteria">' . $sma . '</td>'
           . '<td class="criteria">' . $balita . '</td>'
           . '<td class="criteria">' . $ibu . '</td>'
           . '</tr>';
    $no++;
}

$html .= '</tbody></table>';

// If total rows exceed limit, show small note
if ($totals['total_keluarga'] > $maxRows) {
    $html .= '<div style="margin-top:6px; font-size:9px; color:#6b7280;">Menampilkan ' . intval($maxRows) . ' dari ' . number_format($totals['total_keluarga']) . ' keluarga. Buka sistem untuk daftar lengkap.</div>';
}

// Footer note
$html .= '<div class="footer-note">'
        . '<strong>Keterangan Kriteria PKH:</strong>'
        . '<ul class="note-list">'
        . '<li>Lansia = anggota ≥60 tahun</li>'
        . '<li>Disabilitas = disabilitas berat</li>'
        . '<li>Anak SD/SMP/SMA = sesuai rentang umur</li>'
        . '<li>Balita = 0–5 tahun</li>'
        . '<li>Ibu Hamil = jumlah ibu hamil dalam keluarga</li>'
        . '</ul>'
        . '</div>';

// remove previous page script injection and close html
$html .= '</body></html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

// Add page numbering/footer using Dompdf canvas API
$canvas = $dompdf->get_canvas();
if ($canvas) {
    $font = $dompdf->getFontMetrics()->get_font('DejaVu Sans', 'normal');
    $size = 9;
    $y = $canvas->get_height() - 24;
    $xRight = $canvas->get_width() - 60;
    $canvas->page_text($xRight, $y, 'Halaman {PAGE_NUM} / {PAGE_COUNT}', $font, $size, array(0.4,0.4,0.4));
}

$filename = 'Laporan_Data_Warga_PKH_' . date('Y-m-d') . '.pdf';
$dompdf->stream($filename, array('Attachment' => true));
?>
