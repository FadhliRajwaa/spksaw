<?php
include "../configurasi/koneksi.php";

if ($_SESSION['leveluser']=='admin'){
  $sql=mysqli_query($koneksi, "select * from modul where aktif='Y' and type <> 'Report' order by urutan");
  while ($m=mysqli_fetch_array($sql)){
    $module_name = isset($_GET['module']) ? $_GET['module'] : '';
    $active_class = '';
    
    // Determine if this menu item should be active
    if (strpos($m['link'], 'module=' . $module_name) !== false) {
        $active_class = 'active';
    }
    
    // Map old icons to modern Font Awesome 6 icons
    $icon_map = [
        'fa-circle-o' => 'fas fa-users',
        'fa-group' => 'fas fa-users-cog',
        'fa-list' => 'fas fa-clipboard-list',
        'fa-cog' => 'fas fa-cogs',
        'fa-database' => 'fas fa-database',
        'fa-graduation-cap' => 'fas fa-users',
        'fa-book' => 'fas fa-clipboard-list'
    ];
    
    // Default icon mapping based on module content
    $icon = 'fas fa-circle';
    if (strpos($m['link'], 'warga') !== false) {
        $icon = 'fas fa-users';
    } elseif (strpos($m['link'], 'kriteria') !== false) {
        $icon = 'fas fa-clipboard-list';
    } elseif (strpos($m['link'], 'klasifikasi') !== false) {
        $icon = 'fas fa-tags';
    } elseif (strpos($m['link'], 'modul') !== false) {
        $icon = 'fas fa-cogs';
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
  $sql=mysqli_query($koneksi, "select * from modul where status='pengajar' and aktif='Y' and type <> 'Report' order by urutan");
  echo "<li class='modern-sidebar-item'>
          <a href='?module=home' class='modern-sidebar-link'>
            <i class='fas fa-list-check modern-sidebar-icon'></i> 
            <span class='modern-sidebar-text'>Daftar Absensi</span>
          </a>
        </li>";
  while ($m=mysqli_fetch_array($sql)){
    $module_name = isset($_GET['module']) ? $_GET['module'] : '';
    $active_class = '';
    
    if (strpos($m['link'], 'module=' . $module_name) !== false) {
        $active_class = 'active';
    }
    
    $icon = 'fas fa-circle';
    if (strpos($m['link'], 'warga') !== false) {
        $icon = 'fas fa-users';
    } elseif (strpos($m['link'], 'kriteria') !== false) {
        $icon = 'fas fa-clipboard-list';
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
