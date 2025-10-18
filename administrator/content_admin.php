<script>
function confirmdelete(delUrl) {
if (confirm("Anda yakin ingin menghapus?")) {
document.location = delUrl;
}
}
</script>



<?php
// DEBUG aid: when running locally, enable error display to diagnose blank content
if (getenv('DEBUG_LOCAL')) {
    @ini_set('display_errors', 1);
    @error_reporting(E_ALL);
}
if (session_status() !== PHP_SESSION_ACTIVE) { @session_start(); }
echo "<!-- DEBUG: module=" . htmlspecialchars($_GET['module'] ?? 'null') . ", leveluser=" . htmlspecialchars($_SESSION['leveluser'] ?? 'null') . " -->\n";
?>
<?php
require_once "../configurasi/koneksi.php";
require_once "../configurasi/library.php";
require_once "../configurasi/fungsi_indotgl.php";
require_once "../configurasi/fungsi_combobox.php";
require_once "../configurasi/class_paging.php";

$aksi_kelas="modul/mod_kelas/aksi_kelas.php";
$aksi_mapel="modul/mod_matapelajaran/aksi_matapelajaran.php";

// Bagian Home
if ($_GET['module']=='home' || !isset($_GET['module'])){
  if ($_SESSION['leveluser']=='admin'){
	?>
	<!-- DBG: entered home/admin branch -->
	
	<!-- Modern Welcome Section with Profile -->
	<div class="welcome-section">
		<div class="welcome-content">
			<div class="profile-header">
				<div class="profile-info">
					<div class="profile-avatar">
						<i class="fas fa-user-circle"></i>
					</div>
					<div class="profile-details">
						<h1 class="welcome-title">
							Selamat Datang, <?php echo $_SESSION['namalengkap']; ?>! 👋
						</h1>
						<div class="profile-meta">
							<span class="profile-role">
								<i class="fas fa-shield-alt"></i>
								Administrator PKH
							</span>
							<span class="profile-login-time">
								<i class="fas fa-clock"></i>
								Login: <?php echo date('d/m/Y H:i'); ?>
							</span>
						</div>
					</div>
				</div>
			</div>
			<p class="welcome-subtitle">
				Anda berada di Sistem Pendukung Keputusan Program Keluarga Harapan (PKH) 
				menggunakan metode <strong>MFEP (Multi Factor Evaluation Process)</strong>. Kelola data warga, kriteria penilaian, 
				dan lakukan perhitungan ranking untuk menentukan penerima bantuan PKH.
			</p>
		</div>
	</div>

	<!-- Modern Stats Grid - Enhanced Layout -->
	<style>
	.modern-stats-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
		gap: 2rem;
		margin: 2rem 0;
		padding: 0;
	}
	
	.stat-card {
		background: rgba(255, 255, 255, 0.95);
		border-radius: 16px;
		padding: 2rem;
		box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
		border: 1px solid rgba(255, 255, 255, 0.2);
		transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
		position: relative;
		overflow: hidden;
		backdrop-filter: blur(10px);
	}
	
	.stat-card::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		height: 4px;
		background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
		transition: height 0.3s ease;
	}
	
	.stat-card:hover {
		transform: translateY(-8px);
		box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
		border-color: rgba(102, 126, 234, 0.3);
	}
	
	.stat-card:hover::before {
		height: 6px;
	}
	
	.stat-card:nth-child(1)::before { background: linear-gradient(90deg, #667eea 0%, #764ba2 100%); }
	.stat-card:nth-child(2)::before { background: linear-gradient(90deg, #f093fb 0%, #f5576c 100%); }
	.stat-card:nth-child(3)::before { background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%); }
	.stat-card:nth-child(4)::before { background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%); }
	
	.stat-header {
		display: flex;
		justify-content: space-between;
		align-items: flex-start;
		margin-bottom: 1.5rem;
	}
	
	.stat-icon {
		width: 60px;
		height: 60px;
		border-radius: 16px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 1.5rem;
		color: #fff;
		box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
		transition: all 0.3s ease;
	}
	
	.stat-card:nth-child(1) .stat-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
	.stat-card:nth-child(2) .stat-icon { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
	.stat-card:nth-child(3) .stat-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
	.stat-card:nth-child(4) .stat-icon { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
	
	.stat-card:hover .stat-icon {
		transform: scale(1.1) rotate(5deg);
		box-shadow: 0 12px 32px rgba(0, 0, 0, 0.25);
	}
	
	.stat-value {
		font-size: 3rem;
		font-weight: 700;
		color: #2d3748;
		margin-bottom: 0.5rem;
		line-height: 1;
		text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
	}
	
	.stat-label {
		color: #718096;
		font-size: 1rem;
		font-weight: 600;
		margin-bottom: 1.5rem;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}
	
	.stat-link {
		color: #4a5568;
		text-decoration: none;
		font-size: 0.95rem;
		font-weight: 500;
		display: inline-flex;
		align-items: center;
		gap: 0.75rem;
		padding: 0.75rem 1.5rem;
		background: rgba(74, 85, 104, 0.1);
		border-radius: 12px;
		transition: all 0.3s ease;
		border: 1px solid transparent;
	}
	
	.stat-link:hover {
		color: #2d3748;
		background: rgba(74, 85, 104, 0.15);
		transform: translateX(4px);
		text-decoration: none;
		border-color: rgba(74, 85, 104, 0.2);
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
	}
	
	.stat-link i {
		transition: transform 0.3s ease;
	}
	
	.stat-link:hover i {
		transform: translateX(4px);
	}
	
	/* Mobile responsiveness */
	@media (max-width: 768px) {
		.modern-stats-grid {
			grid-template-columns: 1fr;
			gap: 1.5rem;
			margin: 1.5rem 0;
		}
		
		.stat-card {
			padding: 1.5rem;
		}
		
		.stat-value {
			font-size: 2.5rem;
		}
		
		.stat-icon {
			width: 50px;
			height: 50px;
			font-size: 1.25rem;
		}
	}
	
	@media (max-width: 480px) {
		.stat-value {
			font-size: 2rem;
		}
		
		.stat-link {
			padding: 0.5rem 1rem;
			font-size: 0.875rem;
		}
	}
	</style>

	<div class="modern-stats-grid">
		<div class="stat-card" style="background: white !important; box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;">
			<div class="stat-header">
				<div class="stat-icon">
					<i class="fas fa-users"></i>
				</div>
			</div>
			<?php $warga = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM data_warga")); ?>
			<div class="stat-value" style="color: #2d3748 !important;"><?php echo number_format($warga); ?></div>
			<div class="stat-label" style="color: #718096 !important;">Total Data Warga</div>
			<a href="?module=warga" class="stat-link" style="color: #4a5568 !important;">
				<span>Kelola Data Warga</span>
				<i class="fas fa-arrow-right"></i>
			</a>
		</div>

		<div class="stat-card" style="background: white !important; box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;">
			<div class="stat-header">
				<div class="stat-icon">
					<i class="fas fa-clipboard-list"></i>
				</div>
			</div>
			<?php $kriteria = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tbl_kriteria")); ?>
			<div class="stat-value" style="color: #2d3748 !important;"><?php echo number_format($kriteria); ?></div>
			<div class="stat-label" style="color: #718096 !important;">Kriteria Penilaian</div>
			<a href="?module=kriteria" class="stat-link" style="color: #4a5568 !important;">
				<span>Kelola Kriteria</span>
				<i class="fas fa-arrow-right"></i>
			</a>
		</div>

		<div class="stat-card" style="background: white !important; box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;">
			<div class="stat-header">
				<div class="stat-icon">
					<i class="fas fa-chart-line"></i>
				</div>
			</div>
			<?php $hasil = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tbl_hasil_saw")); ?>
			<div class="stat-value" style="color: #2d3748 !important;"><?php echo number_format($hasil); ?></div>
			<div class="stat-label" style="color: #718096 !important;">Hasil Perhitungan</div>
			<a href="?module=perankingan" class="stat-link" style="color: #4a5568 !important;">
				<span>Lihat Ranking</span>
				<i class="fas fa-arrow-right"></i>
			</a>
		</div>

		<div class="stat-card" style="background: white !important; box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;">
			<div class="stat-header">
				<div class="stat-icon">
					<i class="fas fa-calculator"></i>
				</div>
			</div>
			<?php 
			// Check if there's any calculation in progress
			$lastCalculation = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tbl_hasil_saw WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)");
			$recentCalc = mysqli_fetch_array($lastCalculation);
			?>
			<div class="stat-value" style="color: #2d3748 !important;"><?php echo number_format($recentCalc['total']); ?></div>
			<div class="stat-label" style="color: #718096 !important;">Perhitungan Hari Ini</div>
			<a href="?module=laporan" class="stat-link" style="color: #4a5568 !important;">
				<span>Hitung SAW</span>
				<i class="fas fa-arrow-right"></i>
			</a>
		</div>
	</div>
	
  <?php
  echo "<p align=right>Login : $hari_ini,
  <span id='date'></span>, <span id='clock'></span></p>
  </div>
 
 ";
  
  }
  elseif ($_SESSION['leveluser']=='pengajar'){
  echo "<p>Hai <b>$_SESSION[namalengkap]</b>,  selamat datang di halaman Dosen.<br>
          Silahkan klik menu pilihan yang berada di sebelah kiri untuk mengelola website.</p><br>";
		  if($_GET['message']=='success'){
			echo "<div class='success msg'>Data Tersimpan, Terimakasih Telah Melakukan Absensi Untuk Mata Kuliah ini <br>
				--Terima Kasih -- <i>IT LP3I Tasikmalaya </i>
			</div>";
			}
          echo "<p align=right>Login : $hari_ini,
                <span id='date'></span>, <span id='clock'></span></p>";
          //detail pengajar
          $detail_pengajar=mysqli_query($koneksi, "SELECT * FROM pengajar WHERE id_pengajar='$_SESSION[idpengajar]'");
          $p=mysqli_fetch_array($detail_pengajar);
          $tgl_lahir   = tgl_indo($p[tgl_lahir]);
          echo "<form><fieldset>
              <legend>Detail Profil Anda</legend>
              <dl class='inline'>
          <table id='table1' class='gtable sortable'>
          <tr><td rowspan='14'>";if ($p[foto]!=''){
              echo "<ul class='photos sortable'>
                    <li>
                    <img src='../foto_pengajar/medium_$p[foto]'>
                    <div class='links'>
                    <a href='../foto_pengajar/medium_$p[foto]' rel='facebox'>View</a>
                    <div>
                    </li>
                    </ul>";
          }echo "</td><td>Kode Dosen</td>  <td> : $p[kodedosen]</td><tr>
          <tr><td>Nama Lengkap</td> <td> : $p[nama_lengkap]</td></tr>
          <tr><td>Username</td>     <td> : $p[username_login]</td></tr>
          <tr><td>Alamat</td>       <td> : $p[alamat]</td></tr>
          <tr><td>Tempat Lahir</td> <td> : $p[tempat_lahir]</td></tr>
          <tr><td>Tanggal Lahir</td><td> : $tgl_lahir</td></tr>";
          if ($p[jenis_kelamin]=='P'){
           echo "<tr><td>Jenis Kelamin</td>     <td>  : Perempuan</td></tr>";
            }
            else{
           echo "<tr><td>Jenis kelamin</td>     <td> :  Laki - Laki </td></tr>";
            }echo"
          <tr><td>Agama</td>        <td> : $p[agama]</td></tr>
          <tr><td>No.Telp/HP</td>   <td> : $p[no_telp]</td></tr>
          <tr><td>E-mail</td>       <td> : $p[email]</td></tr>
          <tr><td>Website</td>      <td> : <a href=http://$p[website] target=_blank>$p[website]</a></td></tr>       
          <tr><td>Jabatan</td>      <td> : $p[jabatan]</td></tr>
          <tr><td>Aksi</td>         <td> : <input class='button small white' type=button value='Edit Profil' onclick=\"window.location.href='?module=admin&act=editpengajar';\"></td></tr>
          </table></dl></fieldset></form>";
		  $cekpa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT id_pengajar FROM Kelas WHERE id_pengajar='$_SESSION[idpengajar]'"));
		  if(empty($cekpa)){
			  
		  }else{
         //kelas yang diampu
         echo"<form><fieldset>
              <legend>Kelas Yang anda ampu</legend>
              <dl class='inline'>";
         // <input class='button small blue' type=button value='Tambah' onclick=\"window.location.href='?module=kelas&act=tambahkelas';\">";
         
         $tampil_kelas = mysqli_query($koneksi, "SELECT * FROM kelas WHERE id_pengajar = '$_SESSION[idpengajar]'");
         $ketemu=mysqli_num_rows($tampil_kelas);
         if (!empty($ketemu)){
                echo "<br><br><table id='table1' class='gtable sortable'><thead>
                <tr><th>No</th><th>Kelas</th><th>Pembimbing Akademik</th><th>Ketua Kelas</th><th>Aksi</th></tr></thead>";

                $no=1;
                while ($r=mysqli_fetch_array($tampil_kelas)){
                    echo "<tr><td>$no</td>                    
                    <td>$r[nama]</td>";

                    $pengajar = mysqli_query($koneksi, "SELECT * FROM pengajar WHERE id_pengajar = '$_SESSION[idpengajar]'");
                    $ada_pengajar = mysqli_num_rows($pengajar);
                    if(!empty($ada_pengajar)){
                    while($p=mysqli_fetch_array($pengajar)){
                            echo "<td><a href=?module=admin&act=detailpengajar&id=$r[id_pengajar] title='Detail Wali Kelas'>$p[nama_lengkap]</a></td>";
                    }
                    }else{
                            echo "<td></td>";
                    }

                    $siswa = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa = '$r[id_siswa]'");
                    $ada_siswa = mysqli_num_rows($siswa);
                    if(!empty($ada_siswa)){
                    while ($s=mysqli_fetch_array($siswa)){
                            echo"<td><a href=?module=siswa&act=detailsiswa&id=$s[id_siswa] title='Detail Siswa'>$s[nama_lengkap]</td>";
                     }
                    }else{
                            echo"<td></td>";
                    }
                    echo "
					<td>
                    <input class='button small white' type=button value='Lihat Siswa' onclick=\"window.location.href='?module=siswa&act=lihatmurid&id=$r[id_kelas]';\"> 
					 <a href='?module=absensi&act=laporanpa&id=$r[id_kelas]' class='button red' >Laporan PA</a>
                    </td>
					";
                $no++;
                }
                echo "</table></dl></fieldset></form>";
                }else{
                    echo"<br><br>Tidak ada kelas yang anda ampu";
                }
		  }
   //mata pelajaran
   $tanggal = gmdate("Y-m-d ",time()+60*60*7);
   $cek_jadwaltambahan = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM V_jadwaltambahan WHERE id_pengajar='$_SESSION[idpengajar]' and tanggalt='$tanggal'"));
   $tampil_matkul = mysqli_query($koneksi, "SELECT * FROM V_jadwaltambahan WHERE id_pengajar='$_SESSION[idpengajar]' and tanggalt='$tanggal'");

  if($cek_jadwaltambahan != 0){
   echo "<div class='information msg'>Hari ini Anda Memiliki $cek_jadwaltambahan Jadwal Tambahan Untuk Matakuliah <font color='green'>( "; 
   ?>
   
   <?php
   while ($matkul=mysqli_fetch_array($tampil_matkul)){
	   echo "$matkul[nama] ($matkul[nama_kelas]), ";
   }
   
   echo")</font><br><font color='red'>.::Abaikan Jika Anda Sudah Melakukan Absensi Jadwal Tambahan Untuk Mata Kuliah Tersebut::.</font> <br>
				<i>--Terima Kasih -- IT LP3I Tasikmalaya </i>
			</div>";
  }
   echo"<form><fieldset>
              <legend>Mata Kuliah Yang Anda Ampu</legend>
              <dl class='inline'>";
			  
   //<input type=button class='button small blue' value='Tambah' onclick=\"window.location.href='?module=matapelajaran&act=tambahmatapelajaran';\">";
   
  $tampil_pelajaran = mysqli_query($koneksi, "SELECT * FROM mata_kuliah WHERE id_pengajar = '$_SESSION[idpengajar]'");
  $cek_mapel = mysqli_num_rows($tampil_pelajaran);
  if (!empty($cek_mapel)){
    echo "<br><br><table id='table1' class='gtable sortable'><thead>
          <tr><th>No</th><th>Hari</th><th>Jam</th><th>Nama</th><th>Kelas</th><th>Dosen</th><th>Deskripsi</th><th>Aksi</th></tr></thead>";
    $no=1;
    while ($r=mysqli_fetch_array($tampil_pelajaran)){
       echo "<tr><td>$no</td> 
			 <td>$r[Hari]</td>
			 <td>$r[Jam]</td>			 
             <td>$r[nama]</td>";
             $kelas = mysqli_query($koneksi, "SELECT * FROM kelas WHERE id_kelas = '$r[id_kelas]'");
             $cek = mysqli_num_rows($kelas);
             if(!empty($cek)){
             while($k=mysqli_fetch_array($kelas)){
                 echo "<td><a href=?module=kelas&act=detailkelas&id=$r[id_kelas] title='Detail Kelas'>$k[nama]</td>";
             }
             }else{
                 echo"<td></td>";
             }
             $pengajar = mysqli_query($koneksi, "SELECT * FROM pengajar WHERE id_pengajar = '$r[id_pengajar]'");
             $cek_pengajar = mysqli_num_rows($pengajar);
             if(!empty($cek_pengajar)){
             while($p=mysqli_fetch_array($pengajar)){
             echo "<td><a href=?module=admin&act=detailpengajar&id=$r[id_pengajar] title='Detail Pengajar'>$p[nama_lengkap]</a></td>";
             }
             }else{
                 echo"<td></td>";
             }
             echo "<td>$r[deskripsi]</td>
             <td><a href='?module=absensi&id=$r[id]' title='Absensi'>Absensi</a> |
             <a href='?module=absensi&act=tambahabsensi&id=$r[id]' title='Tambahan'>Tambahan</a> | 
			 <a href='?module=absensi&act=pilihprttgl&id=$r[kodematkul]' title='Edit Absen' >Edit Absensi</a>";
      $no++;
    }
    echo "</table></dl></fieldset></form>";
  }else{
      echo"<br><br>Tidak Ada Mata Pelajaran Yang Di Ampu";
  }

		echo"
                <p>&nbsp;</p>";
 	}
        else{
             echo "<h2>Home</h2>
          <p>Hai <b>$_SESSION[namalengkap]</b>, selamat datang di E-Learning.</p>
          <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
          <p align=right>Login : $hari_ini, ";
  echo tgl_indo(date("Y m d"));
  echo " | ";
  echo date("H:i:s");
  echo " WIB</p>";
        }
}
// Bagian Modul
elseif ($_GET['module']=='modul'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_modul/modul.php";
  }
}
// Bagian user admin
elseif ($_GET['module']=='admin'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_admin/admin.php";
  }else{
      include "modul/mod_admin/admin.php";
  }
}

