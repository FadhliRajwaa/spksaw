<?php
session_start();
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
    echo "<link href=../css/style.css rel=stylesheet type=text/css>";
    echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
}
else{

include "../../../configurasi/koneksi.php";
include "../../../configurasi/library.php";
include "../../../configurasi/fungsi_indotgl.php";
include "../../../configurasi/class_paging.php";

$aksi="modul/mod_warga/aksi_warga.php";
switch($_GET['act']){
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
                        </div>
                    </div>
                    <br>
                    
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Alamat</th>
                                <th>Lansia</th>
                                <th>Disabilitas</th>
                                <th>Anak SD</th>
                                <th>Anak SMP</th>
                                <th>Anak SMA</th>
                                <th>Balita</th>
                                <th>Ibu Hamil</th>
                                <th>Aksi</th>
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
