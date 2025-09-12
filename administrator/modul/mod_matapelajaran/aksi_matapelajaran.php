<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
include "../../../configurasi/koneksi.php";

$module=$_GET['module'];
$act=$_GET['act'];

// Input mapel
if ($module=='matapelajaran' AND $act=='input_matapelajaran'){
    mysqli_query($koneksi, "INSERT INTO tbl_kriteria(kriteria,
                                 bobot
								 )
	                       VALUES('$_POST[nm_kriteria]',
                                '$_POST[bobot]')");
  header('location:../../media_admin.php?module='.$module);
}


if ($module=='matapelajaran' AND $act=='input_himpunan'){
    mysqli_query($koneksi, "INSERT INTO tbl_himpunankriteria(id_kriteria,nama,keterangan, 
                                 nilai
								 )
	                       VALUES(
								'$_POST[id_kriteria]',
								'$_POST[nama]',
								'$_POST[ket]',
                                '$_POST[nilai]')");
  header('location:../../media_admin.php?module='.$module.'&act=listhimpunankriteria&id='.$_POST['id_kriteria']);
}



elseif ($module=='matapelajaran' AND $act=='update_matapelajaran'){
   mysqli_query($koneksi, "UPDATE tbl_kriteria SET kriteria  = '$_POST[nm_kriteria]',
                                          bobot   = '$_POST[bobot]' WHERE id='$_POST[id]'");
  header('location:../../media_admin.php?module='.$module);
}



elseif ($module=='matapelajaran' AND $act=='update_himpunan'){
   mysqli_query($koneksi, "UPDATE tbl_himpunankriteria SET nama  = '$_POST[nama]',keterangan  = '$_POST[ket]',  
											nilai   = '$_POST[nilai]' WHERE id_hk='$_POST[id_hk]'");
  header('location:../../media_admin.php?module='.$module.'&act=listhimpunankriteria&id='.$_POST['id_kriteria']);
}




elseif ($module=='matapelajaran' AND $act=='hapus'){
  mysqli_query($koneksi, "DELETE FROM tbl_kriteria WHERE id = '$_GET[id]'");
  mysqli_query($koneksi, "DELETE FROM tbl_himpunankriteria WHERE id_kriteria = '$_GET[id]'");
  header('location:../../media_admin.php?module='.$module);
}

elseif ($module=='matapelajaran' AND $act=='hapus_himpunan'){
  mysqli_query($koneksi, "DELETE FROM tbl_himpunankriteria WHERE id_hk = '$_GET[id]'");
  
  header('location:../../media_admin.php?module='.$module.'&act=listhimpunankriteria&id='.$_GET['id_kriteria']);
}

elseif ($module=='matapelajaran' AND $act=='input_klasifikasi'){
  
  $jumkriteria = $_POST['jumkriteria'];
  echo $jumkriteria;
  
  for ($i=1; $i<=$jumkriteria; $i++)
	{
		$idhk = $_POST['id_hk'.$i];
		//$idhk = $_POST['idhk'.$i];
		
		echo $idhk.'<br>';
		
		mysqli_query($koneksi, "INSERT INTO tbl_klasifikasi(id_siswa,
                                 id_hk
								 
								 
								 )
	                       VALUES('$_POST[id_siswa]',
                                '$idhk'
								 )");
  
		
	}
  header('location:../../media_admin.php?module='.$module.'&act=listhimpunankriteria&id='.$_GET['id_kriteria']);
}












elseif($module=='matapelajaran' AND $act=='input_ujian'){
		
		mysqli_query($koneksi, "INSERT INTO jadwalujian(
								 kodematkul,
                                 tglujian,
								 jenis,
                                 tingkat
								 )
	                       VALUES(
								'$_POST[kodematkul]',
                                '$_POST[tglujian]',
								'$_POST[jenis]',
                                '$_POST[tingkat]')");
  header('location:../../media_admin.php?module='.$module.'&act=jadwalujian');

}elseif($module=='matapelajaran' AND $act=='hapusujian'){
	 mysqli_query($koneksi, "DELETE FROM jadwalujian WHERE id_jadwalujian = '$_GET[id]'");
	 header('location:../../media_admin.php?module='.$module.'&act=jadwalujian');
}elseif($module=='matapelajaran' AND $act=='editujian'){
	 mysqli_query($koneksi, "UPDATE jadwalujian SET tglujian='$_POST[tglujian]' WHERE id_jadwalujian = '$_POST[id]'");
	 header('location:../../media_admin.php?module='.$module.'&act=jadwalujian');

}
}
?>
