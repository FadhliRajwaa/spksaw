<?php
require 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

include "../configurasi/koneksi.php";

// ambil data tabel (bisa copy dari kode tampilan tabel di atas)
ob_start();
?>
<h3 style="text-align:center;">Laporan Matrik SAW</h3>
<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <?php 
            $a = 1;
            $tampil_kriteria = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria");
            while($f= mysqli_fetch_array($tampil_kriteria)){
                echo "<th>C$a</th>";
                $a++;
            } 
            ?>
        </tr>
    </thead>
    <tbody>
    <?php 
    $no=1;
    $tampil_klasifikasi = mysqli_query($koneksi, "SELECT * FROM tbl_klasifikasi GROUP by id_siswa");
    while ($r=mysqli_fetch_array($tampil_klasifikasi)){
        $h = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa ='$r[id_siswa]'"));
        echo "<tr>
            <td>$no</td>
            <td>$h[nim]</td>
            <td>$h[nama_lengkap]</td>";
        $klasifikasi = mysqli_query($koneksi, "SELECT * FROM tbl_klasifikasi WHERE id_siswa = '$r[id_siswa]'");
        while ($n=mysqli_fetch_array($klasifikasi)){
            $himpunankriteria = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM tbl_himpunankriteria WHERE id_hk ='$n[id_hk]'"));
            echo "<td>{$himpunankriteria['nama']}</td>";
        }
        echo "</tr>";
        $no++;
    }
    ?>
    </tbody>
</table>
<?php
$html = ob_get_clean();

// Buat PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("laporan_saw.pdf", array("Attachment" => 1)); // 1 = download langsung, 0 = tampil di browser