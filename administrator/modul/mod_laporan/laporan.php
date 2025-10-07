<?php
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
    echo "<link href=../css/style.css rel=stylesheet type=text/css>";
    echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
}
else{

$aksi="modul/mod_laporan/aksi_laporan.php";
switch($_GET['act']){
    // Tampil Laporan Hasil Analisa
    case "analisa":
    default:
        if ($_SESSION['leveluser']=='admin'){
            // Get kriteria dengan nama lengkap
            $kriteria_query = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria ORDER BY id_kriteria");
            $kriteria_data = [];
            while($k = mysqli_fetch_array($kriteria_query)) {
                $kriteria_data[$k['kode_kriteria']] = $k;
            }
            
            // Get hasil SAW jika ada
            $hasil_saw = mysqli_query($koneksi, "
                SELECT h.*, w.nama_lengkap, w.alamat 
                FROM tbl_hasil_saw h 
                JOIN data_warga w ON h.id_warga = w.id_warga 
                ORDER BY h.skor_akhir DESC
            ");
            
            $total_hasil = mysqli_num_rows($hasil_saw);
            ?>
            
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-bar-chart"></i> Laporan Hasil Analisa SAW PKH</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body" id="analisaContent">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> 
                                <strong>Analisa SAW PKH:</strong> Hasil perhitungan metode Simple Additive Weighting 
                                untuk menentukan ranking kelayakan penerima bantuan PKH berdasarkan 8 kriteria yang telah ditetapkan.
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="action-btns" style="display:flex; flex-wrap:wrap; gap:10px; align-items:center;">
                                <a class='btn btn-success btn-flat action-btn' href='?module=laporan&act=hitung_saw'>
                                    <i class="fa fa-calculator"></i> Hitung Ulang SAW
                                </a>
                                <a class='btn btn-info btn-flat action-btn' href='?module=laporan&act=detail_perhitungan'>
                                    <i class="fa fa-list"></i> Detail Perhitungan
                                </a>
                                <a class='btn btn-warning btn-flat action-btn' href='?module=perankingan'>
                                    <i class="fa fa-trophy"></i> Lihat Perankingan
                                </a>
                                <button class="btn btn-default btn-flat action-btn" onclick="printRankingTable()">
                                    <i class="fa fa-print"></i> Print
                                </button>
                                <a class='btn btn-primary btn-flat action-btn' href='modul/mod_perankingan/export_pdf.php' target="_blank">
                                    <i class='fa fa-file-pdf-o'></i> Save PDF
                                </a>
                            </div>
                         </div>
                     </div>
                    <br>
                    
                    <?php if ($total_hasil > 0): ?>
                    
                    <!-- 1. Matriks Nilai Dasar -->
                    <h4 style="color: white !important;"><i class="fa fa-table"></i> 1. Matriks Nilai Dasar</h4>
                    <div style="overflow-x: scroll; overflow-y: visible; width: 100%; max-width: 100%; border: 1px solid #ddd;">
                        <table class="table table-bordered table-striped" style="width: 1600px; white-space: nowrap;">
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Nama Warga</th>
                                    <th colspan="<?php echo count($kriteria_data); ?>" class="text-center">Kriteria PKH</th>
                                </tr>
                                <tr>
                                    <?php foreach($kriteria_data as $k): ?>
                                    <th class="text-center" title="<?php echo $k['keterangan']; ?>">
                                        <?php echo substr($k['keterangan'], 0, 15) . '...'; ?>
                                    </th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $no = 1;
                            $klasifikasi_query = mysqli_query($koneksi, "
                                SELECT k.*, w.nama_lengkap 
                                FROM tbl_klasifikasi k 
                                JOIN data_warga w ON k.id_warga = w.id_warga 
                                ORDER BY w.nama_lengkap
                            ");
                            
                            while($data = mysqli_fetch_array($klasifikasi_query)) {
                                echo "<tr>
                                        <td>$no</td>
                                        <td><strong>{$data['nama_lengkap']}</strong></td>";
                                foreach($kriteria_data as $k) {
                                    $nilai = isset($data[$k['kode_kriteria']]) ? $data[$k['kode_kriteria']] : 0;
                                    echo "<td class='text-center'>$nilai</td>";
                                }
                                echo "</tr>";
                                $no++;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- 2. Matriks Normalisasi -->
                    <h4 style="color: white !important;"><i class="fa fa-calculator"></i> 2. Matriks Normalisasi</h4>
                    <div style="overflow-x: scroll; overflow-y: visible; width: 100%; max-width: 100%; border: 1px solid #ddd;">
                        <table class="table table-bordered table-striped" style="width: 1600px; white-space: nowrap;">
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Nama Warga</th>
                                    <th colspan="<?php echo count($kriteria_data); ?>" class="text-center">Nilai Normalisasi</th>
                                </tr>
                                <tr>
                                    <?php foreach($kriteria_data as $k): ?>
                                    <th class="text-center" title="<?php echo $k['keterangan']; ?>">
                                        <?php echo substr($k['keterangan'], 0, 15) . '...'; ?>
                                    </th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $no = 1;
                            mysqli_data_seek($hasil_saw, 0);
                            while($data = mysqli_fetch_array($hasil_saw)) {
                                echo "<tr>
                                        <td>$no</td>
                                        <td><strong>{$data['nama_lengkap']}</strong></td>";
                                foreach($kriteria_data as $k) {
                                    $norm_field = $k['kode_kriteria'] . '_norm';
                                    $nilai_norm = isset($data[$norm_field]) ? number_format($data[$norm_field], 3) : '0.000';
                                    echo "<td class='text-center'>$nilai_norm</td>";
                                }
                                echo "</tr>";
                                $no++;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- 3. Matriks Terbobot -->
                    <h4 style="color: white !important;"><i class="fa fa-balance-scale"></i> 3. Matriks Terbobot</h4>
                    <div class="alert alert-warning">
                        <strong>Bobot Kriteria:</strong>
                        <?php foreach($kriteria_data as $k): ?>
                            <span class="label label-primary"><?php echo $k['kode_kriteria']; ?>: <?php echo $k['nilai']; ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div style="overflow-x: scroll; overflow-y: visible; width: 100%; max-width: 100%; border: 1px solid #ddd;">
                        <table class="table table-bordered table-striped" style="width: 1600px; white-space: nowrap;">
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Nama Warga</th>
                                    <th colspan="<?php echo count($kriteria_data); ?>" class="text-center">Nilai Terbobot</th>
                                    <th rowspan="2">Total SAW</th>
                                </tr>
                                <tr>
                                    <?php foreach($kriteria_data as $k): ?>
                                    <th class="text-center" title="<?php echo $k['keterangan']; ?>">
                                        <?php echo substr($k['keterangan'], 0, 15) . '...'; ?>
                                    </th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $no = 1;
                            mysqli_data_seek($hasil_saw, 0);
                            while($data = mysqli_fetch_array($hasil_saw)) {
                                echo "<tr>
                                        <td>$no</td>
                                        <td><strong>{$data['nama_lengkap']}</strong></td>";
                                
                                // Hitung nilai terbobot untuk setiap kriteria
                                foreach($kriteria_data as $k) {
                                    $norm_field = $k['kode_kriteria'] . '_norm';
                                    $nilai_norm = isset($data[$norm_field]) ? $data[$norm_field] : 0;
                                    $nilai_terbobot = $nilai_norm * $k['nilai'];
                                    echo "<td class='text-center'>" . number_format($nilai_terbobot, 3) . "</td>";
                                }
                                
                                echo "<td class='text-center'><strong>" . number_format($data['skor_akhir'], 4) . "</strong></td>
                                      </tr>";
                                $no++;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- 4. Hasil Ranking Final -->
                    <h4 style="color: white !important;"><i class="fa fa-trophy"></i> 4. Ranking Akhir</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <a class='btn btn-danger btn-flat' href='modul/mod_perankingan/export_pdf.php' target="_blank">
                                <i class="fa fa-file-pdf-o"></i> Export PDF
                            </a>
                            <a class='btn btn-primary btn-flat' href='?module=laporan&act=detail_perhitungan'>
                                <i class="fa fa-eye"></i> Detail Perhitungan
                            </a>
                        </div>
                    </div>
                    <br>
                    <?php endif; ?>
                    
                    <?php if ($total_hasil > 0): ?>
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">Rank</th>
                                <th width="25%">Nama Warga</th>
                                <th width="30%">Alamat</th>
                                <th width="15%">Nilai SAW</th>
                                <th width="15%">Status Kelayakan</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                            $rank = 1;
                            mysqli_data_seek($hasil_saw, 0);
                            while ($r = mysqli_fetch_array($hasil_saw)){
                                $nilai_saw = number_format($r['skor_akhir'], 4);
                                
                                // Tentukan status kelayakan berdasarkan ranking
                                if ($rank <= ceil($total_hasil * 0.3)) {
                                    $status = 'Sangat Layak';
                                    $badge = 'success';
                                } elseif ($rank <= ceil($total_hasil * 0.6)) {
                                    $status = 'Layak';
                                    $badge = 'primary';
                                } elseif ($rank <= ceil($total_hasil * 0.8)) {
                                    $status = 'Cukup Layak';
                                    $badge = 'warning';
                                } else {
                                    $status = 'Kurang Layak';
                                    $badge = 'danger';
                                }
                                
                                echo "<tr>
                                        <td><span class='label label-info'>$rank</span></td>
                                        <td><strong>$r[nama_lengkap]</strong></td>
                                        <td>$r[alamat]</td>
                                        <td><span class='label label-primary'>$nilai_saw</span></td>
                                        <td><span class='label label-$badge'>$status</span></td>
                                        <td>
                                            <a href='?module=laporan&act=detail_warga&id=$r[id_warga]' class='btn btn-info btn-xs'>
                                                <i class='fa fa-eye'></i> Detail
                                            </a>
                                        </td>
                                    </tr>";
                                $rank++;
                            }
                        ?>
                        </tbody>
                    </table>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="stat-card-modern layak">
                                <div class="stat-header-modern">
                                    <div>
                                        <div class="stat-value-modern"><?php echo ceil($total_hasil * 0.3); ?></div>
                                        <div class="stat-label-modern">Sangat Layak (Top 30%)</div>
                                        <div class="stat-trend">
                                            <i class="fas fa-check-circle"></i>
                                            Prioritas utama penerima PKH
                                        </div>
                                    </div>
                                    <div class="stat-icon-modern layak">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card-modern total">
                                <div class="stat-header-modern">
                                    <div>
                                        <div class="stat-value-modern"><?php echo $total_hasil; ?></div>
                                        <div class="stat-label-modern">Total Dianalisa</div>
                                        <div class="stat-trend">
                                            <i class="fas fa-users"></i>
                                            Warga yang telah dianalisis
                                        </div>
                                    </div>
                                    <div class="stat-icon-modern total">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <style>
                    /* Flat Laporan Stats - Same as Perankingan */
                    .stat-card-modern {
                        background: transparent;
                        border-radius: 0;
                        padding: 1rem 0;
                        box-shadow: none;
                        border: none;
                        border-bottom: 2px solid rgba(204, 201, 220, 0.3);
                        transition: all 0.3s ease;
                        position: relative;
                        overflow: hidden;
                        margin-bottom: 1rem;
                    }
                    
                    .stat-card-modern:hover {
                        transform: none;
                        box-shadow: none;
                        background: rgba(50, 74, 95, 0.02);
                    }
                    
                    .stat-header-modern {
                        display: flex;
                        align-items: flex-start;
                        justify-content: flex-start;
                        margin-bottom: 0.5rem;
                        gap: 1rem;
                    }
                    
                    .stat-icon-modern {
                        width: 40px;
                        height: 40px;
                        border-radius: 6px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 1.2rem;
                        color: white;
                        box-shadow: none;
                    }
                    
                    .stat-icon-modern.total {
                        background: #3B82F6;
                    }
                    
                    .stat-icon-modern.layak {
                        background: #10B981;
                    }
                    
                    .stat-value-modern {
                        font-size: 2rem;
                        font-weight: 600;
                        color: white;
                        margin-bottom: 0.25rem;
                        line-height: 1;
                    }
                    
                    .stat-label-modern {
                        color: rgba(255, 255, 255, 0.9);
                        font-size: 0.875rem;
                        font-weight: 500;
                        margin-bottom: 0.25rem;
                    }
                    
                    .stat-trend {
                        font-size: 0.75rem;
                        color: rgba(255, 255, 255, 0.7);
                        font-weight: 400;
                        display: flex;
                        align-items: center;
                        gap: 0.25rem;
                    }
                    </style>
                    
                    <?php else: ?>
                    <div class="alert alert-warning">
                        <h4><i class="fa fa-warning"></i> Belum Ada Data Hasil SAW</h4>
                        <p>Silakan lakukan perhitungan SAW terlebih dahulu dengan mengklik tombol 
                           <strong>"Hitung Ulang SAW"</strong> di atas.</p>
                        <p>Pastikan data warga dan kriteria sudah lengkap sebelum melakukan perhitungan.</p>
                    </div>
                    <?php endif; ?>

                    <!-- 5. Daftar Perankingan - New Ranking Table -->
                    <?php if ($total_hasil > 0): ?>
                    <br><br>
                    <h4 style="color: white !important;"><i class="fa fa-list-ol"></i> 5. Daftar Perankingan</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-group pull-right" style="margin-bottom: 15px;">
                                <button class="btn btn-info btn-flat" onclick="printRankingTable()">
                                    <i class="fa fa-print"></i> Print
                                </button>
                                <a href="modul/mod_perankingan/export_pdf.php" class="btn btn-success btn-flat" target="_blank">
                                    <i class="fa fa-file-pdf-o"></i> Save PDF
                                </a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="rankingTable">
                            <thead class="bg-primary">
                                <tr>
                                    <th width="15%" class="text-center">Ranking</th>
                                    <th width="50%">Nama</th>
                                    <th width="35%" class="text-center">Total Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            // Reset hasil_saw pointer and get ranking data
                            mysqli_data_seek($hasil_saw, 0);
                            $ranking = 1;
                            while ($row = mysqli_fetch_array($hasil_saw)) {
                                $nama_warga = htmlspecialchars($row['nama_lengkap']);
                                $total_nilai = number_format($row['skor_akhir'], 4);

                                // Add ranking styling
                                $rank_badge_class = '';
                                if ($ranking == 1) {
                                    $rank_badge_class = 'style="background: linear-gradient(45deg, #FFD700, #FFA500); color: #000;"';
                                } elseif ($ranking == 2) {
                                    $rank_badge_class = 'style="background: linear-gradient(45deg, #C0C0C0, #A8A8A8); color: #000;"';
                                } elseif ($ranking == 3) {
                                    $rank_badge_class = 'style="background: linear-gradient(45deg, #CD7F32, #A0522D); color: #000;"';
                                } else {
                                    $rank_badge_class = 'style="background: #337ab7;"';
                                }

                                echo "<tr>
                                        <td class='text-center'>
                                            <span class='badge' {$rank_badge_class}>
                                                <strong>#{$ranking}</strong>
                                            </span>
                                        </td>
                                        <td>
                                            <strong>{$nama_warga}</strong>
                                        </td>
                                        <td class='text-center'>
                                            <span class='label label-info' style='font-size: 14px; padding: 5px 10px;'>
                                                {$total_nilai}
                                            </span>
                                        </td>
                                      </tr>";
                                $ranking++;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Print and PDF JavaScript -->
                    <script>
                    function printRankingTable() {
                        // Get the ranking table content
                        var tableContent = document.getElementById('rankingTable').outerHTML;

                        // Create a new window for printing
                        var printWindow = window.open('', '_blank', 'width=1000,height=800');

                        // Modern styled print layout
                        printWindow.document.write(`
                            <!doctype html>
                            <html>
                            <head>
                                <meta charset="utf-8">
                                <title>Daftar Perankingan Penerima PKH</title>
                                <meta name="viewport" content="width=device-width,initial-scale=1">
                                <style>
                                    /* Page setup for printing */
                                    @page { size: A4 portrait; margin: 10mm; }

                                    :root{ --brand:#0f62fe; --muted:#6b7280; --card:#ffffff; --accent:#eef2ff; }
                                    html,body{height:100%;}

                                    /* Base layout */
                                    body{font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; background:#f3f4f6; margin:0; padding:0; color:#0f172a}

                                    /* Container uses small padding so printed area maximizes */
                                    .print-wrap{max-width:1000px; margin:0 auto; padding:8mm;}
                                    .print-card{background:var(--card); border-radius:8px; box-shadow:0 6px 18px rgba(15,23,42,0.06); padding:14px;}

                                    .brand-row{display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:12px}
                                    .brand-left{display:flex; align-items:center; gap:12px}
                                    .brand-logo{width:48px; height:48px; border-radius:6px; background:linear-gradient(135deg,var(--brand),#0366d6); display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:16px}
                                    .brand-title h1{margin:0; font-size:16px; letter-spacing:0.2px}
                                    .brand-title p{margin:0; color:var(--muted); font-size:11px}
                                    .meta{ text-align:right; color:var(--muted); font-size:11px }
                                    .stats-row{display:flex; gap:8px; margin-bottom:12px}
                                    .stat{flex:1; background:var(--accent); padding:10px; border-radius:8px; text-align:center}
                                    .stat .value{font-size:18px; font-weight:700; color:var(--brand)}
                                    .stat .label{font-size:10px; color:var(--muted); margin-top:4px}

                                    /* Modern table, compact spacing for print */
                                    table.modern-table{width:100%; border-collapse:separate; border-spacing:0; border-radius:6px; overflow:hidden; font-size:12px}
                                    table.modern-table thead th{background-color:var(--brand) !important; background-image: linear-gradient(135deg,var(--brand),#0366d6); color:#fff !important; padding:10px 8px; text-transform:uppercase; font-size:10px; letter-spacing:0.5px; text-align:left}
                                    table.modern-table tbody td{background:#fff; padding:9px 8px; vertical-align:middle}
                                    table.modern-table tbody tr:nth-child(even) td{background:#fbfdff}

                                    /* Ensure printed colors are preserved where possible */
                                    table.modern-table thead th, .brand-logo { -webkit-print-color-adjust: exact; print-color-adjust: exact; }

                                    .ranking-badge{display:inline-block; min-width:34px; height:34px; line-height:34px; border-radius:17px; background:#0f172a; color:#fff; text-align:center; font-weight:700}
                                    .pill{display:inline-block; padding:6px 10px; border-radius:999px; font-size:12px; color:#fff}
                                    .pill.layak{background:#10b981}
                                    .pill.tidak{background:#ef4444}
                                    .table-caption{font-size:12px; color:var(--muted); margin-bottom:6px}

                                    /* Print-specific tweaks */
                                    @media print{
                                        html, body { height: auto; }
                                        body{background:#fff;}
                                        .print-wrap{padding:4mm}
                                        .print-card{box-shadow:none; border-radius:0}
                                        .brand-logo{width:44px; height:44px}
                                        .brand-title h1{font-size:15px}
                                        /* Reduce table cell padding when printing to fit more */
                                        table.modern-table thead th{padding:8px 6px}
                                        table.modern-table tbody td{padding:6px 6px}
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="print-wrap">
                                    <div class="print-card">
                                        <div class="brand-row">
                                            <div class="brand-left">
                                                <div class="brand-logo">PKH</div>
                                                <div class="brand-title">
                                                    <h1>Sistem Pendukung Keputusan PKH</h1>
                                                    <p>Daftar Perankingan - Metode SAW</p>
                                                </div>
                                            </div>
                                            <div class="meta">
                                                <div>Tanggal: ${new Date().toLocaleString('id-ID')}</div>
                                                <div>Total: <?php echo $total_hasil; ?> warga</div>
                                            </div>
                                        </div>

                                        <div class="stats-row">
                                            <div class="stat">
                                                <div class="value"><?php echo $total_hasil; ?></div>
                                                <div class="label">Total Dianalisis</div>
                                            </div>
                                            <div class="stat">
                                                <div class="value"><?php echo $layak_count; ?></div>
                                                <div class="label">Layak (Rekomendasi)</div>
                                            </div>
                                            <div class="stat">
                                                <div class="value"><?php echo ($total_hasil - $layak_count); ?></div>
                                                <div class="label">Tidak Layak</div>
                                            </div>
                                        </div>

                                        <div class="table-caption">Tabel di bawah menampilkan perankingan penerima PKH berdasarkan skor SAW.</div>

                                        ${tableContent.replace(/class=\"table table-bordered table-striped table-hover\"/g, 'class="modern-table"')}

                                        <div style="margin-top:12px; font-size:12px; color:var(--muted)">
                                            <strong>Catatan:</strong> Dokumen ini bersifat informatif. Skor tertinggi menandakan prioritas penerimaan PKH.
                                        </div>
                                    </div>
                                </div>
                            </body>
                            </html>
                        `);

                        // Close the document writing
                        printWindow.document.close();

                        // Wait for content to load, then print
                        printWindow.onload = function() {
                            printWindow.focus();
                            printWindow.print();
                            setTimeout(function() { printWindow.close(); }, 800);
                        };

                        // Fallback: print immediately if onload doesn't fire
                        setTimeout(function() {
                            if (!printWindow.closed) {
                                printWindow.print();
                                setTimeout(function() { printWindow.close(); }, 800);
                            }
                        }, 600);
                    }
                    </script>

                    <?php endif; ?>
                </div>
            </div>
            <?php
        }
        break;

    case "hitung_saw":
        if ($_SESSION['leveluser']=='admin'){
            // Force output buffering and immediate display
            ob_end_flush();
            if (ob_get_level()) ob_end_flush();
            
            echo "<div class='box box-success box-solid'>
                    <div class='box-header with-border'>
                        <h3 class='box-title'><i class='fa fa-cog fa-spin'></i> Menghitung SAW...</h3>
                    </div>
                    <div class='box-body'>";
            
            flush();
            
            // 1. Clear existing results
            mysqli_query($koneksi, "DELETE FROM tbl_hasil_saw");
            echo "<p>✅ Membersihkan hasil perhitungan sebelumnya...</p>";
            flush();
            
            // 2. Get kriteria data
            $kriteria = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria ORDER BY id_kriteria");
            $kriteria_data = [];
            while($k = mysqli_fetch_array($kriteria)) {
                $kriteria_data[$k['kode_kriteria']] = $k;
            }
            echo "<p>✅ Memuat " . count($kriteria_data) . " kriteria...</p>";
            flush();
            
            // 3. Get klasifikasi data
            $klasifikasi = mysqli_query($koneksi, "
                SELECT k.*, w.nama_lengkap 
                FROM tbl_klasifikasi k 
                JOIN data_warga w ON k.id_warga = w.id_warga 
                ORDER BY w.nama_lengkap
            ");
            
            if (!$klasifikasi) {
                echo "<p>❌ Error: " . mysqli_error($koneksi) . "</p>";
                flush();
                break;
            }
            
            $data_alternatif = [];
            $max_values = ['C1' => 0, 'C2' => 0, 'C3' => 0, 'C4' => 0, 'C5' => 0, 'C6' => 0, 'C7' => 0, 'C8' => 0];
            
            while($row = mysqli_fetch_array($klasifikasi)) {
                $data_alternatif[] = $row;
                
                // Find max values for normalization (all criteria are Benefit type)
                for($i = 1; $i <= 8; $i++) {
                    $col = 'C' . $i;
                    if(isset($row[$col]) && $row[$col] > $max_values[$col]) $max_values[$col] = $row[$col];
                }
            }
            echo "<p>✅ Memuat " . count($data_alternatif) . " data warga...</p>";
            flush();
            
            // 4. Calculate normalization and SAW scores
            echo "<p>🔄 Melakukan normalisasi dan perhitungan SAW...</p>";
            flush();
            
            if (count($data_alternatif) == 0) {
                echo "<p>❌ Error: Tidak ada data warga untuk diproses!</p>";
                flush();
                break;
            }
            
            foreach($data_alternatif as $data) {
                $normalized = [];
                $saw_score = 0;
                
                // Normalize each criteria (all are Benefit type for PKH)
                for($i = 1; $i <= 8; $i++) {
                    $col = 'C' . $i;
                    $kode = 'C' . $i;
                    
                    if($max_values[$col] > 0 && isset($data[$col])) {
                        $normalized[$col] = $data[$col] / $max_values[$col];
                    } else {
                        $normalized[$col] = 0;
                    }
                    
                    // Add weighted value to SAW score
                    if (isset($kriteria_data[$kode]['nilai'])) {
                        $weight = $kriteria_data[$kode]['nilai'];
                        $saw_score += $normalized[$col] * $weight;
                    }
                }
                
                // Insert results with error checking
                $nama_escaped = mysqli_real_escape_string($koneksi, $data['nama_lengkap']);
                $insert_query = "
                    INSERT INTO tbl_hasil_saw 
                    (id_warga, nama_warga, C1_norm, C2_norm, C3_norm, C4_norm, C5_norm, C6_norm, C7_norm, C8_norm, skor_akhir, created_at) 
                    VALUES 
                    ({$data['id_warga']}, '{$nama_escaped}', 
                     {$normalized['C1']}, {$normalized['C2']}, {$normalized['C3']}, {$normalized['C4']}, 
                     {$normalized['C5']}, {$normalized['C6']}, {$normalized['C7']}, {$normalized['C8']}, 
                     $saw_score, NOW())
                ";
                
                $result = mysqli_query($koneksi, $insert_query);
                if (!$result) {
                    echo "<p>❌ Error inserting data for {$data['nama_lengkap']}: " . mysqli_error($koneksi) . "</p>";
                    flush();
                }
            }
            
            // 5. Update rankings with error checking
            echo "<p>🔄 Updating rankings...</p>";
            flush();
            
            $hasil_ordered = mysqli_query($koneksi, "SELECT id_hasil, nama_warga, skor_akhir FROM tbl_hasil_saw ORDER BY skor_akhir DESC");
            if (!$hasil_ordered) {
                echo "<p>❌ Error retrieving results: " . mysqli_error($koneksi) . "</p>";
                flush();
                break;
            }
            
            // Initialize ranking counter
            $rank = 1;
            $updated_count = 0;
            
            // Clear any existing rankings first to avoid conflicts
            mysqli_query($koneksi, "UPDATE tbl_hasil_saw SET ranking = NULL");
            
            while($row = mysqli_fetch_array($hasil_ordered)) {
                $update_result = mysqli_query($koneksi, "UPDATE tbl_hasil_saw SET ranking = $rank WHERE id_hasil = {$row['id_hasil']}");
                
                if ($update_result) {
                    $updated_count++;
                    echo "<p style='color: #666; font-size: 12px;'>Rank $rank: {$row['nama_warga']} (Score: " . number_format($row['skor_akhir'], 4) . ")</p>";
                    flush();
                } else {
                    echo "<p>❌ Error updating rank for {$row['nama_warga']}: " . mysqli_error($koneksi) . "</p>";
                    flush();
                }
                
                $rank++;
            }
            
            echo "<p>✅ Updated $updated_count rankings successfully!</p>";
            flush();
            
            echo "<p>✅ Ranking berhasil dihitung!</p>";
            flush();
            
            // 6. Set recommendations (top 30% get "Ya")
            $total_warga = count($data_alternatif);
            $top_30_percent = ceil($total_warga * 0.3);
            
            mysqli_query($koneksi, "UPDATE tbl_hasil_saw SET rekomendasi = 'Tidak'");
            mysqli_query($koneksi, "UPDATE tbl_hasil_saw SET rekomendasi = 'Ya' WHERE ranking <= $top_30_percent");
            
            echo "<p>✅ Rekomendasi berhasil ditetapkan (Top 30% = $top_30_percent warga)!</p>";
            flush();
            
            // Update header to show completion
            echo "<script>
                    document.querySelector('.box-title').innerHTML = '<i class=\"fa fa-check text-success\"></i> Perhitungan SAW Selesai!';
                    document.querySelector('.box').className = 'box box-success box-solid';
                  </script>";
            
            echo "<div class='alert alert-success' style='margin-top: 10px;'>
                    <h4><i class='fa fa-check'></i> Perhitungan Berhasil Diselesaikan!</h4>
                    <p>Mengarahkan ke halaman hasil analisa dalam <span id='countdown'>3</span> detik...</p>
                    <div class='progress' style='margin-top: 10px;'>
                        <div class='progress-bar progress-bar-success progress-bar-striped active' 
                             style='width: 100%; animation: countdown 3s linear;'></div>
                    </div>
                  </div>";
            
            echo "<script>
                    var count = 3;
                    var countdown = setInterval(function() {
                        count--;
                        document.getElementById('countdown').textContent = count;
                        if (count <= 0) {
                            clearInterval(countdown);
                            window.location.href = '?module=laporan&act=analisa';
                        }
                    }, 1000);
                    
                    // CSS for countdown animation
                    var style = document.createElement('style');
                    style.textContent = '@keyframes countdown { from { width: 100%; } to { width: 0%; } }';
                    document.head.appendChild(style);
                  </script>";
            
            echo "</div>
                  <div class='box-footer'>
                      <a href='?module=laporan&act=analisa' class='btn btn-primary'>
                          <i class='fa fa-eye'></i> Lihat Hasil Analisa Sekarang
                      </a>
                      <a href='?module=perankingan' class='btn btn-success'>
                          <i class='fa fa-trophy'></i> Lihat Perankingan
                      </a>
                  </div>
                  </div>";
        }
        break;

    case "detail_perhitungan":
        if ($_SESSION['leveluser']=='admin'){
            // Get kriteria info
            $kriteria = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria ORDER BY id_kriteria");
            
            // Get klasifikasi data
            $klasifikasi = mysqli_query($koneksi, "
                SELECT k.*, w.nama_lengkap 
                FROM tbl_klasifikasi k 
                JOIN data_warga w ON k.id_warga = w.id_warga 
                ORDER BY w.nama_lengkap
            ");
            ?>
            <div class="box box-info box-solid saw-detail">
                <div class="box-header with-border" style="color: #fff !important;">
                    <h3 class="box-title" style="color: #fff !important;"><i class="fa fa-calculator" style="color: #fff !important;"></i> Detail Perhitungan Metode SAW</h3>
                </div>
                <div class="box-body">
                    <style>
                        /* Scope only for SAW detail page */
                        .saw-detail .box-header, .saw-detail .box-title, .saw-detail .box-title i {
                            color: #fff !important;
                        }
                        .saw-detail table tfoot tr,
                        .saw-detail table tfoot th {
                            color: #fff !important;
                            opacity: 1 !important;
                        }
                        .saw-detail h4, .saw-detail h4 i { color: #fff !important; }
                    </style>
                    <div class="alert alert-info">
                        <h4><i class="fa fa-info-circle"></i> Metode Simple Additive Weighting (SAW)</h4>
                        <p>Metode SAW menggunakan formula: <code>Ri = Σ(wj × rij)</code></p>
                        <ul>
                            <li><strong>Ri</strong> = Nilai akhir alternatif ke-i</li>
                            <li><strong>wj</strong> = Bobot kriteria ke-j</li>
                            <li><strong>rij</strong> = Nilai normalisasi matriks</li>
                        </ul>
                    </div>
                    
                    <h4><i class="fa fa-table"></i> 1. Bobot Kriteria</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Keterangan</th>
                                <th>Bobot</th>
                                <th>Jenis</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $total_bobot = 0;
                        mysqli_data_seek($kriteria, 0);
                        while($k = mysqli_fetch_array($kriteria)) {
                            $total_bobot += $k['nilai'];
                            echo "<tr>
                                    <td><strong>{$k['kode_kriteria']}</strong></td>
                                    <td>{$k['keterangan']}</td>
                                    <td><span class='label label-primary'>{$k['nilai']}</span></td>
                                    <td><span class='label label-" . ($k['jenis']=='Benefit' ? 'success' : 'warning') . "'>{$k['jenis']}</span></td>
                                  </tr>";
                        }
                        ?>
                        </tbody>
                        <tfoot>
                            <tr style="color: #fff !important; opacity: 1 !important;">
                                <th colspan="2" style="color: #fff !important;">Total Bobot</th>
                                <th><span class="label label-<?php echo ($total_bobot == 1.0) ? 'success' : 'danger'; ?>"><?php echo $total_bobot; ?></span></th>
                                <th><?php echo ($total_bobot == 1.0) ? '<i class="fa fa-check text-success"></i> Valid' : '<i class="fa fa-times text-danger"></i> <span style="color:#fff !important;">Invalid</span>'; ?></th>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <h4 style="color: white !important;"><i class="fa fa-table"></i> 2. Matriks Data Awal</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Warga</th>
                                    <th>C1</th><th>C2</th><th>C3</th><th>C4</th>
                                    <th>C5</th><th>C6</th><th>C7</th><th>C8</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            mysqli_data_seek($klasifikasi, 0);
                            while($data = mysqli_fetch_array($klasifikasi)) {
                                echo "<tr>
                                        <td><strong>{$data['nama_lengkap']}</strong></td>
                                        <td>{$data['C1']}</td>
                                        <td>{$data['C2']}</td>
                                        <td>{$data['C3']}</td>
                                        <td>{$data['C4']}</td>
                                        <td>{$data['C5']}</td>
                                        <td>{$data['C6']}</td>
                                        <td>{$data['C7']}</td>
                                        <td>{$data['C8']}</td>
                                      </tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h4><i class="fa fa-warning"></i> Catatan Perhitungan</h4>
                        <ol>
                            <li><strong>Normalisasi:</strong> Untuk kriteria Benefit menggunakan rumus Xij/Max(Xi), untuk Cost menggunakan Min(Xi)/Xij</li>
                            <li><strong>Pembobotan:</strong> Hasil normalisasi dikalikan dengan bobot masing-masing kriteria</li>
                            <li><strong>Ranking:</strong> Nilai SAW tertinggi menunjukkan alternatif terbaik</li>
                            <li><strong>Validasi:</strong> Total bobot kriteria harus sama dengan 1.0</li>
                        </ol>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick="history.back()">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </button>
                    <a href="?module=laporan&act=hitung_saw" class="btn btn-success">
                        <i class="fa fa-calculator"></i> Hitung SAW
                    </a>
                </div>
            </div>
            <?php
        }
        break;

    case "detail_warga":
        if ($_SESSION['leveluser']=='admin'){
            $id_warga = (int)$_GET['id'];
            
            // Get warga detail with SAW result
            $detail = mysqli_query($koneksi, "
                SELECT w.*, h.skor_akhir as nilai_saw, h.created_at as saw_created,
                       k.C1, k.C2, k.C3, k.C4, k.C5, k.C6, k.C7, k.C8
                FROM data_warga w
                LEFT JOIN tbl_hasil_saw h ON w.id_warga = h.id_warga
                LEFT JOIN tbl_klasifikasi k ON w.id_warga = k.id_warga
                WHERE w.id_warga = $id_warga
            ");
            
            if (mysqli_num_rows($detail) == 0) {
                echo "<script>alert('Data tidak ditemukan!'); history.back();</script>";
                exit;
            }
            
            $r = mysqli_fetch_array($detail);
            ?>
            <div class="box box-info box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-user"></i> Detail Analisa Warga: <?php echo $r['nama_lengkap']; ?></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4><i class="fa fa-user"></i> Data Warga</h4>
                            <table class="table table-bordered">
                                <tr><td><strong>Nama</strong></td><td><?php echo $r['nama_lengkap']; ?></td></tr>
                                <tr><td><strong>Alamat</strong></td><td><?php echo $r['alamat']; ?></td></tr>
                                <tr><td><strong>Lansia</strong></td><td><?php echo $r['jumlah_lansia']; ?> orang</td></tr>
                                <tr><td><strong>Disabilitas</strong></td><td><?php echo $r['jumlah_disabilitas_berat']; ?> orang</td></tr>
                                <tr><td><strong>Anak SD</strong></td><td><?php echo $r['jumlah_anak_sd']; ?> orang</td></tr>
                                <tr><td><strong>Anak SMP</strong></td><td><?php echo $r['jumlah_anak_smp']; ?> orang</td></tr>
                                <tr><td><strong>Anak SMA</strong></td><td><?php echo $r['jumlah_anak_sma']; ?> orang</td></tr>
                                <tr><td><strong>Balita</strong></td><td><?php echo $r['jumlah_balita']; ?> orang</td></tr>
                                <tr><td><strong>Ibu Hamil</strong></td><td><?php echo $r['jumlah_ibu_hamil']; ?> orang</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4><i class="fa fa-bar-chart"></i> Hasil Analisa SAW</h4>
                            <?php if ($r['nilai_saw']): ?>
                            <table class="table table-bordered">
                                <tr><td><strong>Nilai SAW</strong></td><td><span class="label label-primary"><?php echo number_format($r['nilai_saw'], 4); ?></span></td></tr>
                                <tr><td><strong>Dihitung Pada</strong></td><td><?php echo date('d/m/Y H:i', strtotime($r['saw_created'])); ?></td></tr>
                            </table>
                            
                            <div class="alert alert-success">
                                <h4><i class="fa fa-check"></i> Status: Sudah Dianalisa</h4>
                                <p>Warga ini telah melalui proses perhitungan SAW dan memiliki nilai kelayakan.</p>
                            </div>
                            <?php else: ?>
                            <div class="alert alert-warning">
                                <h4><i class="fa fa-warning"></i> Status: Belum Dianalisa</h4>
                                <p>Warga ini belum melalui proses perhitungan SAW.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick="history.back()">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </button>
                    <a href="?module=warga&act=edit&id=<?php echo $r['id_warga']; ?>" class="btn btn-warning">
                        <i class="fa fa-edit"></i> Edit Data
                    </a>
                </div>
            </div>
            <?php
        }
        break;

    case "export_pdf":
        if ($_SESSION['leveluser']=='admin') {
            // Redirect to aksi_laporan.php for export
            header("Location: modul/mod_laporan/aksi_laporan.php?act=export_pdf");
            exit;
        }
        break;

    case "export_ranking_pdf":
        if ($_SESSION['leveluser']=='admin'){
            // Use Dompdf
            require_once('../../../vendor/autoload.php');

            // Get ranking data
            $hasil_saw = mysqli_query($koneksi, "
                SELECT h.*, w.nama_lengkap
                FROM tbl_hasil_saw h
                JOIN data_warga w ON h.id_warga = w.id_warga
                ORDER BY h.skor_akhir DESC
            ");
-
-            $options = new Options();
-            $options->set('isRemoteEnabled', true);
-            $dompdf = new Dompdf($options);
+            $options = new \Dompdf\Options();
+            $options->set('isRemoteEnabled', true);
+            $options->set('defaultFont', 'DejaVu Sans');
+            $dompdf = new \Dompdf\Dompdf($options);

            ob_start();
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8">
                <title>Daftar Perankingan Penerima PKH</title>
                <style>
                    body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color:#222; }
                    h1 { text-align: center; margin: 0 0 8px; }
                    h3 { text-align: center; margin: 0 0 14px; color:#555; }
                    .meta { font-size: 10px; margin-bottom: 10px; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #ddd; padding: 6px 8px; }
                    thead th { background: #3498db; color: #fff; text-align: center; }
                    .text-center { text-align: center; }
                </style>
            </head>
            <body>
                <h1>DAFTAR PERANKINGAN PENERIMA PKH</h1>
                <h3>SPK Metode SAW</h3>
                <div class="meta">Tanggal: <?= date('d/m/Y'); ?></div>
                <table>
                    <thead>
                        <tr>
                            <th width="20%">Ranking</th>
                            <th width="55%">Nama</th>
                            <th width="25%">Total Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $ranking = 1; mysqli_data_seek($hasil_saw, 0); while($row = mysqli_fetch_array($hasil_saw)): ?>
                            <tr>
                                <td class="text-center">#<?= $ranking++; ?></td>
                                <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                                <td class="text-center"><?= number_format((float)$row['skor_akhir'], 4); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </body>
            </html>
            <?php
            $html = ob_get_clean();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream('Perankingan_PKH_' . date('Y-m-d') . '.pdf', ['Attachment' => true]);
            exit;
        }
        break;
}
}
?>
