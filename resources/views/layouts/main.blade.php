<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>@yield('title', 'Inventori Gudang IT')</title>
    
    <!-- Bootstrap 5 CSS (Local) -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap/bootstrap.min.css') }}">
    <!-- Bootstrap Icons (Local) -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-icons.css') }}">
    <!-- Font Awesome (Local) -->
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}">
    
    <style>
        /* ========================================
           CSS VARIABLES - Professional Theme
        ======================================== */
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #818cf8;
            --secondary-color: #0ea5e9;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            
            --sidebar-bg: #1e1b4b;
            --sidebar-hover: #312e81;
            --sidebar-active: #4338ca;
            
            --body-bg: #f1f5f9;
            --card-bg: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            
            --border-color: #e2e8f0;
            --border-radius: 12px;
            --border-radius-sm: 8px;
            --border-radius-lg: 16px;
            
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-md: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            
            --transition: all 0.3s ease;
            --sidebar-width: 260px;
            --navbar-height: 64px;
        }

        /* ========================================
           BASE STYLES
        ======================================== */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        html, body {
            height: 100%;
            width: 100%;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: var(--text-primary);
            background-color: var(--body-bg);
            overflow-x: hidden;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: var(--text-muted);
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }

        /* ========================================
           NAVBAR
        ======================================== */
        .navbar-fixed {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            height: var(--navbar-height);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            box-shadow: var(--shadow-md);
            border: none;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            color: #fff !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand i {
            font-size: 1.5rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
        }

        .user-name {
            color: rgba(255,255,255,0.9);
            font-weight: 500;
        }

        .btn-logout {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            color: #fff;
            padding: 8px 16px;
            border-radius: var(--border-radius-sm);
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-logout:hover {
            background: rgba(255,255,255,0.25);
            color: #fff;
            transform: translateY(-1px);
        }

        /* ========================================
           SIDEBAR
        ======================================== */
        .sidebar-fixed {
            position: fixed;
            top: var(--navbar-height);
            bottom: 0;
            left: 0;
            width: var(--sidebar-width);
            z-index: 100;
            overflow-y: auto;
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #0f0a2e 100%);
            padding: 24px 16px;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 0 8px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }

        .sidebar-title {
            color: rgba(255,255,255,0.5);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu-item {
            margin-bottom: 4px;
        }

        .sidebar-menu-item a {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
            font-weight: 500;
            font-size: 14px;
        }

        .sidebar-menu-item a:hover {
            background: var(--sidebar-hover);
            color: #fff;
            transform: translateX(4px);
        }

        .sidebar-menu-item a.active {
            background: var(--sidebar-active);
            color: #fff;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
        }

        .sidebar-menu-item a i {
            width: 24px;
            font-size: 1.2rem;
            margin-right: 12px;
        }

        .menu-divider {
            height: 1px;
            background: rgba(255,255,255,0.1);
            margin: 20px 0;
        }

        /* Menu Colors */
        .menu-dashboard a i { color: var(--primary-light); }
        .menu-master a i { color: #a78bfa; }
        .menu-masuk a i { color: var(--success-color); }
        .menu-keluar a i { color: var(--danger-color); }
        .menu-laporan a i { color: var(--warning-color); }

        /* ========================================
           MAIN CONTENT
        ======================================== */
        .content-wrapper {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            min-height: calc(100vh - var(--navbar-height));
            padding: 24px;
            background-color: var(--body-bg);
        }

        /* ========================================
           CARDS
        ======================================== */
        .card {
            background: var(--card-bg);
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 16px 20px;
            font-weight: 600;
        }

        .card-body {
            padding: 20px;
        }

        /* Stat Cards */
        .stat-card {
            border-radius: var(--border-radius);
            border: none;
            overflow: hidden;
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card .card-body {
            padding: 24px;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: var(--border-radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-icon.primary { background: rgba(79, 70, 229, 0.1); color: var(--primary-color); }
        .stat-icon.success { background: rgba(16, 185, 129, 0.1); color: var(--success-color); }
        .stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: var(--warning-color); }
        .stat-icon.danger { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); }
        .stat-icon.info { background: rgba(6, 182, 212, 0.1); color: var(--info-color); }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* ========================================
           FORMS
        ======================================== */
        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius-sm);
            padding: 12px 16px;
            font-size: 14px;
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .input-group-text {
            background: var(--primary-color);
            border: none;
            color: #fff;
            font-weight: 500;
        }

        /* ========================================
           BUTTONS
        ======================================== */
        .btn {
            padding: 10px 20px;
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            font-size: 14px;
            transition: var(--transition);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            border: none;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
            border: none;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
            border: none;
            color: #fff;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
            color: #fff;
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: #fff;
        }

        /* ========================================
           TABLES
        ======================================== */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: var(--body-bg);
            border-bottom: 2px solid var(--border-color);
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 16px;
        }

        .table tbody td {
            padding: 16px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }

        .table tbody tr:hover {
            background: rgba(79, 70, 229, 0.02);
        }

        /* ========================================
           SCANNER
        ======================================== */
        .scanner-container {
            width: 100%;
            max-width: 100%;
            height: 280px;
            background: linear-gradient(135deg, #1e1b4b 0%, #0f0a2e 100%);
            border-radius: var(--border-radius);
            overflow: hidden;
            position: relative;
            border: 3px solid var(--primary-color);
        }

        .scanner-container video,
        .scanner-container canvas,
        .scanner-container #interactive {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }

        .scanner-container #interactive {
            position: absolute;
            top: 0;
            left: 0;
        }

        .scanner-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70%;
            height: 60%;
            border: 2px dashed rgba(255,255,255,0.5);
            border-radius: var(--border-radius-sm);
            pointer-events: none;
        }

        .scanner-hint {
            position: absolute;
            bottom: 16px;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255,255,255,0.7);
            font-size: 12px;
            text-align: center;
        }

        /* ========================================
           TABS
        ======================================== */
        .nav-tabs {
            border-bottom: 2px solid var(--border-color);
        }

        .nav-tabs .nav-link {
            border: none;
            color: var(--text-secondary);
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 0;
            position: relative;
            transition: var(--transition);
        }

        .nav-tabs .nav-link:hover {
            color: var(--primary-color);
            border: none;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            background: transparent;
            border: none;
        }

        .nav-tabs .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary-color);
            border-radius: 3px 3px 0 0;
        }

        /* ========================================
           ALERTS
        ======================================== */
        .alert {
            border: none;
            border-radius: var(--border-radius-sm);
            padding: 16px 20px;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #047857;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #b91c1c;
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.1);
            color: #b45309;
        }

        .alert-info {
            background: rgba(6, 182, 212, 0.1);
            color: #0e7490;
        }

        /* ========================================
           BADGES
        ======================================== */
        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12px;
        }

        /* ========================================
           PAGE HEADER
        ======================================== */
        .page-header {
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
        }

        /* ========================================
           MOBILE RESPONSIVE
        ======================================== */
        @media (max-width: 767.98px) {
            .sidebar-fixed {
                transform: translateX(-100%);
                width: 280px;
            }
            .sidebar-fixed.show {
                transform: translateX(0);
            }
            .content-wrapper {
                margin-left: 0;
                margin-top: 60px;
                padding: 16px 12px;
            }
            .navbar-fixed {
                height: 60px;
            }
            .page-title {
                font-size: 1.4rem;
            }
            .card {
                margin-bottom: 16px;
            }
            .btn {
                width: 100%;
                margin-bottom: 8px;
            }
            .table {
                font-size: 13px;
            }
            .scanner-container {
                height: 250px;
            }
        }

        @media (min-width: 768px) {
            .navbar-toggler-visible {
                display: none !important;
            }
        }

        /* ========================================
           UTILITY
        ======================================== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 99;
            backdrop-filter: blur(2px);
        }
        .sidebar-overlay.show {
            display: block;
        }

        .text-gradient {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.3s ease;
        }
    </style>
</head>
<body>
    @if(Session::get('user_logged_in'))
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-fixed">
        <div class="container-fluid">
            <button class="navbar-toggler me-2 navbar-toggler-visible" type="button" id="sidebarToggle">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-boxes"></i>
                <span>Inventori Gudang</span>
            </a>
            <div class="user-info">
                <span class="user-name d-none d-md-inline">{{ Session::get('user_name') }}</span>
                @if(Session::get('user_role') === 'admin')
                <span class="badge bg-danger ms-1">Admin</span>
                @else
                <span class="badge bg-info ms-1">User</span>
                @endif
                <div class="user-avatar">
                    <i class="bi bi-person"></i>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-logout">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="d-none d-md-inline">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>
    
    <!-- Sidebar Overlay (for mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <nav class="sidebar-fixed" id="sidebarMenu">
        <div class="sidebar-header">
            <span class="sidebar-title">Menu Utama</span>
        </div>
        
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item menu-dashboard">
                <a class="{{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-1x2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            @if(Session::get('user_role') === 'admin')
            <li class="sidebar-menu-item menu-master">
                <a class="{{ Request::is('master-barang*') ? 'active' : '' }}" href="{{ route('master.barang') }}">
                    <i class="bi bi-box-seam"></i>
                    <span>Master Barang</span>
                </a>
            </li>
            @endif
            
            <li class="sidebar-menu-item menu-masuk">
                <a class="{{ Request::is('barang-masuk*') ? 'active' : '' }}" href="{{ route('barang.masuk') }}">
                    <i class="bi bi-arrow-down-circle"></i>
                    <span>Barang Masuk</span>
                </a>
            </li>
            
            <li class="sidebar-menu-item menu-keluar">
                <a class="{{ Request::is('barang-keluar*') ? 'active' : '' }}" href="{{ route('barang.keluar') }}">
                    <i class="bi bi-arrow-up-circle"></i>
                    <span>Barang Keluar</span>
                </a>
            </li>

            @if(Session::get('user_role') === 'admin')
            <li class="sidebar-menu-item menu-retur">
                <a class="{{ Request::is('barang-retur*') ? 'active' : '' }}" href="{{ route('barang.retur') }}">
                    <i class="bi bi-arrow-return-left"></i>
                    <span>Barang Retur</span>
                </a>
            </li>

            <li class="sidebar-menu-item menu-rusak">
                <a class="{{ Request::is('barang-rusak*') ? 'active' : '' }}" href="{{ route('barang.rusak') }}">
                    <i class="bi bi-exclamation-triangle"></i>
                    <span>Barang Rusak</span>
                </a>
            </li>
            @endif
            
            <li class="menu-divider"></li>
            
            <li class="sidebar-menu-item menu-laporan">
                <a class="{{ Request::is('laporan*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
                    <i class="bi bi-bar-chart-line"></i>
                    <span>Laporan</span>
                </a>
            </li>
            
            @if(Session::get('user_role') === 'admin')
            <li class="sidebar-menu-item menu-laporan">
                <a class="{{ Request::is('laporan-rak*') ? 'active' : '' }}" href="{{ route('laporan.rak') }}">
                    <i class="bi bi-boxes"></i>
                    <span>Laporan per Rak</span>
                </a>
            </li>

            <li class="sidebar-menu-item menu-laporan">
                <a class="{{ Request::is('laporan-rusak*') ? 'active' : '' }}" href="{{ route('laporan.rusak') }}">
                    <i class="bi bi-file-earmark-x"></i>
                    <span>Laporan Barang Rusak</span>
                </a>
            </li>

            <li class="menu-divider"></li>

            <li class="sidebar-menu-item menu-users">
                <a class="{{ Request::is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="bi bi-people"></i>
                    <span>Manajemen Akun</span>
                </a>
            </li>
            @endif
        </ul>
    </nav>
    
    <!-- Main Content -->
    <div class="content-wrapper">
        @yield('content')
    </div>
    
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebarMenu').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        });
        
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            document.getElementById('sidebarMenu').classList.remove('show');
            document.getElementById('sidebarOverlay').classList.remove('show');
        });
    </script>
    @else
    <!-- Not logged in - full width content -->
    @yield('content')
    @endif

    <!-- Bootstrap 5 JS (Local) -->
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- Barcode Scanning JS -->
    <script src="{{ asset('assets/js/quagga.min.js') }}"></script>
    <script src="{{ asset('assets/js/JsBarcode.all.min.js') }}"></script>
    
    @yield('scripts')
</body>
</html>
