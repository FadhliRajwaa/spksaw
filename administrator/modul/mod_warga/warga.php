<?php
if (session_status() !== PHP_SESSION_ACTIVE) { @session_start(); }
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
    echo "<link href=../css/style.css rel=stylesheet type=text/css>";
    echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
}
else{

// Pastikan koneksi & libs tersedia (gunakan path absolut relatif file modul)
if (!isset($koneksi)) { require_once __DIR__ . "/../../../configurasi/koneksi.php"; }
require_once __DIR__ . "/../../../configurasi/library.php";
require_once __DIR__ . "/../../../configurasi/fungsi_indotgl.php";
require_once __DIR__ . "/../../../configurasi/class_paging.php";

$aksi="modul/mod_warga/aksi_warga.php";
// Hindari warning bila parameter act tidak ada
$__act = isset($_GET['act']) ? $_GET['act'] : '';
switch($__act){
    // Tampil Data Warga
    default:
        if ($_SESSION['leveluser']=='admin'){
            $tampil_warga = mysqli_query($koneksi, "SELECT * FROM data_warga ORDER BY nama_lengkap");
            ?>
            
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"></i> Data Warga PKH</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a class='btn btn-primary btn-flat' href='?module=warga&act=tambah'>
                                <i class="fa fa-plus"></i> Tambah Data Warga
                            </a>
                            <a class='btn btn-success btn-flat' href='export_warga_pdf.php' target="_blank">
                                <i class="fa fa-file-pdf-o"></i> Export PDF
                            </a>
                            <button class='btn btn-info btn-flat' onclick='printTable()'>
                                <i class="fa fa-print"></i> Cetak Data
                            </button>
                        </div>
                    </div>
                    <br>
                    
                    <div class="table-scroll-wrap">
                    <style>
                        /* Light theme overrides for Data Warga table */
                        .table-scroll-wrap { overflow-x: auto; }
                        .warga-table { 
                            width: 100%; 
                            border-collapse: collapse; 
                            background: #ffffff; 
                            border: 1px solid #e2e8f0;
                        }
                        .warga-table thead th {
                            color: #1e293b !important;
                            background: #f3f6fb !important;
                            border: 1px solid #e2e8f0 !important;
                            white-space: nowrap;
                            vertical-align: middle;
                            font-weight: 600;
                        }
                        .warga-table tbody tr { background: #ffffff; }
                        .warga-table tbody tr:hover { background: #fafbff; }
                        .warga-table th, .warga-table td { 
                            vertical-align: middle; 
                            border: 1px solid #e2e8f0 !important;
                        }
                        .warga-table td { color: #1a202c !important; }
                        /* Center numeric columns (Lansia..Ibu Hamil) -> columns 4..10 */
                        .warga-table td:nth-child(n+4):nth-child(-n+10),
                        .warga-table th:nth-child(n+4):nth-child(-n+10) { text-align: center; }
                        /* Keep action column aligned center */
                        .warga-table th:last-child, .warga-table td:last-child { text-align: center; white-space: nowrap; }

                        /* Module-scoped: force ONLY DataTables areas to white (Edge/system dark override) */
                        #example1_wrapper,
                        #example1_wrapper > .row,
                        #example1_wrapper .dataTables_length,
                        #example1_wrapper .dataTables_filter,
                        #example1_wrapper .dataTables_info,
                        #example1_wrapper .dataTables_paginate,
                        #example1_wrapper .dataTables_scroll,
                        #example1_wrapper .dataTables_scrollHead,
                        #example1_wrapper .dataTables_scrollBody,
                        #example1_wrapper .dataTables_scrollFoot { background: #ffffff !important; }
                    </style>
                    <table id="example1" class="table table-bordered table-striped warga-table">
                        <thead>
                            <tr>
                                <th width="6%">No</th>
                                <th width="24%">Nama Lengkap (sesuai KK)</th>
                                <th width="26%">Alamat Lengkap</th>
                                <th width="7%">Lansia (≥60 th)</th>
                                <th width="9%">Disabilitas Berat</th>
                                <th width="7%">Anak usia SD</th>
                                <th width="7%">Anak usia SMP</th>
                                <th width="7%">Anak usia SMA</th>
                                <th width="7%">Balita (0–5 th)</th>
                                <th width="7%">Ibu Hamil</th>
                                <th width="13%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                            $no=1;
                            while ($r=mysqli_fetch_array($tampil_warga)){
                                echo "<tr>
                                        <td>$no</td>
                                        <td><strong>$r[nama_lengkap]</strong></td>
                                        <td>$r[alamat]</td>
                                        <td><span class='label label-info'>$r[jumlah_lansia]</span></td>
                                        <td><span class='label label-warning'>$r[jumlah_disabilitas_berat]</span></td>
                                        <td><span class='label label-success'>$r[jumlah_anak_sd]</span></td>
                                        <td><span class='label label-success'>$r[jumlah_anak_smp]</span></td>
                                        <td><span class='label label-success'>$r[jumlah_anak_sma]</span></td>
                                        <td><span class='label label-primary'>$r[jumlah_balita]</span></td>
                                        <td><span class='label label-danger'>$r[jumlah_ibu_hamil]</span></td>
                                        <td>
                                            <a href='?module=warga&act=edit&id=$r[id_warga]' title='Edit' class='btn btn-warning btn-xs'>
                                                <i class='fa fa-edit'></i> Edit
                                            </a> 
                                            <a href=javascript:confirmdelete('$aksi?act=hapus&id=$r[id_warga]') title='Hapus' class='btn btn-danger btn-xs'>
                                                <i class='fa fa-trash'></i> Hapus
                                            </a>
                                            <a href='?module=warga&act=detail&id=$r[id_warga]' class='btn btn-info btn-xs'>
                                                <i class='fa fa-eye'></i> Detail
                                            </a>
                                        </td>
                                    </tr>";
                            $no++;
                            }
                        ?>
                        </tbody>
                    </table>
                    </div>
                 </div>
             </div>
            
            <?php
        }
        break;

    case "tambah":
        if ($_SESSION['leveluser']=='admin'){
            ?>
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-plus"></i> Tambah Data Warga</h3>
                </div>
                <form method="POST" action="<?php echo $aksi; ?>?act=input" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Nama Lengkap *</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nama_lengkap" required maxlength="100">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Alamat *</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="alamat" rows="3" required maxlength="200"></textarea>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Jumlah Lansia</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" name="jumlah_lansia" min="0" max="10" value="0">
                                        <small class="text-muted">Anggota keluarga usia ≥60 tahun</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Disabilitas Berat</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" name="jumlah_disabilitas_berat" min="0" max="10" value="0">
                                        <small class="text-muted">Anggota dengan disabilitas berat</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Anak SD</label>
                                    <div class="col-sm-6">
                                        <input type="number" class="form-control" name="jumlah_anak_sd" min="0" max="10" value="0">
                                        <small class="text-muted">Usia 7-12 tahun</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Anak SMP</label>
                                    <div class="col-sm-6">
                                        <input type="number" class="form-control" name="jumlah_anak_smp" min="0" max="10" value="0">
                                        <small class="text-muted">Usia 13-15 tahun</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Anak SMA</label>
                                    <div class="col-sm-6">
                                        <input type="number" class="form-control" name="jumlah_anak_sma" min="0" max="10" value="0">
                                        <small class="text-muted">Usia 16-18 tahun</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Jumlah Balita</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" name="jumlah_balita" min="0" max="10" value="0">
                                        <small class="text-muted">Anak usia 0-5 tahun</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Ibu Hamil</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" name="jumlah_ibu_hamil" min="0" max="10" value="0">
                                        <small class="text-muted">Jumlah ibu hamil dalam keluarga</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="box-footer">
                        <button type="button" class="btn btn-default" onclick="history.back()">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
            <?php
        }
        break;

    case "edit":
        if ($_SESSION['leveluser']=='admin'){
            $edit = mysqli_query($koneksi, "SELECT * FROM data_warga WHERE id_warga='$_GET[id]'");
            $r = mysqli_fetch_array($edit);
            ?>
            <div class="box box-warning box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-edit"></i> Edit Data Warga</h3>
                </div>
                <form method="POST" action="<?php echo $aksi; ?>?act=update" class="form-horizontal">
                    <input type="hidden" name="id_warga" value="<?php echo $r['id_warga']; ?>">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Nama Lengkap *</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nama_lengkap" value="<?php echo $r['nama_lengkap']; ?>" required maxlength="100">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Alamat *</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="alamat" rows="3" required maxlength="200"><?php echo $r['alamat']; ?></textarea>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Jumlah Lansia</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" name="jumlah_lansia" min="0" max="10" value="<?php echo $r['jumlah_lansia']; ?>">
                                        <small class="text-muted">Anggota keluarga usia ≥60 tahun</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Disabilitas Berat</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" name="jumlah_disabilitas_berat" min="0" max="10" value="<?php echo $r['jumlah_disabilitas_berat']; ?>">
                                        <small class="text-muted">Anggota dengan disabilitas berat</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Anak SD</label>
                                    <div class="col-sm-6">
                                        <input type="number" class="form-control" name="jumlah_anak_sd" min="0" max="10" value="<?php echo $r['jumlah_anak_sd']; ?>">
                                        <small class="text-muted">Usia 7-12 tahun</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Anak SMP</label>
                                    <div class="col-sm-6">
                                        <input type="number" class="form-control" name="jumlah_anak_smp" min="0" max="10" value="<?php echo $r['jumlah_anak_smp']; ?>">
                                        <small class="text-muted">Usia 13-15 tahun</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Anak SMA</label>
                                    <div class="col-sm-6">
                                        <input type="number" class="form-control" name="jumlah_anak_sma" min="0" max="10" value="<?php echo $r['jumlah_anak_sma']; ?>">
                                        <small class="text-muted">Usia 16-18 tahun</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Jumlah Balita</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" name="jumlah_balita" min="0" max="10" value="<?php echo $r['jumlah_balita']; ?>">
                                        <small class="text-muted">Anak usia 0-5 tahun</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Ibu Hamil</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" name="jumlah_ibu_hamil" min="0" max="10" value="<?php echo $r['jumlah_ibu_hamil']; ?>">
                                        <small class="text-muted">Jumlah ibu hamil dalam keluarga</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="box-footer">
                        <button type="button" class="btn btn-default" onclick="history.back()">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fa fa-save"></i> Update Data
                        </button>
                    </div>
                </form>
            </div>
            <?php
        }
        break;

    case "detail":
        if ($_SESSION['leveluser']=='admin'){
            $detail = mysqli_query($koneksi, "SELECT * FROM data_warga WHERE id_warga='$_GET[id]'");
            $r = mysqli_fetch_array($detail);
            ?>
            <div class="box box-info box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-eye"></i> Detail Data Warga</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <td width="30%"><strong>ID Warga</strong></td>
                                    <td><?php echo $r['id_warga']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Lengkap</strong></td>
                                    <td><?php echo $r['nama_lengkap']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat</strong></td>
                                    <td><?php echo $r['alamat']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Input</strong></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($r['created_at'])); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Terakhir Update</strong></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($r['updated_at'])); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4><i class="fa fa-users"></i> Komposisi Keluarga</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <td width="50%"><strong>Jumlah Lansia</strong></td>
                                    <td><span class="label label-info"><?php echo $r['jumlah_lansia']; ?> orang</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Disabilitas Berat</strong></td>
                                    <td><span class="label label-warning"><?php echo $r['jumlah_disabilitas_berat']; ?> orang</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Anak SD</strong></td>
                                    <td><span class="label label-success"><?php echo $r['jumlah_anak_sd']; ?> orang</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Anak SMP</strong></td>
                                    <td><span class="label label-success"><?php echo $r['jumlah_anak_smp']; ?> orang</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Anak SMA</strong></td>
                                    <td><span class="label label-success"><?php echo $r['jumlah_anak_sma']; ?> orang</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Balita</strong></td>
                                    <td><span class="label label-primary"><?php echo $r['jumlah_balita']; ?> orang</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Ibu Hamil</strong></td>
                                    <td><span class="label label-danger"><?php echo $r['jumlah_ibu_hamil']; ?> orang</span></td>
                                </tr>
                            </table>
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
        if ($_SESSION['leveluser']=='admin'){
            // Include DOMPDF library
            require_once '../vendor/autoload.php';
            
            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', true);
            $dompdf = new \Dompdf\Dompdf($options);
            
            $tampil_warga = mysqli_query($koneksi, "SELECT * FROM data_warga ORDER BY nama_lengkap");
            
            $html = '
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; font-size: 12px; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .header h2 { color: #2c5aa0; margin: 5px 0; }
                    .header h3 { margin: 5px 0; }
                    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #2c5aa0; color: white; font-weight: bold; }
                    .text-center { text-align: center; }
                    .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #666; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>SISTEM PENDUKUNG KEPUTUSAN</h2>
                    <h2>REKOMENDASI PENERIMA BANTUAN PKH</h2>
                    <h3>DINAS SOSIAL REPUBLIK INDONESIA</h3>
                    <hr>
                    <h4>LAPORAN DATA WARGA PKH</h4>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%">Nama Lengkap</th>
                            <th width="25%">Alamat</th>
                            <th width="8%">Lansia</th>
                            <th width="8%">Disabilitas</th>
                            <th width="8%">Anak SD</th>
                            <th width="8%">Anak SMP</th>
                            <th width="8%">Anak SMA</th>
                            <th width="8%">Balita</th>
                            <th width="8%">Ibu Hamil</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            $no = 1;
            while ($r = mysqli_fetch_array($tampil_warga)) {
                $html .= '
                        <tr>
                            <td class="text-center">'.$no.'</td>
                            <td>'.$r['nama_lengkap'].'</td>
                            <td>'.$r['alamat'].'</td>
                            <td class="text-center">'.$r['jumlah_lansia'].'</td>
                            <td class="text-center">'.$r['jumlah_disabilitas_berat'].'</td>
                            <td class="text-center">'.$r['jumlah_anak_sd'].'</td>
                            <td class="text-center">'.$r['jumlah_anak_smp'].'</td>
                            <td class="text-center">'.$r['jumlah_anak_sma'].'</td>
                            <td class="text-center">'.$r['jumlah_balita'].'</td>
                            <td class="text-center">'.$r['jumlah_ibu_hamil'].'</td>
                        </tr>';
                $no++;
            }
            
            $html .= '
                    </tbody>
                </table>
                
                <div class="footer">
                    <p>Dicetak pada: '.date('d/m/Y H:i:s').' | Total Data: '.($no-1).' Warga</p>
                    <p>Sistem Pendukung Keputusan PKH - Dinas Sosial RI</p>
                </div>
            </body>
            </html>';
            
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            $dompdf->stream("Data_Warga_PKH_".date('Y-m-d').".pdf", array("Attachment" => false));
        }
        break;
}
}
?>

<script>
function printTable() {
    // Get data from the actual table with proper column handling
    var rows = document.querySelectorAll('#example1 tbody tr');
    var totalWarga = rows.length;
    
    // Calculate statistics
    var totalLansia = 0;
    var totalDisabilitas = 0;
    var totalAnakSD = 0;
    var totalAnakSMP = 0;
    var totalAnakSMA = 0;
    var totalBalita = 0;
    var totalIbuHamil = 0;
    
    // Build table content with proper data extraction
    var tableRows = '';
    rows.forEach(function(row, index) {
        var cells = row.querySelectorAll('td');
        if (cells.length >= 10) { // Ensure we have enough columns
            var nama = cells[1].textContent.trim();
            var alamat = cells[2].textContent.trim();
            var lansia = parseInt(cells[3].textContent.trim()) || 0;
            var disabilitas = parseInt(cells[4].textContent.trim()) || 0;
            var anakSD = parseInt(cells[5].textContent.trim()) || 0;
            var anakSMP = parseInt(cells[6].textContent.trim()) || 0;
            var anakSMA = parseInt(cells[7].textContent.trim()) || 0;
            var balita = parseInt(cells[8].textContent.trim()) || 0;
            var ibuHamil = parseInt(cells[9].textContent.trim()) || 0;
            
            // Update totals
            totalLansia += lansia;
            totalDisabilitas += disabilitas;
            totalAnakSD += anakSD;
            totalAnakSMP += anakSMP;
            totalAnakSMA += anakSMA;
            totalBalita += balita;
            totalIbuHamil += ibuHamil;
            
            tableRows += `
                <tr>
                    <td style="text-align: center; font-weight: bold;">${index + 1}</td>
                    <td style="font-weight: bold; color: #2c5aa0;">${nama}</td>
                    <td>${alamat}</td>
                    <td style="text-align: center; background-color: #e3f2fd;">${lansia}</td>
                    <td style="text-align: center; background-color: #fff3e0;">${disabilitas}</td>
                    <td style="text-align: center; background-color: #e8f5e8;">${anakSD}</td>
                    <td style="text-align: center; background-color: #e8f5e8;">${anakSMP}</td>
                    <td style="text-align: center; background-color: #e8f5e8;">${anakSMA}</td>
                    <td style="text-align: center; background-color: #f3e5f5;">${balita}</td>
                    <td style="text-align: center; background-color: #ffebee;">${ibuHamil}</td>
                </tr>
            `;
        }
    });

    // Create print window with modern design
    var printWindow = window.open('', '_blank', 'width=1200,height=800');
    var currentDate = new Date().toLocaleString('id-ID', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Data Warga PKH - ${new Date().toLocaleDateString('id-ID')}</title>
            <meta charset="UTF-8">
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
                
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                
                body {
                    font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    line-height: 1.6;
                    color: #1a1a1a;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    min-height: 100vh;
                    padding: 0;
                }
                
                .document-container {
                    background: white;
                    margin: 0;
                    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
                    border-radius: 0;
                    overflow: hidden;
                }
                
                .header {
                    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
                    color: white;
                    padding: 40px;
                    text-align: center;
                    position: relative;
                    overflow: hidden;
                }
                
                .header::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="25" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="25" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
                    opacity: 0.3;
                }
                
                .header-content {
                    position: relative;
                    z-index: 1;
                }
                
                .government-seal {
                    width: 80px;
                    height: 80px;
                    background: rgba(255, 255, 255, 0.1);
                    border: 3px solid rgba(255, 255, 255, 0.3);
                    border-radius: 50%;
                    margin: 0 auto 20px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 24px;
                    font-weight: bold;
                    backdrop-filter: blur(10px);
                }
                
                .header h1 {
                    font-size: 28px;
                    font-weight: 700;
                    margin-bottom: 8px;
                    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
                    letter-spacing: 1px;
                }
                
                .header h2 {
                    font-size: 20px;
                    font-weight: 500;
                    margin-bottom: 6px;
                    opacity: 0.95;
                    letter-spacing: 0.5px;
                }
                
                .header .subtitle {
                    font-size: 16px;
                    font-weight: 400;
                    opacity: 0.9;
                    margin-bottom: 20px;
                }
                
                .header .meta {
                    font-size: 14px;
                    opacity: 0.85;
                    background: rgba(255, 255, 255, 0.1);
                    padding: 12px 24px;
                    border-radius: 25px;
                    display: inline-block;
                    backdrop-filter: blur(10px);
                    border: 1px solid rgba(255, 255, 255, 0.2);
                }
                
                .content {
                    padding: 40px;
                }
                
                .executive-summary {
                    margin-bottom: 40px;
                }
                
                .summary-title {
                    font-size: 22px;
                    font-weight: 600;
                    color: #1e3c72;
                    margin-bottom: 25px;
                    text-align: center;
                    position: relative;
                }
                
                .summary-title::after {
                    content: '';
                    position: absolute;
                    bottom: -8px;
                    left: 50%;
                    transform: translateX(-50%);
                    width: 60px;
                    height: 3px;
                    background: linear-gradient(90deg, #667eea, #764ba2);
                    border-radius: 2px;
                }
                
                .stats-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                    gap: 20px;
                    margin-bottom: 30px;
                }
                
                .stat-card {
                    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                    border: 1px solid #e2e8f0;
                    border-radius: 12px;
                    padding: 20px;
                    text-align: center;
                    transition: all 0.3s ease;
                    position: relative;
                    overflow: hidden;
                }
                
                .stat-card::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 4px;
                    background: var(--card-color, #667eea);
                }
                
                .stat-card.lansia::before { background: linear-gradient(90deg, #3b82f6, #1d4ed8); }
                .stat-card.disabilitas::before { background: linear-gradient(90deg, #f59e0b, #d97706); }
                .stat-card.pendidikan::before { background: linear-gradient(90deg, #10b981, #059669); }
                .stat-card.balita::before { background: linear-gradient(90deg, #8b5cf6, #7c3aed); }
                .stat-card.ibu-hamil::before { background: linear-gradient(90deg, #ef4444, #dc2626); }
                
                .stat-number {
                    font-size: 32px;
                    font-weight: 700;
                    color: #1e293b;
                    margin-bottom: 8px;
                    display: block;
                }
                
                .stat-label {
                    font-size: 14px;
                    font-weight: 500;
                    color: #64748b;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }
                
                .data-table-container {
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
                    overflow: hidden;
                    border: 1px solid #e2e8f0;
                }
                
                .table-title {
                    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                    padding: 20px;
                    border-bottom: 1px solid #e2e8f0;
                }
                
                .table-title h3 {
                    font-size: 18px;
                    font-weight: 600;
                    color: #1e293b;
                    margin: 0;
                }
                
                .data-table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 12px;
                }
                
                .data-table th {
                    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
                    color: white;
                    padding: 16px 12px;
                    text-align: center;
                    font-weight: 600;
                    font-size: 11px;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    border: none;
                    position: relative;
                }
                
                .data-table th::after {
                    content: '';
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    height: 2px;
                    background: rgba(255, 255, 255, 0.3);
                }
                
                .data-table td {
                    padding: 14px 12px;
                    border-bottom: 1px solid #f1f5f9;
                    font-size: 11px;
                    line-height: 1.4;
                }
                
                .data-table tr:nth-child(even) {
                    background-color: #f8fafc;
                }
                
                .data-table tr:hover {
                    background-color: #e2e8f0;
                    transform: scale(1.01);
                    transition: all 0.2s ease;
                }
                
                .footer {
                    background: linear-gradient(135deg, #1e293b 0%, #374151 100%);
                    color: white;
                    padding: 30px 40px;
                    text-align: center;
                    margin-top: 40px;
                }
                
                .footer-content {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    flex-wrap: wrap;
                    gap: 20px;
                }
                
                .footer-left, .footer-right {
                    font-size: 12px;
                    opacity: 0.9;
                }
                
                .footer-center {
                    flex: 1;
                    text-align: center;
                }
                
                .footer-center h4 {
                    font-size: 14px;
                    font-weight: 600;
                    margin-bottom: 5px;
                }
                
                .footer-center p {
                    font-size: 11px;
                    opacity: 0.8;
                }
                
                /* Print-specific styles */
                @media print {
                    body {
                        background: white !important;
                        padding: 0 !important;
                    }
                    
                    .document-container {
                        box-shadow: none !important;
                        border-radius: 0 !important;
                    }
                    
                    .header, .footer {
                        background: #1e3c72 !important;
                        color: white !important;
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                    }
                    
                    .stat-card {
                        break-inside: avoid;
                    }
                    
                    .data-table {
                        break-inside: avoid;
                    }
                    
                    .data-table th {
                        background: #1e3c72 !important;
                        color: white !important;
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                    }
                }
                
                @page {
                    size: A4 landscape;
                    margin: 0.5cm;
                }
            </style>
        </head>
        <body>
            <div class="document-container">
                <div class="header">
                    <div class="header-content">
                        <div class="government-seal">🏛️</div>
                        <h1>REPUBLIK INDONESIA</h1>
                        <h2>SISTEM PENDUKUNG KEPUTUSAN PKH</h2>
                        <div class="subtitle">Dinas Sosial Republik Indonesia</div>
                        <div class="meta">
                            📄 LAPORAN DATA WARGA PROGRAM KELUARGA HARAPAN<br>
                            📅 ${currentDate}
                        </div>
                    </div>
                </div>
                
                <div class="content">
                    <div class="executive-summary">
                        <h2 class="summary-title">📊 RINGKASAN EKSEKUTIF</h2>
                        <div class="stats-grid">
                            <div class="stat-card">
                                <span class="stat-number">${totalWarga}</span>
                                <div class="stat-label">Total Keluarga</div>
                            </div>
                            <div class="stat-card lansia">
                                <span class="stat-number">${totalLansia}</span>
                                <div class="stat-label">Lansia (≥60 tahun)</div>
                            </div>
                            <div class="stat-card disabilitas">
                                <span class="stat-number">${totalDisabilitas}</span>
                                <div class="stat-label">Disabilitas Berat</div>
                            </div>
                            <div class="stat-card pendidikan">
                                <span class="stat-number">${totalAnakSD + totalAnakSMP + totalAnakSMA}</span>
                                <div class="stat-label">Anak Usia Sekolah</div>
                            </div>
                            <div class="stat-card balita">
                                <span class="stat-number">${totalBalita}</span>
                                <div class="stat-label">Balita (0-5 tahun)</div>
                            </div>
                            <div class="stat-card ibu-hamil">
                                <span class="stat-number">${totalIbuHamil}</span>
                                <div class="stat-label">Ibu Hamil</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="data-table-container">
                        <div class="table-title">
                            <h3>📋 DATA LENGKAP WARGA PKH</h3>
                        </div>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 20%;">Nama Lengkap</th>
                                    <th style="width: 25%;">Alamat</th>
                                    <th style="width: 8%;">Lansia<br><small>(≥60 thn)</small></th>
                                    <th style="width: 8%;">Disabilitas<br><small>Berat</small></th>
                                    <th style="width: 8%;">Anak SD<br><small>(7-12 thn)</small></th>
                                    <th style="width: 8%;">Anak SMP<br><small>(13-15 thn)</small></th>
                                    <th style="width: 8%;">Anak SMA<br><small>(16-18 thn)</small></th>
                                    <th style="width: 8%;">Balita<br><small>(0-5 thn)</small></th>
                                    <th style="width: 8%;">Ibu Hamil</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${tableRows}
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="footer">
                    <div class="footer-content">
                        <div class="footer-left">
                            <strong>🏛️ DINAS SOSIAL RI</strong><br>
                            Sistem PKH Terintegrasi
                        </div>
                        <div class="footer-center">
                            <h4>📄 DOKUMEN RESMI</h4>
                            <p>Dicetak secara otomatis • Tanggal: ${new Date().toLocaleDateString('id-ID')}</p>
                        </div>
                        <div class="footer-right">
                            <strong>📊 STATISTIK</strong><br>
                            ${totalWarga} Keluarga Terdaftar
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.focus();
    
    // Wait for content and fonts to load, then print
    setTimeout(function() {
        printWindow.print();
        printWindow.close();
    }, 1000);
}
</script>
