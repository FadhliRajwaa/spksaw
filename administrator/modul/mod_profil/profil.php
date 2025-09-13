<?php
session_start();
if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
    echo "<link href=../css/style.css rel=stylesheet type=text/css>";
    echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
    exit;
}
include "../../../configurasi/koneksi.php";

// Ambil data admin dari session dan database
$username = $_SESSION['username'];
$query_admin = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username'");
$data_admin = mysqli_fetch_array($query_admin);

// Hitung beberapa statistik
$total_warga = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM data_warga"));
$total_kriteria = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tbl_kriteria"));
$total_klasifikasi = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tbl_klasifikasi"));
$total_hasil = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tbl_hasil_saw"));

switch(@$_GET['act']){
    default:
        if ($_SESSION['leveluser']=='admin'){
            ?>
            <style>
            /* Dark theme: black background with white text */
            .profil-container { background:#1a1a1a; min-height:100vh; padding:10px 15px; margin:-20px; }
            .profil-wrapper { max-width:1250px; margin:0 auto; background:transparent; box-shadow:none; padding:0; }
            .profil-header { text-align:center; margin:15px 0 25px; color:#ffffff; }
            .profil-avatar { width:110px; height:110px; margin:0 auto 10px; border-radius:50%; background:linear-gradient(45deg,#2980b9,#2ecc71); display:flex; align-items:center; justify-content:center; font-size:38px; color:#fff; box-shadow:0 6px 18px rgba(255,255,255,.1); }
            .profil-name { font-size:1.65rem; font-weight:600; margin:4px 0 2px; color:#ffffff; }
            .profil-role { font-size:.85rem; color:#cccccc; background:#333333; padding:5px 14px; border-radius:20px; display:inline-block; border:1px solid #444444; }
            .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:16px; margin:0 0 22px; }
            .stat-card { background:#2a2a2a; color:#fff; border-radius:10px; padding:16px 14px; text-align:center; box-shadow:0 4px 12px rgba(255,255,255,.05); border:1px solid #333333; }
            .stat-number { font-size:1.9rem; font-weight:700; margin-bottom:4px; }
            .stat-label { letter-spacing:.5px; font-size:.75rem; text-transform:uppercase; font-weight:600; opacity:.85; }
            .profil-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(320px,1fr)); gap:18px; margin-bottom:20px; }
            .profil-card { background:#2a2a2a; border:1px solid #404040; border-radius:10px; padding:18px 20px 14px; box-shadow:0 3px 10px rgba(255,255,255,.02); transition:box-shadow .25s ease,transform .25s ease; }
            .profil-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(255,255,255,.05); }
            .card-header { display:flex; align-items:center; margin-bottom:12px; }
            .card-icon { width:44px; height:44px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:18px; color:#fff; margin-right:12px; }
            .card-title { font-size:1rem; font-weight:600; color:#ffffff; margin:0; letter-spacing:.3px; }
            .card-content { color:#cccccc; line-height:1.5; font-size:.9rem; }
            .info-item { display:flex; justify-content:space-between; margin:0 0 8px; padding:6px 0; border-bottom:1px solid #404040; }
            .info-item:last-child { border-bottom:none; margin-bottom:0; }
            .info-label { font-weight:600; color:#999999; font-size:.8rem; text-transform:uppercase; letter-spacing:.5px; }
            .info-value { font-weight:500; color:#ffffff; }
            .action-buttons { display:flex; gap:10px; flex-wrap:wrap; justify-content:center; margin:6px 0 25px; }
            .action-btn { padding:9px 18px; border:none; border-radius:20px; font-weight:600; font-size:.85rem; text-decoration:none; display:inline-flex; align-items:center; gap:7px; transition:all .22s ease; box-shadow:0 3px 10px rgba(255,255,255,.1); }
            .action-btn i { font-size:.95rem; }
            .btn-primary { background:linear-gradient(45deg,#2980b9,#3498db); color:#fff; }
            .btn-success { background:linear-gradient(45deg,#2ecc71,#27ae60); color:#fff; }
            .btn-warning { background:linear-gradient(45deg,#f39c12,#e67e22); color:#fff; }
            .action-btn:hover { transform:translateY(-2px); box-shadow:0 7px 20px rgba(255,255,255,.15); color:#fff; }
            @media (max-width:768px){ .profil-grid{grid-template-columns:1fr;} .stats-grid{grid-template-columns:repeat(2,1fr);} .profil-avatar{width:90px;height:90px;font-size:30px;} .profil-name{font-size:1.35rem;} .action-buttons{flex-direction:column;} .action-btn{width:100%; justify-content:center;} }
            </style>
            
            <div class="profil-container">
                <div class="profil-wrapper">
                    <!-- Header Profil -->
                    <div class="profil-header">
                        <div class="profil-avatar">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h1 class="profil-name"><?= htmlspecialchars($data_admin['nama_lengkap']); ?></h1>
                        <div class="profil-role">
                            <i class="fas fa-crown"></i> Administrator Sistem PKH
                        </div>
                    </div>
                    
                    <!-- Statistik Dashboard -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-number" style="color: #3498db;"><?= $total_warga; ?></div>
                            <div class="stat-label">Total Warga</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number" style="color: #2ecc71;"><?= $total_kriteria; ?></div>
                            <div class="stat-label">Kriteria</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number" style="color: #e74c3c;"><?= $total_klasifikasi; ?></div>
                            <div class="stat-label">Klasifikasi</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number" style="color: #f39c12;"><?= $total_hasil; ?></div>
                            <div class="stat-label">Hasil SAW</div>
                        </div>
                    </div>
                    
                    <!-- Grid Informasi -->
                    <div class="profil-grid">
                        <!-- Card Informasi Personal -->
                        <div class="profil-card">
                            <div class="card-header">
                                <div class="card-icon" style="background: linear-gradient(45deg, #3498db, #2980b9);">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h3 class="card-title">Informasi Personal</h3>
                            </div>
                            <div class="card-content">
                                <div class="info-item">
                                    <span class="info-label">Nama Lengkap</span>
                                    <span class="info-value"><?= htmlspecialchars($data_admin['nama_lengkap']); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Username</span>
                                    <span class="info-value"><?= htmlspecialchars($data_admin['username']); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Level Akses</span>
                                    <span class="info-value">
                                        <span style="background: #2ecc71; color: white; padding: 3px 8px; border-radius: 12px; font-size: 0.8rem;">
                                            <?= strtoupper($data_admin['level']); ?>
                                        </span>
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Email</span>
                                    <span class="info-value"><?= htmlspecialchars($data_admin['email']); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Kontak -->
                        <div class="profil-card">
                            <div class="card-header">
                                <div class="card-icon" style="background: linear-gradient(45deg, #2ecc71, #27ae60);">
                                    <i class="fas fa-address-book"></i>
                                </div>
                                <h3 class="card-title">Informasi Kontak</h3>
                            </div>
                            <div class="card-content">
                                <div class="info-item">
                                    <span class="info-label">No. Telepon</span>
                                    <span class="info-value"><?= htmlspecialchars($data_admin['no_telp']); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Alamat</span>
                                    <span class="info-value"><?= htmlspecialchars($data_admin['alamat']); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Dibuat</span>
                                    <span class="info-value">
                                        <?= date('d/m/Y H:i', strtotime($data_admin['created_at'])); ?>
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Terakhir Update</span>
                                    <span class="info-value">
                                        <?= date('d/m/Y H:i', strtotime($data_admin['updated_at'])); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Aktivitas -->
                        <div class="profil-card">
                            <div class="card-header">
                                <div class="card-icon" style="background: linear-gradient(45deg, #e74c3c, #c0392b);">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h3 class="card-title">Aktivitas Sistem</h3>
                            </div>
                            <div class="card-content">
                                <div class="info-item">
                                    <span class="info-label">Login Terakhir</span>
                                    <span class="info-value"><?= date('d/m/Y H:i'); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Status Akun</span>
                                    <span class="info-value">
                                        <span style="background: #2ecc71; color: white; padding: 3px 8px; border-radius: 12px; font-size: 0.8rem;">
                                            <i class="fas fa-check-circle"></i> Aktif
                                        </span>
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Session ID</span>
                                    <span class="info-value" style="font-family: monospace; font-size: 0.9rem;">
                                        <?= substr(session_id(), 0, 12); ?>...
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Browser</span>
                                    <span class="info-value">
                                        <i class="fas fa-globe"></i> <?= substr($_SERVER['HTTP_USER_AGENT'], 0, 30); ?>...
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Sistem -->
                        <div class="profil-card">
                            <div class="card-header">
                                <div class="card-icon" style="background: linear-gradient(45deg, #f39c12, #e67e22);">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                <h3 class="card-title">Informasi Sistem</h3>
                            </div>
                            <div class="card-content">
                                <div class="info-item">
                                    <span class="info-label">Aplikasi</span>
                                    <span class="info-value">SPK PKH SAW</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Versi</span>
                                    <span class="info-value">2.0.0</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Database</span>
                                    <span class="info-value">MySQL/MariaDB</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Server Time</span>
                                    <span class="info-value"><?= date('d/m/Y H:i:s'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tombol Aksi -->
                    <div class="action-buttons">
                        <a href="?module=profil&act=edit" class="action-btn btn-primary">
                            <i class="fas fa-edit"></i>
                            Edit Profil
                        </a>
                        <a href="?module=profil&act=password" class="action-btn btn-warning">
                            <i class="fas fa-key"></i>
                            Ubah Password
                        </a>
                        <a href="?module=home" class="action-btn btn-success">
                            <i class="fas fa-home"></i>
                            Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Script untuk efek dinamis -->
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Animasi fade in untuk cards
                const cards = document.querySelectorAll('.profil-card, .stat-card');
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(30px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.6s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100);
                });
                
                // Real-time clock update
                function updateTime() {
                    const now = new Date();
                    const timeString = now.toLocaleString('id-ID', {
                        day: '2-digit',
                        month: '2-digit', 
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });
                    
                    const timeElements = document.querySelectorAll('[data-time="server"]');
                    timeElements.forEach(el => el.textContent = timeString);
                }
                
                // Update time every second
                setInterval(updateTime, 1000);
                updateTime(); // Initial call
            });
            </script>
            <?php
        }
    break;
    
    case 'edit':
        if ($_SESSION['leveluser']=='admin'){
            if (isset($_POST['submit'])) {
                // Proses update profil
                $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
                $email = mysqli_real_escape_string($koneksi, $_POST['email']);
                $no_telp = mysqli_real_escape_string($koneksi, $_POST['no_telp']);
                $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
                
                $update = mysqli_query($koneksi, "UPDATE admin SET 
                    nama_lengkap='$nama_lengkap',
                    email='$email',
                    no_telp='$no_telp',
                    alamat='$alamat',
                    updated_at=NOW()
                    WHERE username='$username'");
                    
                if ($update) {
                    echo "<script>alert('Profil berhasil diupdate!'); window.location.href='?module=profil';</script>";
                } else {
                    echo "<script>alert('Gagal update profil!');</script>";
                }
            }
            ?>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-edit"></i> Edit Profil Administrator</h3>
                </div>
                <form method="POST" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Nama Lengkap</label>
                            <div class="col-sm-8">
                                <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($data_admin['nama_lengkap']); ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Email</label>
                            <div class="col-sm-8">
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data_admin['email']); ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">No. Telepon</label>
                            <div class="col-sm-8">
                                <input type="text" name="no_telp" class="form-control" value="<?= htmlspecialchars($data_admin['no_telp']); ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Alamat</label>
                            <div class="col-sm-8">
                                <textarea name="alamat" class="form-control" rows="3" required><?= htmlspecialchars($data_admin['alamat']); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="?module=profil" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
                        <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
            <?php
        }
    break;
    
    case 'password':
        if ($_SESSION['leveluser']=='admin'){
            if (isset($_POST['submit'])) {
                $password_lama = md5($_POST['password_lama']);
                $password_baru = md5($_POST['password_baru']);
                $konfirmasi = md5($_POST['konfirmasi']);
                
                // Cek password lama
                $cek = mysqli_query($koneksi, "SELECT password FROM admin WHERE username='$username' AND password='$password_lama'");
                if (mysqli_num_rows($cek) == 0) {
                    echo "<script>alert('Password lama tidak sesuai!');</script>";
                } elseif ($password_baru != $konfirmasi) {
                    echo "<script>alert('Konfirmasi password tidak sesuai!');</script>";
                } else {
                    $update = mysqli_query($koneksi, "UPDATE admin SET password='$password_baru', updated_at=NOW() WHERE username='$username'");
                    if ($update) {
                        echo "<script>alert('Password berhasil diubah!'); window.location.href='?module=profil';</script>";
                    } else {
                        echo "<script>alert('Gagal mengubah password!');</script>";
                    }
                }
            }
            ?>
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-key"></i> Ubah Password</h3>
                </div>
                <form method="POST" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Password Lama</label>
                            <div class="col-sm-8">
                                <input type="password" name="password_lama" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Password Baru</label>
                            <div class="col-sm-8">
                                <input type="password" name="password_baru" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Konfirmasi Password</label>
                            <div class="col-sm-8">
                                <input type="password" name="konfirmasi" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="?module=profil" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
                        <button type="submit" name="submit" class="btn btn-warning"><i class="fa fa-save"></i> Ubah Password</button>
                    </div>
                </form>
            </div>
            <?php
        }
    break;
}
?>
