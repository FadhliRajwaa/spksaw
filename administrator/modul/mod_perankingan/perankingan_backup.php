<?php
$act=isset($_GET['act'])?$_GET['act']:'';
switch($act){
    default:
        if($_SESSION['leveluser']=='admin'){
            echo "<div class='box box-primary box-solid'>
                    <div class='box-header with-border'>
                        <h3 class='box-title'><i class='fa fa-trophy'></i> Daftar Perankingan Penerima PKH</h3>
                        <div class='box-tools pull-right'>
                            <a href='?module=perankingan&act=pdf' class='btn btn-sm btn-success' target='_blank'>
                                <i class='fa fa-file-pdf-o'></i> Export PDF
                            </a>
                        </div>
                    </div>
                    <div class='box-body'>";
            
            // Check if SAW calculation has been done
            $check_hasil = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tbl_hasil_saw");
            $total_hasil = mysqli_fetch_array($check_hasil)['total'];
            
            if($total_hasil == 0) {
                echo "<div class='alert alert-warning'>
                        <h4><i class='icon fa fa-warning'></i> Perhitungan SAW Belum Dilakukan!</h4>
                        Silakan lakukan perhitungan SAW terlebih dahulu.
                        <br><br>
                        <a href='?module=laporan&act=hitung_saw' class='btn btn-primary'>
                            <i class='fa fa-calculator'></i> Hitung SAW Sekarang
                        </a>
                      </div>";
            } else {
                echo "<div class='alert alert-info'>
                        <i class='fa fa-info-circle'></i> 
                        Menampilkan hasil perankingan dari <strong>$total_hasil warga</strong> 
                        yang telah dianalisis menggunakan metode SAW (Simple Additive Weighting).
                      </div>";
                
                echo "<div class='table-responsive'>
                        <table class='table table-bordered table-striped table-hover'>
                            <thead class='bg-primary'>
                                <tr>
                                    <th width='10%' class='text-center'>Ranking</th>
                                    <th width='40%'>Nama Warga</th>
                                    <th width='20%' class='text-center'>Total Nilai</th>
                                    <th width='20%' class='text-center'>Rekomendasi</th>
                                    <th width='10%' class='text-center'>Detail</th>
                                </tr>
                            </thead>
                            <tbody>";
                
                $hasil = mysqli_query($koneksi, "
                    SELECT h.*, w.alamat 
                    FROM tbl_hasil_saw h 
                    JOIN data_warga w ON h.id_warga = w.id_warga 
                    ORDER BY h.ranking ASC
                ");
                
                $no = 1;
                while($row = mysqli_fetch_array($hasil)) {
                    $badge_class = $row['rekomendasi'] == 'Ya' ? 'success' : 'danger';
                    $rank_class = '';
                    if($row['ranking'] == 1) $rank_class = 'text-yellow';
                    elseif($row['ranking'] == 2) $rank_class = 'text-gray';
                    elseif($row['ranking'] == 3) $rank_class = 'text-orange';
                    
                    echo "<tr>
                            <td class='text-center'>
                                <span class='badge bg-primary $rank_class' style='font-size: 14px;'>
                                    #{$row['ranking']}
                                </span>
                            </td>
                            <td>
                                <strong>{$row['nama_warga']}</strong>
                                <br><small class='text-muted'>{$row['alamat']}</small>
                            </td>
                            <td class='text-center'>
                                <span class='label label-info'>" . number_format($row['skor_akhir'], 4) . "</span>
                            </td>
                            <td class='text-center'>
                                <span class='label label-{$badge_class}'>{$row['rekomendasi']}</span>
                            </td>
                            <td class='text-center'>
                                <a href='?module=perankingan&act=detail&id={$row['id_hasil']}' 
                                   class='btn btn-xs btn-info' title='Lihat Detail'>
                                    <i class='fa fa-eye'></i>
                                </a>
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
                        MAX(skor_akhir) as skor_max,
                        MIN(skor_akhir) as skor_min,
                        AVG(skor_akhir) as skor_avg
                    FROM tbl_hasil_saw
                ");
                $stat = mysqli_fetch_array($stats);
                
                echo "<div class='row'>
                        <div class='col-md-3'>
                            <div class='info-box bg-aqua'>
                                <span class='info-box-icon'><i class='fa fa-users'></i></span>
                                <div class='info-box-content'>
                                    <span class='info-box-text'>Total Warga</span>
                                    <span class='info-box-number'>{$stat['total']}</span>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-3'>
                            <div class='info-box bg-green'>
                                <span class='info-box-icon'><i class='fa fa-check'></i></span>
                                <div class='info-box-content'>
                                    <span class='info-box-text'>Layak PKH</span>
                                    <span class='info-box-number'>{$stat['layak']}</span>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-3'>
                            <div class='info-box bg-red'>
                                <span class='info-box-icon'><i class='fa fa-times'></i></span>
                                <div class='info-box-content'>
                                    <span class='info-box-text'>Tidak Layak</span>
                                    <span class='info-box-number'>{$stat['tidak_layak']}</span>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-3'>
                            <div class='info-box bg-yellow'>
                                <span class='info-box-icon'><i class='fa fa-star'></i></span>
                                <div class='info-box-content'>
                                    <span class='info-box-text'>Skor Tertinggi</span>
                                    <span class='info-box-number'>" . number_format($stat['skor_max'], 3) . "</span>
                                </div>
                            </div>
                        </div>
                      </div>";
            }
            
            echo "</div>
                  </div>";
        }
        break;
        
    case "detail":
        if($_SESSION['leveluser']=='admin'){
            $id = $_GET['id'];
            $detail = mysqli_query($koneksi, "
                SELECT h.*, w.alamat, w.no_kk, w.no_ktp 
                FROM tbl_hasil_saw h 
                JOIN data_warga w ON h.id_warga = w.id_warga 
                WHERE h.id_hasil = '$id'
            ");
            $data = mysqli_fetch_array($detail);
            
            // Get original criteria values
            $klasifikasi = mysqli_query($koneksi, "
                SELECT * FROM tbl_klasifikasi WHERE id_warga = '{$data['id_warga']}'
            ");
            $klasif = mysqli_fetch_array($klasifikasi);
            
            // Get criteria names
            $kriteria = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria ORDER BY id_kriteria");
            $kriteria_names = [];
            while($k = mysqli_fetch_array($kriteria)) {
                $kriteria_names[$k['kode_kriteria']] = $k['keterangan'];
            }
            
            echo "<div class='box box-info box-solid'>
                    <div class='box-header with-border'>
                        <h3 class='box-title'>
                            <i class='fa fa-user'></i> Detail Perankingan: {$data['nama_warga']}
                        </h3>
                        <div class='box-tools pull-right'>
                            <a href='?module=perankingan' class='btn btn-sm btn-default'>
                                <i class='fa fa-arrow-left'></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class='box-body'>
                        <div class='row'>
                            <div class='col-md-6'>
                                <h4>Informasi Warga</h4>
                                <table class='table table-bordered'>
                                    <tr><td><strong>Nama</strong></td><td>{$data['nama_warga']}</td></tr>
                                    <tr><td><strong>No. KK</strong></td><td>{$data['no_kk']}</td></tr>
                                    <tr><td><strong>No. KTP</strong></td><td>{$data['no_ktp']}</td></tr>
                                    <tr><td><strong>Alamat</strong></td><td>{$data['alamat']}</td></tr>
                                    <tr><td><strong>Ranking</strong></td><td><span class='label label-primary'>#{$data['ranking']}</span></td></tr>
                                    <tr><td><strong>Skor Akhir</strong></td><td><span class='label label-info'>" . number_format($data['skor_akhir'], 4) . "</span></td></tr>
                                    <tr><td><strong>Rekomendasi</strong></td><td><span class='label label-" . ($data['rekomendasi'] == 'Ya' ? 'success' : 'danger') . "'>{$data['rekomendasi']}</span></td></tr>
                                </table>
                            </div>
                            <div class='col-md-6'>
                                <h4>Detail Perhitungan</h4>
                                <table class='table table-bordered table-striped'>
                                    <thead>
                                        <tr>
                                            <th>Kriteria</th>
                                            <th>Nilai Asli</th>
                                            <th>Normalisasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
            
            for($i = 1; $i <= 8; $i++) {
                $col = 'C' . $i;
                $col_norm = 'C' . $i . '_norm';
                echo "<tr>
                        <td>{$kriteria_names[$col]}</td>
                        <td class='text-center'>{$klasif[$col]}</td>
                        <td class='text-center'>" . number_format($data[$col_norm], 4) . "</td>
                      </tr>";
            }
            
            echo "</tbody>
                  </table>
                  </div>
                  </div>
                  </div>
                  </div>";
        }
        break;
        
    case "pdf":
        require_once('../vendor/autoload.php');
        
        ob_start();
        
        // Get data
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
        
        $html = "
        <html>
        <head>
            <title>Laporan Perankingan Penerima PKH</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 12px; }
                .header { text-align: center; margin-bottom: 30px; }
                .header h1 { margin: 5px 0; font-size: 18px; }
                .header h2 { margin: 5px 0; font-size: 16px; }
                .stats { margin-bottom: 20px; }
                .stats table { width: 100%; border-collapse: collapse; }
                .stats td { padding: 8px; border: 1px solid #ddd; text-align: center; background: #f9f9f9; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
                th { background-color: #3c8dbc; color: white; text-align: center; }
                .text-center { text-align: center; }
                .rank-1 { background-color: #fff3cd; }
                .rank-2 { background-color: #d4edda; }
                .rank-3 { background-color: #cce7ff; }
                .layak { color: green; font-weight: bold; }
                .tidak-layak { color: red; font-weight: bold; }
                .footer { margin-top: 30px; text-align: right; font-size: 10px; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>LAPORAN PERANKINGAN PENERIMA PKH</h1>
                <h2>Program Keluarga Harapan</h2>
                <p>Metode Simple Additive Weighting (SAW)</p>
                <p>Tanggal: " . date('d F Y') . "</p>
            </div>
            
            <div class='stats'>
                <table>
                    <tr>
                        <td><strong>Total Warga Dianalisis</strong></td>
                        <td><strong>{$stat['total']} orang</strong></td>
                        <td><strong>Layak Menerima PKH</strong></td>
                        <td><strong>{$stat['layak']} orang</strong></td>
                        <td><strong>Tidak Layak</strong></td>
                        <td><strong>{$stat['tidak_layak']} orang</strong></td>
                    </tr>
                </table>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th width='8%'>Ranking</th>
                        <th width='35%'>Nama Warga</th>
                        <th width='25%'>Alamat</th>
                        <th width='15%'>Total Nilai</th>
                        <th width='17%'>Rekomendasi</th>
                    </tr>
                </thead>
                <tbody>";
        
        while($row = mysqli_fetch_array($hasil)) {
            $row_class = '';
            if($row['ranking'] <= 3) {
                $row_class = 'rank-' . $row['ranking'];
            }
            
            $rekomendasi_class = $row['rekomendasi'] == 'Ya' ? 'layak' : 'tidak-layak';
            
            $html .= "<tr class='$row_class'>
                        <td class='text-center'>#{$row['ranking']}</td>
                        <td>{$row['nama_warga']}</td>
                        <td>{$row['alamat']}</td>
                        <td class='text-center'>" . number_format($row['skor_akhir'], 4) . "</td>
                        <td class='text-center $rekomendasi_class'>{$row['rekomendasi']}</td>
                      </tr>";
        }
        
        $html .= "</tbody>
                </table>
                
                <div class='footer'>
                    <p>Dokumen ini dibuat secara otomatis oleh Sistem Pendukung Keputusan PKH</p>
                    <p>Dicetak pada: " . date('d F Y H:i:s') . "</p>
                </div>
            </body>
            </html>";
        
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $filename = "Laporan_Perankingan_PKH_" . date('Y-m-d_H-i-s') . ".pdf";
        $dompdf->stream($filename, array("Attachment" => false));
        
        ob_end_clean();
        exit();
        break;
        
        ob_end_clean();
        exit();
        break;
}
?>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Warga</span>
                    <span class="info-box-number"><?php echo $total_hasil; ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="box box-primary box-solid">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-trophy"></i> Perankingan Penerima Bantuan PKH</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success">
                        <h4><i class="fa fa-trophy"></i> Sistem Perankingan PKH</h4>
                        <p>Hasil ranking berdasarkan metode Simple Additive Weighting (SAW) dengan 8 kriteria PKH. 
                           Semakin tinggi nilai SAW, semakin layak menerima bantuan PKH.</p>
                    </div>
                </div>
            </div>
            
            <?php if ($total_hasil > 0): ?>
            
            <!-- Top 3 Winners -->
            <div class="row">
                <div class="col-md-12">
                    <h4><i class="fa fa-medal"></i> Peringkat Teratas</h4>
                </div>
            </div>
            
            <div class="row">
                <?php 
                mysqli_data_seek($hasil_saw, 0);
                for($i = 1; $i <= 3 && $top = mysqli_fetch_array($hasil_saw); $i++): 
                    $colors = ['gold', 'silver', '#cd7f32']; // Gold, Silver, Bronze
                    $icons = ['trophy', 'trophy', 'trophy'];
                    $bg_colors = ['bg-yellow', 'bg-gray', 'bg-orange'];
                ?>
                <div class="col-md-4">
                    <div class="box box-widget widget-user-2">
                        <div class="widget-user-header <?php echo $bg_colors[$i-1]; ?>">
                            <div class="widget-user-image">
                                <span class="fa fa-<?php echo $icons[$i-1]; ?> fa-3x" style="color: <?php echo $colors[$i-1]; ?>;"></span>
                            </div>
                            <h3 class="widget-user-username">Peringkat <?php echo $i; ?></h3>
                            <h5 class="widget-user-desc">Nilai SAW: <?php echo number_format($top['nilai_saw'], 4); ?></h5>
                        </div>
                        <div class="box-footer no-padding">
                            <ul class="nav nav-stacked">
                                <li><a href="#"><?php echo $top['nama_lengkap']; ?></a></li>
                                <li><a href="#"><?php echo $top['alamat']; ?></a></li>
                                <li><a href="#">
                                    <span class="pull-right badge bg-green">Sangat Layak</span>
                                    Status PKH
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
            
            <!-- Complete Ranking Table -->
            <div class="row">
                <div class="col-md-12">
                    <h4><i class="fa fa-list"></i> Daftar Lengkap Perankingan</h4>
                    
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab">Semua Warga</a></li>
                            <li><a href="#tab_2" data-toggle="tab">Sangat Layak</a></li>
                            <li><a href="#tab_3" data-toggle="tab">Layak</a></li>
                            <li><a href="#tab_4" data-toggle="tab">Detail Analisa</a></li>
                        </ul>
                        <div class="tab-content">
                            <!-- Tab 1: Semua Warga -->
                            <div class="tab-pane active" id="tab_1">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Rank</th>
                                            <th>Nama Warga</th>
                                            <th>Alamat</th>
                                            <th>Nilai SAW</th>
                                            <th>Status</th>
                                            <th>Progress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    mysqli_data_seek($hasil_saw, 0);
                                    $rank = 1;
                                    while ($r = mysqli_fetch_array($hasil_saw)){
                                        $nilai_saw = number_format($r['nilai_saw'], 4);
                                        $percentage = ($r['nilai_saw'] / 1.0) * 100; // Assuming max possible is 1.0
                                        
                                        if ($rank <= ceil($total_hasil * 0.3)) {
                                            $status = 'Sangat Layak';
                                            $badge = 'success';
                                            $progress = 'success';
                                        } elseif ($rank <= ceil($total_hasil * 0.6)) {
                                            $status = 'Layak';
                                            $badge = 'primary';
                                            $progress = 'primary';
                                        } elseif ($rank <= ceil($total_hasil * 0.8)) {
                                            $status = 'Cukup Layak';
                                            $badge = 'warning';
                                            $progress = 'warning';
                                        } else {
                                            $status = 'Kurang Layak';
                                            $badge = 'danger';
                                            $progress = 'danger';
                                        }
                                    ?>
                                    <tr>
                                        <td>
                                            <?php if($rank <= 3): ?>
                                            <span class="label label-warning"><?php echo $rank; ?></span>
                                            <?php else: ?>
                                            <span class="label label-default"><?php echo $rank; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><strong><?php echo $r['nama_lengkap']; ?></strong></td>
                                        <td><?php echo $r['alamat']; ?></td>
                                        <td><span class="label label-primary"><?php echo $nilai_saw; ?></span></td>
                                        <td><span class="label label-<?php echo $badge; ?>"><?php echo $status; ?></span></td>
                                        <td>
                                            <div class="progress progress-xs">
                                                <div class="progress-bar progress-bar-<?php echo $progress; ?>" 
                                                     style="width: <?php echo $percentage; ?>%"></div>
                                            </div>
                                            <span class="badge bg-<?php echo $progress; ?>"><?php echo number_format($percentage, 1); ?>%</span>
                                        </td>
                                    </tr>
                                    <?php 
                                    $rank++; 
                                    } 
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Tab 2: Sangat Layak -->
                            <div class="tab-pane" id="tab_2">
                                <div class="alert alert-success">
                                    <h4><i class="fa fa-check"></i> Kategori Sangat Layak (Top 30%)</h4>
                                    <p>Warga dengan prioritas tertinggi untuk menerima bantuan PKH.</p>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Rank</th>
                                            <th>Nama Warga</th>
                                            <th>Alamat</th>
                                            <th>Nilai SAW</th>
                                            <th>Detail Kriteria</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    mysqli_data_seek($hasil_saw, 0);
                                    $rank = 1;
                                    $limit_sangat_layak = ceil($total_hasil * 0.3);
                                    while (($r = mysqli_fetch_array($hasil_saw)) && $rank <= $limit_sangat_layak){
                                    ?>
                                    <tr class="success">
                                        <td><span class="label label-success"><?php echo $rank; ?></span></td>
                                        <td><strong><?php echo $r['nama_lengkap']; ?></strong></td>
                                        <td><?php echo $r['alamat']; ?></td>
                                        <td><span class="label label-primary"><?php echo number_format($r['nilai_saw'], 4); ?></span></td>
                                        <td>
                                            <small>
                                                Lansia: <?php echo $r['C1']; ?> | 
                                                Disabilitas: <?php echo $r['C2']; ?> | 
                                                Anak: <?php echo ($r['C3'] + $r['C4'] + $r['C5']); ?> | 
                                                Balita: <?php echo $r['C6']; ?> | 
                                                Ibu Hamil: <?php echo $r['C7']; ?>
                                            </small>
                                        </td>
                                    </tr>
                                    <?php 
                                    $rank++; 
                                    } 
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Tab 3: Layak -->
                            <div class="tab-pane" id="tab_3">
                                <div class="alert alert-info">
                                    <h4><i class="fa fa-info"></i> Kategori Layak (31%-60%)</h4>
                                    <p>Warga yang layak menerima bantuan PKH pada tahap kedua.</p>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Rank</th>
                                            <th>Nama Warga</th>
                                            <th>Alamat</th>
                                            <th>Nilai SAW</th>
                                            <th>Detail Kriteria</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    mysqli_data_seek($hasil_saw, 0);
                                    $rank = 1;
                                    $start_layak = ceil($total_hasil * 0.3) + 1;
                                    $end_layak = ceil($total_hasil * 0.6);
                                    
                                    while ($r = mysqli_fetch_array($hasil_saw)){
                                        if ($rank >= $start_layak && $rank <= $end_layak){
                                    ?>
                                    <tr class="info">
                                        <td><span class="label label-primary"><?php echo $rank; ?></span></td>
                                        <td><strong><?php echo $r['nama_lengkap']; ?></strong></td>
                                        <td><?php echo $r['alamat']; ?></td>
                                        <td><span class="label label-primary"><?php echo number_format($r['nilai_saw'], 4); ?></span></td>
                                        <td>
                                            <small>
                                                Lansia: <?php echo $r['C1']; ?> | 
                                                Disabilitas: <?php echo $r['C2']; ?> | 
                                                Anak: <?php echo ($r['C3'] + $r['C4'] + $r['C5']); ?> | 
                                                Balita: <?php echo $r['C6']; ?> | 
                                                Ibu Hamil: <?php echo $r['C7']; ?>
                                            </small>
                                        </td>
                                    </tr>
                                    <?php 
                                        }
                                        $rank++;
                                    } 
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Tab 4: Detail Analisa -->
                            <div class="tab-pane" id="tab_4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>Statistik Nilai SAW</h4>
                                        <?php 
                                        mysqli_data_seek($hasil_saw, 0);
                                        $nilai_array = array();
                                        while($stat = mysqli_fetch_array($hasil_saw)) {
                                            $nilai_array[] = $stat['nilai_saw'];
                                        }
                                        $max_nilai = max($nilai_array);
                                        $min_nilai = min($nilai_array);
                                        $avg_nilai = array_sum($nilai_array) / count($nilai_array);
                                        ?>
                                        <table class="table table-bordered">
                                            <tr><td><strong>Nilai Tertinggi</strong></td><td><span class="label label-success"><?php echo number_format($max_nilai, 4); ?></span></td></tr>
                                            <tr><td><strong>Nilai Terendah</strong></td><td><span class="label label-danger"><?php echo number_format($min_nilai, 4); ?></span></td></tr>
                                            <tr><td><strong>Nilai Rata-rata</strong></td><td><span class="label label-primary"><?php echo number_format($avg_nilai, 4); ?></span></td></tr>
                                            <tr><td><strong>Total Warga</strong></td><td><span class="label label-info"><?php echo $total_hasil; ?></span></td></tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h4>Distribusi Status Kelayakan</h4>
                                        <table class="table table-bordered">
                                            <tr>
                                                <td><strong>Sangat Layak</strong></td>
                                                <td><span class="label label-success"><?php echo ceil($total_hasil * 0.3); ?> warga (30%)</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Layak</strong></td>
                                                <td><span class="label label-primary"><?php echo ceil($total_hasil * 0.3); ?> warga (30%)</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Cukup Layak</strong></td>
                                                <td><span class="label label-warning"><?php echo ceil($total_hasil * 0.2); ?> warga (20%)</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Kurang Layak</strong></td>
                                                <td><span class="label label-danger"><?php echo floor($total_hasil * 0.2); ?> warga (20%)</span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php else: ?>
            <div class="alert alert-warning">
                <h4><i class="fa fa-warning"></i> Belum Ada Data Ranking</h4>
                <p>Silakan lakukan perhitungan SAW terlebih dahulu di menu 
                   <a href="?module=laporan&act=analisa">Laporan Hasil Analisa</a>.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php
}
}
?>
