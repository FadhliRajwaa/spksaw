<?php
if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
    echo "<link href=../css/style.css rel=stylesheet type=text/css>";
    echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
}
else{
    echo "<div class='box box-primary box-solid'>
            <div class='box-header with-border'>
                <h3 class='box-title'><i class='fa fa-bar-chart'></i> Laporan Hasil Analisa SAW PKH - TEST</h3>
            </div>
            <div class='box-body'>
                <div class='alert alert-success'>
                    <h4>✅ Module Laporan berhasil dimuat!</h4>
                    <p>Ini adalah halaman test untuk module laporan. Jika Anda melihat pesan ini, berarti routing module sudah bekerja dengan baik.</p>
                </div>
            </div>
          </div>";
}
?>
