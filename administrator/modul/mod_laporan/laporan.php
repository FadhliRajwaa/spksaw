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
                <div class="box-body">
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
                            <a class='btn btn-success btn-flat' href='?module=laporan&act=hitung_saw'>
                                <i class="fa fa-calculator"></i> Hitung Ulang SAW
                            </a>
                            <a class='btn btn-info btn-flat' href='?module=laporan&act=detail_perhitungan'>
                                <i class="fa fa-list"></i> Detail Perhitungan
                            </a>
                            <a class='btn btn-warning btn-flat' href='?module=perankingan'>
                                <i class="fa fa-trophy"></i> Lihat Perankingan
                            </a>
                        </div>
                    </div>
                    <br>
                    
                    <?php if ($total_hasil > 0): ?>
                    
                    <!-- 1. Matriks Nilai Dasar -->
                    <h4><i class="fa fa-table"></i> 1. Matriks Nilai Dasar</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Nama Warga</th>
                                    <th colspan="8" class="text-center">Kriteria PKH</th>
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
                                        <td><strong>{$data['nama_lengkap']}</strong></td>
                                        <td class='text-center'>{$data['C1']}</td>
                                        <td class='text-center'>{$data['C2']}</td>
                                        <td class='text-center'>{$data['C3']}</td>
                                        <td class='text-center'>{$data['C4']}</td>
                                        <td class='text-center'>{$data['C5']}</td>
                                        <td class='text-center'>{$data['C6']}</td>
                                        <td class='text-center'>{$data['C7']}</td>
                                        <td class='text-center'>{$data['C8']}</td>
                                      </tr>";
                                $no++;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- 2. Matriks Normalisasi -->
                    <h4><i class="fa fa-calculator"></i> 2. Matriks Normalisasi</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Nama Warga</th>
                                    <th colspan="8" class="text-center">Nilai Normalisasi</th>
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
                                        <td><strong>{$data['nama_lengkap']}</strong></td>
                                        <td class='text-center'>" . number_format($data['C1_norm'], 3) . "</td>
                                        <td class='text-center'>" . number_format($data['C2_norm'], 3) . "</td>
                                        <td class='text-center'>" . number_format($data['C3_norm'], 3) . "</td>
                                        <td class='text-center'>" . number_format($data['C4_norm'], 3) . "</td>
                                        <td class='text-center'>" . number_format($data['C5_norm'], 3) . "</td>
                                        <td class='text-center'>" . number_format($data['C6_norm'], 3) . "</td>
                                        <td class='text-center'>" . number_format($data['C7_norm'], 3) . "</td>
                                        <td class='text-center'>" . number_format($data['C8_norm'], 3) . "</td>
                                      </tr>";
                                $no++;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- 3. Matriks Terbobot -->
                    <h4><i class="fa fa-balance-scale"></i> 3. Matriks Terbobot</h4>
                    <div class="alert alert-warning">
                        <strong>Bobot Kriteria:</strong>
                        <?php foreach($kriteria_data as $k): ?>
                            <span class="label label-primary"><?php echo $k['kode_kriteria']; ?>: <?php echo $k['nilai']; ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Nama Warga</th>
                                    <th colspan="8" class="text-center">Nilai Terbobot</th>
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
                                // Hitung nilai terbobot
                                $w1 = $data['C1_norm'] * $kriteria_data['C1']['nilai'];
                                $w2 = $data['C2_norm'] * $kriteria_data['C2']['nilai'];
                                $w3 = $data['C3_norm'] * $kriteria_data['C3']['nilai'];
                                $w4 = $data['C4_norm'] * $kriteria_data['C4']['nilai'];
                                $w5 = $data['C5_norm'] * $kriteria_data['C5']['nilai'];
                                $w6 = $data['C6_norm'] * $kriteria_data['C6']['nilai'];
                                $w7 = $data['C7_norm'] * $kriteria_data['C7']['nilai'];
                                $w8 = $data['C8_norm'] * $kriteria_data['C8']['nilai'];
                                
                                echo "<tr>
                                        <td>$no</td>
                                        <td><strong>{$data['nama_lengkap']}</strong></td>
                                        <td class='text-center'>" . number_format($w1, 3) . "</td>
                                        <td class='text-center'>" . number_format($w2, 3) . "</td>
                                        <td class='text-center'>" . number_format($w3, 3) . "</td>
                                        <td class='text-center'>" . number_format($w4, 3) . "</td>
                                        <td class='text-center'>" . number_format($w5, 3) . "</td>
                                        <td class='text-center'>" . number_format($w6, 3) . "</td>
                                        <td class='text-center'>" . number_format($w7, 3) . "</td>
                                        <td class='text-center'>" . number_format($w8, 3) . "</td>
                                        <td class='text-center'><strong>" . number_format($data['skor_akhir'], 4) . "</strong></td>
                                      </tr>";
                                $no++;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- 4. Hasil Ranking Final -->
                    <h4><i class="fa fa-trophy"></i> 4. Ranking Akhir</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <a class='btn btn-danger btn-flat' href='?module=laporan&act=export_pdf' target="_blank">
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
            
            // 5. Update rankings
            $hasil_ordered = mysqli_query($koneksi, "SELECT id_hasil FROM tbl_hasil_saw ORDER BY skor_akhir DESC");
            if (!$hasil_ordered) {
                echo "<p>❌ Error retrieving results: " . mysqli_error($koneksi) . "</p>";
                flush();
                break;
            }
            
            $rank = 1;
            while($row = mysqli_fetch_array($hasil_ordered)) {
                mysqli_query($koneksi, "UPDATE tbl_hasil_saw SET ranking = $rank WHERE id_hasil = {$row['id_hasil']}");
                $rank++;
            }
            
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
            <div class="box box-info box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-calculator"></i> Detail Perhitungan Metode SAW</h3>
                </div>
                <div class="box-body">
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
                            <tr>
                                <th colspan="2">Total Bobot</th>
                                <th><span class="label label-<?php echo ($total_bobot == 1.0) ? 'success' : 'danger'; ?>"><?php echo $total_bobot; ?></span></th>
                                <th><?php echo ($total_bobot == 1.0) ? '<i class="fa fa-check text-success"></i> Valid' : '<i class="fa fa-times text-danger"></i> Invalid'; ?></th>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <h4><i class="fa fa-table"></i> 2. Matriks Data Awal</h4>
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
}
}
?>
