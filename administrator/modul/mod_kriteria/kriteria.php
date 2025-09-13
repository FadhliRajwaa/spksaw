<?php
session_start();
if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
    echo "<link href=../css/style.css rel=stylesheet type=text/css>";
    echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
}
else{

$aksi="modul/mod_kriteria/aksi_kriteria.php";
switch($_GET['act']){
    // Tampil Data Kriteria (Himpunan) Saja
    default:
        if ($_SESSION['leveluser']=='admin'){
            $tampil_kriteria = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria ORDER BY id_kriteria");
            ?>
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-list"></i> Data Kriteria</h3>
                </div>
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="10%">No</th>
                                <th width="70%">Nama Kriteria</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                            $no = 1;
                            while ($r=mysqli_fetch_array($tampil_kriteria)){
                                echo "<tr>
                                        <td>$no</td>
                                        <td>$r[keterangan]</td>
                                        <td>
                                            <button class='btn btn-info btn-sm' onclick=\"window.location.href='?module=kriteria&act=tambahhimpunan&id=$r[id_kriteria]'\">
                                                Input Data Kriteria
                                            </button>
                                        </td>
                                      </tr>";
                                $no++;
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <script>
            if (window.jQuery && $.fn.DataTable) {
                $('#example1').DataTable({
                    "pageLength": 10,
                    "ordering": true,
                    "language": {"url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"}
                });
            }
            </script>
            <?php
        }
        break;

    case 'tambahhimpunan':
        if ($_SESSION['leveluser']=='admin'){
            $kriteria = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM tbl_kriteria WHERE id_kriteria='$_GET[id]'"));
            ?>
            <div class='col-md-8'>
              <div class='box box-danger box-solid'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Data Himpunan Kriteria <?= htmlspecialchars($kriteria['keterangan']); ?></h3>
                  <div class='box-tools pull-right'>
                    <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                  </div>
                </div>
                <div class='box-body'>
                  <form method='POST' action='modul/mod_kriteria/aksi_kriteria.php?act=add_himpunan' class='form-horizontal'>
                    <input type='hidden' name='id_kriteria' value='<?= $kriteria['id_kriteria']; ?>'>
                    <div class='form-group'>
                      <label class='col-sm-3 control-label'>Masukan Data</label>
                      <div class='col-sm-5'>
                        <input type='text' name='nama' class='form-control' placeholder='Masukan Data' required>
                      </div>
                    </div>
                    <div class='form-group'>
                      <label class='col-sm-3 control-label'>Keterangan</label>
                      <div class='col-sm-5'>
                        <input type='text' name='ket' class='form-control' placeholder='Keterangan' required>
                      </div>
                    </div>
                    <div class='form-group'>
                      <label class='col-sm-3 control-label'>Nilai</label>
                      <div class='col-sm-3'>
                        <input type='number' name='nilai' class='form-control' placeholder='Nilai' min='1' max='100' required>
                      </div>
                    </div>
                    <div class='buttons'>
                      <button type='submit' class='btn btn-primary'>Simpan</button>
                      <a href='?module=kriteria' class='btn btn-danger'>Batal</a>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <?php
        }
        break;

    case "input":
        if ($_SESSION['leveluser']=='admin'){
            $kriteria = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria WHERE id_kriteria='$_GET[id]'");
            $r = mysqli_fetch_array($kriteria);
            
            // Check if tbl_himpunan exists, if not create it
            $check_table = mysqli_query($koneksi, "SHOW TABLES LIKE 'tbl_himpunan'");
            if (mysqli_num_rows($check_table) == 0) {
                $create_table = mysqli_query($koneksi, "
                    CREATE TABLE tbl_himpunan (
                        id_himpunan int(11) NOT NULL AUTO_INCREMENT,
                        id_kriteria int(11) NOT NULL,
                        keterangan varchar(100) NOT NULL,
                        nilai int(11) NOT NULL,
                        PRIMARY KEY (id_himpunan),
                        KEY id_kriteria (id_kriteria)
                    )
                ");
            }
            
            // Get existing himpunan data for this criteria
            $himpunan = mysqli_query($koneksi, "SELECT * FROM tbl_himpunan WHERE id_kriteria='$_GET[id]' ORDER BY nilai DESC");
            ?>
            <style>
            /* Disable DataTables on this page */
            .no-datatables table {
                width: 100% !important;
            }
            </style>
            
            <div class="row no-datatables">
                <!-- Left Column: Kriteria Info -->
                <div class="col-md-6">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-info-circle"></i> <?php echo $r['keterangan']; ?></h3>
                        </div>
                        <div class="box-body">
                            <p><strong>Kode:</strong> <?php echo $r['kode_kriteria']; ?></p>
                            <p><strong>Jenis:</strong> <?php echo $r['jenis']; ?></p>
                            <p><strong>Bobot:</strong> <?php echo ($r['nilai'] * 100); ?>%</p>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column: Himpunan Data -->
                <div class="col-md-6">
                    <div class="box box-success box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-table"></i> Data Himpunan Kriteria <?php echo $r['keterangan']; ?></h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addModal">
                                <i class="fa fa-plus"></i> Tambah Data
                            </button>
                            <br><br>
                            
                            <table class="table table-bordered table-striped himpunan-table">
                                <thead>
                                    <tr>
                                        <th width="15%">No</th>
                                        <th width="25%">Keterangan</th>
                                        <th width="20%">Nilai</th>
                                        <th width="40%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    $no = 1;
                                    if (mysqli_num_rows($himpunan) > 0) {
                                        while ($h = mysqli_fetch_array($himpunan)){
                                            echo "<tr>
                                                    <td>$no</td>
                                                    <td>$h[keterangan]</td>
                                                    <td>$h[nilai]</td>
                                                    <td>
                                                        <button class='btn btn-warning btn-xs' onclick=\"editData($h[id_himpunan], '$h[keterangan]', '$h[nilai]')\">
                                                            <i class='fa fa-edit'></i> Edit
                                                        </button>
                                                        <button class='btn btn-danger btn-xs' onclick=\"deleteData($h[id_himpunan])\">
                                                            <i class='fa fa-trash'></i> Hapus
                                                        </button>
                                                    </td>
                                                </tr>";
                                            $no++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='4' class='text-center'>Belum ada data himpunan</td></tr>";
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="box-footer">
                            <button type="button" class="btn btn-default" onclick="history.back()">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Modal -->
            <div class="modal fade" id="addModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="POST" action="<?php echo $aksi; ?>?act=add_himpunan">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Tambah Data Himpunan</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_kriteria" value="<?php echo $_GET['id']; ?>">
                                
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Sangat Baik" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Nilai</label>
                                    <input type="number" name="nilai" class="form-control" placeholder="Contoh: 5" min="1" max="10" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="POST" action="<?php echo $aksi; ?>?act=update_himpunan">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Edit Data Himpunan</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_himpunan" id="edit_id">
                                <input type="hidden" name="id_kriteria" value="<?php echo $_GET['id']; ?>">
                                
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <input type="text" name="keterangan" id="edit_keterangan" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Nilai</label>
                                    <input type="number" name="nilai" id="edit_nilai" class="form-control" min="1" max="10" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-warning">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
            // Wait for document ready and jQuery to load
            document.addEventListener('DOMContentLoaded', function() {
                // Disable DataTables initialization on this page if jQuery and DataTables are available
                if (typeof $ !== 'undefined' && $.fn.DataTable) {
                    $('.himpunan-table').removeClass('table-striped');
                    $.fn.dataTable.ext.errMode = 'none';
                }
            });
            
            function editData(id, keterangan, nilai) {
                if (typeof $ !== 'undefined') {
                    $('#edit_id').val(id);
                    $('#edit_keterangan').val(keterangan);
                    $('#edit_nilai').val(nilai);
                    $('#editModal').modal('show');
                } else {
                    // Fallback if jQuery is not available
                    document.getElementById('edit_id').value = id;
                    document.getElementById('edit_keterangan').value = keterangan;
                    document.getElementById('edit_nilai').value = nilai;
                }
            }

            function deleteData(id) {
                if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    window.location.href = '<?php echo $aksi; ?>?act=delete_himpunan&id=' + id + '&kriteria_id=<?php echo $_GET['id']; ?>';
                }
            }
            </script>
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
                                           value="<?php echo ($r['nilai'] * 100); ?>" 
                                           min="1" max="100" step="1" required>
                                    <span class="input-group-addon">%</span>
                                </div>
                                <small class="text-muted">Bobot kriteria antara 1% - 100%. Semakin tinggi semakin penting dalam penilaian PKH.</small>
                                <small class="text-info">Contoh: Lansia = 15%, Disabilitas = 20%, Anak SD = 15%, dll. Total semua kriteria harus = 100%</small>
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
