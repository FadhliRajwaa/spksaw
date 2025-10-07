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
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' rx='20' fill='%23212529'/%3E%3Cpath d='M38 25c-4 0-7 3-7 7 0 2 1 4 2 5l7 7 7-7c1-1 2-3 2-5 0-4-3-7-7-7zm0 4c1 0 2 1 2 2s-1 2-2 2-2-1-2-2 1-2 2-2zM20 55h8c2 0 4 2 4 4v12c0 2-2 4-4 4h-8c-2 0-4-2-4-4V59c0-2 2-4 4-4zm28 0h28c2 0 4 2 4 4v12c0 2-2 4-4 4H48c-2 0-4-2-4-4V59c0-2 2-4 4-4z' fill='white'/%3E%3C/svg%3E">
    <link rel="shortcut icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' rx='20' fill='%23212529'/%3E%3Cpath d='M38 25c-4 0-7 3-7 7 0 2 1 4 2 5l7 7 7-7c1-1 2-3 2-5 0-4-3-7-7-7zm0 4c1 0 2 1 2 2s-1 2-2 2-2-1-2-2 1-2 2-2zM20 55h8c2 0 4 2 4 4v12c0 2-2 4-4 4h-8c-2 0-4-2-4-4V59c0-2 2-4 4-4zm28 0h28c2 0 4 2 4 4v12c0 2-2 4-4 4H48c-2 0-4-2-4-4V59c0-2 2-4 4-4z' fill='white'/%3E%3C/svg%3E">
    <link rel="apple-touch-icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' rx='20' fill='%23212529'/%3E%3Cpath d='M38 25c-4 0-7 3-7 7 0 2 1 4 2 5l7 7 7-7c1-1 2-3 2-5 0-4-3-7-7-7zm0 4c1 0 2 1 2 2s-1 2-2 2-2-1-2-2 1-2 2-2zM20 55h8c2 0 4 2 4 4v12c0 2-2 4-4 4h-8c-2 0-4-2-4-4V59c0-2 2-4 4-4zm28 0h28c2 0 4 2 4 4v12c0 2-2 4-4 4H48c-2 0-4-2-4-4V59c0-2 2-4 4-4z' fill='white'/%3E%3C/svg%3E">
    
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

    <!-- Admin custom theme (system-aware) -->
    <link rel="stylesheet" href="css/admin-theme.css">
    
    <style>
        /* Fix DataTables table duplication issues */
        /* NOTE: Allow DataTables native scroll containers to be visible for horizontal scrolling */
        /* The old rule hid these and prevented scrollX from working */
        /* Re-enable specifically for #example1 to avoid side effects */
        #example1_wrapper .dataTables_scrollHead,
        #example1_wrapper .dataTables_scrollBody,
        #example1_wrapper .dataTables_scrollFoot {
            display: block !important;
        }
        
        /* Ensure only one table is visible */
        .dataTable + .dataTable {
            display: none !important;
        }
        
        /* Prevent DataTables header separation */
        .dataTables_wrapper .dataTables_scroll div.dataTables_scrollHead table,
        .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody table {
            margin: 0 !important;
        }
        
        /* Enable overflow on DataTables scroll containers for #example1 */
        #example1_wrapper .dataTables_scroll,
        #example1_wrapper .dataTables_scroll div.dataTables_scrollHead,
        #example1_wrapper .dataTables_scroll div.dataTables_scrollBody {
            overflow: auto !important;
        }
        
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            font-family: 'Inter', sans-serif;
        }

        /* Remove white boxes from all admin sections */
        .box, .panel, .card, .widget-box {
            background: transparent !important;
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
        }
        
        .box-header, .panel-heading, .card-header {
            background: transparent !important;
            border-bottom: 1px solid rgba(204, 201, 220, 0.2) !important;
            box-shadow: none !important;
        }
        
        .box-body, .panel-body, .card-body {
            background: transparent !important;
            padding: 1rem 0 !important;
        }
        
        /* Flatten form elements */
        .form-group, .input-group {
            margin-bottom: 1rem;
        }
        
        .well, .alert {
            background: rgba(50, 74, 95, 0.05) !important;
            border: 1px solid rgba(204, 201, 220, 0.2) !important;
            border-radius: 4px !important;
            box-shadow: none !important;
        }

        /* Custom Sidebar Styling */
        .modern-sidebar {
            background: var(--gradient-primary);
            box-shadow: 4px 0 20px rgba(12, 24, 33, 0.1);
            color: white !important;
        }

        /* Ensure all text in sidebar is white */
        .modern-sidebar,
        .modern-sidebar *,
        .modern-sidebar a,
        .modern-sidebar span,
        .modern-sidebar div,
        .modern-sidebar li,
        .modern-sidebar p,
        .modern-sidebar h1,
        .modern-sidebar h2,
        .modern-sidebar h3,
        .modern-sidebar h4,
        .modern-sidebar h5,
        .modern-sidebar h6 {
            color: white !important;
        }

        .modern-sidebar-header {
            background: rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: white !important;
        }

        .modern-sidebar-logo {
            font-size: 1.4rem;
            font-weight: 700;
            color: white !important;
        }

        .modern-sidebar-link {
            color: white !important;
            text-decoration: none;
        }

        .modern-sidebar-link.active {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            margin: 0 0.5rem;
            color: white !important;
        }

        .modern-sidebar-link:hover {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            margin: 0 0.5rem;
            color: white !important;
        }

        /* Additional sidebar text elements */
        .modern-sidebar .nav-link,
        .modern-sidebar .nav-item,
        .modern-sidebar .menu-item,
        .modern-sidebar .sidebar-item {
            color: white !important;
        }

        /* Sidebar icons */
        .modern-sidebar i,
        .modern-sidebar .fas,
        .modern-sidebar .fa {
            color: white !important;
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
        
        /* Fix icon visibility issues */
        .modern-header .fas,
        .modern-header i {
            color: #374151 !important;
        }
        
        .modern-header-avatar {
            background: var(--gradient-primary) !important;
            color: white !important;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
        }
        
        .modern-header-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #374151 !important;
            text-decoration: none;
        }
        
        .modern-header-user:hover {
            color: #1f2937 !important;
        }
        
        .user-dropdown-menu .fas,
        .user-dropdown-menu i {
            color: #6b7280 !important;
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

        /* Modern Stats Cards - Flat Design */
        .stat-card {
            background: transparent;
            border-radius: 0;
            padding: 1.5rem 0;
            box-shadow: none;
            border: none;
            border-bottom: 1px solid rgba(204, 201, 220, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: none;
            box-shadow: none;
            background: rgba(50, 74, 95, 0.02);
        }

        .stat-card::before {
            display: none;
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: var(--white);
            background: var(--gradient-accent);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 600;
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

        /* Quick Actions - Flat Design */
        .quick-actions {
            background: transparent;
            border-radius: 0;
            padding: 1.5rem 0;
            box-shadow: none;
            border: none;
            border-bottom: 1px solid rgba(204, 201, 220, 0.2);
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
            background: transparent;
            border: 1px solid rgba(50, 74, 95, 0.15);
            border-radius: 6px;
            text-decoration: none;
            color: var(--primary-dark);
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: rgba(50, 74, 95, 0.05);
            border-color: var(--accent-blue);
            color: var(--primary-dark);
            transform: none;
            box-shadow: none;
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
            background: var(--accent-blue);
            color: var(--white);
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

        /* Dropdown item styling */
        .dropdown-item:hover {
            background: rgba(50, 74, 95, 0.05);
        }

        /* White text for information boxes and alerts in admin sections */
        .alert-info,
        .alert-info *,
        .box-body .alert,
        .box-body .alert *,
        .alert,
        .alert * {
            color: white !important;
        }

        /* Specific white text for perankingan and other admin content */
        .box-body p,
        .box-body div,
        .box-body span,
        .box-body strong,
        .content p,
        .content div,
        .content span,
        .content strong {
            color: white !important;
        }

        /* Information text elements */
        .alert-info strong,
        .alert strong,
        .box-body strong {
            color: white !important;
        }

        /* Target specific informational content */
        .modern-main p,
        .modern-main div:not(.btn):not(.label):not(.badge),
        .modern-main span:not(.btn):not(.label):not(.badge),
        .modern-main strong {
            color: white !important;
        }

        /* Table content text - keep readable */
        .table,
        .table *,
        .table th,
        .table td {
            color: #1a202c !important;
        }

        /* Modern table visual fixes - unify header/body and borders in dark theme */
        .modern-main .table {
            border-collapse: collapse !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
        }
        .modern-main .table thead th {
            background: rgba(255, 255, 255, 0.06);
            color: #e5e7eb !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            white-space: nowrap;
            vertical-align: middle;
            font-weight: 600;
        }
        .modern-main .table th,
        .modern-main .table td {
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            color: #e5e7eb !important;
        }

        /* Button text should remain readable */
        .btn,
        .btn *,
        .label,
        .label *,
        .badge,
        .badge * {
            color: inherit !important;
        }

        /* Fix button text visibility - ensure buttons have proper contrast */
        .btn-default,
        .btn-default *,
        .btn-secondary,
        .btn-secondary * {
            color: #374151 !important;
            background: rgba(255, 255, 255, 0.9) !important;
            border: 1px solid rgba(156, 163, 175, 0.5) !important;
        }

        .btn-default:hover,
        .btn-secondary:hover {
            color: #1f2937 !important;
            background: rgba(255, 255, 255, 1) !important;
            border-color: rgba(156, 163, 175, 0.8) !important;
        }

        /* Ensure other button types remain readable */
        .btn-primary,
        .btn-primary * {
            color: white !important;
        }

        .btn-success,
        .btn-success * {
            color: white !important;
        }

        .btn-info,
        .btn-info * {
            color: white !important;
        }

        .btn-warning,
        .btn-warning * {
            color: #1f2937 !important;
        }

        .btn-danger,
        .btn-danger * {
            color: white !important;
        }

        /* Hide white boxes in top right corner across all admin sections */
        .box-tools,
        .box-tools *,
        .pull-right .btn-group,
        .pull-right .btn-group *,
        .modern-header-right .btn,
        .modern-header-right .btn-group,
        .content-header .btn-group,
        .content-header .box-tools {
            display: none !important;
        }

        /* Hide floating action buttons and tool boxes */
        .btn-group.pull-right,
        .box-header .box-tools,
        .content-wrapper .btn-group.pull-right,
        .main-content .btn-group.pull-right {
            display: none !important;
        }

        /* Ensure no white floating elements in header areas */
        .modern-header .btn-group,
        .modern-header .pull-right,
        .box-header .pull-right {
            display: none !important;
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

    <!-- Overlay for mobile sidebar -->
    <div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>

    <!-- Modern Header -->
    <header class="modern-header" id="header">
        <div class="modern-header-left">
            <button class="mobile-menu-toggle" id="sidebarToggle" type="button" aria-label="Toggle navigation">
                <i class="fas fa-bars" aria-hidden="true"></i>
            </button>
            <h1 class="modern-header-title">
                <i class="fas fa-hand-holding-heart" style="margin-right: 0.5rem; color: #3b82f6;"></i>
                <?php 
                if (isset($_GET['module'])) {
                    switch($_GET['module']) {
                        case 'home': echo 'Dashboard'; break;
                        case 'warga': echo 'Data Warga'; break;
                        case 'kriteria': echo 'Data Kriteria'; break;
                        case 'laporan': echo 'Perhitungan SAW'; break;
                        case 'perankingan': echo 'Hasil Perankingan'; break;
                        case 'pembobotan': echo 'Pembobotan Kriteria'; break;
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
                // Set error mode to silent
                $.fn.dataTable.ext.errMode = 'none';
                
                // Exclude tables that should not be DataTables
                var excludeSelectors = '.no-datatables table, .himpunan-table, table.no-datatable';
                
                // Configure specific tables separately
                if ($('#example1').length > 0 && !$('#example1').closest('.no-datatables, .himpunan-table').length) {
                    // Data tables without horizontal scroll to keep header/body unified
                    $('#example1').DataTable({
                        responsive: false,
                        scrollX: false,
                        scrollCollapse: false,
                        autoWidth: true,
                        pageLength: 10,
                        language: {
                            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                        },
                        dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center"l><"d-flex align-items-center"f>>rtip',
                        destroy: true,
                        drawCallback: function() {
                            $('.pagination').addClass('modern-pagination');
                            $('.page-link').addClass('modern-pagination-btn');
                        }
                    });
                }
                
                // Other tables with responsive mode (excluding certain classes)
                $('.modern-table:not(.no-datatable), #example2, #example3, #example4').not(excludeSelectors).DataTable({
                    responsive: true,
                    scrollX: false,
                    pageLength: 10,
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                    },
                    dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center"l><"d-flex align-items-center"f>>rtip',
                    destroy: true,
                    drawCallback: function() {
                        $('.pagination').addClass('modern-pagination');
                        $('.page-link').addClass('modern-pagination-btn');
                    }
                });
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

        // DataTable enhancements
        function initDataTableEnhancements() {
            // Initialize DataTables with responsive design
            if ($.fn.DataTable) {
                // Only initialize DataTables for specific safe modules
                const currentModule = new URLSearchParams(window.location.search).get('module');
                const safeModules = ['ranking', 'admin', 'siswa'];
                
                if (!safeModules.includes(currentModule)) {
                    return; // Skip DataTable initialization for problematic modules
                }
                
                try {
                    const $tables = $('#example1').filter(function() {
                        const isCustomScroll = $(this).closest('[style*="overflow-x: scroll"]').length > 0;
                        const isKlasifikasiTable = $(this).attr('id') === 'klasifikasi-table';
                        return !isCustomScroll && !isKlasifikasiTable;
                    });
                    
                    $tables.each(function() {
                        const $table = $(this);
                        
                        // Skip if table is already a DataTable
                        if ($.fn.DataTable.isDataTable($table)) {
                            return;
                        }
                        
                        setTimeout(function() {
                            if ($table.length && $table.is(':visible') && $table.find('tbody tr').length > 0) {
                                try {
                                    $table.DataTable({
                                        responsive: false,
                                        lengthChange: false,
                                        autoWidth: true,
                                        pageLength: 10,
                                        language: {
                                            search: "Cari:",
                                            lengthMenu: "Tampilkan _MENU_ data",
                                            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                                            infoEmpty: "Tidak ada data",
                                            infoFiltered: "(difilter dari _MAX_ total data)",
                                            paginate: {
                                                first: "Pertama",
                                                last: "Terakhir",
                                                next: "Selanjutnya",
                                                previous: "Sebelumnya"
                                            }
                                        }
                                    });
                                } catch (e) {
                                    // Silent fail
                                }
                            }
                        }, 100);
                    });
                } catch (e) {
                    // Silent fail
                }
            }
            
            // Enhanced CSV/PDF export buttons
            $('a[href*="export"], a[href*="pdf"]').on('click', function() {
                showNotification('info', 'Memproses export data...');
            });
        }

        // Mobile responsive enhancements
        function initMobileEnhancements() {
            // Add mobile menu toggle functionality
            const mobileToggle = document.querySelector('#sidebarToggle');
            const sidebar = document.querySelector('.modern-sidebar');
            const overlay = document.querySelector('#sidebarOverlay');
            
            if (mobileToggle && sidebar) {
                mobileToggle.addEventListener('click', function() {
                    // Toggle class names that CSS recognizes
                    sidebar.classList.toggle('open');
                    sidebar.classList.toggle('active');
                    
                    // Create overlay if it doesn't exist
                    if (!overlay) {
                        const newOverlay = document.createElement('div');
                        newOverlay.id = 'sidebarOverlay';
                        newOverlay.className = 'sidebar-overlay active';
                        document.body.appendChild(newOverlay);
                        newOverlay.addEventListener('click', function() {
                            sidebar.classList.remove('open');
                            sidebar.classList.remove('active');
                            newOverlay.classList.remove('active');
                        });
                    } else {
                        overlay.classList.toggle('active');
                    }
                });
            }

            // Close mobile menu when clicking menu items
            const sidebarLinks = document.querySelectorAll('.modern-sidebar-link');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 991) {
                        sidebar.classList.remove('open');
                        sidebar.classList.remove('active');
                        const currentOverlay = document.querySelector('#sidebarOverlay');
                        if (currentOverlay) {
                            currentOverlay.classList.remove('active');
                        }
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 991) {
                    sidebar.classList.remove('open');
                    sidebar.classList.remove('active');
                    const currentOverlay = document.querySelector('#sidebarOverlay');
                    if (currentOverlay) {
                        currentOverlay.classList.remove('active');
                    }
                }
            });
        }

        // Initialize all enhancements when DOM is ready
        $(document).ready(function() {
            initMobileEnhancements();
            initDataTableEnhancements();
            initCalculationEnhancements();
        });

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
    
    <!-- Print Functions -->
    <script src="assets/js/print-functions.js"></script>
</body>
</html>
<?php
}
}
}
?>

