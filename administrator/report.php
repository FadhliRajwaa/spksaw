<?php
include "../configurasi/koneksi.php";

if ($_SESSION['leveluser']=='admin'){
  $sql=mysqli_query($koneksi, "select * from modul where aktif='Y' and type = 'Report' order by urutan");
  while ($m=mysqli_fetch_array($sql)){
    $module_name = isset($_GET['module']) ? $_GET['module'] : '';
    $active_class = '';
    
    // Determine if this menu item should be active
    if (strpos($m['link'], 'module=' . $module_name) !== false) {
        $active_class = 'active';
    }
    
    // Icon mapping for report modules
    $icon = 'fas fa-chart-line';
    if (strpos($m['link'], 'laporan') !== false) {
        $icon = 'fas fa-calculator';
    } elseif (strpos($m['link'], 'perankingan') !== false) {
        $icon = 'fas fa-trophy';
    } elseif (strpos($m['link'], 'ranking') !== false) {
        $icon = 'fas fa-medal';
    } elseif (strpos($m['link'], 'print') !== false || strpos($m['link'], 'pdf') !== false) {
        $icon = 'fas fa-file-pdf';
    }
    
    echo "<li class='modern-sidebar-item'>
            <a href='$m[link]' class='modern-sidebar-link $active_class'>
              <i class='$icon modern-sidebar-icon'></i> 
              <span class='modern-sidebar-text'>$m[nama_modul]</span>
            </a>
          </li>";
  }
}
elseif ($_SESSION['leveluser']=='pengajar'){
  $sql=mysqli_query($koneksi, "select * from modul where status='pengajar' and aktif='Y' and type = 'Report' order by urutan");
  while ($m=mysqli_fetch_array($sql)){
    $module_name = isset($_GET['module']) ? $_GET['module'] : '';
    $active_class = '';
    
    if (strpos($m['link'], 'module=' . $module_name) !== false) {
        $active_class = 'active';
    }
    
    $icon = 'fas fa-chart-line';
    if (strpos($m['link'], 'laporan') !== false) {
        $icon = 'fas fa-calculator';
    } elseif (strpos($m['link'], 'perankingan') !== false) {
        $icon = 'fas fa-trophy';
    }
    
    echo "<li class='modern-sidebar-item'>
            <a href='$m[link]' class='modern-sidebar-link $active_class'>
              <i class='$icon modern-sidebar-icon'></i> 
              <span class='modern-sidebar-text'>$m[nama_modul]</span>
            </a>
          </li>";
  }
}
?>
