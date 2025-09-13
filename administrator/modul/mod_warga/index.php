<?php
// File: administrator/modul/mod_warga/index.php
session_start();

// Security check
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
    echo "<link href=../../css/style.css rel=stylesheet type=text/css>";
    echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
    exit;
}

// Include database connection
include "../../../configurasi/koneksi.php";
include "../../../configurasi/library.php";
include "../../../configurasi/fungsi_indotgl.php";

?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Warga PKH</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"></i> Data Warga Penerima PKH</h3>
                    <div class="box-tools pull-right">
                        <a href="warga.php?module=warga&act=tambah" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Tambah Data Warga
                        </a>
                    </div>
                </div>
                
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped">
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
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM data_warga ORDER BY nama_lengkap ASC";
                                $result = mysqli_query($koneksi, $query);
                                $no = 1;
                                
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<tr>";
                                        echo "<td>$no</td>";
                                        echo "<td>" . htmlspecialchars($row['nama_lengkap']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['alamat']) . "</td>";
                                        echo "<td class='text-center'>" . $row['jumlah_lansia'] . "</td>";
                                        echo "<td class='text-center'>" . $row['jumlah_disabilitas_berat'] . "</td>";
                                        echo "<td class='text-center'>" . $row['jumlah_anak_sd'] . "</td>";
                                        echo "<td class='text-center'>" . $row['jumlah_anak_smp'] . "</td>";
                                        echo "<td class='text-center'>" . $row['jumlah_anak_sma'] . "</td>";
                                        echo "<td class='text-center'>" . $row['jumlah_balita'] . "</td>";
                                        echo "<td class='text-center'>" . $row['jumlah_ibu_hamil'] . "</td>";
                                        echo "<td class='text-center'>";
                                        echo "<a href='warga.php?module=warga&act=edit&id=" . $row['id_warga'] . "' class='btn btn-warning btn-xs' title='Edit Data'>";
                                        echo "<i class='fa fa-edit'></i> Edit</a> ";
                                        echo "<a href='javascript:confirmdelete(\"aksi_warga.php?act=hapus&id=" . $row['id_warga'] . "\")' class='btn btn-danger btn-xs' title='Hapus Data'>";
                                        echo "<i class='fa fa-trash'></i> Hapus</a>";
                                        echo "</td>";
                                        echo "</tr>";
                                        $no++;
                                    }
                                } else {
                                    echo "<tr><td colspan='11' class='text-center'>Belum ada data warga yang tersimpan</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="box-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Total Data: <?php echo mysqli_num_rows($result); ?> warga</strong>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="../../media_admin.php?module=home" class="btn btn-default">
                                <i class="fa fa-home"></i> Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="../../plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap -->
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>

<script>
function confirmdelete(delUrl) {
    if (confirm("Anda yakin ingin menghapus?")) {
        document.location = delUrl;
    }
}

$(function () {
    $("#example1").DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Indonesian.json"
        }
    });
});
</script>

</body>
</html>
