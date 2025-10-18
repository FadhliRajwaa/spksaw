<?php
if (session_status() !== PHP_SESSION_ACTIVE) { @session_start(); }
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
    echo "<link href=../css/style.css rel=stylesheet type=text/css>";
    echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
    exit;
}
// Pastikan koneksi tersedia menggunakan path absolut terhadap file modul ini
if (!isset($koneksi)) {
    require_once __DIR__ . "/../../configurasi/koneksi.php";
}

// Ambil data admin dari session dan database dengan sanitasi
$username = mysqli_real_escape_string($koneksi, $_SESSION['namauser']);
$query_admin = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username'");
$data_admin = mysqli_fetch_array($query_admin);

// Cek jika data admin tidak ditemukan
if (!$data_admin) {
    echo "<div class='error msg'>Data admin tidak ditemukan. Silakan login kembali.</div>";
    exit;
}

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
            /* Light theme aligned with modern palette */
            .profil-container { background:transparent; min-height:auto; padding:10px 0; margin:0; }
            .profil-wrapper { max-width:1250px; margin:0 auto; background:transparent; box-shadow:none; padding:0; }
            .profil-header { text-align:center; margin:15px 0 25px; color:#1f2937; }
            .profil-avatar { width:110px; height:110px; margin:0 auto 10px; border-radius:50%; background:linear-gradient(135deg,var(--c-accent,#FF2E63),var(--c-cyan,#08D9D6)); display:flex; align-items:center; justify-content:center; font-size:38px; color:#fff; box-shadow:0 6px 18px rgba(37,42,52,.08); }
            .profil-name { font-size:1.65rem; font-weight:600; margin:4px 0 2px; color:#111827; }
            .profil-role { font-size:.85rem; color:#0b1220; background:#EAF8F8; padding:5px 14px; border-radius:20px; display:inline-block; border:1px solid rgba(37,42,52,.12); }
            .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:16px; margin:0 0 22px; }
            .stat-card { background:#ffffff; color:#111827; border-radius:12px; padding:16px 14px; text-align:center; box-shadow:0 2px 8px rgba(37,42,52,.08); border:1px solid rgba(37,42,52,.08); }
            .stat-number { font-size:1.9rem; font-weight:700; margin-bottom:4px; }
            .stat-label { letter-spacing:.5px; font-size:.75rem; text-transform:uppercase; font-weight:600; opacity:.85; }
            .profil-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(320px,1fr)); gap:18px; margin-bottom:20px; }
            .profil-card { background:#ffffff; border:1px solid rgba(37,42,52,.08); border-radius:12px; padding:18px 20px 14px; box-shadow:0 3px 10px rgba(37,42,52,.06); transition:box-shadow .25s ease,transform .25s ease; }
            .profil-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(37,42,52,.12); }
            .card-header { display:flex; align-items:center; margin-bottom:12px; }
            .card-icon { width:44px; height:44px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:18px; color:#fff; margin-right:12px; }
            .card-title { font-size:1rem; font-weight:600; color:#111827; margin:0; letter-spacing:.3px; }
            .card-content { color:#334155; line-height:1.5; font-size:.9rem; }
            .info-item { display:flex; justify-content:space-between; margin:0 0 8px; padding:6px 0; border-bottom:1px solid rgba(37,42,52,.08); }
            .info-item:last-child { border-bottom:none; margin-bottom:0; }
            .info-label { font-weight:600; color:#64748b; font-size:.8rem; text-transform:uppercase; letter-spacing:.5px; }
            .info-value { font-weight:500; color:#111827; }
            .action-buttons { display:flex; gap:10px; flex-wrap:wrap; justify-content:center; margin:6px 0 25px; }
            .action-btn { padding:9px 18px; border:1px solid rgba(37,42,52,.12); border-radius:20px; font-weight:600; font-size:.85rem; text-decoration:none; display:inline-flex; align-items:center; gap:7px; transition:all .22s ease; box-shadow:0 3px 10px rgba(37,42,52,.06); }
            .action-btn i { font-size:.95rem; }
            .btn-primary { background:linear-gradient(135deg,#FF2E63,#08D9D6); color:#fff; }
            .btn-success { background:#08D9D6; color:#0b1220; }
            .btn-warning { background:#FFD166; color:#1f2937; }
            .action-btn:hover { transform:translateY(-2px); box-shadow:0 7px 20px rgba(37,42,52,.12); }
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
            // Check if this is a POST request with form data
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama_lengkap'])) {
                // Validasi input
                $errors = array();
                
                if (empty(trim($_POST['nama_lengkap']))) {
                    $errors[] = "Nama lengkap harus diisi";
                }
                if (empty(trim($_POST['email'])) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Email tidak valid";
                }
                if (empty(trim($_POST['no_telp']))) {
                    $errors[] = "No. telepon harus diisi";
                }
                if (empty(trim($_POST['alamat']))) {
                    $errors[] = "Alamat harus diisi";
                }
                
                if (empty($errors)) {
                    // Proses update profil dengan sanitasi
                    $nama_lengkap = mysqli_real_escape_string($koneksi, trim($_POST['nama_lengkap']));
                    $email = mysqli_real_escape_string($koneksi, trim($_POST['email']));
                    $no_telp = mysqli_real_escape_string($koneksi, trim($_POST['no_telp']));
                    $alamat = mysqli_real_escape_string($koneksi, trim($_POST['alamat']));
                    
                    $update_query = "UPDATE admin SET 
                        nama_lengkap='$nama_lengkap',
                        email='$email',
                        no_telp='$no_telp',
                        alamat='$alamat',
                        updated_at=NOW()
                        WHERE username='$username'";
                    
                    $update = mysqli_query($koneksi, $update_query);
                    
                    if ($update) {
                        // Update session jika nama lengkap berubah
                        $_SESSION['namalengkap'] = $nama_lengkap;
                        
                        echo "<script>
                            alert('Profil berhasil diupdate!'); 
                            window.location.href='?module=profil';
                        </script>";
                    } else {
                        echo "<script>
                            alert('Gagal update profil: " . mysqli_error($koneksi) . "');
                        </script>";
                    }
                } else {
                    $error_message = implode("\\n", $errors);
                    echo "<script>
                        alert('Error:\\n$error_message');
                    </script>";
                }
            }
            ?>
            <style>
            /* Light theme styling untuk form edit */
            .edit-container { background:transparent; min-height:auto; padding:20px; margin:0; }
            .edit-wrapper { max-width:800px; margin:0 auto; }
            .edit-card { background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:25px; box-shadow:0 4px 12px rgba(37,42,52,.06); }
            .edit-header { text-align:center; margin-bottom:25px; color:#1f2937; }
            .edit-title { font-size:1.5rem; font-weight:600; margin:0; color:#111827; }
            .form-group { margin-bottom:20px; }
            .form-label { display:block; margin-bottom:8px; font-weight:600; color:#64748b; font-size:0.9rem; text-transform:uppercase; letter-spacing:0.5px; }
            .form-control { width:100%; padding:12px 15px; border:1px solid #e2e8f0; border-radius:8px; background:#ffffff; color:#111827; font-size:0.95rem; transition:all 0.3s ease; }
            .form-control:focus { outline:none; border-color:#08D9D6; box-shadow:0 0 0 3px rgba(8,217,214,0.15); background:#ffffff; }
            .form-control::placeholder { color:#94a3b8; }
            textarea.form-control { resize:vertical; min-height:80px; }
            .button-group { display:flex; gap:12px; justify-content:center; margin-top:25px; }
            .btn { padding:12px 24px; border:none; border-radius:8px; font-weight:600; font-size:0.9rem; text-decoration:none; display:inline-flex; align-items:center; gap:8px; transition:all 0.3s ease; cursor:pointer; }
            .btn-back { background:#666666; color:#ffffff; }
            .btn-primary { background:linear-gradient(45deg,#3498db,#2980b9); color:#ffffff; }
            .btn:hover { transform:translateY(-2px); box-shadow:0 6px 16px rgba(255,255,255,0.1); }
            .btn i { font-size:0.9rem; }
            @media (max-width:768px){ .edit-container{padding:15px;} .button-group{flex-direction:column;} .btn{width:100%; justify-content:center;} }
            </style>
            
            <div class="edit-container">
                <div class="edit-wrapper">
                    <div class="edit-card">
                        <div class="edit-header">
                            <h2 class="edit-title">
                                <i class="fas fa-user-edit"></i>
                                Edit Profil Administrator
                            </h2>
                        </div>
                        
                        <form method="POST">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-user"></i> Nama Lengkap
                                </label>
                                <input type="text" name="nama_lengkap" class="form-control" 
                                       value="<?= htmlspecialchars($data_admin['nama_lengkap']); ?>" 
                                       placeholder="Masukkan nama lengkap" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-envelope"></i> Email
                                </label>
                                <input type="email" name="email" class="form-control" 
                                       value="<?= htmlspecialchars($data_admin['email']); ?>" 
                                       placeholder="Masukkan email" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-phone"></i> No. Telepon
                                </label>
                                <input type="text" name="no_telp" class="form-control" 
                                       value="<?= htmlspecialchars($data_admin['no_telp']); ?>" 
                                       placeholder="Masukkan no. telepon" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-map-marker-alt"></i> Alamat
                                </label>
                                <textarea name="alamat" class="form-control" rows="4" 
                                          placeholder="Masukkan alamat lengkap" required><?= htmlspecialchars($data_admin['alamat']); ?></textarea>
                            </div>
                            
                            <div class="button-group">
                                <a href="?module=profil" class="btn btn-back">
                                    <i class="fas fa-arrow-left"></i>
                                    Kembali
                                </a>
                                <button type="submit" name="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        }
    break;
    
    case 'password':
        if ($_SESSION['leveluser']=='admin'){
            // Check if this is a POST request with password form data
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password_lama'])) {
                // Validasi input
                $errors = array();
                
                if (empty(trim($_POST['password_lama']))) {
                    $errors[] = "Password lama harus diisi";
                }
                if (empty(trim($_POST['password_baru']))) {
                    $errors[] = "Password baru harus diisi";
                } elseif (strlen($_POST['password_baru']) < 6) {
                    $errors[] = "Password baru minimal 6 karakter";
                }
                if (empty(trim($_POST['konfirmasi']))) {
                    $errors[] = "Konfirmasi password harus diisi";
                } elseif ($_POST['password_baru'] != $_POST['konfirmasi']) {
                    $errors[] = "Konfirmasi password tidak sesuai";
                }
                
                if (empty($errors)) {
                    $password_lama = md5($_POST['password_lama']);
                    $password_baru = md5($_POST['password_baru']);
                    
                    // Cek password lama
                    $check_query = "SELECT password FROM admin WHERE username='$username' AND password='$password_lama'";
                    $cek = mysqli_query($koneksi, $check_query);
                    
                    if (mysqli_num_rows($cek) == 0) {
                        echo "<script>
                            alert('Password lama tidak sesuai!');
                        </script>";
                    } else {
                        $update_query = "UPDATE admin SET password='$password_baru', updated_at=NOW() WHERE username='$username'";
                        $update = mysqli_query($koneksi, $update_query);
                        
                        if ($update) {
                            echo "<script>
                                alert('Password berhasil diubah!'); 
                                window.location.href='?module=profil';
                            </script>";
                        } else {
                            echo "<script>
                                alert('Gagal mengubah password: " . mysqli_error($koneksi) . "');
                            </script>";
                        }
                    }
                } else {
                    $error_message = implode("\\n", $errors);
                    echo "<script>
                        alert('Error:\\n$error_message');
                    </script>";
                }
            }
            ?>
            <style>
            /* Light theme styling untuk form ubah password */
            .password-container { background:transparent; min-height:auto; padding:20px; margin:0; }
            .password-wrapper { max-width:700px; margin:0 auto; }
            .password-card { background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:25px; box-shadow:0 4px 12px rgba(37,42,52,.06); }
            .password-header { text-align:center; margin-bottom:25px; color:#1f2937; }
            .password-title { font-size:1.5rem; font-weight:600; margin:0; color:#111827; }
            .security-info { background:#eef7ff; border:1px solid #bfdbfe; border-radius:8px; padding:15px; margin-bottom:20px; color:#1e3a8a; font-size:0.9rem; }
            .security-info h4 { color:#1e3a8a; margin:0 0 8px; font-size:1rem; }
            .security-info ul { margin:5px 0 0 20px; }
            .form-group { margin-bottom:20px; }
            .form-label { display:block; margin-bottom:8px; font-weight:600; color:#cccccc; font-size:0.9rem; text-transform:uppercase; letter-spacing:0.5px; }
            .form-control { width:100%; padding:12px 15px; border:1px solid #e2e8f0; border-radius:8px; background:#ffffff; color:#111827; font-size:0.95rem; transition:all 0.3s ease; }
            .form-control:focus { outline:none; border-color:#FF2E63; box-shadow:0 0 0 3px rgba(255,46,99,0.12); background:#ffffff; }
            .form-control::placeholder { color:#94a3b8; }
            .password-strength { margin-top:8px; font-size:0.8rem; }
            .strength-weak { color:#e74c3c; }
            .strength-medium { color:#f39c12; }
            .strength-strong { color:#2ecc71; }
            .button-group { display:flex; gap:12px; justify-content:center; margin-top:25px; }
            .btn { padding:12px 24px; border:none; border-radius:8px; font-weight:600; font-size:0.9rem; text-decoration:none; display:inline-flex; align-items:center; gap:8px; transition:all 0.3s ease; cursor:pointer; }
            .btn-back { background:#666666; color:#ffffff; }
            .btn-warning { background:linear-gradient(45deg,#f39c12,#e67e22); color:#ffffff; }
            .btn:hover { transform:translateY(-2px); box-shadow:0 6px 16px rgba(255,255,255,0.1); }
            .btn i { font-size:0.9rem; }
            @media (max-width:768px){ .password-container{padding:15px;} .button-group{flex-direction:column;} .btn{width:100%; justify-content:center;} }
            </style>
            
            <div class="password-container">
                <div class="password-wrapper">
                    <div class="password-card">
                        <div class="password-header">
                            <h2 class="password-title">
                                <i class="fas fa-shield-alt"></i>
                                Ubah Password Administrator
                            </h2>
                        </div>
                        
                        <div class="security-info">
                            <h4><i class="fas fa-info-circle"></i> Panduan Keamanan Password</h4>
                            <ul>
                                <li>Gunakan minimal 6 karakter</li>
                                <li>Kombinasikan huruf besar, kecil, dan angka</li>
                                <li>Hindari menggunakan informasi pribadi</li>
                                <li>Jangan gunakan password yang sama di tempat lain</li>
                            </ul>
                        </div>
                        
                        <form method="POST">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i> Password Lama
                                </label>
                                <input type="password" name="password_lama" class="form-control" 
                                       placeholder="Masukkan password lama" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-key"></i> Password Baru
                                </label>
                                <input type="password" name="password_baru" id="password_baru" class="form-control" 
                                       placeholder="Masukkan password baru (min. 6 karakter)" required>
                                <div id="password-strength" class="password-strength"></div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-check-circle"></i> Konfirmasi Password
                                </label>
                                <input type="password" name="konfirmasi" id="konfirmasi" class="form-control" 
                                       placeholder="Ulangi password baru" required>
                                <div id="password-match" class="password-strength"></div>
                            </div>
                            
                            <div class="button-group">
                                <a href="?module=profil" class="btn btn-back">
                                    <i class="fas fa-arrow-left"></i>
                                    Kembali
                                </a>
                                <button type="submit" name="submit" class="btn btn-warning">
                                    <i class="fas fa-shield-alt"></i>
                                    Ubah Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const passwordInput = document.getElementById('password_baru');
                const confirmInput = document.getElementById('konfirmasi');
                const strengthDiv = document.getElementById('password-strength');
                const matchDiv = document.getElementById('password-match');
                
                // Password strength checker
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    let strength = 0;
                    let feedback = '';
                    
                    if (password.length >= 6) strength++;
                    if (password.match(/[a-z]/)) strength++;
                    if (password.match(/[A-Z]/)) strength++;
                    if (password.match(/[0-9]/)) strength++;
                    if (password.match(/[^A-Za-z0-9]/)) strength++;
                    
                    switch(strength) {
                        case 0:
                        case 1:
                            strengthDiv.innerHTML = '<i class="fas fa-times-circle"></i> Password terlalu lemah';
                            strengthDiv.className = 'password-strength strength-weak';
                            break;
                        case 2:
                        case 3:
                            strengthDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Password sedang';
                            strengthDiv.className = 'password-strength strength-medium';
                            break;
                        case 4:
                        case 5:
                            strengthDiv.innerHTML = '<i class="fas fa-check-circle"></i> Password kuat';
                            strengthDiv.className = 'password-strength strength-strong';
                            break;
                    }
                });
                
                // Password match checker
                confirmInput.addEventListener('input', function() {
                    const password = passwordInput.value;
                    const confirm = this.value;
                    
                    if (confirm === '') {
                        matchDiv.innerHTML = '';
                        return;
                    }
                    
                    if (password === confirm) {
                        matchDiv.innerHTML = '<i class="fas fa-check-circle"></i> Password cocok';
                        matchDiv.className = 'password-strength strength-strong';
                    } else {
                        matchDiv.innerHTML = '<i class="fas fa-times-circle"></i> Password tidak cocok';
                        matchDiv.className = 'password-strength strength-weak';
                    }
                });
            });
            </script>
            <?php
        }
    break;
}
?>
