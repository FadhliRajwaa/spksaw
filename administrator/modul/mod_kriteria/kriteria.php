<?php
session_start();
if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
    echo "<link href=../css/style.css rel=stylesheet type=text/css>";
    echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
}
else{

$aksi="modul/mod_kriteria/aksi_kriteria.php";
switch($_GET['act']){
    // Tampil Data Kriteria
    default:
        if ($_SESSION['leveluser']=='admin'){
            $tampil_kriteria = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria ORDER BY id_kriteria");
            ?>
            
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-list"></i> Data Kriteria PKH</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> 
                        <strong>Informasi:</strong> Kriteria PKH menggunakan 8 kriteria tetap sesuai standar pemerintah.
                        Anda hanya dapat mengubah keterangan dan bobot nilai kriteria.
                    </div>
                    
                    <div class="margin-bottom">
                        <a href="export_kriteria_pdf.php" target="_blank" class="btn btn-danger">
                            <i class="fa fa-file-pdf-o"></i> Export PDF
                        </a>
                    </div>
                    
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="10%">Kode</th>
                                <th width="50%">Keterangan Kriteria</th>
                                <th width="15%">Bobot Nilai</th>
                                <th width="15%">Jenis</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                            while ($r=mysqli_fetch_array($tampil_kriteria)){
                                $jenis_badge = $r['jenis'] == 'Benefit' ? 'success' : 'warning';
                                echo "<tr>
                                        <td><strong>$r[kode_kriteria]</strong></td>
                                        <td>$r[keterangan]</td>
                                        <td><span class='label label-primary'>$r[nilai]</span></td>
                                        <td><span class='label label-$jenis_badge'>$r[jenis]</span></td>
                                        <td>
                                            <a href='?module=kriteria&act=edit&id=$r[id_kriteria]' title='Edit' class='btn btn-warning btn-xs'>
                                                <i class='fa fa-edit'></i> Edit
                                            </a>
                                        </td>
                                    </tr>";
                            }
                        ?>
                        </tbody>
                    </table>
                    
                    <div class="alert alert-warning">
                        <i class="fa fa-warning"></i> 
                        <strong>Catatan:</strong> Kriteria PKH bersifat tetap dan tidak dapat ditambah/dihapus. 
                        Hanya keterangan dan bobot nilai yang dapat dimodifikasi sesuai kebutuhan daerah.
                    </div>
                </div>
            </div>
            
            <?php
        }
        break;

    case "edit":
        if ($_SESSION['leveluser']=='admin'){
            $edit = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria WHERE id_kriteria='$_GET[id]'");
            $r = mysqli_fetch_array($edit);
            ?>
            <div class="box box-warning box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-edit"></i> Edit Kriteria PKH</h3>
                </div>
                <form method="POST" action="<?php echo $aksi; ?>?act=update" class="form-horizontal">
                    <input type="hidden" name="id_kriteria" value="<?php echo $r['id_kriteria']; ?>">
                    <input type="hidden" name="kode_kriteria" value="<?php echo $r['kode_kriteria']; ?>">
                    
                    <div class="box-body">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> 
                            <strong>Editing:</strong> Kriteria <?php echo $r['kode_kriteria']; ?> - 
                            Anda hanya dapat mengubah keterangan dan bobot nilai.
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Kode Kriteria</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="<?php echo $r['kode_kriteria']; ?>" readonly>
                                <small class="text-muted">Kode kriteria tidak dapat diubah</small>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Keterangan Kriteria *</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="keterangan" rows="3" required maxlength="255"><?php echo $r['keterangan']; ?></textarea>
                                <small class="text-muted">Jelaskan kriteria ini dengan detail untuk memudahkan pemahaman</small>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Bobot Nilai *</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="number" class="form-control" name="nilai" 
                                           value="<?php echo $r['nilai']; ?>" 
                                           min="0.1" max="1.0" step="0.1" required>
                                    <span class="input-group-addon">/ 1.0</span>
                                </div>
                                <small class="text-muted">Bobot kriteria antara 0.1 - 1.0. Semakin tinggi semakin penting.</small>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Jenis Kriteria</label>
                            <div class="col-sm-9">
                                <select name="jenis" class="form-control" required>
                                    <option value="Benefit" <?php echo ($r['jenis']=='Benefit') ? 'selected' : ''; ?>>
                                        Benefit (Semakin tinggi semakin baik)
                                    </option>
                                    <option value="Cost" <?php echo ($r['jenis']=='Cost') ? 'selected' : ''; ?>>
                                        Cost (Semakin rendah semakin baik)
                                    </option>
                                </select>
                                <small class="text-muted">Tentukan apakah nilai tinggi = baik (Benefit) atau nilai rendah = baik (Cost)</small>
                            </div>
                        </div>
                        
                        <?php 
                        // Contoh interpretasi berdasarkan kode kriteria
                        $interpretasi = '';
                        switch($r['kode_kriteria']) {
                            case 'C1': $interpretasi = 'Semakin banyak lansia = semakin butuh bantuan (Benefit)'; break;
                            case 'C2': $interpretasi = 'Semakin banyak disabilitas = semakin butuh bantuan (Benefit)'; break;
                            case 'C3': case 'C4': case 'C5': $interpretasi = 'Semakin banyak anak sekolah = semakin butuh bantuan (Benefit)'; break;
                            case 'C6': $interpretasi = 'Semakin banyak balita = semakin butuh bantuan (Benefit)'; break;
                            case 'C7': $interpretasi = 'Semakin banyak ibu hamil = semakin butuh bantuan (Benefit)'; break;
                            case 'C8': $interpretasi = 'Kriteria tambahan sesuai kebutuhan daerah'; break;
                        }
                        if($interpretasi): ?>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Interpretasi</label>
                            <div class="col-sm-9">
                                <div class="alert alert-success">
                                    <i class="fa fa-lightbulb-o"></i> <?php echo $interpretasi; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="box-footer">
                        <button type="button" class="btn btn-default" onclick="history.back()">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fa fa-save"></i> Update Kriteria
                        </button>
                    </div>
                </form>
            </div>
            <?php
        }
        break;

    case "detail":
        if ($_SESSION['leveluser']=='admin'){
            $detail = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria WHERE id_kriteria='$_GET[id]'");
            $r = mysqli_fetch_array($detail);
            
            // Hitung penggunaan kriteria dalam klasifikasi
            $usage = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tbl_klasifikasi");
            $usage_data = mysqli_fetch_array($usage);
            ?>
            <div class="box box-info box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-eye"></i> Detail Kriteria PKH</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <td width="40%"><strong>Kode Kriteria</strong></td>
                                    <td><?php echo $r['kode_kriteria']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Keterangan</strong></td>
                                    <td><?php echo $r['keterangan']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Bobot Nilai</strong></td>
                                    <td><span class="label label-primary"><?php echo $r['nilai']; ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Kriteria</strong></td>
                                    <td>
                                        <?php 
                                        $badge = $r['jenis'] == 'Benefit' ? 'success' : 'warning';
                                        echo "<span class='label label-$badge'>{$r['jenis']}</span>";
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td><span class="label label-success">Aktif</span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4><i class="fa fa-bar-chart"></i> Statistik Penggunaan</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <td width="60%"><strong>Total Warga Menggunakan</strong></td>
                                    <td><span class="label label-info"><?php echo $usage_data['total']; ?> warga</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Dalam Sistem SAW</strong></td>
                                    <td><span class="label label-success">Terintegrasi</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Nilai Minimum</strong></td>
                                    <td><?php 
                                    $min_val = mysqli_query($koneksi, "SELECT MIN({$r['kode_kriteria']}) as min_val FROM tbl_klasifikasi");
                                    $min_data = mysqli_fetch_array($min_val);
                                    echo $min_data['min_val'] ?? '0';
                                    ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Nilai Maksimum</strong></td>
                                    <td><?php 
                                    $max_val = mysqli_query($koneksi, "SELECT MAX({$r['kode_kriteria']}) as max_val FROM tbl_klasifikasi");
                                    $max_data = mysqli_fetch_array($max_val);
                                    echo $max_data['max_val'] ?? '0';
                                    ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h4><i class="fa fa-info-circle"></i> Interpretasi Kriteria</h4>
                            <div class="alert alert-info">
                                <?php 
                                switch($r['kode_kriteria']) {
                                    case 'C1': 
                                        echo "<strong>Jumlah Lansia:</strong> Kriteria ini mengukur jumlah anggota keluarga berusia 60 tahun ke atas. 
                                              Keluarga dengan lebih banyak lansia memiliki kebutuhan perawatan kesehatan yang lebih tinggi."; 
                                        break;
                                    case 'C2': 
                                        echo "<strong>Disabilitas Berat:</strong> Mengukur jumlah anggota keluarga dengan disabilitas berat yang memerlukan 
                                              bantuan khusus dan biaya perawatan tambahan."; 
                                        break;
                                    case 'C3': 
                                        echo "<strong>Anak SD:</strong> Jumlah anak usia 7-12 tahun yang memerlukan biaya pendidikan dasar, 
                                              seragam, buku, dan kebutuhan sekolah lainnya."; 
                                        break;
                                    case 'C4': 
                                        echo "<strong>Anak SMP:</strong> Jumlah anak usia 13-15 tahun dengan kebutuhan pendidikan menengah pertama 
                                              yang lebih tinggi dari SD."; 
                                        break;
                                    case 'C5': 
                                        echo "<strong>Anak SMA:</strong> Jumlah anak usia 16-18 tahun dengan kebutuhan pendidikan menengah atas 
                                              dan persiapan melanjutkan ke jenjang yang lebih tinggi."; 
                                        break;
                                    case 'C6': 
                                        echo "<strong>Jumlah Balita:</strong> Anak usia 0-5 tahun yang memerlukan nutrisi khusus, imunisasi, 
                                              dan perawatan kesehatan intensif."; 
                                        break;
                                    case 'C7': 
                                        echo "<strong>Ibu Hamil:</strong> Jumlah ibu hamil dalam keluarga yang memerlukan perawatan prenatal, 
                                              gizi tambahan, dan persiapan persalinan."; 
                                        break;
                                    case 'C8': 
                                        echo "<strong>Kriteria Tambahan:</strong> Kriteria fleksibel yang dapat disesuaikan dengan kebutuhan 
                                              dan kondisi khusus daerah masing-masing."; 
                                        break;
                                    default: 
                                        echo "Kriteria PKH untuk menentukan kelayakan penerima bantuan program keluarga harapan.";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick="history.back()">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </button>
                    <a href="?module=kriteria&act=edit&id=<?php echo $r['id_kriteria']; ?>" class="btn btn-warning">
                        <i class="fa fa-edit"></i> Edit Kriteria
                    </a>
                </div>
            </div>
            <?php
        }
        break;
}
}
?>
