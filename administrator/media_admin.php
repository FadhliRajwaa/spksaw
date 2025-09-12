<?php
session_start();
error_reporting(0);
include "timeout.php";

if($_SESSION['login']==1){
	if(!cek_login()){
		$_SESSION['login'] = 0;
	}
}
if($_SESSION['login']==0){
  header('location:logout.php');
}
else{
if (empty($_SESSION['username']) AND empty($_SESSION['passuser']) AND $_SESSION['login']==0){
  echo "<link href=css/style.css rel=stylesheet type=text/css>";
  echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
}
else{
    if ($_SESSION['leveluser']=='siswa'){
     echo "<link href=css/style.css rel=stylesheet type=text/css>";
     echo "<div class='error msg'>Anda tidak diperkenankan mengakses halaman ini.</div>";
    }
    else{

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SPK PKH SAW - Sistem Pendukung Keputusan</title>
    <meta name="description" content="Sistem Pendukung Keputusan Rekomendasi Penerima Bantuan PKH dengan Metode Simple Additive Weighting">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Modern Framework CSS -->
    <link rel="stylesheet" href="css/modern-framework.css">
    
    <!-- Modern Content CSS -->
    <link rel="stylesheet" href="css/modern-content.css">
    
    <!-- DataTables Modern Styling -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            font-family: 'Inter', sans-serif;
        }

        /* Custom Sidebar Styling */
        .modern-sidebar {
            background: var(--gradient-primary);
            box-shadow: 4px 0 20px rgba(12, 24, 33, 0.1);
        }

        .modern-sidebar-header {
            background: rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modern-sidebar-logo {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .modern-sidebar-link.active {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            margin: 0 0.5rem;
        }

        .modern-sidebar-link:hover {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            margin: 0 0.5rem;
        }

        /* Custom Header Styling */
        .modern-header {
            background: var(--white);
            border-bottom: 1px solid rgba(204, 201, 220, 0.2);
            backdrop-filter: blur(10px);
        }

        .modern-header-title {
            background: var(--gradient-accent);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }

        /* Welcome Section */
        .welcome-section {
            background: var(--gradient-primary);
            color: var(--white);
            padding: 2rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(50%, -50%);
        }

        .welcome-content {
            position: relative;
            z-index: 2;
        }

        .welcome-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-subtitle {
            opacity: 0.9;
            font-size: 1rem;
            line-height: 1.6;
        }

        /* Modern Stats Cards */
        .stat-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(204, 201, 220, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-accent);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--white);
            background: var(--gradient-accent);
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .stat-link {
            color: var(--accent-blue);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
            transition: var(--transition);
        }

        .stat-link:hover {
            color: var(--primary-dark);
            transform: translateX(4px);
        }

        /* Quick Actions */
        .quick-actions {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(204, 201, 220, 0.1);
        }

        .quick-actions-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(50, 74, 95, 0.05);
            border: 1px solid rgba(50, 74, 95, 0.1);
            border-radius: var(--border-radius-sm);
            text-decoration: none;
            color: var(--primary-dark);
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: var(--accent-blue);
            border-color: var(--accent-blue);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .action-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: var(--accent-blue);
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
            transition: var(--transition);
        }

        .action-btn:hover .action-icon {
            background: var(--white);
            color: var(--accent-blue);
        }

        .action-text {
            font-weight: 500;
            font-size: 0.875rem;
        }

        /* Mobile Optimizations */
        @media (max-width: 768px) {
            .welcome-section {
                padding: 1.5rem;
                text-align: center;
            }
            
            .welcome-title {
                font-size: 1.5rem;
            }
            
            .stat-value {
                font-size: 2rem;
            }
            
            .action-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Loading States */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .loading-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .loading-content {
            text-align: center;
            color: var(--primary-dark);
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(50, 74, 95, 0.1);
            border-top: 4px solid var(--accent-blue);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        /* User Menu Dropdown */
        .user-dropdown {
            position: relative;
        }

        .user-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: var(--white);
            border-radius: var(--border-radius-sm);
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(204, 201, 220, 0.2);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: var(--transition);
            z-index: 1000;
        }

        .user-dropdown:hover .user-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--primary-dark);
            text-decoration: none;
            transition: var(--transition);
            border-bottom: 1px solid rgba(204, 201, 220, 0.1);
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown-item:hover {
            background: rgba(50, 74, 95, 0.05);
        }
    </style>
</head>

<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <p>Memuat dashboard...</p>
        </div>
    </div>

<?php
if ($_SESSION['leveluser']=='admin'){
?>
    <!-- Modern Sidebar -->
    <aside class="modern-sidebar" id="sidebar">
        <div class="modern-sidebar-header">
            <a href="?module=home" class="modern-sidebar-logo">
                <i class="fas fa-hand-holding-heart"></i>
                <span class="modern-sidebar-text">SPK PKH</span>
            </a>
        </div>
        
        <ul class="modern-sidebar-menu">
            <li class="modern-sidebar-item">
                <a href="?module=home" class="modern-sidebar-link <?php echo (!isset($_GET['module']) || $_GET['module'] == 'home') ? 'active' : ''; ?>">
                    <i class="fas fa-home modern-sidebar-icon"></i>
                    <span class="modern-sidebar-text">Dashboard</span>
                </a>
            </li>
            
            <li class="modern-sidebar-item" style="margin-top: 1rem;">
                <div style="color: rgba(255,255,255,0.6); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.5rem 1.5rem;">
                    Data Master
                </div>
            </li>
            
            <?php include "menu.php"; ?>
            
            <li class="modern-sidebar-item" style="margin-top: 1rem;">
                <div style="color: rgba(255,255,255,0.6); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.5rem 1.5rem;">
                    Laporan
                </div>
            </li>
            
            <?php include "report.php"; ?>
            
            <li class="modern-sidebar-item" style="margin-top: 2rem;">
                <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin logout?')" class="modern-sidebar-link">
                    <i class="fas fa-sign-out-alt modern-sidebar-icon"></i>
                    <span class="modern-sidebar-text">Logout</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Modern Header -->
    <header class="modern-header" id="header">
        <div class="modern-header-left">
            <button class="modern-header-toggle" id="sidebarToggle" type="button">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="modern-header-title">
                <?php 
                if (isset($_GET['module'])) {
                    switch($_GET['module']) {
                        case 'home': echo 'Dashboard'; break;
                        case 'warga': echo 'Data Warga'; break;
                        case 'kriteria': echo 'Data Kriteria'; break;
                        case 'laporan': echo 'Perhitungan SAW'; break;
                        case 'perankingan': echo 'Hasil Perankingan'; break;
                        default: echo 'Dashboard';
                    }
                } else {
                    echo 'Dashboard';
                }
                ?>
            </h1>
        </div>
        
        <div class="modern-header-right">
            <div class="user-dropdown">
                <a href="#" class="modern-header-user">
                    <div class="modern-header-avatar">
                        <?php echo strtoupper(substr($_SESSION['namalengkap'], 0, 1)); ?>
                    </div>
                    <span style="font-weight: 500;"><?php echo $_SESSION['namalengkap']; ?></span>
                    <i class="fas fa-chevron-down" style="font-size: 0.75rem; opacity: 0.7;"></i>
                </a>
                <div class="user-dropdown-menu">
                    <a href="?module=profil" class="dropdown-item">
                        <i class="fas fa-user"></i>
                        <span>Profil Saya</span>
                    </a>
                    <a href="?module=pengaturan" class="dropdown-item">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                    <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin logout?')" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Modern Main Content -->
    <main class="modern-main" id="mainContent">
        <?php include "content_admin.php"; ?>
    </main>

<?php
}
elseif ($_SESSION['leveluser']=='pengajar'){
    // Pengajar interface can be added here if needed
}
?>

    <!-- Modern Framework JavaScript -->
    <script src="js/modern-framework.js"></script>
    
    <!-- jQuery and DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hide loading overlay
            setTimeout(() => {
                document.getElementById('loadingOverlay').classList.remove('show');
            }, 500);

            // Initialize DataTables with modern styling
            if ($.fn.DataTable) {
                // Configure specific tables separately
                if ($('#example1').length > 0) {
                    // Data Warga table with all columns visible
                    $('#example1').DataTable({
                        responsive: false,
                        scrollX: true,
                        pageLength: 10,
                        language: {
                            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                        },
                        dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center"l><"d-flex align-items-center"f>>rtip',
                        drawCallback: function() {
                            $('.pagination').addClass('modern-pagination');
                            $('.page-link').addClass('modern-pagination-btn');
                        }
                    });
                } else {
                    // Other tables with responsive mode
                    $('.modern-table, #example2, #example3, #example4').DataTable({
                        responsive: true,
                        pageLength: 10,
                        language: {
                            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                        },
                        dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center"l><"d-flex align-items-center"f>>rtip',
                        drawCallback: function() {
                            $('.pagination').addClass('modern-pagination');
                            $('.page-link').addClass('modern-pagination-btn');
                        }
                    });
                }
            }

            // Add modern styling to existing elements
            $('table').addClass('modern-table');
            
            // Enhanced button styling
            $('.btn-primary').removeClass('btn-primary').addClass('modern-btn modern-btn-primary');
            $('.btn-success').removeClass('btn-success').addClass('modern-btn modern-btn-accent');
            $('.btn-warning').removeClass('btn-warning').addClass('modern-btn modern-btn-outline');
            $('.btn-danger').removeClass('btn-danger').addClass('modern-btn modern-btn-danger');
            $('.btn-info').removeClass('btn-info').addClass('modern-btn modern-btn-secondary');
            
            // Modern form styling
            $('input[type="text"], input[type="email"], input[type="password"], input[type="number"], select, textarea').addClass('modern-form-control');
            $('.form-group label').addClass('modern-form-label');
            
            // Modern alert styling
            $('.alert-success').addClass('modern-alert modern-alert-success');
            $('.alert-danger').addClass('modern-alert modern-alert-danger');
            $('.alert-warning').addClass('modern-alert modern-alert-warning');
            $('.alert-info').addClass('modern-alert modern-alert-info');
            
            // Initialize tooltips for buttons with titles
            $('[title]').each(function() {
                $(this).attr('data-tooltip', $(this).attr('title'));
                $(this).removeAttr('title');
            });

            // Enhanced calculation progress for SAW
            if (window.location.href.includes('laporan') || window.location.href.includes('saw')) {
                initCalculationEnhancements();
            }

            // Enhanced form submissions
            $('form').on('submit', function(e) {
                const form = $(this);
                const submitBtn = form.find('button[type="submit"], input[type="submit"]');
                
                if (submitBtn.length) {
                    const originalText = submitBtn.html() || submitBtn.val();
                    submitBtn.prop('disabled', true);
                    
                    if (submitBtn.is('button')) {
                        submitBtn.html('<div class="modern-spinner" style="width: 16px; height: 16px; border-width: 2px; margin-right: 8px;"></div>Memproses...');
                    } else {
                        submitBtn.val('Memproses...');
                    }
                    
                    // Re-enable after 3 seconds as fallback
                    setTimeout(() => {
                        submitBtn.prop('disabled', false);
                        if (submitBtn.is('button')) {
                            submitBtn.html(originalText);
                        } else {
                            submitBtn.val(originalText);
                        }
                    }, 3000);
                }
            });

            // Enhanced table interactions
            $('.table tbody tr').hover(
                function() { $(this).addClass('table-hover-highlight'); },
                function() { $(this).removeClass('table-hover-highlight'); }
            );

            // Smooth scroll for anchor links
            $('a[href^="#"]').on('click', function(e) {
                const target = $(this.getAttribute('href'));
                if (target.length) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top - 80
                    }, 500);
                }
            });

            // Auto-hide alerts after 5 seconds
            $('.modern-alert, .alert').each(function() {
                const alert = $(this);
                setTimeout(() => {
                    alert.fadeOut(300);
                }, 5000);
            });

            // Add loading state to navigation links
            $('.modern-sidebar-link, .action-btn').on('click', function(e) {
                if ($(this).attr('href') && $(this).attr('href') !== '#' && !$(this).attr('href').startsWith('javascript:')) {
                    $('#loadingOverlay').addClass('show');
                }
            });

            // Add confirmation to delete actions
            $('a[href*="hapus"], a[href*="delete"]').on('click', function(e) {
                if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    e.preventDefault();
                }
            });

            // Enhanced CSV/PDF export buttons
            $('a[href*="export"], a[href*="pdf"]').on('click', function() {
                showNotification('info', 'Memproses export data...');
            });
        });

        // Enhanced calculation functions for SAW module
        function initCalculationEnhancements() {
            // Add progress indicators for calculation steps
            $('.table').each(function(index) {
                $(this).attr('data-step', index + 1);
            });

            // Add step indicators
            const stepIndicator = `
                <div class="calculation-steps">
                    <div class="step-indicator">
                        <div class="step active">
                            <span class="step-number">1</span>
                            <span class="step-text">Matriks Nilai</span>
                        </div>
                        <div class="step">
                            <span class="step-number">2</span>
                            <span class="step-text">Normalisasi</span>
                        </div>
                        <div class="step">
                            <span class="step-number">3</span>
                            <span class="step-text">Pembobotan</span>
                        </div>
                        <div class="step">
                            <span class="step-number">4</span>
                            <span class="step-text">Ranking</span>
                        </div>
                    </div>
                </div>
            `;

            // Add enhanced styling for calculation results
            $('h4').each(function() {
                if ($(this).text().includes('Matriks') || $(this).text().includes('Ranking')) {
                    $(this).addClass('calculation-step-title');
                }
            });
        }

        // Global function for showing notifications
        function showNotification(type, message) {
            if (window.modernFramework) {
                window.modernFramework.showAlert(type, message);
            } else {
                alert(message);
            }
        }

        // Global function for showing loading
        function showLoading(message = 'Memuat...') {
            const overlay = document.getElementById('loadingOverlay');
            overlay.querySelector('p').textContent = message;
            overlay.classList.add('show');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('show');
        }
    </script>
</body>
</html>
<?php
}
}
}
?>	
	
		