// Bagian Profil Administrator
elseif ($_GET['module']=='profil'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_profil/profil.php";
  }
}

// Bagian user admin
elseif ($_GET['module']=='detailpengajar'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_admin/admin.php";
  }else{
      include "modul/mod_admin/admin.php";
  }
}

// Bagian Data Warga PKH
elseif ($_GET['module']=='warga'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_warga/warga.php";
  }
}

// Bagian Data Kriteria PKH
elseif ($_GET['module']=='kriteria'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_kriteria/kriteria.php";
  }
}
// Bagian Pembobotan Kriteria PKH
elseif ($_GET['module']=='pembobotan'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_pembobotan/pembobotan.php";
  }
}
// Bagian Data Klasifikasi PKH - DEPRECATED (MFEP v3.0)
// Data klasifikasi sekarang terintegrasi di Data Warga
elseif ($_GET['module']=='klasifikasi'){
  if ($_SESSION['leveluser']=='admin'){
    // Redirect to warga module
    echo "<script>alert('Fitur Data Klasifikasi sudah tidak digunakan.\\nInput kriteria sekarang langsung di Data Warga.');
          window.location.href='?module=warga';</script>";
  }
}

// Bagian Laporan Hasil Perhitungan PKH (MFEP)
elseif ($_GET['module']=='laporan'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_laporan/laporan_mfep.php";
  }
}

