<?php
if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
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
                            <a href="?module=laporan&act=hitung_saw" class="btn btn-success">
                                <i class="fa fa-calculator"></i> Hitung Ulang SAW
                            </a>
                            <a href="?module=laporan&act=detail_perhitungan" class="btn btn-info">
                                <i class="fa fa-eye"></i> Detail Perhitungan
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
                                <tr style="background-color:#3c8dbc; color:white;">
                                    <th>Nama Warga</th>
                                    <?php foreach($kriteria_data as $k): ?>
                                    <th><?php echo $k['kode_kriteria']; ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="<?php echo count($kriteria_data) + 1; ?>" class="text-center">
                                        Data akan ditampilkan setelah perhitungan SAW selesai
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- 2. Hasil Ranking -->
                    <h4><i class="fa fa-trophy"></i> 2. Hasil Ranking SAW</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr style="background-color:#3c8dbc; color:white;">
                                    <th>Rank</th>
                                    <th>Nama Warga</th>
                                    <th>Alamat</th>
                                    <th>Skor SAW</th>
                                    <th>Rekomendasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                mysqli_data_seek($hasil_saw, 0);
                                while($row = mysqli_fetch_array($hasil_saw)): 
                                ?>
                                <tr>
                                    <td><strong><?php echo $no; ?></strong></td>
                                    <td><?php echo $row['nama_warga']; ?></td>
                                    <td><?php echo $row['alamat']; ?></td>
                                    <td><span class="badge bg-blue"><?php echo number_format($row['skor_akhir'], 4); ?></span></td>
                                    <td>
                                        <span class="label label-<?php echo ($row['rekomendasi'] == 'Ya') ? 'success' : 'default'; ?>">
                                            <?php echo $row['rekomendasi']; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php 
                                $no++;
                                endwhile; 
                                ?>
                            </tbody>
                        </table>
                    </div>
                    
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
            echo "<div class='box box-success box-solid'>
                    <div class='box-header with-border'>
                        <h3 class='box-title'><i class='fa fa-cog fa-spin'></i> Menghitung SAW...</h3>
                    </div>
                    <div class='box-body'>";
            
            // 1. Clear existing results
            mysqli_query($koneksi, "DELETE FROM tbl_hasil_saw");
            echo "<p>✅ Membersihkan hasil perhitungan sebelumnya...</p>";
            
            // 2. Get kriteria data
            $kriteria = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria ORDER BY id_kriteria");
            $kriteria_data = [];
            while($k = mysqli_fetch_array($kriteria)) {
                $kriteria_data[$k['kode_kriteria']] = $k;
            }
            echo "<p>✅ Memuat " . count($kriteria_data) . " kriteria...</p>";
            
            // 3. Get klasifikasi data
            $klasifikasi = mysqli_query($koneksi, "
                SELECT k.*, w.nama_lengkap 
                FROM tbl_klasifikasi k 
                JOIN data_warga w ON k.id_warga = w.id_warga 
                ORDER BY w.nama_lengkap
            ");
            
            $data_alternatif = [];
            $max_values = ['C1' => 0, 'C2' => 0, 'C3' => 0, 'C4' => 0, 'C5' => 0, 'C6' => 0, 'C7' => 0, 'C8' => 0];
            
            while($row = mysqli_fetch_array($klasifikasi)) {
                $data_alternatif[] = $row;
                
                // Find max values for normalization (all criteria are Benefit type)
                for($i = 1; $i <= 8; $i++) {
                    $col = 'C' . $i;
                    if($row[$col] > $max_values[$col]) $max_values[$col] = $row[$col];
                }
            }
            echo "<p>✅ Memuat " . count($data_alternatif) . " data warga...</p>";
            
            // 4. Calculate normalization and SAW scores
            echo "<p>🔄 Melakukan normalisasi dan perhitungan SAW...</p>";
            
            foreach($data_alternatif as $data) {
                $normalized = [];
                $saw_score = 0;
                
                // Normalize each criteria (all are Benefit type for PKH)
                for($i = 1; $i <= 8; $i++) {
                    $col = 'C' . $i;
                    $kode = 'C' . $i;
                    
                    if($max_values[$col] > 0) {
                        $normalized[$col] = $data[$col] / $max_values[$col];
                    } else {
                        $normalized[$col] = 0;
                    }
                    
                    // Add weighted value to SAW score
                    $weight = $kriteria_data[$kode]['nilai'];
                    $saw_score += $normalized[$col] * $weight;
                }
                
                // Insert results
                $insert_query = "
                    INSERT INTO tbl_hasil_saw 
                    (id_warga, nama_warga, C1_norm, C2_norm, C3_norm, C4_norm, C5_norm, C6_norm, C7_norm, C8_norm, skor_akhir, created_at) 
                    VALUES 
                    ({$data['id_warga']}, '{$data['nama_lengkap']}', 
                     {$normalized['C1']}, {$normalized['C2']}, {$normalized['C3']}, {$normalized['C4']}, 
                     {$normalized['C5']}, {$normalized['C6']}, {$normalized['C7']}, {$normalized['C8']}, 
                     $saw_score, NOW())
                ";
                
                mysqli_query($koneksi, $insert_query);
            }
            
            // 5. Update rankings
            $hasil_ordered = mysqli_query($koneksi, "SELECT id_hasil FROM tbl_hasil_saw ORDER BY skor_akhir DESC");
            $rank = 1;
            while($row = mysqli_fetch_array($hasil_ordered)) {
                mysqli_query($koneksi, "UPDATE tbl_hasil_saw SET ranking = $rank WHERE id_hasil = {$row['id_hasil']}");
                $rank++;
            }
            
            echo "<p>✅ Ranking berhasil dihitung!</p>";
            
            // 6. Set recommendations (top 30% get "Ya")
            $total_warga = count($data_alternatif);
            $top_30_percent = ceil($total_warga * 0.3);
            
            mysqli_query($koneksi, "UPDATE tbl_hasil_saw SET rekomendasi = 'Tidak'");
            mysqli_query($koneksi, "UPDATE tbl_hasil_saw SET rekomendasi = 'Ya' WHERE ranking <= $top_30_percent");
            
            echo "<p>✅ Rekomendasi berhasil ditetapkan (Top 30% = $top_30_percent warga)!</p>";
            
            echo "</div>
                  <div class='box-footer'>
                      <a href='?module=laporan&act=analisa' class='btn btn-primary'>
                          <i class='fa fa-eye'></i> Lihat Hasil Analisa
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
            
            echo "<div class='box box-info box-solid saw-detail'>
                    <div class='box-header with-border'>
                        <h3 class='box-title' style='color:#fff !important;'><i class='fa fa-calculator' style='color:#fff !important;'></i> Detail Perhitungan Metode SAW</h3>
                    </div>
                    <div class='box-body'>
                        <style>
                            /* Scope only for SAW detail page */
                            .saw-detail .box-header, .saw-detail .box-title, .saw-detail .box-title i { color:#fff !important; }
                            .saw-detail table tfoot tr, .saw-detail table tfoot th { color:#fff !important; opacity:1 !important; }
                            .saw-detail h4, .saw-detail h4 i { color:#fff !important; }
                        </style>
                        <div class='alert alert-info'>
                            <h4><i class='fa fa-info-circle'></i> Metode Simple Additive Weighting (SAW)</h4>
                            <p>Metode SAW menggunakan formula: <code>Ri = Σ(wj × rij)</code></p>
                            <ul>
                                <li><strong>Ri</strong> = Nilai akhir alternatif ke-i</li>
                                <li><strong>wj</strong> = Bobot kriteria ke-j</li>
                                <li><strong>rij</strong> = Nilai normalisasi matriks</li>
                            </ul>
                        </div>
                        
                        <h4><i class='fa fa-table'></i> Bobot Kriteria</h4>
                        <table class='table table-bordered'>
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Keterangan</th>
                                    <th>Bobot</th>
                                    <th>Jenis</th>
                                </tr>
                            </thead>
                            <tbody>";
            
            $total_bobot = 0;
            while($k = mysqli_fetch_array($kriteria)) {
                $total_bobot += $k['nilai'];
                echo "<tr>
                        <td><strong>{$k['kode_kriteria']}</strong></td>
                        <td>{$k['keterangan']}</td>
                        <td><span class='label label-primary'>{$k['nilai']}</span></td>
                        <td><span class='label label-success'>Benefit</span></td>
                      </tr>";
            }
            
            echo "          </tbody>
                            <tfoot>
                                <tr style='color:#fff !important; opacity:1 !important;'>
                                    <th colspan='2' style='color:#fff !important;'>Total Bobot</th>
                                    <th><span class='label label-" . (($total_bobot == 1.0) ? 'success' : 'danger') . "'>{$total_bobot}</span></th>
                                    <th>" . (($total_bobot == 1.0) ? '<i class="fa fa-check text-success"></i> Valid' : '<i class="fa fa-times text-danger"></i> <span style="color:#fff !important;">Invalid</span>') . "</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class='box-footer'>
                        <button type='button' class='btn btn-default' onclick='history.back()'>
                            <i class='fa fa-arrow-left'></i> Kembali
                        </button>
                        <a href='?module=laporan&act=hitung_saw' class='btn btn-success'>
                            <i class='fa fa-calculator'></i> Hitung SAW
                        </a>
                    </div>
                  </div>";
        }
        break;
}
}
?>
