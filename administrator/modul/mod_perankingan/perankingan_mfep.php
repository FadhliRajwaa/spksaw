<?php
$act = isset($_GET['act']) ? $_GET['act'] : '';
switch($act){
    default:
        if($_SESSION['leveluser']=='admin'){
            echo "<div class='box box-primary box-solid'>
                    <div class='box-header with-border'>
                        <h3 class='box-title'><i class='box-title-icon fa fa-trophy'></i> Daftar Perankingan Penerima PKH (MFEP)</h3>
                        <div class='box-tools pull-right'>
                            <a href='modul/mod_perankingan/export_pdf_mfep.php' class='btn btn-success btn-flat' target='_blank' title='Download Laporan PDF'>
                                <i class='fa fa-file-pdf-o'></i> Export PDF
                            </a>
                        </div>
                    </div>
                    <div class='box-body'>
                        <div class='print-header' style='display:none; text-align:center; margin-bottom:10px;'>
                            <h3 style='margin:0;'>Perankingan Penerima PKH (MFEP)</h3>
                            <small>".date('d M Y, H:i')." WIB</small>
                        </div>";

            // Hitung total data hasil MFEP
            $check_hasil = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tbl_hasil_mfep");
            $total_hasil = ($check_hasil) ? (int)mysqli_fetch_array($check_hasil)['total'] : 0;

            if($total_hasil == 0) {
                echo "<div class='alert alert-warning'>
                        <h4><i class='icon fa fa-warning'></i> Perhitungan MFEP Belum Dilakukan!</h4>
                        Silakan lakukan perhitungan MFEP terlebih dahulu di menu <strong>Laporan Hasil Perhitungan</strong>.
                        <br><br>
                        <a href='?module=laporan&act=analisa' class='btn btn-primary'>
                            <i class='fa fa-calculator'></i> Ke Laporan Perhitungan
                        </a>
                      </div>";
            } else {
                echo "<div class='alert alert-info'>
                        <i class='fa fa-info-circle'></i> 
                        Menampilkan hasil perankingan dari <strong>$total_hasil warga</strong> 
                        yang telah dianalisis menggunakan metode <strong>MFEP (Multi Factor Evaluation Process)</strong>.
                      </div>";
                
                // Export and action buttons section
                echo "<div class='row' style='margin-bottom: 20px;'>
                        <div class='col-md-12'>
                            <div class='btn-action-group pull-right' style='display: flex; flex-wrap: wrap; gap: 8px; align-items: center; justify-content: flex-end;'>
                                <a href='modul/mod_perankingan/export_pdf_mfep.php' class='btn btn-success btn-flat action-btn' target='_blank' title='Download Laporan PDF' style='background: #28a745 !important; color: white !important; text-decoration: none; padding: 8px 14px; line-height: 1;'>
                                    <i class='fa fa-file-pdf-o'></i> Export PDF
                                </a>
                                <button class='btn btn-info btn-flat action-btn' onclick='window.print()' title='Cetak Hasil Perankingan' style='background: #17a2b8 !important; color: white !important; border: none; padding: 8px 14px; line-height: 1;'>
                                    <i class='fa fa-print'></i> Cetak Data
                                </button>
                            </div>
                            <div class='clearfix'></div>
                        </div>
                      </div>";
                
                echo "<div class='table-responsive'>
                        <table class='table table-bordered table-striped table-hover' id='rankingTable'>
                            <thead class='bg-primary'>
                                <tr>
                                    <th width='8%' class='text-center'>Ranking</th>
                                    <th width='28%'>Nama Warga</th>
                                    <th width='15%' class='text-center'>Total WE (∑WE)</th>
                                    <th width='15%' class='text-center'>Nilai MFEP</th>
                                    <th width='18%' class='text-center'>Rekomendasi</th>
                                    <th width='16%' class='text-center'>Status</th>
                                </tr>
                            </thead>
                            <tbody>";
                
                $hasil = mysqli_query($koneksi, "
                    SELECT * FROM tbl_hasil_mfep 
                    ORDER BY ranking ASC
                ");
                
                $no = 1;
                while($row = mysqli_fetch_array($hasil)) {
                    $badge_class = $row['rekomendasi'] == 'Ya' ? 'success' : 'default';
                    
                    // Tentukan status dan class
                    if($row['ranking'] <= ceil($total_hasil * 0.3)) {
                        $status = 'Sangat Layak';
                        $status_class = 'success';
                    } elseif($row['ranking'] <= ceil($total_hasil * 0.6)) {
                        $status = 'Layak';
                        $status_class = 'info';
                    } elseif($row['ranking'] <= ceil($total_hasil * 0.8)) {
                        $status = 'Cukup Layak';
                        $status_class = 'warning';
                    } else {
                        $status = 'Kurang Layak';
                        $status_class = 'danger';
                    }
                    
                    // Medal icons for top 3
                    $medal = '';
                    if($row['ranking'] == 1) $medal = '<i class="fa fa-trophy" style="color: #FFD700;"></i> ';
                    elseif($row['ranking'] == 2) $medal = '<i class="fa fa-trophy" style="color: #C0C0C0;"></i> ';
                    elseif($row['ranking'] == 3) $medal = '<i class="fa fa-trophy" style="color: #CD7F32;"></i> ';
                    
                    echo "<tr>
                            <td class='text-center'>
                                <h4 style='margin: 5px 0;'>
                                    <strong>$medal#{$row['ranking']}</strong>
                                </h4>
                            </td>
                            <td>
                                <strong>{$row['nama_warga']}</strong>
                            </td>
                            <td class='text-center'>
                                <span class='label label-primary' style='font-size: 13px;'>" . number_format($row['total_we'], 4) . "</span>
                            </td>
                            <td class='text-center'>
                                <span class='label label-success' style='font-size: 13px;'>" . number_format($row['nilai_mfep'], 4) . "</span>
                            </td>
                            <td class='text-center'>
                                <span class='label label-{$badge_class}' style='font-size: 13px;'>{$row['rekomendasi']}</span>
                            </td>
                            <td class='text-center'>
                                <span class='label label-{$status_class}' style='font-size: 13px;'>{$status}</span>
                            </td>
                          </tr>";
                    $no++;
                }
                
                echo "</tbody>
                      </table>
                      </div>";
                
                // Summary statistics
                $stats = mysqli_query($koneksi, "
                    SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN rekomendasi = 'Ya' THEN 1 ELSE 0 END) as layak,
                        SUM(CASE WHEN rekomendasi = 'Tidak' THEN 1 ELSE 0 END) as tidak_layak,
                        MAX(nilai_mfep) as mfep_max,
                        MIN(nilai_mfep) as mfep_min,
                        AVG(nilai_mfep) as mfep_avg
                    FROM tbl_hasil_mfep
                ");
                $stat = mysqli_fetch_array($stats);
                
                echo "<div class='row' style='margin-top: 30px;'>
                        <div class='col-md-3 col-sm-6'>
                            <div class='info-box bg-aqua'>
                                <span class='info-box-icon'><i class='fa fa-users'></i></span>
                                <div class='info-box-content'>
                                    <span class='info-box-text'>Total Warga</span>
                                    <span class='info-box-number'>{$stat['total']}</span>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-3 col-sm-6'>
                            <div class='info-box bg-green'>
                                <span class='info-box-icon'><i class='fa fa-check-circle'></i></span>
                                <div class='info-box-content'>
                                    <span class='info-box-text'>Layak PKH</span>
                                    <span class='info-box-number'>{$stat['layak']}</span>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-3 col-sm-6'>
                            <div class='info-box bg-yellow'>
                                <span class='info-box-icon'><i class='fa fa-star'></i></span>
                                <div class='info-box-content'>
                                    <span class='info-box-text'>Nilai Tertinggi</span>
                                    <span class='info-box-number'>" . number_format($stat['mfep_max'], 4) . "</span>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-3 col-sm-6'>
                            <div class='info-box bg-red'>
                                <span class='info-box-icon'><i class='fa fa-calculator'></i></span>
                                <div class='info-box-content'>
                                    <span class='info-box-text'>Rata-rata MFEP</span>
                                    <span class='info-box-number'>" . number_format($stat['mfep_avg'], 4) . "</span>
                                </div>
                            </div>
                        </div>
                      </div>";
                
                echo "<div class='alert alert-success' style='margin-top: 20px;'>
                        <h4><i class='fa fa-info-circle'></i> Keterangan Status Kelayakan:</h4>
                        <ul style='margin-bottom: 0;'>
                            <li><strong>Sangat Layak (Top 30%):</strong> Prioritas utama penerima bantuan PKH</li>
                            <li><strong>Layak (31%-60%):</strong> Penerima bantuan PKH tahap kedua</li>
                            <li><strong>Cukup Layak (61%-80%):</strong> Cadangan penerima bantuan PKH</li>
                            <li><strong>Kurang Layak (81%-100%):</strong> Perlu evaluasi ulang kriteria</li>
                        </ul>
                      </div>";
            }
            
            echo "</div></div>";
            
            echo "<style>
            @media print {
                @page { size: A4 landscape; margin: 10mm; }
                html, body { background: #fff !important; zoom: 0.9; }

                /* Sembunyikan elemen layout agar area cetak maksimal */
                .main-header, .content-header, .main-sidebar, .left-side, .control-sidebar, .navbar, .sidebar-toggle,
                .box-header, .box-tools, .btn-action-group, .alert, .btn { display: none !important; }
                .content-wrapper, .right-side, .box, .box-body { margin: 0 !important; padding: 0 !important; border: 0 !important; }

                .print-header { display:block !important; }
                .table-responsive { overflow: visible !important; }
                table { width: 100% !important; border-collapse: collapse !important; table-layout: fixed; }
                thead { display: table-header-group !important; }
                tr { page-break-inside: avoid !important; }
                th, td { padding: 6px 5px !important; font-size: 10px !important; word-wrap: break-word; white-space: normal !important; }
                table, th, td { border-color: #999 !important; }
                h3, h4 { page-break-after: avoid !important; margin-top: 6px !important; margin-bottom: 6px !important; }

                thead.bg-primary th {
                    -webkit-print-color-adjust: exact; print-color-adjust: exact;
                    background: #3c8dbc !important; color: #fff !important;
                }
            }
            </style>";
        }
        break;
        
    case "detail":
        // Detail perhitungan untuk warga tertentu
        if($_SESSION['leveluser']=='admin' && isset($_GET['id'])){
            $id = $_GET['id'];
            $detail = mysqli_query($koneksi, "
                SELECT * FROM tbl_hasil_mfep WHERE id_hasil='$id'
            ");
            
            if(mysqli_num_rows($detail) > 0) {
                $d = mysqli_fetch_array($detail);
                
                echo "<div class='box box-info box-solid'>
                        <div class='box-header with-border'>
                            <h3 class='box-title'><i class='fa fa-info-circle'></i> Detail Perhitungan MFEP: {$d['nama_warga']}</h3>
                            <div class='box-tools pull-right'>
                                <button class='btn btn-box-tool' onclick='history.back()'><i class='fa fa-arrow-left'></i> Kembali</button>
                            </div>
                        </div>
                        <div class='box-body'>
                            <h4>Informasi Umum</h4>
                            <table class='table table-bordered'>
                                <tr>
                                    <th width='30%'>Ranking</th>
                                    <td><strong>#{$d['ranking']}</strong></td>
                                </tr>
                                <tr>
                                    <th>Nama Warga</th>
                                    <td><strong>{$d['nama_warga']}</strong></td>
                                </tr>
                                <tr>
                                    <th>Total WE</th>
                                    <td>" . number_format($d['total_we'], 4) . "</td>
                                </tr>
                                <tr>
                                    <th>Nilai MFEP</th>
                                    <td><strong>" . number_format($d['nilai_mfep'], 4) . "</strong></td>
                                </tr>
                                <tr>
                                    <th>Rekomendasi</th>
                                    <td><span class='label label-" . ($d['rekomendasi'] == 'Ya' ? 'success' : 'default') . "'>{$d['rekomendasi']}</span></td>
                                </tr>
                            </table>
                            
                            <h4>Rincian Perhitungan</h4>
                            <table class='table table-bordered table-striped'>
                                <thead class='bg-info'>
                                    <tr>
                                        <th>Kriteria</th>
                                        <th class='text-center'>Nilai (X)</th>
                                        <th class='text-center'>Factor (E)</th>
                                        <th class='text-center'>Weight Eval (WE)</th>
                                    </tr>
                                </thead>
                                <tbody>";
                
                for($i=1; $i<=8; $i++) {
                    echo "<tr>
                            <td>C$i</td>
                            <td class='text-center'>{$d['C'.$i]}</td>
                            <td class='text-center'>" . number_format($d['E'.$i], 4) . "</td>
                            <td class='text-center'>" . number_format($d['WE'.$i], 4) . "</td>
                          </tr>";
                }
                
                echo "</tbody>
                            </table>
                        </div>
                      </div>";
            }
        }
        break;
}
?>
