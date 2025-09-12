<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
include "../../../configurasi/koneksi.php";
include "../../../configurasi/fungsi_thumb.php";
include "../../../configurasi/library.php";

$module=$_GET['module'];
$act=$_GET['act'];

// Input admin
if ($module=='siswa' AND $act=='input_siswa'){
  $lokasi_file    = $_FILES['fupload']['tmp_name'];
  $tipe_file      = $_FILES['fupload']['type'];
  $nama_file      = $_FILES['fupload']['name'];
  $direktori_file = "../../../foto_siswa/$nama_file";
  
  $cek_nis = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nim='$_POST[nim]'");
  $ketemu=mysqli_num_rows($cek_nis);

  $tgl_lahir=$_POST[thn].'-'.$_POST[bln].'-'.$_POST[tgl];

  //apabila nis tersedia dan ada foto
  if (empty($ketemu) AND !empty($lokasi_file)){
      if (file_exists($direktori_file)){
            echo "<script>window.alert('Nama file gambar sudah ada, mohon diganti dulu');
            window.location=(href='../../media_admin.php?module=siswa&act=tambahsiswa')</script>";
        }else{
            if($tipe_file != "image/jpeg" AND
               $tipe_file != "image/jpg"){
                    echo "<script>window.alert('Tipe File tidak di ijinkan.');
                    window.location=(href='../../media_admin.php?module=siswa&act=tambahsiswa')</script>";
                }else{
                UploadImage_siswa($nama_file);
                $pass=md5($_POST[password]);
                mysqli_query($koneksi, "INSERT INTO siswa(nim,
                                 nama_lengkap,
                                 username_login,
                                 password_login,
                                 id_kelas,
                                 alamat,
                                 tempat_lahir,
                                 tgl_lahir,
                                 jenis_kelamin,
                                 agama,
                                 nama_ayah,
                                 nama_ibu,
                                 th_masuk,
                                 email,
                                 no_telp,
                                 foto,
                                 blokir,
                                 id_session,
                                 id_session_soal)
	                       VALUES('$_POST[nim]',
                                '$_POST[nama_lengkap]',
                                '$_POST[username]',
                                '$pass',
                                '$_POST[id_kelas]',
                                '$_POST[alamat]',
                                '$_POST[tempat_lahir]',
                                '$tgl_lahir',
                                '$_POST[jk]',
                                '$_POST[agama]',
                                '$_POST[nama_ayah]',
                                '$_POST[nama_ibu]',
                                '$_POST[th_masuk]',
                                '$_POST[email]',
                                '$_POST[no_telp]',
                                '$nama_file',
                                '$_POST[blokir]',
                                '$_POST[nim]',
                                '$_POST[nim]')");
            }
            header('location:../../media_admin.php?message=success&module='.$module);
        }
        header('location:../../media_admin.php?module='.$module);
  }
  //apabila nis sudah ada dan foto tidak ada
  elseif(!empty($ketemu) AND empty($lokasi_file)){
      echo "<script>window.alert('nis sudah digunakan mohon ulangi.');
            window.location=(href='../../media_admin.php?module=siswa&act=tambahsiswa')</script>";
      }
  //apablia nis tersedia dan foto tidak ada
  elseif(empty($ketemu) AND empty($lokasi_file)){
    $pass=md5($_POST[password]);
    mysqli_query($koneksi, "INSERT INTO siswa(nim,
                                 nama_lengkap,
                                 username_login,
                                 password_login,
                                 id_kelas,
                             
                                 alamat,
                                 tempat_lahir,
                                 tgl_lahir,
                                 jenis_kelamin,
                                 agama,
                                 nama_ayah,
                                 nama_ibu,
                                 th_masuk,
                                 email,
                                 no_telp,
                                 blokir,
                                 id_session,
                                 id_session_soal)
	                       VALUES('$_POST[nim]',
                                '$_POST[nama_lengkap]',
                                '$_POST[username]',
                                '$pass',
                                '$_POST[id_kelas]',
                               
                                '$_POST[alamat]',
                                '$_POST[tempat_lahir]',
                                '$tgl_lahir',
                                '$_POST[jk]',
                                '$_POST[agama]',
                                '$_POST[nama_ayah]',
                                '$_POST[nama_ibu]',
                                '$_POST[th_masuk]',
                                '$_POST[email]',
                                '$_POST[no_telp]',
                                '$_POST[blokir]',
                                '$_POST[nim]',
                                '$_POST[nim]')");
            header('location:../../media_admin.php?message=success&module='.$module);
    }else{
       echo "<script>window.alert('nis sudah digunakan mohon ulangi.');
                window.location=(href='../../media_admin.php?module=siswa&act=tambahsiswa')</script>";
    }
}
 //updata siswa
 elseif ($module=='siswa' AND $act=='update_siswa'){
  $lokasi_file    = $_FILES['fupload']['tmp_name'];
  $tipe_file      = $_FILES['fupload']['type'];
  $nama_file      = $_FILES['fupload']['name'];
  $direktori_file = "../../../foto_siswa/$nama_file";

  $tgl_lahir=$_POST['thn'].'-'.$_POST['bln'].'-'.$_POST['tgl'];

  $cek_nis = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa = '$_POST[id]'");
  $ketemu=mysqli_fetch_array($cek_nis);

  if($_POST['nim']==$ketemu['nim']){

   //apabila foto tidak diubah dan password tidak di ubah
  if (empty($lokasi_file) AND empty($_POST['password'])){
      mysqli_query($koneksi, "UPDATE siswa SET
                        
                                  nama_lengkap    = '$_POST[nama]',
                                  username_login  = '$_POST[username]',
                                  id_kelas        = '$_POST[id_kelas]',
                                  
                                  alamat          = '$_POST[alamat]',
                                  tempat_lahir    = '$_POST[tempat_lahir]',
                                  tgl_lahir       = '$tgl_lahir',
                                  jenis_kelamin   = '$_POST[jk]',
                                  agama           = '$_POST[agama]',
                                  nama_ayah       = '$_POST[nama_ayah]',
                                  nama_ibu        = '$_POST[nama_ibu]',
                                  th_masuk        = '$_POST[th_masuk]',
                                  email           = '$_POST[email]',
                                  no_telp         = '$_POST[no_telp]',
                                  blokir          = '$_POST[blokir]',
                                  id_session      = '$_POST[nim]',
                                  id_session_soal = '$_POST[nim]'
                           WHERE  id_siswa        = '$_POST[id]'");
  
  }
  //apabila foto diubah dan password tidak diubah
  elseif(!empty($lokasi_file) AND empty($_POST['password'])){
	 
      if (file_exists($direktori_file)){
            echo "<script>window.alert('Nama file gambar sudah ada, mohon diganti dulu');
            window.location=(href='../../media_admin.php?module=siswa')</script>";
         }else{
            if($tipe_file != "image/jpeg" AND
               $tipe_file != "image/jpg"){
                     echo "<script>window.alert('Tipe File tidak di ijinkan.');
                     window.location=(href='../../media_admin.php?module=siswa')</script>";
            }else{
            $cek = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa = '$_POST[id]'");
            $r = mysqli_fetch_array($cek);

            if(!empty($r['foto'])){
            $img = "../../../foto_siswa/$r[foto]";
            unlink($img);
            $img2 = "../../../foto_siswa/medium_$r[foto]";
            unlink($img2);
            $img3 = "../../../foto_siswa/small_$r[foto]";
            unlink($img3);
            
            UploadImage_siswa($nama_file);
			echo $_POST['nim'];
            mysqli_query($koneksi, "UPDATE siswa SET
								  nama_lengkap    = '$_POST[nama]',
                                  username_login  = '$_POST[username]',
                                  id_kelas        = '$_POST[id_kelas]',
                                  alamat          = '$_POST[alamat]',
                                  tempat_lahir    = '$_POST[tempat_lahir]',
                                  tgl_lahir       = '$tgl_lahir',
                                  jenis_kelamin   = '$_POST[jk]',
                                  agama           = '$_POST[agama]',
                                  nama_ayah       = '$_POST[nama_ayah]',
                                  nama_ibu        = '$_POST[nama_ibu]',
                                  th_masuk        = '$_POST[th_masuk]',
                                  email           = '$_POST[email]',
                                  no_telp         = '$_POST[no_telp]',
                                  foto             = '$nama_file',
                                  blokir          = '$_POST[blokir]',
                                  id_session      = '$_POST[nim]',
                                  id_session_soal = '$_POST[nim]'
                           WHERE  id_siswa         = '$_POST[id]'");
            }else{
				 echo $nama_file;
                UploadImage_siswa($nama_file);
                mysqli_query($koneksi, "UPDATE siswa SET
                                  nama_lengkap    = '$_POST[nama]',
                                  username_login  = '$_POST[username]',
                                  id_kelas        = '$_POST[id_kelas]',
                                  alamat          = '$_POST[alamat]',
                                  tempat_lahir    = '$_POST[tempat_lahir]',
                                  tgl_lahir       = '$tgl_lahir',
                                  jenis_kelamin   = '$_POST[jk]',
                                  agama           = '$_POST[agama]',
                                  nama_ayah       = '$_POST[nama_ayah]',
                                  nama_ibu        = '$_POST[nama_ibu]',
                                  th_masuk        = '$_POST[th_masuk]',
                                  email           = '$_POST[email]',
                                  no_telp         = '$_POST[no_telp]',
                                  foto             = '$nama_file',
								  id_session      = '$_POST[nim]',
                                  id_session_soal = '$_POST[nim]'
                                 
                           WHERE  id_siswa         = '$_POST[id]'");
            }
         }
         }
  }
  //apabila foto tidak diubah dan password diubah
  elseif(empty($lokasi_file) AND !empty($_POST['password'])){
      $pass=md5($_POST['password']);
      mysqli_query($koneksi, "UPDATE siswa SET
                                 nama_lengkap    = '$_POST[nama]',
                                 username_login  = '$_POST[username]',
                                  password_login   = '$pass',
                                  id_kelas        = '$_POST[id_kelas]',
                                  alamat          = '$_POST[alamat]',
                                  tempat_lahir    = '$_POST[tempat_lahir]',
                                  tgl_lahir       = '$tgl_lahir',
                                  jenis_kelamin   = '$_POST[jk]',
                                  agama           = '$_POST[agama]',
                                  nama_ayah       = '$_POST[nama_ayah]',
                                  nama_ibu        = '$_POST[nama_ibu]',
                                  th_masuk        = '$_POST[th_masuk]',
                                  email           = '$_POST[email]',
                                  no_telp         = '$_POST[no_telp]',
								  id_session      = '$_POST[nim]',
                                  id_session_soal = '$_POST[nim]'
								  
                           WHERE  id_siswa         = '$_POST[id]'");
  }else{
      if (file_exists($direktori_file)){
            echo "<script>window.alert('Nama file gambar sudah ada, mohon diganti dulu');
            window.location=(href='../../media_admin.php?module=siswa')</script>";
         }else{
            if($tipe_file != "image/jpeg" AND
               $tipe_file != "image/jpg"){
                echo "<script>window.alert('Tipe File tidak di ijinkan.');
                window.location=(href='../../media_admin.php?module=siswa')</script>";
            }else{
            $cek = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa = '$_POST[id]'");
            $r = mysqli_fetch_array($cek);
            if(!empty($r['foto'])){
            $img = "../../../foto_siswa/$r[foto]";
            unlink($img);
            $img2 = "../../../foto_siswa/medium_$r[foto]";
            unlink($img2);
            $img3 = "../../../foto_siswa/small_$r[foto]";
            unlink($img3);

            UploadImage_siswa($nama_file);
            $pass=md5($_POST['password']);
            mysqli_query($koneksi, "UPDATE siswa SET
                                 
                                  nama_lengkap    = '$_POST[nama]',
                                  username_login  = '$_POST[username]',
								  password_login   = '$pass',
								  id_kelas        = '$_POST[id_kelas]',
                                  alamat          = '$_POST[alamat]',
                                  tempat_lahir    = '$_POST[tempat_lahir]',
                                  tgl_lahir       = '$tgl_lahir',
                                  jenis_kelamin   = '$_POST[jk]',
                                  agama           = '$_POST[agama]',
                                  nama_ayah       = '$_POST[nama_ayah]',
                                  nama_ibu        = '$_POST[nama_ibu]',
                                  th_masuk        = '$_POST[th_masuk]',
                                  email           = '$_POST[email]',
                                  no_telp         = '$_POST[no_telp]', 
                                  foto             = '$nama_file',
                                  id_session      = '$_POST[nim]',
                                  id_session_soal = '$_POST[nim]'
                           WHERE  id_siswa         = '$_POST[id]'");
            }
            else{
               UploadImage_siswa($nama_file);
               $pass=md5($_POST['password']);
               mysqli_query($koneksi, "UPDATE siswa SET
                             
                                  nama_lengkap    = '$_POST[nama]',
                                  username_login  = '$_POST[username]',
								  password_login   = '$pass',
								  id_kelas        = '$_POST[id_kelas]',
                                  alamat          = '$_POST[alamat]',
                                  tempat_lahir    = '$_POST[tempat_lahir]',
                                  tgl_lahir       = '$tgl_lahir',
                                  jenis_kelamin   = '$_POST[jk]',
                                  agama           = '$_POST[agama]',
                                  nama_ayah       = '$_POST[nama_ayah]',
                                  nama_ibu        = '$_POST[nama_ibu]',
                                  th_masuk        = '$_POST[th_masuk]',
                                  email           = '$_POST[email]',
                                  no_telp         = '$_POST[no_telp]', 
                                  foto             = '$nama_file',
                                  id_session      = '$_POST[nim]',
                                  id_session_soal = '$_POST[nim]'
                           WHERE  id_siswa        = '$_POST[id]'");
            }
            }
         }
  }
  header('location:../../media_admin.php?module='.$module);
  }
  elseif($_POST['nim']!= $ketemu['nim']){
      $cek_nim = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nim = '$_POST[nim]'");
      $c = mysqli_num_rows($cek_nim);
      //apabila nim tersedia
      if(empty($c)){
          //apabila foto tidak diubah dan password tidak di ubah
  if (empty($lokasi_file) AND empty($_POST[password])){
      mysqli_query($koneksi, "UPDATE siswa SET
                                  nim  = '$_POST[nim]',
                                  nama_lengkap    = '$_POST[nama]',
                                  username_login  = '$_POST[username]',
                                  id_kelas        = '$_POST[id_kelas]',
                                  
                                  alamat          = '$_POST[alamat]',
                                  tempat_lahir    = '$_POST[tempat_lahir]',
                                  tgl_lahir       = '$tgl_lahir',
                                  jenim_kelamin   = '$_POST[jk]',
                                  agama           = '$_POST[agama]',
                                  nama_ayah       = '$_POST[nama_ayah]',
                                  nama_ibu        = '$_POST[nama_ibu]',
                                  th_masuk        = '$_POST[th_masuk]',
                                  email           = '$_POST[email]',
                                  no_telp         = '$_POST[no_telp]',
                                  blokir          = '$_POST[blokir]',
                                  id_session      = '$_POST[nim]',
                                  id_session_soal = '$_POST[nim]'
                           WHERE  id_siswa        = '$_POST[id]'");

  }
  //apabila foto diubah dan password tidak diubah
  elseif(!empty($lokasi_file) AND empty($_POST[password])){
      if (file_exists($direktori_file)){
            echo "<script>window.alert('Nama file gambar sudah ada, mohon diganti dulu');
            window.location=(href='../../media_admin.php?module=siswa')</script>";
         }else{
            if($tipe_file != "image/jpeg" AND
               $tipe_file != "image/jpg"){
                     echo "<script>window.alert('Tipe File tidak di ijinkan.');
                     window.location=(href='../../media_admin.php?module=siswa')</script>";
            }else{
            $cek = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa = '$_POST[id]'");
            $r = mysqli_fetch_array($cek);

            if(!empty($r[foto])){
            $img = "../../../foto_siswa/$r[foto]";
            unlink($img);
            $img2 = "../../../foto_siswa/medium_$r[foto]";
            unlink($img2);
            $img3 = "../../../foto_siswa/small_$r[foto]";
            unlink($img3);

            UploadImage_siswa($nama_file);
            mysqli_query($koneksi, "UPDATE siswa SET
                                  nim              = '$_POST[nim]',
                                  nama_lengkap     = '$_POST[nama]',
                                  username_login   = '$_POST[username]',
                                  id_kelas         = '$_POST[id_kelas]',
                                  
                                  alamat           = '$_POST[alamat]',
                                  tempat_lahir    = '$_POST[tempat_lahir]',
                                  tgl_lahir       = '$tgl_lahir',
                                  jenim_kelamin    = '$_POST[jk]',
                                  agama           = '$_POST[agama]',
                                  nama_ayah        = '$_POST[nama_ayah]',
                                  nama_ibu         = '$_POST[nama_ibu]',
                                  th_masuk         = '$_POST[th_masuk]',
                                  email            = '$_POST[email]',
                                  no_telp          = '$_POST[no_telp]',
                                  foto             = '$nama_file',
                                  blokir           = '$_POST[blokir]',
                                  id_session       = '$_POST[nim]',
                                  id_session_soal  = '$_POST[nim]'
                           WHERE  id_siswa         = '$_POST[id]'");
            }else{
                UploadImage_siswa($nama_file);
                mysqli_query($koneksi, "UPDATE siswa SET
                                  nim              = '$_POST[nim]',
                                  nama_lengkap     = '$_POST[nama]',
                                  username_login   = '$_POST[username]',
                                  id_kelas         = '$_POST[id_kelas]',
                                  
                                  alamat           = '$_POST[alamat]',
                                  tempat_lahir    = '$_POST[tempat_lahir]',
                                  tgl_lahir       = '$tgl_lahir',
                                  jenim_kelamin    = '$_POST[jk]',
                                  agama           = '$_POST[agama]',
                                  nama_ayah        = '$_POST[nama_ayah]',
                                  nama_ibu         = '$_POST[nama_ibu]',
                                  th_masuk         = '$_POST[th_masuk]',
                                  email            = '$_POST[email]',
                                  no_telp          = '$_POST[no_telp]',
                                  foto             = '$nama_file',
                                  blokir           = '$_POST[blokir]',
                                  id_session       = '$_POST[nim]',
                                  id_session_soal  = '$_POST[nim]'
                           WHERE  id_siswa         = '$_POST[id]'");
            }
         }
         }
  }
  //apabila foto tidak diubah dan password diubah
  elseif(empty($lokasi_file) AND !empty($_POST[password])){
      $pass=md5($_POST[password]);
      mysqli_query($koneksi, "UPDATE siswa SET
                                  nim              = '$_POST[nim]',
                                  nama_lengkap     = '$_POST[nama]',
                                  username_login   = '$_POST[username]',
                                  password_login   = '$pass',
                                  id_kelas         = '$_POST[id_kelas]',
                                  
                                  alamat           = '$_POST[alamat]',
                                  tempat_lahir    = '$_POST[tempat_lahir]',
                                  tgl_lahir       = '$tgl_lahir',
                                  jenim_kelamin    = '$_POST[jk]',
                                  agama           = '$_POST[agama]',
                                  nama_ayah        = '$_POST[nama_ayah]',
                                  nama_ibu         = '$_POST[nama_ibu]',
                                  th_masuk         = '$_POST[th_masuk]',
                                  email            = '$_POST[email]',
                                  no_telp          = '$_POST[no_telp]',
                                  blokir           = '$_POST[blokir]',
                                  id_session       = '$_POST[nim]',
                                  id_session_soal  = '$_POST[nim]'
                           WHERE  id_siswa         = '$_POST[id]'");
  }else{
      if (file_exists($direktori_file)){
            echo "<script>window.alert('Nama file gambar sudah ada, mohon diganti dulu');
            window.location=(href='../../media_admin.php?module=siswa')</script>";
         }else{
            if($tipe_file != "image/jpeg" AND
               $tipe_file != "image/jpg"){
                echo "<script>window.alert('Tipe File tidak di ijinkan.');
                window.location=(href='../../media_admin.php?module=siswa')</script>";
            }else{
            $cek = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa = '$_POST[id]'");
            $r = mysqli_fetch_array($cek);
            if(!empty($r[foto])){
            $img = "../../../foto_siswa/$r[foto]";
            unlink($img);
            $img2 = "../../../foto_siswa/medium_$r[foto]";
            unlink($img2);
            $img3 = "../../../foto_siswa/small_$r[foto]";
            unlink($img3);

            UploadImage_siswa($nama_file);
            $pass=md5($_POST[password]);
            mysqli_query($koneksi, "UPDATE siswa SET
                                  nim              = '$_POST[nim]',
                                  nama_lengkap     = '$_POST[nama]',
                                  username_login   = '$_POST[username]',
                                  password_login   = '$pass',
                                  id_kelas         = '$_POST[id_kelas]',
                                  
                                  alamat           = '$_POST[alamat]',
                                  tempat_lahir    = '$_POST[tempat_lahir]',
                                  tgl_lahir       = '$tgl_lahir',
                                  jenim_kelamin    = '$_POST[jk]',
                                  agama           = '$_POST[agama]',
                                  nama_ayah        = '$_POST[nama_ayah]',
                                  nama_ibu         = '$_POST[nama_ibu]',
                                  th_masuk         = '$_POST[th_masuk]',
                                  email            = '$_POST[email]',
                                  no_telp          = '$_POST[no_telp]',
                                  foto             = '$nama_file',
                                  blokir           = '$_POST[blokir]',
                                  id_session       = '$_POST[nim]',
                                  id_session_soal  = '$_POST[nim]'
                           WHERE  id_siswa         = '$_POST[id]'");
            }
            else{
               UploadImage_siswa($nama_file);
               $pass=md5($_POST[password]);
               mysqli_query($koneksi, "UPDATE siswa SET
                                  nim              = '$_POST[nim]',
                                  nama_lengkap     = '$_POST[nama]',
                                  username_login   = '$_POST[username]',
                                  password_login   = '$pass',
                                  id_kelas         = '$_POST[id_kelas]',
                                  
                                  alamat           = '$_POST[alamat]',
                                  tempat_lahir    = '$_POST[tempat_lahir]',
                                  tgl_lahir       = '$tgl_lahir',
                                  jenim_kelamin    = '$_POST[jk]',
                                  agama           = '$_POST[agama]',
                                  nama_ayah        = '$_POST[nama_ayah]',
                                  nama_ibu         = '$_POST[nama_ibu]',
                                  th_masuk         = '$_POST[th_masuk]',
                                  email            = '$_POST[email]',
                                  no_telp          = '$_POST[no_telp]',
                                  foto             = '$nama_file',
                                  blokir           = '$_POST[blokir]',
                                  id_session       = '$_POST[nim]',
                                  id_session_soal  = '$_POST[nim]'
                           WHERE  id_siswa         = '$_POST[id]'");
            }
            }
         }
  }
  header('location:../../media_admin.php?module='.$module);
    }
      else{
        echo "<script>window.alert('nim sudah pernah digunakan.');
        window.location=(href='../../media_admin.php?module=siswa')</script>";
      }
  }
}

elseif ($module=='siswa' AND $act=='update_kelas_siswa'){
    mysqli_query($koneksi, "UPDATE siswa SET id_kelas         = '$_POST[id_kelas]'
                                WHERE  id_siswa    = '$_SESSION[idsiswa]'");

header('location:../../../media.php?module=kelas');
}

//Hapus Siswa
elseif ($module=='siswa' AND $act=='hapus'){
  mysqli_query($koneksi, "DELETE FROM siswa WHERE id_siswa = '$_GET[id]'");
  mysqli_query($koneksi, "DELETE FROM tbl_klasifikasi WHERE id_siswa = '$_GET[id]'");
  header('location:../../media_admin.php?module='.$module);
}

elseif ($module=='siswa' AND $act=='update_profil_siswa'){
  $lokasi_file    = $_FILES['fupload']['tmp_name'];
  $tipe_file      = $_FILES['fupload']['type'];
  $nama_file      = $_FILES['fupload']['name'];
  $direktori_file = "../../../foto_siswa/$nama_file";

  $tgl_lahir=$_POST[thn].'-'.$_POST[bln].'-'.$_POST[tgl];

  $cek_nim = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa = '$_POST[id]'");
  $ketemu=mysqli_fetch_array($cek_nim);

  if($_POST['nim']==$ketemu['nim']){

   //apabila foto tidak diubah
  if (empty($lokasi_file)){
      mysqli_query($koneksi, "UPDATE siswa SET
                                  nim  = '$_POST[nim]',
                                  nama_lengkap    = '$_POST[nama]',                                  
                                  alamat          = '$_POST[alamat]',
                                  tempat_lahir    = '$_POST[tempat_lahir]',
                                  tgl_lahir       = '$tgl_lahir',
                                  jenim_kelamin   = '$_POST[jk]',
                                  agama           = '$_POST[agama]',
                                  nama_ayah       = '$_POST[nama_ayah]',
                                  nama_ibu        = '$_POST[nama_ibu]',
                                  th_masuk        = '$_POST[th_masuk]',
                                  email           = '$_POST[email]',
                                  no_telp         = '$_POST[no_telp]',
                                  jabatan         = '$_POST[jabatan]',
                                  id_session      = '$_POST[nim]',
                                  id_session_soal = '$_POST[nim]'
                           WHERE  id_siswa        = '$_POST[id]'");

  }
  //apabila foto diubah
  elseif(!empty($lokasi_file)){
      if (file_exists($direktori_file)){
            echo "<script>window.alert('Nama file gambar sudah ada, mohon diganti dulu');
            window.location=(href='../../../media.php?module=siswa&act=detailprofilsiswa&id=$_SESSION[idsiswa]')</script>";
         }else{
            if($tipe_file != "image/jpeg" AND
               $tipe_file != "image/jpg"){
                     echo "<script>window.alert('Tipe File tidak di ijinkan.');
                     window.location=(href='../../../media.php?module=siswa&act=detailprofilsiswa&id=$_SESSION[idsiswa]')</script>";
            }else{
            $cek = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa = '$_POST[id]'");
            $r = mysqli_fetch_array($cek);

            if(!empty($r[foto])){
            $img = "../../../foto_siswa/$r[foto]";
            unlink($img);
            $img2 = "../../../foto_siswa/medium_$r[foto]";
            unlink($img2);
            $img3 = "../../../foto_siswa/small_$r[foto]";
            unlink($img3);

            UploadImage_siswa($nama_file);
            mysqli_query($koneksi, "UPDATE siswa SET
                                  nim              = '$_POST[nim]',
                                  nama_lengkap     = '$_POST[nama]',                                  
                                  alamat           = '$_POST[alamat]',
                                  tempat_lahir    = '$_POST[tempat_lahir]',
                                  tgl_lahir       = '$tgl_lahir',
                                  jenim_kelamin    = '$_POST[jk]',
                                  agama           = '$_POST[agama]',
                                  nama_ayah        = '$_POST[nama_ayah]',
                                  nama_ibu         = '$_POST[nama_ibu]',
                                  th_masuk         = '$_POST[th_masuk]',
                                  email            = '$_POST[email]',
                                  no_telp          = '$_POST[no_telp]',
                                  foto             = '$nama_file',
                                  jabatan         = '$_POST[jabatan]',
                                  id_session       = '$_POST[nim]',
                                  id_session_soal  = '$_POST[nim]'
                           WHERE  id_siswa         = '$_POST[id]'");
            }else{
                UploadImage_siswa($nama_file);
                mysqli_query($koneksi, "UPDATE siswa SET
                                  nim              = '$_POST[nim]',
                                  nama_lengkap     = '$_POST[nama]',                                  
                                  alamat           = '$_POST[alamat]',
                                  tempat_lahir    = '$_POST[tempat_lahir]',
                                  tgl_lahir       = '$tgl_lahir',
                                  jenim_kelamin    = '$_POST[jk]',
                                  agama           = '$_POST[agama]',
                                  nama_ayah        = '$_POST[nama_ayah]',
                                  nama_ibu         = '$_POST[nama_ibu]',
                                  th_masuk         = '$_POST[th_masuk]',
                                  email            = '$_POST[email]',
                                  no_telp          = '$_POST[no_telp]',
                                  foto             = '$nama_file',
                                  jabatan         = '$_POST[jabatan]',
                                  id_session       = '$_POST[nim]',
                                  id_session_soal  = '$_POST[nim]'
                           WHERE  id_siswa         = '$_POST[id]'");
            }
         }
         }
  }
  header('location:../../../media.php?module=siswa&act=detailprofilsiswa&id='.$_SESSION[idsiswa]);
  }
  elseif($_POST['nim']!= $ketemu['nim']){
      $cek_nim = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nim = '$_POST[nim]'");
      $c = mysqli_num_rows($cek_nim);
      //apabila nim tersedia
      if(empty($c)){
          //apabila foto tidak diubah
  if (empty($lokasi_file)){
      mysqli_query($koneksi, "UPDATE siswa SET
                                  nim  = '$_POST[nim]',
                                  nama_lengkap    = '$_POST[nama]',                                  
                                  alamat          = '$_POST[alamat]',
                                  tempat_lahir    = '$_POST[tempat_lahir]',
                                  tgl_lahir       = '$tgl_lahir',
                                  jenim_kelamin   = '$_POST[jk]',
                                  agama           = '$_POST[agama]',
                                  nama_ayah       = '$_POST[nama_ayah]',
                                  nama_ibu        = '$_POST[nama_ibu]',
                                  th_masuk        = '$_POST[th_masuk]',
                                  email           = '$_POST[email]',
                                  no_telp         = '$_POST[no_telp]',
                                  jabatan         = '$_POST[jabatan]',
                                  id_session      = '$_POST[nim]',
                                  id_session_soal = '$_POST[nim]'
                           WHERE  id_siswa        = '$_POST[id]'");

  }
  //apabila foto diubah
  elseif(!empty($lokasi_file)){
      if (file_exists($direktori_file)){
            echo "<script>window.alert('Nama file gambar sudah ada, mohon diganti dulu');
            window.location=(href='../../../media.php?module=siswa&act=detailprofilsiswa&id=$_SESSION[idsiswa]')</script>";
         }else{
            if($tipe_file != "image/jpeg" AND
               $tipe_file != "image/jpg"){
                     echo "<script>window.alert('Tipe File tidak di ijinkan.');
                     window.location=(href='../../../media.php?module=siswa&act=detailprofilsiswa&id=$_SESSION[idsiswa]')</script>";
            }else{
            $cek = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa = '$_POST[id]'");
            $r = mysqli_fetch_array($cek);

            if(!empty($r[foto])){
            $img = "../../../foto_siswa/$r[foto]";
            unlink($img);
            $img2 = "../../../foto_siswa/medium_$r[foto]";
            unlink($img2);
            $img3 = "../../../foto_siswa/small_$r[foto]";
            unlink($img3);

            UploadImage_siswa($nama_file);
            mysqli_query($koneksi, "UPDATE siswa SET
                                  nim              = '$_POST[nim]',
                                  nama_lengkap     = '$_POST[nama]',                                  
                                  alamat           = '$_POST[alamat]',
                                  tempat_lahir    = '$_POST[tempat_lahir]',
                                  tgl_lahir       = '$tgl_lahir',
                                  jenim_kelamin    = '$_POST[jk]',
                                  agama           = '$_POST[agama]',
                                  nama_ayah        = '$_POST[nama_ayah]',
                                  nama_ibu         = '$_POST[nama_ibu]',
                                  th_masuk         = '$_POST[th_masuk]',
                                  email            = '$_POST[email]',
                                  no_telp          = '$_POST[no_telp]',
                                  foto             = '$nama_file',
                                  jabatan         = '$_POST[jabatan]',
                                  id_session       = '$_POST[nim]',
                                  id_session_soal  = '$_POST[nim]'
                           WHERE  id_siswa         = '$_POST[id]'");
            }else{
                UploadImage_siswa($nama_file);
                mysqli_query($koneksi, "UPDATE siswa SET
                                  nim              = '$_POST[nim]',
                                  nama_lengkap     = '$_POST[nama]',                                  
                                  alamat           = '$_POST[alamat]',
                                  tempat_lahir    = '$_POST[tempat_lahir]',
                                  tgl_lahir       = '$tgl_lahir',
                                  jenim_kelamin    = '$_POST[jk]',
                                  agama           = '$_POST[agama]',
                                  nama_ayah        = '$_POST[nama_ayah]',
                                  nama_ibu         = '$_POST[nama_ibu]',
                                  th_masuk         = '$_POST[th_masuk]',
                                  email            = '$_POST[email]',
                                  no_telp          = '$_POST[no_telp]',
                                  foto             = '$nama_file',
                                  jabatan         = '$_POST[jabatan]',
                                  id_session       = '$_POST[nim]',
                                  id_session_soal  = '$_POST[nim]'
                           WHERE  id_siswa         = '$_POST[id]'");
            }
         }
         }
  }
  header('location:../../../media.php?module=siswa&act=detailprofilsiswa&id='.$_SESSION[idsiswa]);
    }
      else{
        echo "<script>window.alert('nim sudah pernah digunakan.');
        window.location=(href='../../../media.php?module=siswa&act=detailprofilsiswa&id=$_SESSION[idsiswa]')</script>";
      }
  }
}

elseif ($module=='siswa' AND $act=='update_account_siswa'){
    //jika username dan password tidak diubah
    if (empty($_POST[username]) AND empty($_POST[password])){
        header('location:../../../media.php?module=siswa&act=detailaccount');
    }
    //jika username diubah dan pasword tidak diubah
    elseif (!empty($_POST[username]) AND empty($_POST[password])){
        $username = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa = '$_SESSION[idsiswa]'");
        $data_username = mysqli_fetch_array($username);
           
        //jika username sama dengan username yang ada di datbase
        if ($_POST[username] == $data_username[username_login]){
        mysqli_query($koneksi, "UPDATE siswa SET username_login = '$_POST[username]'
                                  WHERE id_siswa     = '$_SESSION[idsiswa]'");

        echo "<script>window.alert('Username berhasil diubah');
                    window.location=(href='../../../media.php?module=home')</script>";
        }
        //jika username tidak sama username di database
        elseif ($_POST[username] != $data_username[username_login]){
            $username2 = mysqli_query($koneksi, "SELECT * FROM siswa WHERE username_login = '$_POST[username]'");
            $data_username2 = mysqli_num_rows($username2);
            //jika username tersedia
            if (empty($data_username2)){
                mysqli_query($koneksi, "UPDATE siswa SET username_login = '$_POST[username]'
                                  WHERE id_siswa     = '$_SESSION[idsiswa]'");

                echo "<script>window.alert('Username berhasil diubah');
                              window.location=(href='../../../media.php?module=home')</script>";
            }
            //jika username tiak tersedia
            else{
                echo "<script>window.alert('Username sudah digunakan mohon diganti');
                              window.location=(href='../../../media.php?module=siswa&act=detailaccount')</script>";
            }
        }
    }
    //jika username tidak di ubah dan pasword di ubah
    elseif (empty($_POST[username]) AND !empty($_POST[password])){
        $pass = md5($_POST[password]);
        mysqli_query($koneksi, "UPDATE siswa SET password_login = '$pass'
                                  WHERE id_siswa     = '$_SESSION[idsiswa]'");

        echo "<script>window.alert('Password berhasil diubah');
                    window.location=(href='../../../media.php?module=home')</script>";
    }
    //jika username di ubah dan password di ubah
    elseif (!empty($_POST[username]) AND !empty($_POST[password])){
        $username = mysqli_query($koneksi, "SELECT * FROM siswa WHERE username_login = '$_POST[username]'");
        $data_username = mysqli_fetch_array($username);
        //jika username sama dengan di database
        if ($_POST[username] == $data_username[username_login]){
        $pass = md5($_POST[password]);
        mysqli_query($koneksi, "UPDATE siswa SET username_login = '$_POST[username]',
                                      password_login = '$pass'
                                  WHERE id_siswa     = '$_SESSION[idsiswa]'");

        echo "<script>window.alert('Username & Password berhasil diubah');
                    window.location=(href='../../../media.php?module=home')</script>";
        }
        //jika username tidak sama dengan username di database
        elseif ($_POST[username] != $data_username[username_login]){
            $username2 = mysqli_query($koneksi, "SELECT * FROM siswa WHERE username_login = '$_POST[username]'");
            $data_username2 = mysqli_num_rows($username2);
            //jika username tersedia
            if (empty($data_username2)){
                $pass = md5($_POST[password]);
                mysqli_query($koneksi, "UPDATE siswa SET username_login = '$_POST[username]',
                                      password_login = '$pass'
                                  WHERE id_siswa     = '$_SESSION[idsiswa]'");

                echo "<script>window.alert('Username & Password berhasil diubah');
                                window.location=(href='../../../media.php?module=home')</script>";
            }
            //jika username tidak tersedia
            else{
                echo "<script>window.alert('Username sudah digunakan mohon diganti');
                              window.location=(href='../../../media.php?module=siswa&act=detailaccount')</script>";
            }
        }
    }

}

}
?>