// Bagian Perankingan PKH (MFEP)
elseif ($_GET['module']=='perankingan'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_perankingan/perankingan_mfep.php";
  }
}

// Bagian kelas
elseif ($_GET['module']=='kelas'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_kelas/kelas.php";
  }
  elseif ($_SESSION['leveluser']=='pengajar'){
      include "modul/mod_kelas/kelas.php";
  }
  elseif ($_SESSION['leveluser']=='siswa'){
      include "modul/mod_kelas/kelas.php";
  }

}


// Bagian siswa
elseif ($_GET['module']=='siswa'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_siswa/siswa.php";
  }else{
      include "modul/mod_siswa/siswa.php";
  }
}

// Bagian siswa
elseif ($_GET['module']=='daftarsiswa'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_siswa/siswa.php";
  }else{
      include "modul/mod_siswa/siswa.php";
  }
}

// Bagian siswa
elseif ($_GET['module']=='detailsiswa'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_siswa/siswa.php";
  }else{
      include "modul/mod_siswa/siswa.php";
  }
}

// Bagian siswa
elseif ($_GET['module']=='detailsiswapengajar'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_siswa/siswa.php";
  }else{
      include "modul/mod_siswa/siswa.php";
  }
}

// Bagian mata pelajaran
elseif ($_GET['module']=='matapelajaran'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_matapelajaran/matapelajaran.php";
  }
  else{
      include "modul/mod_matapelajaran/matapelajaran.php";
  }
}
// Bagian mata pelajaran
elseif ($_GET['module']=='ujian'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_ujian/ujian.php";
  }
  else{
      include "modul/mod_matapelajaran/matapelajaran.php";
  }
}

