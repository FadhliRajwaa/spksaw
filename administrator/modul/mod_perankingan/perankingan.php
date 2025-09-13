<?php
$act=isset($_GET['act'])?$_GET['act']:'';
switch($act){
    default:
        if($_SESSION['leveluser']=='admin'){
            echo "<div class='box box-primary box-solid'>
                    <div class='box-header with-border'>
                        <h3 class='box-title'><i class='fa fa-trophy'></i> Daftar Perankingan Penerima PKH</h3>
                        <div class='box-tools pull-right'>
                            <a href='modul/mod_perankingan/export_pdf.php' class='btn btn-success btn-flat' target='_blank' title='Download Laporan PDF'>
                                <i class='fa fa-file-pdf-o'></i> Export PDF
                            </a>
                            <a href='?module=laporan&act=hitung_saw' class='btn btn-primary btn-flat' title='Hitung Ulang Ranking'>
                                <i class='fa fa-calculator'></i> Hitung Ulang
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
                
                // Export and action buttons section
                echo "<div class='row' style='margin-bottom: 20px;'>
                        <div class='col-md-12'>
                            <div class='btn-group pull-right'>
                                <a href='modul/mod_perankingan/export_pdf.php' class='btn btn-success btn-flat' target='_blank' title='Download Laporan PDF'>
                                    <i class='fa fa-file-pdf-o'></i> Export PDF
                                </a>
                                <button class='btn btn-info btn-flat' onclick='printPerankingan()' title='Cetak Hasil Perankingan'>
                                    <i class='fa fa-print'></i> Cetak Data
                                </button>
                                <a href='?module=laporan&act=hitung_saw' class='btn btn-primary btn-flat' title='Hitung Ulang Ranking'>
                                    <i class='fa fa-calculator'></i> Hitung Ulang
                                </a>
                                <a href='?module=perankingan&act=analisa' class='btn btn-info btn-flat' title='Lihat Analisa Detail'>
                                    <i class='fa fa-bar-chart'></i> Analisa
                                </a>
                            </div>
                            <div class='clearfix'></div>
                        </div>
                      </div>";
                
                echo "<div class='table-responsive'>
                        <table class='table table-bordered table-striped table-hover' id='rankingTable'>
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
                
                echo "<style>
                /* Flat Perankingan Stats */
                .perankingan-stats {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                    gap: 2rem;
                    margin: 2rem 0;
                    padding: 1rem 0;
                }
                
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
                }
                
                .stat-card-modern::before {
                    display: none;
                }
                
                .stat-card-modern.total::before {
                    display: none;
                }
                
                .stat-card-modern.layak::before {
                    display: none;
                }
                
                .stat-card-modern.tidak-layak::before {
                    display: none;
                }
                
                .stat-card-modern.skor::before {
                    display: none;
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
                
                .stat-icon-modern.tidak-layak {
                    background: #EF4444;
                }
                
                .stat-icon-modern.skor {
                    background: #F59E0B;
                }
                
                .stat-value-modern {
                    font-size: 2rem;
                    font-weight: 600;
                    color: #1E293B;
                    margin-bottom: 0.25rem;
                    line-height: 1;
                }
                
                .stat-label-modern {
                    color: #64748B;
                    font-size: 0.875rem;
                    font-weight: 500;
                    margin-bottom: 0.25rem;
                }
                
                .stat-trend {
                    font-size: 0.75rem;
                    color: #64748B;
                    font-weight: 400;
                    display: flex;
                    align-items: center;
                    gap: 0.25rem;
                }
                
                @media (max-width: 768px) {
                    .perankingan-stats {
                        grid-template-columns: 1fr;
                        gap: 1rem;
                    }
                    
                    .stat-value-modern {
                        font-size: 1.75rem;
                    }
                }
                </style>
                
                <div class='perankingan-stats'>
                    <div class='stat-card-modern total'>
                        <div class='stat-header-modern'>
                            <div>
                                <div class='stat-value-modern'>{$stat['total']}</div>
                                <div class='stat-label-modern'>Total Warga</div>
                                <div class='stat-trend'>
                                    <i class='fas fa-users'></i>
                                    Terdaftar dalam sistem
                                </div>
                            </div>
                            <div class='stat-icon-modern total'>
                                <i class='fas fa-users'></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class='stat-card-modern layak'>
                        <div class='stat-header-modern'>
                            <div>
                                <div class='stat-value-modern'>{$stat['layak']}</div>
                                <div class='stat-label-modern'>Layak PKH</div>
                                <div class='stat-trend'>
                                    <i class='fas fa-check-circle'></i>
                                    Memenuhi kriteria
                                </div>
                            </div>
                            <div class='stat-icon-modern layak'>
                                <i class='fas fa-check'></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class='stat-card-modern tidak-layak'>
                        <div class='stat-header-modern'>
                            <div>
                                <div class='stat-value-modern'>{$stat['tidak_layak']}</div>
                                <div class='stat-label-modern'>Tidak Layak</div>
                                <div class='stat-trend'>
                                    <i class='fas fa-times-circle'></i>
                                    Tidak memenuhi kriteria
                                </div>
                            </div>
                            <div class='stat-icon-modern tidak-layak'>
                                <i class='fas fa-times'></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class='stat-card-modern skor'>
                        <div class='stat-header-modern'>
                            <div>
                                <div class='stat-value-modern'>" . number_format($stat['skor_max'], 3) . "</div>
                                <div class='stat-label-modern'>Skor Tertinggi</div>
                                <div class='stat-trend'>
                                    <i class='fas fa-trophy'></i>
                                    Penilaian terbaik
                                </div>
                            </div>
                            <div class='stat-icon-modern skor'>
                                <i class='fas fa-star'></i>
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
        // Clear any previous output and start fresh
        if (ob_get_level()) {
            ob_end_clean();
        }
        ob_start();
        
        require_once('../vendor/autoload.php');
        
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
        
        // Clear all output buffers
        ob_end_clean();
        
        // Set proper headers
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="Laporan_Perankingan_PKH_' . date('Y-m-d_H-i-s') . '.pdf"');
        
        // Output PDF
        echo $dompdf->output();
        exit();
}
?>

<script src="../assets/js/print-functions.js"></script>
