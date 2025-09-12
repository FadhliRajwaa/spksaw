<?php
// Jangan panggil session_start() di sini karena sudah dipanggil sebelumnya
function timer(){
	$time=10000;
	$_SESSION['timeout']=time()+$time;
}
function cek_login(){
	$timeout=$_SESSION['timeout'];
	if(time()<$timeout){
		timer();
		return true;
	}else{
		unset($_SESSION['timeout']);
		return false;
	}
}
?>
