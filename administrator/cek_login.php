<?php
include "../configurasi/koneksi.php";

function anti_injection($data, $koneksi){
    $filter = mysqli_real_escape_string($koneksi, stripslashes(strip_tags(htmlspecialchars($data, ENT_QUOTES))));
    return $filter;
}

$username = anti_injection($_POST['username'], $koneksi);
$pass     = anti_injection(md5($_POST['password']), $koneksi);

// pastikan username dan password adalah berupa huruf atau angka.
if (!ctype_alnum($username) || !ctype_alnum($pass)) {
    echo "<link href=/administrator/css/style.css rel=stylesheet type=text/css>";
    echo "<div class='error msg'>Input tidak valid</div>";
} else {
    $login = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username' AND password='$pass'");
    $ketemu = mysqli_num_rows($login);
    $r = mysqli_fetch_array($login);

    // Apabila username dan password ditemukan
    if ($ketemu > 0) {
        session_start();
        include "timeout.php";

        $_SESSION['namauser']     = $r['username'];
        $_SESSION['namalengkap']  = $r['nama_lengkap'];
        $_SESSION['passuser']     = $r['password'];
        $_SESSION['leveluser']    = $r['level'];
        $_SESSION['idadmin']      = $r['id_admin'];

        $_SESSION['login'] = 1;
        timer();

        session_regenerate_id();
        
        header('location:media_admin.php?module=home');

    } else {
        echo "<link href=/administrator/css/style.css rel=stylesheet type=text/css>";
        echo "<div class='error msg'>Login Gagal! Username atau Password salah. ";
        echo "<a href=index.php><b>ULANGI LAGI</b></a></div>";
    }
}
?>