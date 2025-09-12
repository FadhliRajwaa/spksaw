<!DOCTYPE HTML>
<html lang="en">
<head>
<title>Login - Sistem PKH</title>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="shortcut icon" type="image/x-icon" href="images/logo_pkh.svg">
<style>
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    font-family: 'Arial', sans-serif;
}
#login.box {
    background: rgba(255,255,255,0.95);
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    margin-top: 5%;
}
.pkh-header {
    text-align: center;
    padding: 20px;
    background: #2c5aa0;
    color: white;
    border-radius: 15px 15px 0 0;
}
.pkh-header img {
    max-width: 150px;
    height: auto;
    margin-bottom: 10px;
}
.pkh-title {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 5px;
}
.pkh-subtitle {
    font-size: 12px;
    opacity: 0.9;
}
</style>
<!--[if lte IE 8]>
<script type="text/javascript" src="js/html5.js"></script>
<![endif]-->
<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="js/cufon-yui.js"></script>
<script type="text/javascript" src="js/Delicious_500.font.js"></script>
<script language="javascript">
function validasi(form){
  if (form.username.value == ""){
      document.getElementById('eroruser').innerHTML = "<div class='error msg'>Username is empty, click to close</div>";
      form.username.focus();
      $(function() {
	Cufon.replace('#site-title');
	$('.msg').click(function() {
		$(this).fadeTo('slow', 0);
		$(this).slideUp(341);
	});
      });
    return (false);
  }

  if (form.password.value == ""){
    document.getElementById('erorpass').innerHTML = "<div class='error msg'>Password is empty, click to close</div>";
    form.password.focus();
    $(function() {
	Cufon.replace('#site-title');
	$('.msg').click(function() {
		$(this).fadeTo('slow', 0);
		$(this).slideUp(341);
	});
    });
    return (false);
  }
  return (true);
}
</script>

</head>
<body>

<div id="login" class="box">
	<div class="pkh-header">
		<img src="images/logo_pkh.svg" alt="Logo PKH">
		<div class="pkh-title">Sistem Pendukung Keputusan Rekomendasi Penerima Bantuan PKH</div>
		<div class="pkh-subtitle">Metode Simple Additive Weighting (SAW)</div>
	</div>
	
	<h2>Login Administrator</h2>
	<section>
		
                <p id="eroruser"></p>
                <p id="erorpass"></p>
		<form method="POST" action="cek_login.php" onSubmit="return validasi(this)">
			<dl>
				<dt><label>Username</label></dt>
                <dd><input id="username" type="text" name="username" required/></dd>

				<dt><label>Password</label></dt>
				<dd><input id="adminpassword" type="password" name="password" required/></dd>
			</dl>
			<p>
				<input type="submit" class="button white" value="Masuk Sistem"></input>
                <input type="reset" class="button white" value="Reset"></input>
			</p>
		</form>
		
		<div style="text-align: center; margin-top: 20px; color: #666; font-size: 12px;">
			<i class="fa fa-info-circle"></i> 
			Sistem Informasi Program Keluarga Harapan<br>
			Dinas Sosial Republik Indonesia
		</div>
	</section>
</div>

</body>
</html>