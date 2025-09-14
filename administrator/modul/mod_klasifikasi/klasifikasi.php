<?php
session_start();
if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
    echo "<link href=../css/style.css rel=stylesheet type=text/css>";
    echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
}
else{

$aksi="modul/mod_klasifikasi/aksi_klasifikasi.php";
switch($_GET['act']){
    // Tampil Data Klasifikasi
    default:
        if ($_SESSION['leveluser']=='admin'){
            $tampil_klasifikasi = mysqli_query($koneksi, "
                SELECT k.*, w.nama_lengkap, w.alamat 
                FROM tbl_klasifikasi k 
                JOIN data_warga w ON k.id_warga = w.id_warga 
                ORDER BY w.nama_lengkap
            ");
            
            // Get kriteria untuk header
            $kriteria = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria ORDER BY id_kriteria");
            ?>
            
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-table"></i> Data Klasifikasi PKH</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> 
                        <strong>Informasi:</strong> Data klasifikasi diisi otomatis dari data warga. 
                        Data ini digunakan untuk perhitungan metode SAW (Simple Additive Weighting).
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <a class='btn btn-success btn-flat' href='?module=klasifikasi&act=refresh_all'>
                                <i class="fa fa-refresh"></i> Refresh Semua Data
                            </a>
                            <a class='btn btn-warning btn-flat' href='?module=klasifikasi&act=export_excel' target="_blank">
                                <i class="fa fa-file-excel-o"></i> Export Excel
                            </a>
                            <a class='btn btn-danger btn-flat' href='export_klasifikasi_pdf.php' target="_blank">
                                <i class="fa fa-file-pdf-o"></i> Export PDF
                            </a>
                        </div>
                    </div>
                    <br>
                    
                    <div class="table-scroll-wrapper" style="overflow-x: scroll; overflow-y: visible; width: 100%; max-width: 100%; border: 1px solid #ddd;">
                        <table id="klasifikasi-table" class="table table-bordered table-striped" style="width: 1600px; white-space: nowrap;">
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Nama Warga</th>
                                    <th rowspan="2">Alamat</th>
                                    <th colspan="<?php echo mysqli_num_rows($kriteria); ?>" class="text-center">Kriteria PKH</th>
                                    <th rowspan="2">Aksi</th>
                                </tr>
                                <tr>
                                    <?php 
                                    mysqli_data_seek($kriteria, 0); // Reset pointer
                                    while($k = mysqli_fetch_array($kriteria)) {
                                        echo "<th class='text-center'>{$k['kode_kriteria']}<br><small>{$k['keterangan']}</small></th>";
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                $no=1;
                                while ($r=mysqli_fetch_array($tampil_klasifikasi)){
                                    echo "<tr>
                                            <td>$no</td>
                                            <td><strong>$r[nama_lengkap]</strong></td>
                                            <td>$r[alamat]</td>";
                                            
                                    // Dynamic criteria columns
                                    mysqli_data_seek($kriteria, 0);
                                    $badge_colors = ['info', 'warning', 'success', 'primary', 'danger', 'default', 'info', 'warning'];
                                    $color_index = 0;
                                    while($k = mysqli_fetch_array($kriteria)) {
                                        $badge_color = $badge_colors[$color_index % count($badge_colors)];
                                        $nilai = isset($r[$k['kode_kriteria']]) ? $r[$k['kode_kriteria']] : 0;
                                        echo "<td class='text-center'><span class='label label-$badge_color'>$nilai</span></td>";
                                        $color_index++;
                                    }
                                    
                                    echo "<td>
                                                <a href='?module=klasifikasi&act=detail&id=$r[id_klasifikasi]' class='btn btn-info btn-xs'>
                                                    <i class='fa fa-eye'></i> Detail
                                                </a>
                                                <a href='?module=klasifikasi&act=refresh&id=$r[id_klasifikasi]' class='btn btn-success btn-xs' title='Refresh dari data warga'>
                                                    <i class='fa fa-refresh'></i> Refresh
                                                </a>
                                            </td>
                                        </tr>";
                                $no++;
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert alert-warning">
                                <i class="fa fa-warning"></i> 
                                <strong>Catatan:</strong> Data klasifikasi terhubung langsung dengan data warga. 
                                Perubahan data warga akan otomatis memperbarui klasifikasi.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-blue"><i class="fa fa-table"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Data Klasifikasi</span>
                                    <span class="info-box-number"><?php echo mysqli_num_rows($tampil_klasifikasi); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php
        }
        break;

    case "detail":
        if ($_SESSION['leveluser']=='admin'){
            $detail = mysqli_query($koneksi, "
                SELECT k.*, w.nama_lengkap, w.alamat, w.created_at as warga_created 
                FROM tbl_klasifikasi k 
                JOIN data_warga w ON k.id_warga = w.id_warga 
                WHERE k.id_klasifikasi='$_GET[id]'
            ");
            $r = mysqli_fetch_array($detail);
            
            // Get kriteria info
            $kriteria = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria ORDER BY id_kriteria");
            ?>
            <div class="box box-info box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-eye"></i> Detail Klasifikasi PKH</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4><i class="fa fa-user"></i> Informasi Warga</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <td width="40%"><strong>Nama Lengkap</strong></td>
                                    <td><?php echo $r['nama_lengkap']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat</strong></td>
                                    <td><?php echo $r['alamat']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>ID Warga</strong></td>
                                    <td><?php echo $r['id_warga']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Data Dibuat</strong></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($r['warga_created'])); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Klasifikasi Update</strong></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($r['created_at'])); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4><i class="fa fa-bar-chart"></i> Nilai Kriteria</h4>
                            <table class="table table-bordered">
                                <?php 
                                mysqli_data_seek($kriteria, 0);
                                while($k = mysqli_fetch_array($kriteria)) {
                                    $nilai_kriteria = $r[$k['kode_kriteria']];
                                    $badge_color = '';
                                    switch($k['kode_kriteria']) {
                                        case 'C1': $badge_color = 'info'; break;
                                        case 'C2': $badge_color = 'warning'; break;
                                        case 'C3': case 'C4': case 'C5': $badge_color = 'success'; break;
                                        case 'C6': $badge_color = 'primary'; break;
                                        case 'C7': $badge_color = 'danger'; break;
                                        case 'C8': $badge_color = 'default'; break;
                                    }
                                    echo "<tr>
                                            <td width='15%'><strong>{$k['kode_kriteria']}</strong></td>
                                            <td width='15%'><span class='label label-$badge_color'>$nilai_kriteria</span></td>
                                            <td>{$k['keterangan']}</td>
                                          </tr>";
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h4><i class="fa fa-calculator"></i> Analisis Kelayakan PKH</h4>
                            <?php 
                            // Hitung total skor sementara
                            $total_score = $r['C1'] + $r['C2'] + $r['C3'] + $r['C4'] + $r['C5'] + $r['C6'] + $r['C7'] + $r['C8'];
                            $kategori = '';
                            $badge = '';
                            
                            if ($total_score >= 15) {
                                $kategori = 'Sangat Layak';
                                $badge = 'success';
                            } elseif ($total_score >= 10) {
                                $kategori = 'Layak';
                                $badge = 'primary';
                            } elseif ($total_score >= 5) {
                                $kategori = 'Cukup Layak';
                                $badge = 'warning';
                            } else {
                                $kategori = 'Kurang Layak';
                                $badge = 'danger';
                            }
                            ?>
                            <div class="alert alert-<?php echo $badge; ?>">
                                <h4><i class="fa fa-check-circle"></i> Hasil Analisis</h4>
                                <p><strong>Total Skor:</strong> <?php echo $total_score; ?></p>
                                <p><strong>Kategori:</strong> <span class="label label-<?php echo $badge; ?>"><?php echo $kategori; ?></span></p>
                                <p><strong>Catatan:</strong> Ini adalah analisis awal. Hasil final akan dihitung dengan metode SAW berdasarkan bobot kriteria.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick="history.back()">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </button>
                    <a href="?module=warga&act=edit&id=<?php echo $r['id_warga']; ?>" class="btn btn-warning">
                        <i class="fa fa-edit"></i> Edit Data Warga
                    </a>
                    <a href="?module=klasifikasi&act=refresh&id=<?php echo $r['id_klasifikasi']; ?>" class="btn btn-success">
                        <i class="fa fa-refresh"></i> Refresh Data
                    </a>
                </div>
            </div>
            <?php
        }
        break;

    case "export_pdf":
        if ($_SESSION['leveluser']=='admin'){
            // Include DOMPDF library
            require_once '../vendor/autoload.php';
            
            $tampil_klasifikasi = mysqli_query($koneksi, "
                SELECT k.*, w.nama_lengkap, w.alamat 
                FROM tbl_klasifikasi k 
                JOIN data_warga w ON k.id_warga = w.id_warga 
                ORDER BY w.nama_lengkap
            ");
            
            $html = '
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; font-size: 11px; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .header h2 { color: #2c5aa0; margin: 5px 0; }
                    .header h3 { margin: 5px 0; }
                    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                    th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
                    th { background-color: #2c5aa0; color: white; font-weight: bold; font-size: 10px; }
                    .text-center { text-align: center; }
                    .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #666; }
                    .kriteria-header { background-color: #34495e !important; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>SISTEM PENDUKUNG KEPUTUSAN PKH</h2>
                    <h2>METODE SIMPLE ADDITIVE WEIGHTING (SAW)</h2>
                    <h3>DINAS SOSIAL REPUBLIK INDONESIA</h3>
                    <hr>
                    <h4>LAPORAN DATA KLASIFIKASI WARGA PKH</h4>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th rowspan="2" width="5%">No</th>
                            <th rowspan="2" width="20%">Nama Warga</th>
                            <th rowspan="2" width="20%">Alamat</th>
                            <th colspan="8" class="text-center kriteria-header">Kriteria PKH</th>
                        </tr>
                        <tr>
                            <th width="7%" class="text-center">C1<br>Lansia</th>
                            <th width="7%" class="text-center">C2<br>Disabilitas</th>
                            <th width="7%" class="text-center">C3<br>Anak SD</th>
                            <th width="7%" class="text-center">C4<br>Anak SMP</th>
                            <th width="7%" class="text-center">C5<br>Anak SMA</th>
                            <th width="7%" class="text-center">C6<br>Balita</th>
                            <th width="7%" class="text-center">C7<br>Ibu Hamil</th>
                            <th width="5%" class="text-center">C8<br>Lainnya</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            $no = 1;
            while ($r = mysqli_fetch_array($tampil_klasifikasi)) {
                $html .= '
                        <tr>
                            <td class="text-center">'.$no.'</td>
                            <td>'.$r['nama_lengkap'].'</td>
                            <td>'.$r['alamat'].'</td>
                            <td class="text-center">'.$r['C1'].'</td>
                            <td class="text-center">'.$r['C2'].'</td>
                            <td class="text-center">'.$r['C3'].'</td>
                            <td class="text-center">'.$r['C4'].'</td>
                            <td class="text-center">'.$r['C5'].'</td>
                            <td class="text-center">'.$r['C6'].'</td>
                            <td class="text-center">'.$r['C7'].'</td>
                            <td class="text-center">'.$r['C8'].'</td>
                        </tr>';
                $no++;
            }
            
            $html .= '
                    </tbody>
                </table>
                
                <div class="footer">
                    <p>Dicetak pada: '.date('d/m/Y H:i:s').' | Total Data: '.($no-1).' Klasifikasi</p>
                    <p>Sistem Pendukung Keputusan PKH - Dinas Sosial RI</p>
                </div>
            </body>
            </html>';
            
            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', true);
            $dompdf = new \Dompdf\Dompdf($options);
            
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            $dompdf->stream("Data_Klasifikasi_PKH_".date('Y-m-d').".pdf", array("Attachment" => false));
        }
        break;
}
}
?>