// Bagian materi
elseif ($_GET['module']=='materi'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_materi/materi.php";
  }else{
      include "modul/mod_materi/materi.php";
  }
}
// Bagian absen
elseif ($_GET['module']=='absensi'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_absen/absen.php";
  }else{
      include "modul/mod_absen/absen.php";
  }
}
// Bagian Jadwal Tambahan
elseif ($_GET['module']=='tambahan'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_tambahan/tambahan.php";
  }else{
      include "modul/mod_tambahan/tambahan.php";
  }
}

// Bagian topik soal
elseif ($_GET['module']=='quiz'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_quiz/quiz.php";
  }else{
      include "modul/mod_quiz/quiz.php";
  }
}

// Bagian topik soal
elseif ($_GET['module']=='buatquiz'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_quiz/quiz.php";
  }else{
      include "modul/mod_quiz/quiz.php";
  }
}

// Bagian topik soal
elseif ($_GET['module']=='buatquizesay'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_quiz/quiz.php";
  }else{
      include "modul/mod_quiz/quiz.php";
  }
}

// Bagian topik soal
elseif ($_GET['module']=='buatquizpilganda'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_quiz/quiz.php";
  }else{
      include "modul/mod_quiz/quiz.php";
  }
}

// Bagian topik soal
elseif ($_GET['module']=='daftarquiz'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_quiz/quiz.php";
  }else{
      include "modul/mod_quiz/quiz.php";
  }
}

// Bagian topik soal
elseif ($_GET['module']=='daftarquizesay'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_quiz/quiz.php";
  }else{
      include "modul/mod_quiz/quiz.php";
  }
}

// Bagian topik soal
elseif ($_GET['module']=='daftarquizpilganda'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_quiz/quiz.php";
  }else{
      include "modul/mod_quiz/quiz.php";
  }
}

// Bagian Templates
elseif ($_GET['module']=='templates'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_templates/templates.php";
  }
}

// Bagian Templates
elseif ($_GET['module']=='registrasi'){
  if ($_SESSION['leveluser']=='admin'){
    include "modul/mod_registrasi/registrasi.php";
  }
}
?>
