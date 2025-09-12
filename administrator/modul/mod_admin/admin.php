
<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href=../css/style.css rel=stylesheet type=text/css>";
  echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
}
else{

$aksi="modul/mod_admin/aksi_admin.php";
switch($_GET[act]){
  // Tampil User
  default:
    if ($_SESSION[leveluser]=='admin'){
      $tampil_admin = mysqli_query($koneksi, "SELECT * FROM admin ORDER BY username");
	  ?>
	   <div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">Administrator</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class="box-body">
					<a  class ='btn  btn-success btn-flat' href='?module=admin&act=tambahadmin'>Tambah Administrator </a>
					<br><br><br>
					<table id="example1" class="table table-bordered table-striped" >
						<thead>
							<tr>
								<th>No</th>
								<th>Username</th>
								<th>Nama</th>
								
								<th>Level</th>
								<th>Alamat</th>
								<th>Email</th>
								<th>Telp/HP</th>
								<th>Blokir</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
						<?php 
							$no=1;
							while ($r=mysqli_fetch_array($tampil_admin)){
							   echo "<tr><td>$no</td>
									 <td>$r[username]</td>
									 <td>$r[nama_lengkap]</td>
									 <td>$r[level]</td>  
									 <td>$r[alamat]</td>
										 <td><a href=mailto:$r[email]>$r[email]</a></td>
										 <td>$r[no_telp]</td>
										 <td align=center>$r[blokir]</td>
									 <td><a href='?module=admin&act=editadmin&id=$r[id_session]' title='Edit'><img src='images/icons/edit.png' alt='Edit' /></a></td></tr>";
							  $no++;
							}
						echo "</tbody></table>";
					?>
				</div>
			</div>	
		
	  
	  <?php
    }
    else{
      echo "<link href=../css/style.css rel=stylesheet type=text/css>";
      echo "<div class='error msg'>Anda tidak berhak mengakses halaman ini.</div>";
    }
    break;

  case "pengajar":
  if ($_SESSION[leveluser]=='admin' || $_SESSION['leveluser']=='pendidikan'){
      $tampil_pengajar = mysqli_query($koneksi, "SELECT * FROM pengajar ORDER BY username_login");
   ?>
		<div class="box box-primary box-solid">
			<div class="box-header with-border">
				<h3 class="box-title">Data Pengajar</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				</div><!-- /.box-tools -->
			</div>
			<div class="box-body">
				<a  class ='btn  btn-success btn-flat' href='?module=admin&act=tambahpengajar'>Tambah Data </a>
				<br><br><br>
				
				<table id="example1" class="table table-bordered table-striped" >	
					<thead>
							<tr>
								<th>No</th>
								<th>Kode Pengajar</th>
								<th>Nama Lengkap</th>
								<th>Username</th>
								<th>Blokir</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						$no=1;
						while ($r=mysqli_fetch_array($tampil_pengajar)){
						   echo "<tr><td>$no</td>
								 <td>$r[kodedosen]</td>
								 <td>$r[nama_lengkap]</td>
											
								 <td>$r[username_login]</td>             
								 <td align=center>$r[blokir]</td>
								 <td><a href='?module=admin&act=editpengajar&id=$r[id_pengajar]' title='Edit' class='btn btn-primary btn-xs'>Edit</a> 
									 <a href=?module=detailpengajar&act=detailpengajar&id=$r[id_pengajar] class='btn btn-success btn-xs'>Detail</a></td></tr>";
						  $no++;
						}
						echo "</tbody></table>";
					?>
				</table>
				
   <?php
  }else{
        echo "<link href=../css/style.css rel=stylesheet type=text/css>";
        echo "<div class='error msg'>Anda tidak berhak mengakses halaman ini.</div>";
  }
  break;

  case "tambahadmin":
    if ($_SESSION[leveluser]=='admin' ){
			echo "
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>Tambah Data Administrator</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
						<form method=POST action='$aksi?module=admin&act=input_admin' class='form-horizontal'>
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Username</label>        		
									 <div class='col-sm-4'>
										<input type=text name='username' class='form-control' Placeholder='Username' required='required'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Password</label>        		
									 <div class='col-sm-3'>
										<input type=text name='password' class='form-control' Placeholder='Password' required='required'>
									 </div>
							  </div>
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Nama Lengkap</label>        		
									 <div class='col-sm-6'>
										<input type=text name='nama_lengkap' class='form-control' Placeholder='nama_lengkap' required='required'>
									 </div>
							  </div>
							   <div class='form-group'>
									<label class='col-sm-2 control-label'>Alamat</label>        		
									 <div class='col-sm-6'>
										<input type=text name='alamat' class='form-control' Placeholder='alamat' required='required'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Email</label>        		
									 <div class='col-sm-4'>
										<input type=text name='email' class='form-control' Placeholder='Email' required='required'>
									 </div>
							  </div>
							  
							   <div class='form-group'>
									<label class='col-sm-2 control-label'>Telp/HP</label>        		
									 <div class='col-sm-4'>
										<input type=text name='no_telp' class='form-control' Placeholder='No Telepon' required='required'>
									 </div>
							  </div>
							   
							 <div class='form-group'>
									<label class='col-sm-2 control-label'>Level</label>        		
									 <div class='col-sm-4'>
										<select name='level' class='form-control'> 
											<option value='admin'>Administrator</option>
										</select>
									 </div>
							  </div>
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Blokir</label>        		
									 <div class='col-sm-4'>
										<input type=radio name='blokir' value='Y' checked>Y 
										<input type=radio name='blokir' value='N'> N
									 </div>
							  </div>
							
							  
							  
							  <div class='buttons'>
							  <input class='btn btn-primary' type=submit value=Simpan>
							  <input class='btn btn-danger' type=button value=Batal onclick=self.history.back()>
							  </div>
							  </form>
							  
				</div> 
				
			</div>";
    }
    else{
      echo "<link href=../css/style.css rel=stylesheet type=text/css>";
      echo "<div class='error msg'>Anda tidak berhak mengakses halaman ini.</div>";
    }
     break;

  case "tambahpengajar":
    if ($_SESSION[leveluser]=='admin' || $_SESSION['leveluser']=='pendidikan'){
    
		echo "
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>Tambah Data Pengajar</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
						<form method=POST action='$aksi?module=admin&act=input_pengajar' enctype='multipart/form-data' class='form-horizontal'>
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Kode Pengajar</label>        		
									 <div class='col-sm-4'>
										<input type=text name='kodedosen' class='form-control' Placeholder='Kode Pengajar' required='required'>
									 </div>
							  </div>
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Nama Lengkap</label>        		
									 <div class='col-sm-6'>
										<input type=text name='nama_lengkap' class='form-control' Placeholder='Nama Lengkap' required='required'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Username</label>        		
									 <div class='col-sm-5'>
										<input type=text name='username' class='form-control' Placeholder='Username' required='required' >
									 </div>
							  </div>
							 
							 <div class='form-group'>
									<label class='col-sm-2 control-label'>Password</label>        		
									 <div class='col-sm-5'>
										<input type=text name='password' class='form-control' Placeholder='Password' required='required'>
									 </div>
							  </div>
							  
							 
							 
							 <div class='form-group'>
									<label class='col-sm-2 control-label'>Alamat</label>        		
									 <div class='col-sm-9'>
										<input type=text name='alamat' class='form-control' Placeholder='Alamat' required='required'>
									 </div>
							  </div>
							 
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Tempat Lahir</label>        		
									 <div class='col-sm-4'>
										<input type=text name='tempat_lahir' class='form-control' Placeholder='Tempat Lahir' required='required' >
									 </div>
							  </div>
							 <div class='form-group'>
									<label class='col-sm-2 control-label'>Tanggal Lahir</label>        		
									 <div class='col-sm-2'>";
										combotgl(1,31,'tgl',$tgl_skrg);
								echo"</div>
									<div class='col-sm-3'>";
										 combonamabln(1,12,'bln',$bln_sekarang);
								echo"</div>
									<div class='col-sm-2'>";
										 combothn(1950,$thn_sekarang,'thn',$thn_sekarang);
								echo"</div>
							  </div>
							    <div class='form-group'>
									<label class='col-sm-2 control-label'>Jenis Kelamin</label>        		
									 <div class='col-sm-4'>
										
										<input type=radio name='jk' value='L'>Laki - Laki</input>
										<input type=radio name='jk' value='P'>Perempuan</input>
										
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Agama</label>        		
									 <div class='col-sm-4'>
											<select name='agama' class='form-control'>
											   <option value='0' selected>--Pilih Agama--</option>
											   <option value='Islam'>Islam</option>
											   <option value='Kristen'>Kristen</option>
											   <option value='Katolik'>Katolik</option>
											   <option value='Hindu'>Hindu</option>
											   <option value='Buddha'>Buddha</option>
											</select>
									 </div>
							  </div>
							 
								
							 
							 
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>No HP/Tlp</label>        		
									 <div class='col-sm-4'>
										<input type=text name='no_telp' class='form-control' Placeholder='No HP' required='required'>
									 </div>
							   </div>
							   
							    <div class='form-group'>
									<label class='col-sm-2 control-label'>Email</label>        		
									 <div class='col-sm-5'>
										<input type=text name='email' class='form-control'  Placeholder='Email' >
									 </div>
							   </div>
							   
							    <div class='form-group'>
									<label class='col-sm-2 control-label'>Website</label>        		
									 <div class='col-sm-5'>
										<input type=text name='website' size=30 value='http://' class='form-control'>
									 </div>
							   </div>
							   
							   <div class='form-group'>
									<label class='col-sm-2 control-label'>Foto</label>        		
									 <div class='col-sm-4'>
										<input type=file name='fupload' size=40 class>
										<small>Tipe foto harus JPG/JPEG dan ukuran lebar maks: 400 px</small>									
									</div>
									 
							   </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Jabatan</label>        		
									 <div class='col-sm-3'>
										<input type=text name='jabatan' class='form-control' Placeholder='Jabatan' Lahir' required='required' >
									 </div>
							  </div>
							  
							  
							   <div class='form-group'>
									<label class='col-sm-2 control-label'>Blokir</label>        		
									 <div class='col-sm-4'>
										<input type=radio name='blokir' value='Y'> Y
										<input type=radio name='blokir' value='N' checked> N
									 </div>
							   </div>
							  
							
							  
							  
							  <div class='buttons'>
							  <input class='btn btn-primary' type=submit value=Simpan>
							  <input class='btn btn-danger' type=button value=Batal onclick=self.history.back()>
							  </div>
							  </form>
							  
				</div> 
				
			</div>";
	
	}else{
      echo "<link href=../css/style.css rel=stylesheet type=text/css>";
      echo "<div class='error msg'>Anda tidak berhak mengakses halaman ini.</div>";
    }
     break;

  case "editadmin":
    $edit=mysqli_query($koneksi, "SELECT * FROM admin WHERE id_session='$_GET[id]'");
    $r=mysqli_fetch_array($edit);

    if ($_SESSION[leveluser]=='admin'){
    echo "
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>Tambah Data Administrator</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
						<form method=POST action='$aksi?module=admin&act=update_admin' class='form-horizontal'>
							  <input type=hidden name=id value='$r[id_session]'>
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Username</label>        		
									 <div class='col-sm-4'>
										<input type=text name='username' class='form-control' Placeholder='Username' value='$r[username]'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Password</label>        		
									 <div class='col-sm-3'>
										<input type=text name='password' class='form-control' Placeholder='Password' >
										<small>Apabila password tidak diubah, dikosongkan saja.</small>
									</div>
							  </div>
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Nama Lengkap</label>        		
									 <div class='col-sm-6'>
										<input type=text name='nama_lengkap' class='form-control' Placeholder='nama_lengkap' value='$r[nama_lengkap]'>
									 </div>
							  </div>
							   <div class='form-group'>
									<label class='col-sm-2 control-label'>Alamat</label>        		
									 <div class='col-sm-6'>
										<input type=text name='alamat' class='form-control' Placeholder='alamat' value='$r[alamat]'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Email</label>        		
									 <div class='col-sm-4'>
										<input type=text name='email' class='form-control' Placeholder='Email' value='$r[email]'>
									 </div>
							  </div>
							  
							   <div class='form-group'>
									<label class='col-sm-2 control-label'>Telp/HP</label>        		
									 <div class='col-sm-4'>
										<input type=text name='no_telp' class='form-control' Placeholder='No Telepon' value='$r[no_telp]'>
									 </div>
							  </div>
							   
							 <div class='form-group'>
									<label class='col-sm-2 control-label'>Level</label>        		
									 <div class='col-sm-4'>
										<select name='level' class='form-control'> 
											<option value='admin'>Administrator</option>
										</select>
									 </div>
							  </div>
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Blokir</label>        		
									 <div class='col-sm-4'>
										<input type=radio name='blokir' value='Y' checked>Y 
										<input type=radio name='blokir' value='N'> N
									 </div>
							  </div>
							
							  
							  
							  <div class='buttons'>
							  <input class='btn btn-primary' type=submit value=Simpan>
							  <input class='btn btn-danger' type=button value=Batal onclick=self.history.back()>
							  </div>
							  </form>
							  
				</div> 
				
			</div>";
    }
    else{
      echo "<link href=../css/style.css rel=stylesheet type=text/css>";
      echo "<div class='error msg'>Anda tidak berhak mengakses halaman ini.</div>";
    }
    break;

 case "editpengajar":
    $edit=mysqli_query($koneksi, "SELECT * FROM pengajar WHERE id_pengajar='$_GET[id]'");
    $r=mysqli_fetch_array($edit);

    if ($_SESSION[leveluser]=='admin' || $_SESSION['leveluser']=='pendidikan'){
    
	
		echo "
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>Tambah Data Pengajar</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
						<form method=POST action='$aksi?module=admin&act=update_pengajar ' enctype='multipart/form-data' class='form-horizontal'>
							  <input type=hidden name=id value='$r[id_pengajar]'>
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Kode Pengajar</label>        		
									 <div class='col-sm-4'>
										<input type=text name='kodedosen' class='form-control' Placeholder='Kode Pengajar' value='$r[kodedosen]'>
									 </div>
							  </div>
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Nama Lengkap</label>        		
									 <div class='col-sm-6'>
										<input type=text name='nama_lengkap' class='form-control' Placeholder='Nama Lengkap' value='$r[nama_lengkap]'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Username</label>        		
									 <div class='col-sm-5'>
										<input type=text name='username' class='form-control' Placeholder='Username' value='$r[username_login]' >
									 </div>
							  </div>
							 
							 <div class='form-group'>
									<label class='col-sm-2 control-label'>Password</label>        		
									 <div class='col-sm-5'>
										<input type=text name='password' class='form-control' Placeholder='Password' >
										<small>Apabila password tidak diubah, dikosongkan saja</small>
									 </div>
							  </div>
							  
							 
							 
							 <div class='form-group'>
									<label class='col-sm-2 control-label'>Alamat</label>        		
									 <div class='col-sm-9'>
										<input type=text name='alamat' class='form-control' Placeholder='Alamat' value='$r[alamat]'>
									 </div>
							  </div>
							 
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Tempat Lahir</label>        		
									 <div class='col-sm-4'>
										<input type=text name='tempat_lahir' class='form-control' Placeholder='Tempat Lahir' value='$r[tempat_lahir]' >
									 </div>
							  </div>
							 <div class='form-group'>
									<label class='col-sm-2 control-label'>Tanggal Lahir</label>        		
									 <div class='col-sm-2'>";
										 $get_tgl=substr("$r[tgl_lahir]",8,2);
										 combotgl(1,31,'tgl',$get_tgl);
								echo"</div>
									<div class='col-sm-3'>";
										 $get_bln=substr("$r[tgl_lahir]",5,2);
										 combonamabln(1,12,'bln',$get_bln);
								echo"</div>
									<div class='col-sm-2'>";
										$get_thn=substr("$r[tgl_lahir]",0,4);
										combothn(1950,$thn_sekarang,'thn',$get_thn);
								echo"</div>
							  </div>
							    <div class='form-group'>
									<label class='col-sm-2 control-label'>Jenis Kelamin</label>        		
									 <div class='col-sm-4'>
										
										<input type=radio name='jk' value='L'>Laki - Laki</input>
										<input type=radio name='jk' value='P'>Perempuan</input>
										
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Agama</label>        		
									 <div class='col-sm-4'>
											<select name='agama' class='form-control' >
											   <option value='$r[agama]'selected>$r[agama]</option>
											   <option value='Islam'>Islam</option>
											   <option value='Kristen'>Kristen</option>
											   <option value='Katolik'>Katolik</option>
											   <option value='Hindu'>Hindu</option>
											   <option value='Buddha'>Buddha</option>
											</select>
									 </div>
							  </div>
							 
								
							 
							 
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>No HP/Tlp</label>        		
									 <div class='col-sm-4'>
										<input type=text name='no_telp' class='form-control' Placeholder='No HP' value='$r[no_telp]'>
									 </div>
							   </div>
							   
							    <div class='form-group'>
									<label class='col-sm-2 control-label'>Email</label>        		
									 <div class='col-sm-5'>
										<input type=text name='email' class='form-control'  Placeholder='Email' value='$r[email]'>
									 </div>
							   </div>
							   
							    <div class='form-group'>
									<label class='col-sm-2 control-label'>Website</label>        		
									 <div class='col-sm-5'>
										<input type=text name='website' size=30 value='http://' class='form-control' value='$r[website]'>
									 </div>
							   </div>
							   
							   <div class='form-group'>
									<label class='col-sm-2 control-label'>Foto</label>        		
									 <div class='col-sm-4'>";
									 if ($r[foto]!=''){
											echo "<ul class='photos sortable'>
											<li>
												<img src='../foto_pengajar/medium_$r[foto]'>
											<div class='links'>
												<a href='../foto_pengajar/medium_$r[foto]' rel='facebox'>View</a>
											<div>
											</li>
											</ul>";
									}echo "</dd>
									 
									
																		
									</div>
									 
							   </div>
							   
							   <div class='form-group'>
									<label class='col-sm-2 control-label'>Jabatan</label>        		
									 <div class='col-sm-3'>
										<input type=text name='jabatan' size=30  class='form-control' placeholder='Jabatan' >
									 </div>
							   </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Ganti Foto</label>        		
									 <div class='col-sm-3'>
										<input type=file name='fupload' size=40>
									 </div>
							   </div>
							   <div class='form-group'>
									<label class='col-sm-2 control-label'>Blokir</label>        		
									 <div class='col-sm-4'>
										<input type=radio name='blokir' value='Y'> Y
										<input type=radio name='blokir' value='N' checked> N
									 </div>
							   </div>
							  
							
							  
							  
							  <div class='buttons'>
							  <input class='btn btn-primary' type=submit value=Simpan>
							  <input class='btn btn-danger' type=button value=Batal onclick=self.history.back()>
							  </div>
							  </form>
							  
				</div> 
				
			</div>";
	
	
	
    }
    elseif ($_SESSION[leveluser]=='pengajar'){
        $edit=mysqli_query($koneksi, "SELECT * FROM pengajar WHERE id_pengajar='$_SESSION[idpengajar]'");
        $r=mysqli_fetch_array($edit);
     echo "<form method=POST action=$aksi?module=admin&act=update_pengajar2 enctype='multipart/form-data'>
          <input type=hidden name=id value='$r[id_pengajar]'>          
          <fieldset>
          <legend>Edit Profil</legend>
          <dl class='inline'>
          <dt><label>Kode Dosen</label></dt>          <dd> : <input type=text name='kodedosen' value='$r[kodedosen]'></dd>
          <dt><label>Nama Lengkap</label></dt> <dd> : <input type=text name='nama_lengkap' size=30 value='$r[nama_lengkap]'></dd>
          <dt><label>Username</label></dt>     <dd> : <input type=text name='username' value='$r[username_login]'></dd>
          <dt><label>Password</label></dt>     <dd> : <input type=text name='password'>
                                                      <small>Apabila password tidak diubah, dikosongkan saja</small>
                                               </dd>
          <dt><label>Alamat</label></dt>       <dd> : <input type=text name='alamat' size=70 value='$r[alamat]'></dd>
          <dt><label>Tempat Lahir</label></dt> <dd> : <input type=text name='tempat_lahir' size=60 value='$r[tempat_lahir]'></dd>
          <dt><label>Tanggal Lahir</label></dt><dd> : ";
          $get_tgl=substr("$r[tgl_lahir]",8,2);
          combotgl(1,31,'tgl',$get_tgl);
          $get_bln=substr("$r[tgl_lahir]",5,2);
          combonamabln(1,12,'bln',$get_bln);
          $get_thn=substr("$r[tgl_lahir]",0,4);
          combothn(1950,$thn_sekarang,'thn',$get_thn);

    echo "</dd>";
          if ($r[jenis_kelamin]=='L'){
              echo "<dt><label>Jenis Kelamin</label></dt>  <dd> : <label><input type=radio name='jk' value='L' checked>Laki - Laki</label>
                                                                <label><input type=radio name='jk' value='P'>Perempuan</label></dd>";
          }else{
              echo "<dt><label>Jenis Kelamin<</label></dt> <dd> : <label><input type=radio name='jk' value='L'>Laki - Laki</label>
                                           <label><input type=radio name='jk' value='P' checked>Perempuan</label></dd>";
          }
     echo"<dt><label>Agama</label></dt>        <dd> : <select name=agama>
                                           <option value='$r[agama]' selected>$r[agama]</option>
                                           <option value='Islam'>Islam</option>
                                           <option value='Kristen'>Kristen</option>
                                           <option value='Katolik'>Katolik</option>
                                           <option value='Hindu'>Hindu</option>
                                           <option value='Buddha'>Buddha</option>
                                           </select></dd>
          <dt><label>No.Telp/HP</label></dt>   <dd> : <input type=text name='no_telp' size=20 value='$r[no_telp]'></dd>
          <dt><label>E-mail</label></dt>       <dd> : <input type=text name='email' size=30 value='$r[email]'></dd>
          <dt><label>Website</label></dt>      <dd> : <input type=text name='website' size=30 value='$r[website]'></dd>
          <dt><label>Foto</label></dt>         <dd> : ";
                                if ($r[foto]!=''){
              echo "<ul class='photos sortable'>
                    <li>
                    <img src='../foto_pengajar/medium_$r[foto]'>
                    <div class='links'>
                    <a href='../foto_pengajar/medium_$r[foto]' rel='facebox'>View</a>
		    <div>
                    </li>
                    </ul>";
          }echo "</dd>
          <dt><label>Jabatan</label></dt>          <dd> : <input  readonly='readonly' type=text name='jabatan' value='$r[jabatan]' size=50></dd>
          <dt><label>Ganti Foto</label></dt>       <dd> : <input type=file name='fupload' size=40>
                                           <small>Tipe foto harus JPG/JPEG dan ukuran lebar maks: 400 px</small>
                                           <small>Apabila foto tidak diubah, dikosongkan saja</small></dd>";
          if ($r[blokir]=='N'){

           echo "<dt><label>Blokir</label></dt>     <dd> : <label><input type=radio name='blokir' value='Y'> Y<label>
                                           <label><input type=radio name='blokir' value='N' checked> N <label></dd>";
            }
            else{
           echo "<dt><label>Blokir</label></dt>     <dd> : <label><input type=radio name='blokir' value='Y' checked> Y<label>
                                          <label><input type=radio name='blokir' value='N'> N <label></dd>";
            }
          echo "</dl>
          <div class='buttons'>
          <input class='button blue' type=submit value=Update>
          <input class='button blue' type=button value=Batal onclick=self.history.back()>
          </div>
          </fieldset></form>";
    }
    break;

case "detailpengajar":
    $detail=mysqli_query($koneksi, "SELECT * FROM pengajar WHERE id_pengajar='$_GET[id]'");
    $r=mysqli_fetch_array($detail);
    $tgl_lahir   = tgl_indo($r[tgl_lahir]);

    if ($_SESSION[leveluser]=='admin' || $_SESSION['leveluser']=='pendidikan'){
		 echo "
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>Detail Pengajar</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
					<div class='col-md-3'>
						<div class='box box-primary'>
							<div class='box-body box-profile'>";
							    if ($r[foto]!=''){
									echo "<img class='profile-user-img img-responsive img-circle' src='../foto_pengajar/medium_$r[foto]' alt='User profile picture'>";
								}
	
      
              
							
							  
							 echo "		 
							  <h3 class='profile-username text-center'>$r[nama_lengkap]</h3>
							  <p class='text-muted text-center'>$r[kodedosen]</p>

							  <ul class='list-group list-group-unbordered'>
								<li class='list-group-item'>
								  <b>Username </b> <a class='pull-right'>$r[username_login]</a>
								</li>
								<li class='list-group-item'>
								  <b>Jabatan </b> <a class='pull-right'>$r[jabatan]</a>
								</li>
								<li class='list-group-item'>
								  <b>Website </b> <a class='pull-right'>$r[website]</a>
								</li>
							  </ul>
							  <input class='btn btn-primary btn-block' type=button value=Kembali onclick=self.history.back()>
							  
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div>
					<div class='col-md-9'>	
						<div class='nav-tabs-custom'>
							<ul class='nav nav-tabs'>
								<li class='active'><a href='#activity' data-toggle='tab'>Profil Lengkap</a></li>
								
							</ul>	
								
								<div class='tab-content'>
									<div class='active tab-pane' id='activity'>
										<div class='post'>
											
											
											<p><label class='col-sm-2 control-label'>Nama</label> $r[nama_lengkap] </p>     		
											<p><label class='col-sm-2 control-label'>Alamat</label> $r[alamat]<br> </p>   		
											<p><label class='col-sm-2 control-label'>Tempat Lahir</label>  $r[tempat_lahir]<br></p>      
											<p><label class='col-sm-2 control-label'>Tanggal Lahir</label> $r[tgl_lahir]<br>  ";  		
											
											
												if ($siswa[jenis_kelamin]=='P'){
														echo "<label class='col-sm-2 control-label'>Jenis Kelamin</label> Perempuan<br> </p>   ";
												}
												else{
														echo "<p><label class='col-sm-2 control-label'>Jenis Kelamin</label> Laki - Laki<br> </p>   ";
												}echo"
											
														
											<p><label class='col-sm-2 control-label'>Agama</label> $r[agama]<br></p>     		
											  		
											<p><label class='col-sm-2 control-label'>Email</label> $r[email] <br></p>      		
											<p><label class='col-sm-2 control-label'>No. HP</label> $r[no_telp] <br></p>      		
											<p><label class='col-sm-2 control-label'>Blokir</label> $r[blokir] <br></p>    
											 
										   		
		
										</div>	
								
								    </div>
									
						</div>
					</div>
				
				</div>
			</div>";
	  
          
    }
    elseif ($_SESSION[leveluser]=='pengajar' ){
        echo "<form><fieldset>
          <legend>Detail Pengajar</legend>
          <dl class='inline'>
          <table id='table1' class='gtable sortable'>
          <tr><td rowspan='13'>";if ($r[foto]!=''){
              echo "<ul class='photos sortable'>
                    <li>
                    <img src='../foto_pengajar/medium_$r[foto]'>
                    <div class='links'>
                    <a href='../foto_pengajar/medium_$r[foto]' rel='facebox'>View</a>
                    <div>
                    </li>
                    </ul>";
          }echo "</td><td>Kode Dosen</td>  <td> : $r[kodedosen]</td><tr>
          <tr><td>Nama Lengkap</td> <td> : $r[nama_lengkap]</td></tr>          
          <tr><td>Alamat</td>       <td> : $r[alamat]</td></tr>
          <tr><td>Tempat Lahir</td> <td> : $r[tempat_lahir]</td></tr>
          <tr><td>Tanggal Lahir</td><td> : $tgl_lahir</td></tr>";
          if ($r[jenis_kelamin]=='P'){
           echo "<tr><td>Jenis Kelamin</td>     <td>  : Perempuan</td></tr>";
            }
            else{
           echo "<tr><td>Jenis kelamin</td>     <td> :  Laki - Laki </td></tr>";
            }echo"
          <tr><td>Agama</td>        <td> : $r[agama]</td></tr>
          <tr><td>No.Telp/HP</td>   <td> : $r[no_telp]</td></tr>
          <tr><td>E-mail</td>       <td> : <a href=mailto:$r[email]>$r[email]</a></td></tr>
          <tr><td>Website</td>      <td> : <a href=http://$r[website] target=_blank>$r[website]</a></td></tr>
          <tr><td>Jabatan</td>      <td> : $r[jabatan]</td></tr>
          <tr><td>Aksi</td>         <td> : <input class='button small white' type=button value=Kembali onclick=self.history.back()></td></tr>";
           echo"</table></dl></fieldset</form>";
    }else{
        echo"<br><b class='judul'>Detail Guru</b><br><p class='garisbawah'></p>";
        echo "<table>
          <tr><td rowspan='12'>";if ($r[foto]!=''){
              echo "<img src='foto_pengajar/medium_$r[foto]'>";
          }echo "</td><td>Nip</td>  <td> : $r[nip]</td><tr>
          <tr><td>Nama Lengkap</td> <td> : $r[nama_lengkap]</td></tr>          
          <tr><td>Alamat</td>       <td> : $r[alamat]</td></tr>
          <tr><td>Tempat Lahir</td> <td> : $r[tempat_lahir]</td></tr>
          <tr><td>Tanggal Lahir</td><td> : $tgl_lahir</td></tr>";
          if ($r[jenis_kelamin]=='P'){
           echo "<tr><td>Jenis Kelamin</td>     <td>  : Perempuan</td></tr>";
            }
            else{
           echo "<tr><td>Jenis kelamin</td>     <td> :  Laki - Laki </td></tr>";
            }echo"
          <tr><td>Agama</td>        <td> : $r[agama]</td></tr>
          <tr><td>No.Telp/HP</td>   <td> : $r[no_telp]</td></tr>
          <tr><td>E-mail</td>       <td> : <a href=mailto:$r[email]>$r[email]</a></td></tr>
          <tr><td>Website</td>      <td> : <a href=http://$r[website] target=_blank>$r[website]</a></td></tr>
          <tr><td>Jabatan</td>      <td> : $r[jabatan]</td></tr>
          <tr><td colspan='3'><input type=button class='tombol' value='Kembali'
                              onclick=self.history.back()></td></tr></table>";
    }
    break;
}
}
?>
