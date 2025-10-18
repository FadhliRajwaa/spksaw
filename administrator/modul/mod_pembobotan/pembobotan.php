<?php
if (session_status() !== PHP_SESSION_ACTIVE) { @session_start(); }
if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
    echo "<link href=../css/style.css rel=stylesheet type=text/css>";
    echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
    exit;
}
// Pastikan koneksi tersedia; gunakan path absolut dari file modul ini agar tidak gagal saat di-include
if (!isset($koneksi)) {
    require_once __DIR__ . "/../../../configurasi/koneksi.php";
}
$aksi="modul/mod_kriteria/aksi_pembobotan.php";

switch(@$_GET['act']){
    default:
        if ($_SESSION['leveluser']=='admin'){
            $tampil = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria ORDER BY id_kriteria");
            ?>
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-balance-scale"></i> Data Kriteria</h3>
                    <div class="box-tools pull-right">
                        <a href="?module=pembobotan&act=tambah" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Tambah Data</a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row" style="margin-bottom:10px;">
                        <div class="col-sm-6">
                            <a href="?module=pembobotan&act=tambah" class="btn btn-success btn-sm" id="btnTambahPembobotan"><i class="fa fa-plus"></i> Tambah Data</a>
                        </div>
                        <div class="col-sm-6 text-right">
                            <a href="modul/mod_kriteria/aksi_pembobotan.php?act=validasi_total_bobot" class="btn btn-warning btn-sm"><i class="fa fa-check"></i> Validasi Total</a>
                        </div>
                    </div>
                    <table id="tabel-bobot" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="55%">Nama Kriteria</th>
                                <th width="15%">Bobot (%)</th>
                                <th width="25%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $no=1; $totalBobot=0; while($r=mysqli_fetch_array($tampil)){ $bobot=intval($r['nilai']*100); $totalBobot+=$bobot; ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $r['keterangan']; ?></td>
                                <td class="bobot-col"><?= $bobot; ?></td>
                                <td>
                                    <a href="?module=pembobotan&act=edit&id=<?= $r['id_kriteria']; ?>" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i> Edit</a>
                                    <a href="modul/mod_kriteria/aksi_pembobotan.php?act=hapus&id=<?= $r['id_kriteria']; ?>" class="btn btn-xs btn-danger confirm-delete"><i class="fa fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <div class="row" style="margin-top:10px;">
                        <div class="col-sm-6">
                            <strong>Total Bobot Saat Ini: <span id="totalBobotLabel"><?= $totalBobot; ?>%</span></strong>
                            <span id="statusValid" class="label <?= ($totalBobot==100)?'label-success':'label-danger'; ?>" style="margin-left:8px;">
                                <?= ($totalBobot==100)?'VALID':'BELUM 100%'; ?>
                            </span>
                        </div>
                        <div class="col-sm-6 text-right">
                        </div>
                    </div>
                </div>
            </div>
            <script>
            if(window.jQuery && $.fn.DataTable){
                $('#tabel-bobot').DataTable({"pageLength":25,"ordering":true});
            }
            function updateTotalRealtime(){
                var sum=0; document.querySelectorAll('#tabel-bobot tbody .bobot-col').forEach(td=>{ var v=parseInt(td.textContent)||0; sum+=v; });
                var lbl=document.getElementById('totalBobotLabel'); if(lbl){ lbl.textContent=sum+'%'; }
                var st=document.getElementById('statusValid'); if(st){ if(sum===100){ st.className='label label-success'; st.textContent='VALID'; } else { st.className='label label-danger'; st.textContent='BELUM 100%'; } }
            }
            updateTotalRealtime();
            (function(){
              var bound=false;
              function bindSingleConfirm(){
                if(bound) return; bound=true;
                document.querySelectorAll('a.confirm-delete').forEach(function(a){
                  a.addEventListener('click', function(e){
                    if(this.dataset.confirmed==='1') return; // already confirmed
                    e.preventDefault();
                    if(confirm('Hapus kriteria ini? Data himpunan terkait juga akan dihapus.')){
                      this.dataset.confirmed='1';
                      window.location.href=this.href;
                    }
                  });
                });
              }
              if(document.readyState==='loading') document.addEventListener('DOMContentLoaded', bindSingleConfirm); else bindSingleConfirm();
            })();
            </script>
            <?php
        }
    break;

    case 'tambah':
        if ($_SESSION['leveluser']=='admin'){
            ?>
            <div class="box box-success box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-plus"></i> Tambah Kriteria</h3>
                </div>
                <form method="POST" action="modul/mod_kriteria/aksi_pembobotan.php?act=insert" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Nama Kriteria</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="keterangan" required placeholder="Masukkan nama/deskripsi kriteria"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Bobot (%)</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="number" name="nilai" class="form-control" min="1" max="100" value="10" required>
                                    <span class="input-group-addon">%</span>
                                </div>
                                <small class="text-muted">Pastikan total akhir 100%.</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Jenis</label>
                            <div class="col-sm-4">
                                <select name="jenis" class="form-control" required>
                                    <option value="Benefit">Benefit</option>
                                    <option value="Cost">Cost</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="?module=pembobotan" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                </form>
            </div>
            <?php
        }
    break;

    case 'edit':
        if ($_SESSION['leveluser']=='admin'){
            $e = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM tbl_kriteria WHERE id_kriteria='$_GET[id]'"));
            ?>
            <div class="box box-warning box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-edit"></i> Edit Bobot Kriteria</h3>
                </div>
                <form method="POST" action="<?= $aksi; ?>?act=update_pembobotan" class="form-horizontal" onsubmit="return cekTotalEdit();">
                    <input type="hidden" name="id_kriteria" value="<?= $e['id_kriteria']; ?>">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Nama Kriteria</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="keterangan" required><?= $e['keterangan']; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Bobot (%)</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="number" name="nilai" id="nilaiEdit" class="form-control" min="1" max="100" value="<?= ($e['nilai']*100); ?>" required>
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Jenis</label>
                            <div class="col-sm-4">
                                <select name="jenis" class="form-control" required>
                                    <option value="Benefit" <?= ($e['jenis']=='Benefit')?'selected':''; ?>>Benefit</option>
                                    <option value="Cost" <?= ($e['jenis']=='Cost')?'selected':''; ?>>Cost</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="?module=pembobotan" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn btn-warning"><i class="fa fa-save"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
            <script>
            function cekTotalEdit(){ return true; }
            </script>
            <?php
        }
    break;
}
?>
