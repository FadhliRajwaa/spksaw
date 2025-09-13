<?php
session_start();

// Security check
if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
    echo "<link href=../css/style.css rel=stylesheet type=text/css>";
    echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
    exit;
}

// Include database connection
include "../../../configurasi/koneksi.php";

// Security function to prevent SQL injection
function anti_injection($data){
    global $koneksi;
    $filter = mysqli_real_escape_string($koneksi, stripslashes(strip_tags(htmlspecialchars($data, ENT_QUOTES))));
    return $filter;
}

$module = $_GET['act'];
$aksi = "../../media_admin.php?module=pembobotan"; // canonical redirect

// Ensure log table exists
mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS tbl_log_bobot (id INT AUTO_INCREMENT PRIMARY KEY,id_kriteria INT,kode_kriteria VARCHAR(10),old_nilai DECIMAL(10,4),new_nilai DECIMAL(10,4),jenis VARCHAR(10),aksi VARCHAR(20),username VARCHAR(50),created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");

function log_bobot($id,$kode,$old,$new,$jenis,$aksi_log){
    global $koneksi; $user = addslashes($_SESSION['username']??'system');
    mysqli_query($koneksi,"INSERT INTO tbl_log_bobot(id_kriteria,kode_kriteria,old_nilai,new_nilai,jenis,aksi,username) VALUES($id,'$kode',$old,$new,'$jenis','$aksi_log','$user')");
}

switch($module){
    case "insert":
        if ($_SESSION['leveluser']=='admin') {
            $keterangan = anti_injection($_POST['keterangan']);
            $nilai_persen = (int)$_POST['nilai'];
            $nilai = $nilai_persen/100;
            $jenis = anti_injection($_POST['jenis']);
            if (empty($keterangan) || $nilai_persen<=0 || $nilai_persen>100) { echo "<script>alert('Data tidak valid');window.location.href='$aksi';</script>";exit; }
            if (!in_array($jenis,['Benefit','Cost'])){echo "<script>alert('Jenis tidak valid');window.location.href='$aksi';</script>";exit;}
            $mx = mysqli_fetch_array(mysqli_query($koneksi,"SELECT MAX(CAST(SUBSTRING(kode_kriteria,2) AS UNSIGNED)) mx FROM tbl_kriteria"));
            $next = (int)$mx['mx'] + 1; $kode_kriteria = 'C'.$next;
            mysqli_query($koneksi, "INSERT INTO tbl_kriteria(kode_kriteria,keterangan,nilai,jenis,created_at,updated_at) VALUES('$kode_kriteria','$keterangan',$nilai,'$jenis',NOW(),NOW())");
            $new_id = mysqli_insert_id($koneksi);
            log_bobot($new_id,$kode_kriteria,0,$nilai,$jenis,'insert');
            $q = mysqli_fetch_array(mysqli_query($koneksi, "SELECT SUM(nilai) t FROM tbl_kriteria"));
            $total = $q['t']*100; $rounded=number_format($total,2);
            $msg = (abs($total-100)>0.01)? "Kriteria $kode_kriteria tersimpan. Total bobot sekarang = $rounded% (belum 100%)." : "Kriteria $kode_kriteria tersimpan. Total bobot = 100%.";
            echo "<script>alert('$msg');window.location.href='$aksi';</script>";exit;
        }
        break;
    case "hapus":
        if ($_SESSION['leveluser']=='admin') {
            $id=(int)$_GET['id'];
            $rowk = mysqli_fetch_array(mysqli_query($koneksi,"SELECT kode_kriteria,nilai,jenis FROM tbl_kriteria WHERE id_kriteria=$id"));
            log_bobot($id,$rowk['kode_kriteria'],$rowk['nilai'],0,$rowk['jenis'],'delete');
            mysqli_query($koneksi,"DELETE FROM tbl_himpunan WHERE id_kriteria=$id");
            mysqli_query($koneksi,"DELETE FROM tbl_kriteria WHERE id_kriteria=$id");
            $q = mysqli_fetch_array(mysqli_query($koneksi, "SELECT IFNULL(SUM(nilai),0) t FROM tbl_kriteria"));
            $total=$q['t']*100; $rounded=number_format($total,2);
            $msg = 'Kriteria '.($rowk['kode_kriteria']??'')." dihapus. Total bobot sekarang = $rounded%";
            $msg .= (abs($total-100)>0.01)?' (Belum 100%).':' (Sudah 100%).';
            echo "<script>alert('$msg');window.location.href='$aksi';</script>";exit;
        }
        break;
    case "update_pembobotan":
        if ($_SESSION['leveluser']=='admin') {
            $id_kriteria = (int)$_POST['id_kriteria'];
            $keterangan = anti_injection($_POST['keterangan']);
            $nilai_persen = (int)$_POST['nilai'];
            $nilai = $nilai_persen / 100;
            $jenis = anti_injection($_POST['jenis']);
            if (empty($keterangan) || $nilai_persen < 1 || $nilai_persen>100 || !in_array($jenis,['Benefit','Cost'])){ echo "<script>alert('Input tidak valid');window.location.href='$aksi';</script>";exit; }
            $cek = mysqli_fetch_array(mysqli_query($koneksi,"SELECT kode_kriteria,nilai,jenis FROM tbl_kriteria WHERE id_kriteria=$id_kriteria"));
            if(!$cek){ echo "<script>alert('Kriteria tidak ditemukan');window.location.href='$aksi';</script>";exit; }
            $old = $cek['nilai'];
            $update = mysqli_query($koneksi, "UPDATE tbl_kriteria SET keterangan='$keterangan', nilai=$nilai, jenis='$jenis', updated_at=NOW() WHERE id_kriteria=$id_kriteria");
            if($update){ log_bobot($id_kriteria,$cek['kode_kriteria'],$old,$nilai,$jenis,'update'); echo "<script>alert('Kriteria {$cek['kode_kriteria']} diperbarui: $nilai_persen% ($jenis)');window.location.href='$aksi';</script>";exit; }
            echo "<script>alert('Gagal update');window.location.href='$aksi';</script>";exit;
        }
        break;
    case "validasi_total_bobot":
        if ($_SESSION['leveluser']=='admin') {
            $total_query = mysqli_query($koneksi, "SELECT SUM(nilai) as total_bobot FROM tbl_kriteria WHERE nilai > 0");
            $total_data = mysqli_fetch_array($total_query);
            $total_bobot = round($total_data['total_bobot'] * 100, 2);
            $msg = (abs($total_bobot-100)<0.01)? "Total bobot = $total_bobot% (VALID)" : "Total bobot = $total_bobot% (BELUM 100%)";
            echo "<script>alert('$msg');window.location.href='$aksi';</script>";exit;
        }
        break;
    case "reset_bobot":
        if ($_SESSION['leveluser']=='admin') {
            // default distribution (sum 100)
            $defaults = [6,7,8,9,10,12,13,15,20];
            $res = mysqli_query($koneksi,"SELECT id_kriteria,kode_kriteria,jenis FROM tbl_kriteria ORDER BY kode_kriteria ASC");
            $i=0; while($row=mysqli_fetch_array($res)){ $old = $row['nilai']; $new = (isset($defaults[$i])?$defaults[$i]:0)/100; mysqli_query($koneksi,"UPDATE tbl_kriteria SET nilai=$new, updated_at=NOW() WHERE id_kriteria={$row['id_kriteria']}"); log_bobot($row['id_kriteria'],$row['kode_kriteria'],$old,$new,$row['jenis'],'reset'); $i++; }
            echo "<script>alert('Bobot berhasil direset ke distribusi default.');window.location.href='$aksi';</script>";exit;
        }
        break;
    default:
        // Hanya tampilkan pesan sekali, tidak loop
        echo "<script>if(!window.__aksiWarn){alert('Aksi tidak dikenal!');window.__aksiWarn=true;}window.location.href='$aksi';</script>";break;
}
?>
