<?php
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
    echo "<link href=../css/style.css rel=stylesheet type=text/css>";
    echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
}
else{

$aksi="modul/mod_laporan/aksi_laporan_mfep.php";
switch($_GET['act']){
    // Tampil Laporan Hasil Perhitungan MFEP
    case "analisa":
    default:
        if ($_SESSION['leveluser']=='admin'){
            // Get kriteria dengan nama lengkap
            $kriteria_query = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria WHERE nilai > 0 ORDER BY kode_kriteria");
            $kriteria_data = [];
            while($k = mysqli_fetch_array($kriteria_query)) {
                $kriteria_data[$k['kode_kriteria']] = $k;
            }
            
            // Get hasil MFEP jika ada
            $hasil_mfep = mysqli_query($koneksi, "
                SELECT * FROM tbl_hasil_mfep 
                ORDER BY ranking ASC
            ");
            
            $total_hasil = mysqli_num_rows($hasil_mfep);
            ?>
            
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-bar-chart"></i> Laporan Hasil Data Perhitungan (MFEP)
                    </h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body" id="analisaContent">
                    <div class="print-header" style="display:none; text-align:center; margin-bottom:10px;">
                        <h3 style="margin:0;">Laporan Hasil Data Perhitungan (MFEP)</h3>
                        <small><?php echo date('d M Y, H:i'); ?> WIB</small>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> 
                                <strong>Metode MFEP (Multi Factor Evaluation Process):</strong> 
                                Hasil perhitungan kelayakan penerima bantuan PKH menggunakan metode MFEP 
                                dengan formula <code>MFEP = ∑WE - WP</code> berdasarkan 8 kriteria yang telah ditetapkan.
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="action-btns" style="display:flex; flex-wrap:wrap; gap:10px; align-items:center;">
                                <a class='btn btn-success btn-flat action-btn' href='modul/mod_laporan/aksi_laporan_mfep.php?act=hitung_mfep'>
                                    <i class="fa fa-calculator"></i> Hitung MFEP
                                </a>
                                <a class='btn btn-warning btn-flat action-btn' href='?module=perankingan'>
                                    <i class="fa fa-trophy"></i> Lihat Perankingan
                                </a>
                                <button class="btn btn-default btn-flat action-btn" onclick="window.print()">
                                    <i class="fa fa-print"></i> Print
                                </button>
                                <a class='btn btn-primary btn-flat action-btn' href='modul/mod_laporan/export_pdf_laporan_mfep.php' target="_blank">
                                    <i class='fa fa-file-pdf-o'></i> Save PDF
                                </a>
                            </div>
                         </div>
                     </div>
                    <br>
                    
                    <?php if ($total_hasil > 0): ?>
                    
                    <!-- 1. Matriks Keputusan (X) -->
                    <h4 class="screen-white-title print-black-title" style="color: #fff !important; font-weight: bold; border-bottom: 2px solid #3c8dbc; padding-bottom: 10px;">
                        <i class="fa fa-table"></i> <span class="title-white" style="color:#fff !important; -webkit-text-fill-color:#fff !important;">1. Matriks Keputusan (X)</span>
                    </h4>
                    <p class="text-muted">Data mentah kriteria untuk setiap warga</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" style="font-size: 13px;">
                            <thead class="bg-primary">
                                <tr>
                                    <th width="4%">No</th>
                                    <th width="20%">Nama Warga</th>
                                    <?php foreach($kriteria_data as $kode => $k): ?>
                                    <th class="text-center" width="9%" title="<?php echo $k['keterangan']; ?>">
                                        <strong><?php echo $kode; ?> - <?php echo (!empty($k['nama_kriteria']) ? $k['nama_kriteria'] : $k['keterangan']); ?></strong>
                                    </th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            mysqli_data_seek($hasil_mfep, 0);
                            $no = 1;
                            while($r = mysqli_fetch_array($hasil_mfep)): 
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $no; ?></td>
                                    <td><strong><?php echo $r['nama_warga']; ?></strong></td>
                                    <?php for($i=1; $i<=8; $i++): ?>
                                    <td class="text-center">
                                        <span class="label label-default"><?php echo $r['C'.$i]; ?></span>
                                    </td>
                                    <?php endfor; ?>
                                </tr>
                            <?php 
                            $no++;
                            endwhile; 
                            ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <br>
                    
                    <!-- 2. Nilai Evaluasi Factor (E) -->
                    <h4 style="color: #3c8dbc; font-weight: bold; border-bottom: 2px solid #3c8dbc; padding-bottom: 10px;">
                        <i class="fa fa-calculator"></i> 2. Nilai Evaluasi Factor (E)
                    </h4>
                    <p class="text-muted">Normalisasi nilai: <code>E = X / X<sub>max</sub></code></p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" style="font-size: 13px;">
                            <thead class="bg-info">
                                <tr>
                                    <th width="4%">No</th>
                                    <th width="20%">Nama Warga</th>
                                    <?php foreach($kriteria_data as $kode => $k): ?>
                                    <th class="text-center" width="9%">
                                        <strong>E<?php echo substr($kode, 1); ?> - <?php echo (!empty($k['nama_kriteria']) ? $k['nama_kriteria'] : $k['keterangan']); ?></strong>
                                    </th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            mysqli_data_seek($hasil_mfep, 0);
                            $no = 1;
                            while($r = mysqli_fetch_array($hasil_mfep)): 
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $no; ?></td>
                                    <td><strong><?php echo $r['nama_warga']; ?></strong></td>
                                    <?php for($i=1; $i<=8; $i++): ?>
                                    <td class="text-center">
                                        <span class="label label-info"><?php echo number_format($r['E'.$i], 4); ?></span>
                                    </td>
                                    <?php endfor; ?>
                                </tr>
                            <?php 
                            $no++;
                            endwhile; 
                            ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <br>
                    
                    <!-- 3. Nilai Bobot Evaluasi (WE) -->
                    <h4 style="color: #3c8dbc; font-weight: bold; border-bottom: 2px solid #3c8dbc; padding-bottom: 10px;">
                        <i class="fa fa-balance-scale"></i> 3. Nilai Bobot Evaluasi (WE)
                    </h4>
                    <p class="text-muted">Hasil perkalian: <code>WE = Bobot × E</code></p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" style="font-size: 13px;">
                            <thead class="bg-warning">
                                <tr>
                                    <th width="4%">No</th>
                                    <th width="15%">Nama Warga</th>
                                    <?php foreach($kriteria_data as $kode => $k): ?>
                                    <th class="text-center" width="8%">
                                        <strong>WE<?php echo substr($kode, 1); ?> - <?php echo (!empty($k['nama_kriteria']) ? $k['nama_kriteria'] : $k['keterangan']); ?></strong><br>
                                        <span class="label label-default">W=<?php echo number_format($k['nilai'], 2); ?></span>
                                    </th>
                                    <?php endforeach; ?>
                                    <th class="text-center" width="8%"><strong>∑WE</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            mysqli_data_seek($hasil_mfep, 0);
                            $no = 1;
                            while($r = mysqli_fetch_array($hasil_mfep)): 
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $no; ?></td>
                                    <td><strong><?php echo $r['nama_warga']; ?></strong></td>
                                    <?php for($i=1; $i<=8; $i++): ?>
                                    <td class="text-center">
                                        <span class="label label-warning"><?php echo number_format($r['WE'.$i], 4); ?></span>
                                    </td>
                                    <?php endfor; ?>
                                    <td class="text-center">
                                        <strong><span class="label label-primary"><?php echo number_format($r['total_we'], 4); ?></span></strong>
                                    </td>
                                </tr>
                            <?php 
                            $no++;
                            endwhile; 
                            ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <br>
                    
                    <!-- 4. Nilai Total Evaluasi (∑WE) -->
                    <h4 style="color: #3c8dbc; font-weight: bold; border-bottom: 2px solid #3c8dbc; padding-bottom: 10px;">
                        <i class="fa fa-check-circle"></i> 4. Nilai Total Evaluasi (∑WE)
                    </h4>
                    <p class="text-muted">Penjumlahan seluruh nilai bobot evaluasi</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" style="font-size: 14px;">
                            <thead class="bg-success">
                                <tr>
                                    <th width="10%" class="text-center">No</th>
                                    <th width="40%">Nama Warga</th>
                                    <th width="25%" class="text-center">Total WE (∑WE)</th>
                                    <th width="25%" class="text-center">Formula</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            mysqli_data_seek($hasil_mfep, 0);
                            $no = 1;
                            while($r = mysqli_fetch_array($hasil_mfep)): 
                            ?>
                                <tr>
                                    <td class="text-center"><strong><?php echo $no; ?></strong></td>
                                    <td><strong><?php echo $r['nama_warga']; ?></strong></td>
                                    <td class="text-center">
                                        <h4 style="margin: 5px 0;"><span class="label label-success"><?php echo number_format($r['total_we'], 4); ?></span></h4>
                                    </td>
                                    <td class="text-center">
                                        <small><?php 
                                        $formula = [];
                                        for($i=1; $i<=8; $i++) {
                                            $formula[] = number_format($r['WE'.$i], 4);
                                        }
                                        echo implode(' + ', $formula);
                                        ?></small>
                                    </td>
                                </tr>
                            <?php 
                            $no++;
                            endwhile; 
                            ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <br>
                    
                    <!-- 5. Ranking Akhir dan Daftar Ranking -->
                    <h4 class="screen-white-title print-black-title" style="color: #fff !important; font-weight: bold; border-bottom: 2px solid #3c8dbc; padding-bottom: 10px;">
                        <i class="fa fa-trophy"></i> <span class="title-white" style="color:#fff !important; -webkit-text-fill-color:#fff !important;">5. Ranking Akhir dan Daftar Ranking</span>
                    </h4>
                    <p class="text-muted">Urutan kelayakan penerima bantuan PKH berdasarkan nilai MFEP</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" style="font-size: 14px;">
                            <thead class="bg-primary">
                                <tr>
                                    <th width="8%" class="text-center">Ranking</th>
                                    <th width="30%">Nama Warga</th>
                                    <th width="12%" class="text-center">Total WE</th>
                                    <th width="12%" class="text-center">Nilai MFEP</th>
                                    <th width="20%" class="text-center">Rekomendasi</th>
                                    <th width="18%" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            mysqli_data_seek($hasil_mfep, 0);
                            while($r = mysqli_fetch_array($hasil_mfep)): 
                                // Tentukan class berdasarkan ranking
                                $row_class = '';
                                if($r['ranking'] <= ceil($total_hasil * 0.3)) {
                                    $row_class = 'success';
                                    $status = 'Sangat Layak';
                                } elseif($r['ranking'] <= ceil($total_hasil * 0.6)) {
                                    $row_class = 'info';
                                    $status = 'Layak';
                                } elseif($r['ranking'] <= ceil($total_hasil * 0.8)) {
                                    $row_class = 'warning';
                                    $status = 'Cukup Layak';
                                } else {
                                    $row_class = 'danger';
                                    $status = 'Kurang Layak';
                                }
                            ?>
                                <tr class="<?php echo $row_class; ?>">
                                    <td class="text-center">
                                        <h4 style="margin: 0;"><strong>#<?php echo $r['ranking']; ?></strong></h4>
                                    </td>
                                    <td><strong><?php echo $r['nama_warga']; ?></strong></td>
                                    <td class="text-center">
                                        <span class="label label-primary"><?php echo number_format($r['total_we'], 4); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="label label-success"><?php echo number_format($r['nilai_mfep'], 4); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="label label-<?php echo $r['rekomendasi'] == 'Ya' ? 'success' : 'default'; ?>">
                                            <?php echo $r['rekomendasi']; ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="label label-<?php echo $row_class; ?>"><?php echo $status; ?></span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <br>
                    <div class="alert alert-success">
                        <h4><i class="fa fa-check-circle"></i> Keterangan Status Kelayakan:</h4>
                        <ul>
                            <li><strong>Sangat Layak (Top 30%):</strong> Prioritas utama penerima bantuan PKH</li>
                            <li><strong>Layak (31%-60%):</strong> Penerima bantuan PKH tahap kedua</li>
                            <li><strong>Cukup Layak (61%-80%):</strong> Cadangan penerima bantuan PKH</li>
                            <li><strong>Kurang Layak (81%-100%):</strong> Perlu evaluasi ulang kriteria</li>
                        </ul>
                        <p><strong>Total Data:</strong> <?php echo $total_hasil; ?> warga | 
                        <strong>Rekomendasi Ya:</strong> <?php echo mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tbl_hasil_mfep WHERE rekomendasi='Ya'")); ?> warga</p>
                    </div>
                    
                    <?php else: ?>
                    
                    <div class="alert alert-warning">
                        <h4><i class="fa fa-exclamation-triangle"></i> Belum Ada Data Perhitungan</h4>
                        <p>Silakan lakukan perhitungan MFEP terlebih dahulu dengan klik tombol <strong>"Hitung MFEP"</strong> di atas.</p>
                        <p><strong>Pastikan:</strong></p>
                        <ul>
                            <li>Data warga sudah diinput</li>
                            <li>Bobot kriteria sudah diatur (total = 100%)</li>
                            <li>Semua kriteria sudah terisi nilai</li>
                        </ul>
                    </div>
                    
                    <?php endif; ?>
                    
                </div>
            </div>
            
            <style>
            /* Force white text on screen for specific section headings */
            .screen-white-title, .screen-white-title i {
                color: #ffffff !important;
                opacity: 1 !important;
                -webkit-text-fill-color: #ffffff !important;
            }
            @media screen {
                .box .box-body h4.screen-white-title,
                .box .box-body h4.screen-white-title * {
                    color: #ffffff !important;
                    opacity: 1 !important;
                    -webkit-text-fill-color: #ffffff !important;
                }
            }
            @media print {
                @page { size: A4 landscape; margin: 10mm; }
                html, body { background: #fff !important; zoom: 0.85; }

                /* Sembunyikan elemen layout AdminLTE agar area cetak penuh */
                .main-header, .content-header, .main-sidebar, .left-side, .control-sidebar, .navbar, .sidebar-toggle,
                .box-header, .box-tools, .action-btns, .btn, .alert { display: none !important; }
                .content-wrapper, .right-side, #analisaContent, .box, .box-body { margin: 0 !important; padding: 0 !important; border: 0 !important; }

                /* Tampilkan header khusus print */
                .print-header { display: block !important; }
                /* Opsi A: judul putih di layar, hitam saat print */
                .print-black-title { color: #000 !important; }

                /* Hilangkan overflow responsive agar tidak terpotong */
                .table-responsive { overflow: visible !important; }
                table { width: 100% !important; border-collapse: collapse !important; table-layout: fixed; }
                thead { display: table-header-group !important; }
                tr { page-break-inside: avoid !important; }
                th, td { padding: 6px 5px !important; font-size: 10px !important; word-wrap: break-word; white-space: normal !important; }
                table, th, td { border-color: #999 !important; }
                h4 { page-break-after: avoid !important; margin-top: 6px !important; margin-bottom: 6px !important; }
                p.text-muted { display: none !important; }

                /* Pastikan warna headertable tercetak */
                thead.bg-primary th, thead.bg-info th, thead.bg-warning th, thead.bg-success th {
                    -webkit-print-color-adjust: exact; print-color-adjust: exact; color: #fff !important;
                }
                thead.bg-primary th { background: #3c8dbc !important; }
                thead.bg-info th    { background: #00c0ef !important; }
                thead.bg-warning th { background: #f39c12 !important; }
                thead.bg-success th { background: #00a65a !important; }
            }
            </style>
            
            <?php
        }
        break;
}
}
?>
