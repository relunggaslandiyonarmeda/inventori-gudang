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
           CSS VARIABLES - Elegant Professional Theme
        ======================================== */
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #3b82f6;
            --secondary-color: #0284c7;
            --success-color: #059669;
            --warning-color: #d97706;
            --danger-color: #dc2626;
            --info-color: #0891b2;
            
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --sidebar-active: #2563eb;
            
            --body-bg: #f8fafc;
            --card-bg: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            
            --border-color: #e2e8f0;
            --border-radius: 16px;
            --border-radius-sm: 12px;
            --border-radius-lg: 20px;
            
            --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-md: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            
            --transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            --sidebar-width: 280px;
            --navbar-height: 72px;
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
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            box-shadow: var(--shadow-sm);
            border: none;
            backdrop-filter: saturate(180%) blur(20px);
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
           GLOBAL SEARCH
        ======================================== */
        .global-search-wrapper {
            position: relative;
            width: 260px;
            margin-right: 16px;
        }
        
        @media (min-width: 992px) {
            .global-search-wrapper {
                width: 290px;
            }
        }
        
        @media (max-width: 991px) {
            .global-search-wrapper {
                width: 100%;
                margin: 10px 0;
            }
        }
        
        .global-search-wrapper .input-group {
            border-radius: var(--border-radius-sm);
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .global-search-wrapper .input-group:focus-within {
            border-color: rgba(255,255,255,0.4);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }
        
        .global-search-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            max-height: 420px;
            overflow-y: auto;
            z-index: 9999;
            display: none;
            border: 1px solid var(--border-color);
            margin-top: 8px;
        }
        
        .global-search-dropdown.show {
            display: block;
            animation: fadeIn 0.2s ease;
        }
        
        .global-search-result {
            display: flex;
            align-items: center;
            padding: 14px 18px;
            text-decoration: none;
            color: var(--text-primary);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            transition: var(--transition);
            background: var(--card-bg);
        }
        
        .global-search-result:hover {
            background: rgba(37, 99, 235, 0.03);
            color: var(--primary-color);
            transform: translateX(4px);
        }
        
        .global-search-result:last-child {
            border-bottom: none;
        }
        
        .global-search-result-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 14px;
            flex-shrink: 0;
            font-size: 1.1rem;
        }
        
        .global-search-result-icon.master { background: rgba(37, 99, 235, 0.1); color: var(--primary-color); }
        .global-search-result-icon.masuk { background: rgba(5, 150, 105, 0.1); color: var(--success-color); }
        .global-search-result-icon.keluar { background: rgba(220, 38, 38, 0.1); color: var(--danger-color); }
        .global-search-result-icon.rusak { background: rgba(220, 38, 38, 0.1); color: var(--danger-color); }
        .global-search-result-icon.retur { background: rgba(8, 145, 178, 0.1); color: var(--info-color); }
        
        .global-search-result-content {
            flex: 1;
            min-width: 0;
        }
        
        .global-search-result-title {
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .global-search-result-subtitle {
            font-size: 13px;
            color: var(--text-secondary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .global-search-type-badge {
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 600;
            text-transform: uppercase;
            flex-shrink: 0;
        }
        
        .global-search-loading {
            padding: 24px;
            text-align: center;
            color: var(--text-secondary);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }
        
        .global-search-no-results {
            padding: 24px;
            text-align: center;
            color: var(--text-secondary);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }
        
        .global-search-loading i,
        .global-search-no-results i {
            font-size: 2.5rem;
            color: var(--primary-color);
            animation: pulse 2s infinite;
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
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #0f172a 100%);
            padding: 28px 20px;
            transition: transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            border-right: 1px solid rgba(255,255,255,0.05);
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
            padding: 14px 18px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
            font-weight: 500;
            font-size: 14px;
            gap: 12px;
        }

        .sidebar-menu-item a:hover {
            background: var(--sidebar-hover);
            color: #fff;
            transform: translateX(6px);
        }

        .sidebar-menu-item a.active {
            background: var(--sidebar-active);
            color: #fff;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
            border-left: 3px solid rgba(255,255,255,0.2);
        }

        .sidebar-menu-item a i {
            width: 26px;
            font-size: 1.25rem;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .menu-divider {
            height: 1px;
            background: rgba(255,255,255,0.1);
            margin: 20px 0;
        }

        /* Submenu Styles - Improved */
        .sidebar-menu-item {
            position: relative;
        }

        .sidebar-menu-item > a {
            display: flex;
            align-items: center;
        }

        .sidebar-menu-item > a .bi-chevron-down {
            font-size: 10px;
            margin-left: auto;
            transition: transform 0.3s;
        }

        .sidebar-menu-item:hover > a .bi-chevron-down,
        .sidebar-menu-item > a.active + .sidebar-submenu ~ a .bi-chevron-down {
            transform: rotate(180deg);
        }

        .sidebar-submenu {
            list-style: none;
            padding-left: 0;
            margin: 0;
            display: none;
            background: rgba(0,0,0,0.15);
            border-radius: 12px;
            margin-top: 6px;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-menu-item:hover > .sidebar-submenu,
        .sidebar-menu-item .sidebar-submenu:hover {
            display: block;
        }

        .sidebar-menu-item .sidebar-submenu li a {
            padding: 12px 18px 12px 46px;
            font-size: 13px;
            color: rgba(255,255,255,0.7);
            border-radius: 10px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .sidebar-menu-item .sidebar-submenu li a:hover {
            background: var(--sidebar-hover);
            color: #fff;
            padding-left: 50px;
        }

        .sidebar-menu-item .sidebar-submenu li a.active {
            background: var(--sidebar-active);
            color: #fff;
        }

        .sidebar-menu-item .sidebar-submenu li a i {
            font-size: 14px;
            width: 18px;
        }

        /* Menu Colors */
        .menu-dashboard a i { color: var(--primary-light); }
        .menu-master a i { color: #a78bfa; }
        .menu-masuk a i { color: var(--success-color); }
        .menu-keluar a i { color: var(--danger-color); }
        .menu-retur a i { color: #fbbf24; }
        .menu-rusak a i { color: #dc2626; }
        .menu-laporan a i { color: var(--warning-color); }

        /* ========================================
           MAIN CONTENT
        ======================================== */
        .content-wrapper {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            min-height: calc(100vh - var(--navbar-height));
            padding: 32px;
            background-color: var(--body-bg);
        }

        /* ========================================
           CARDS
        ======================================== */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            overflow: hidden;
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
            border-radius: var(--border-radius-lg);
            border: none;
            overflow: hidden;
            background: var(--card-bg);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            position: relative;
            border-top: 4px solid transparent;
        }
        
        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-md);
            border-top-color: var(--primary-color);
        }
        
        .stat-card .card-body {
            padding: 28px;
        }
        
        .stat-icon {
            width: 64px;
            height: 64px;
            border-radius: var(--border-radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 16px;
            flex-shrink: 0;
        }
        
        .stat-icon.primary { background: rgba(37, 99, 235, 0.1); color: var(--primary-color); }
        .stat-icon.success { background: rgba(5, 150, 105, 0.1); color: var(--success-color); }
        .stat-icon.warning { background: rgba(217, 119, 6, 0.1); color: var(--warning-color); }
        .stat-icon.danger { background: rgba(220, 38, 38, 0.1); color: var(--danger-color); }
        .stat-icon.info { background: rgba(8, 145, 178, 0.1); color: var(--info-color); }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.1;
            margin: 8px 0;
            letter-spacing: -0.5px;
        }
        
        .stat-label {
            color: var(--text-secondary);
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 0.25px;
        }
        
        .stat-card small.text-muted {
            font-size: 0.875rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ========================================
           FORMS
        ======================================== */
        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 10px;
            display: block;
            font-size: 0.95rem;
        }
        
        .form-control, .form-select {
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius-sm);
            padding: 14px 18px;
            font-size: 14px;
            transition: var(--transition);
            background: var(--card-bg);
            color: var(--text-primary);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
            outline: none;
        }
        
        .form-control::placeholder, .form-select::placeholder {
            color: var(--text-muted);
            opacity: 0.7;
        }
        
        .input-group-text {
            background: var(--primary-color);
            border: none;
            color: #fff;
            font-weight: 500;
            padding: 0 16px;
            border-radius: var(--border-radius-sm) 0 0 var(--border-radius-sm);
            white-space: nowrap;
        }
        
        .input-group .form-control:not(:first-child),
        .input-group .form-select:not(:first-child) {
            border-radius: 0 var(--border-radius-sm) var(--border-radius-sm) 0;
        }

        /* ========================================
           BUTTONS
        ======================================== */
        .btn {
            padding: 12px 24px;
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            font-size: 14px;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            letter-spacing: 0.25px;
            position: relative;
            overflow: hidden;
        }
        
        .btn:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
        }
        
        .btn i {
            font-size: 1.1rem;
        }
        
        .btn-primary {
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
            background: linear-gradient(180deg, var(--primary-dark) 0%, #1e40af 100%);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .btn-success {
            background: linear-gradient(180deg, var(--success-color) 0%, #047857 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(5, 150, 105, 0.4);
            background: linear-gradient(180deg, #047857 0%, #065f46 100%);
        }
        
        .btn-danger {
            background: linear-gradient(180deg, var(--danger-color) 0%, #b91c1c 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }
        
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(220, 38, 38, 0.4);
            background: linear-gradient(180deg, #b91c1c 0%, #991b1b 100%);
        }
        
        .btn-warning {
            background: linear-gradient(180deg, var(--warning-color) 0%, #b45309 100%);
            border: none;
            color: #fff;
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(217, 119, 6, 0.4);
            background: linear-gradient(180deg, #b45309 0%, #92400e 100%);
            color: #fff;
        }
        
        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: #fff;
        }
        
        .btn-outline-primary:active {
            transform: translateY(0);
        }
        
        .btn-sm {
            padding: 8px 16px;
            font-size: 13px;
        }
        
        .btn-lg {
            padding: 16px 32px;
            font-size: 16px;
        }
        
        .btn-block {
            width: 100%;
        }

        /* ========================================
           TABLES
        ======================================== */
        .table {
            margin-bottom: 0;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }
        
        .table thead th {
            background: linear-gradient(180deg, var(--body-bg) 0%, #f1f5f9 100%);
            border-bottom: 2px solid var(--border-color);
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 18px 16px;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .table tbody td {
            padding: 18px 16px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
        }
        
        .table tbody tr {
            transition: var(--transition);
        }
        
        .table tbody tr:hover {
            background: rgba(37, 99, 235, 0.03);
            transform: translateY(-1px);
        }
        
        .table tbody tr:nth-child(even):hover {
            background: rgba(37, 99, 235, 0.05);
        }
        
        .table tbody td:first-child,
        .table thead th:first-child {
            border-top-left-radius: var(--border-radius);
            border-bottom-left-radius: var(--border-radius);
        }
        
        .table tbody td:last-child,
        .table thead th:last-child {
            border-top-right-radius: var(--border-radius);
            border-bottom-right-radius: var(--border-radius);
        }
        
        .table tfoot td {
            background: var(--body-bg);
            border-top: 2px solid var(--border-color);
            font-weight: 600;
            color: var(--text-primary);
            padding: 16px;
        }
        
        .table-responsive {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            margin-bottom: 1.5rem;
        }

        /* ========================================
           SCANNER
        ======================================== */
        .scanner-container {
            width: 100%;
            max-width: 100%;
            height: 300px;
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            position: relative;
            border: 3px solid rgba(255,255,255,0.2);
            box-shadow: var(--shadow-lg);
            backdrop-filter: saturate(180%) blur(10px);
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
            width: 75%;
            height: 55%;
            border: 3px dashed rgba(255,255,255,0.5);
            border-radius: var(--border-radius-sm);
            pointer-events: none;
            animation: pulse 2s infinite;
        }
        
        .scanner-hint {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255,255,255,0.8);
            font-size: 13px;
            font-weight: 500;
            text-align: center;
            background: rgba(0,0,0,0.3);
            padding: 8px 16px;
            border-radius: 20px;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.2);
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
            padding: 18px 22px;
            font-weight: 500;
            font-size: 14px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            position: relative;
            overflow: hidden;
        }
        
        .alert::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background-color: inherit;
            opacity: 0.7;
        }
        
        .alert-success {
            background: rgba(5, 150, 105, 0.08);
            color: #047857;
        }
        
        .alert-success::before {
            background: var(--success-color);
        }
        
        .alert-danger {
            background: rgba(220, 38, 38, 0.08);
            color: #b91c1c;
        }
        
        .alert-danger::before {
            background: var(--danger-color);
        }
        
        .alert-warning {
            background: rgba(217, 119, 6, 0.08);
            color: #b45309;
        }
        
        .alert-warning::before {
            background: var(--warning-color);
        }
        
        .alert-info {
            background: rgba(8, 145, 178, 0.08);
            color: #0e7490;
        }
        
        .alert-info::before {
            background: var(--info-color);
        }
        
        .alert-icon {
            flex-shrink: 0;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-top: 2px;
        }
        
        .alert-success .alert-icon {
            background: rgba(5, 150, 105, 0.2);
            color: var(--success-color);
        }
        
        .alert-danger .alert-icon {
            background: rgba(220, 38, 38, 0.2);
            color: var(--danger-color);
        }
        
        .alert-warning .alert-icon {
            background: rgba(217, 119, 6, 0.2);
            color: var(--warning-color);
        }
        
        .alert-info .alert-icon {
            background: rgba(8, 145, 178, 0.2);
            color: var(--info-color);
        }
        
        .alert-title {
            font-weight: 600;
            margin-bottom: 4px;
            display: block;
        }
        
        .alert-dismissible {
            padding-right: 32px;
        }
        
        .btn-close {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 24px;
            height: 24px;
            padding: 4px;
            background: rgba(0,0,0,0.1);
            border-radius: 50%;
            opacity: 0.7;
            transition: var(--transition);
        }
        
        .btn-close:hover {
            background: rgba(0,0,0,0.2);
            opacity: 1;
            transform: translateY(-50%) scale(1.1);
        }

        /* ========================================
           BADGES
        ======================================== */
        .badge {
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: none;
            letter-spacing: 0.25px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }
        
        .badge i {
            font-size: 0.9rem;
        }
        
        .badge.bg-light {
            background: rgba(248, 250, 252, 0.5);
            color: var(--text-primary);
        }
        
        .badge.bg-light.text-dark {
            color: var(--text-secondary);
        }

        /* ========================================
           PAGE HEADER
        ======================================== */
        .page-header {
            margin-bottom: 32px;
            padding: 0 8px;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 8px;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .page-title i {
            font-size: 1.75rem;
            line-height: 1;
        }
        
        .page-subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
            font-weight: 500;
            margin: 0;
            line-height: 1.5;
        }
        
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 8px 0 0;
            border-radius: var(--border-radius-sm);
            background: var(--card-bg);
            box-shadow: var(--shadow-sm);
        }
        
        .breadcrumb-item {
            font-size: 0.9rem;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            color: var(--text-muted);
            font-size: 0.85rem;
        }
        
        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            padding: 2px 6px;
            border-radius: 4px;
            transition: var(--transition);
        }
        
        .breadcrumb-item a:hover {
            background: rgba(37, 99, 235, 0.08);
            color: var(--primary-dark);
        }
        
        .breadcrumb-item.active {
            color: var(--text-secondary);
        }

        /* ========================================
           MOBILE RESPONSIVE
        ======================================== */
        @media (max-width: 767.98px) {
            .sidebar-fixed {
                transform: translateX(-100%);
                width: 300px;
                z-index: 1030;
            }
            .sidebar-fixed.show {
                transform: translateX(0);
            }
            .content-wrapper {
                margin-left: 0;
                margin-top: 68px;
                padding: 20px 16px;
            }
            .navbar-fixed {
                height: 68px;
            }
            
            .global-search-wrapper {
                width: 100% !important;
                position: absolute;
                left: 0;
                right: 0;
                top: 68px;
                padding: 10px 16px;
                background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-dark) 100%);
                display: block !important;
                z-index: 1031;
            }
            
            .global-search-wrapper .input-group {
                width: 100%;
            }
            
            .page-title {
                font-size: 1.75rem;
            }
            
            .card {
                margin-bottom: 20px;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
            
            .table {
                font-size: 14px;
            }
            
            .scanner-container {
                height: 260px;
            }
            
            .stat-value {
                font-size: 2rem;
            }
            
            .page-title {
                font-size: 1.75rem;
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
        
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease;
        }
        
        .slide-in-up {
            animation: slideInUp 0.4s ease;
        }
        
        .btn-pulse {
            animation: pulse 1.5s infinite;
        }
        
        .shimmer {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        /* Hover effects */
        .hover-lift {
            transition: var(--transition);
        }
        
        .hover-lift:hover {
            transform: translateY(-4px);
        }
        
        /* Card enhancements */
        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 20px 24px;
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card-header i {
            font-size: 1.25rem;
        }
        
        .card-body {
            padding: 24px;
        }
        
        /* Form enhancements */
        .form-floating > .form-control,
        .form-floating > .form-select {
            height: calc(3.5rem + 2px);
            padding: 1rem 0.75rem;
        }
        
        .form-floating > label {
            padding: 1rem 0.75rem;
            height: calc(3.5rem + 2px);
        }
        
        /* Table enhancements */
        .table th,
        .table td {
            vertical-align: middle;
        }
        
        .table-align-middle td,
        .table-align-middle th {
            vertical-align: middle !important;
        }
        
        /* Scrollbar enhancements */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.05);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.2);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(0,0,0,0.3);
        }
        
        /* Responsive utility classes */
        .responsive-table {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        @media (max-width: 767.98px) {
            .responsive-table {
                border: 0;
            }
            
            .responsive-table thead {
                border: none;
                clip: rect(0 0 0 0);
                height: 1px;
                margin: -1px;
                overflow: hidden;
                padding: 0;
                position: absolute;
                width: 1px;
            }
            
            .responsive-table tr {
                border-bottom: 3px solid var(--border-color);
                display: block;
                margin-bottom: 0.75rem;
            }
            
            .responsive-table td {
                border-bottom: 1px solid var(--border-color);
                display: flex;
                font-size: 0.9rem;
                justify-content: space-between;
                padding: 0.75rem 0.5rem;
                position: relative;
                text-align: right;
            }
            
            .responsive-table td::before {
                content: attr(data-label);
                font-weight: 600;
                left: 0;
                padding-right: 0.75rem;
                position: absolute;
                text-align: left;
                width: 50%;
            }
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
                <i class="bi bi-box-seam"></i>
                <span class="ms-2 d-none d-md-inline">Inventori Gudang IT</span>
            </a>
            <!-- Global Search -->
            <form class="global-search-wrapper" onsubmit="return false;">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" id="globalSearchInput" placeholder="Cari..." style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.4); color: #fff; font-size: 13px;" autocomplete="off">
                    <button class="btn btn-outline-light" type="button" style="border-color: rgba(255,255,255,0.4);">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                <div class="global-search-dropdown" id="globalSearchDropdown"></div>
            </form>
            
            <div class="user-info">
                <a href="{{ route('profile') }}" class="user-name d-none d-md-inline text-decoration-none text-white">
                    {{ Session::get('user_name') }}
                </a>
                @if(Session::get('user_role') === 'admin')
                <span class="badge bg-danger ms-2 px-3 py-1">
                    <i class="bi bi-shield-lock me-1"></i> Admin
                </span>
                @else
                <span class="badge bg-info ms-2 px-3 py-1">
                    <i class="bi bi-person-badge me-1"></i> User
                </span>
                @endif
                <a href="{{ route('profile') }}" class="user-avatar text-decoration-none">
                    @if(Session::get('user_profile_photo'))
                    <img src="{{ asset('storage/profile_photos/' . Session::get('user_profile_photo')) }}" alt="Profile" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                    @else
                    <i class="bi bi-person"></i>
                    @endif
                </a>
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
            
            @if(Session::get('user_role') === 'admin' || (is_array(Session::get('user_menu_permissions', [])) && in_array('master_barang', Session::get('user_menu_permissions', []))))
            <li class="sidebar-menu-item menu-master">
                <a class="{{ Request::is('master-barang*') ? 'active' : '' }}" href="{{ route('master.barang') }}">
                    <i class="bi bi-box-seam"></i>
                    <span>Master Barang</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('master.barang') }}" class="{{ Request::is('master-barang') && !Request::is('master-barang/riwayat') ? 'active' : '' }}">
                        <i class="bi bi-list-ul"></i> Daftar Barang
                    </a></li>
                    <li><a href="{{ route('master.barang.riwayat') }}" class="{{ Request::is('master-barang/riwayat') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> Riwayat
                    </a></li>
                </ul>
            </li>
            @endif
            
            @if(Session::get('user_role') === 'admin' || (is_array(Session::get('user_menu_permissions', [])) && in_array('barang_masuk', Session::get('user_menu_permissions', []))))
            <li class="sidebar-menu-item menu-masuk">
                <a class="{{ Request::is('barang-masuk*') ? 'active' : '' }}" href="{{ route('barang.masuk') }}">
                    <i class="bi bi-arrow-down-circle"></i>
                    <span>Barang Masuk</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('barang.masuk') }}" class="{{ Request::is('barang-masuk') && !Request::is('barang-masuk/riwayat') ? 'active' : '' }}">
                        <i class="bi bi-plus-circle"></i> Input Masuk
                    </a></li>
                    <li><a href="{{ route('barang.masuk.riwayat') }}" class="{{ Request::is('barang-masuk/riwayat') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> Riwayat
                    </a></li>
                </ul>
            </li>
            @endif
            
            @if(Session::get('user_role') === 'admin' || (is_array(Session::get('user_menu_permissions', [])) && in_array('barang_keluar', Session::get('user_menu_permissions', []))))
            <li class="sidebar-menu-item menu-keluar">
                <a class="{{ Request::is('barang-keluar*') ? 'active' : '' }}" href="{{ route('barang.keluar') }}">
                    <i class="bi bi-arrow-up-circle"></i>
                    <span>Barang Keluar</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('barang.keluar') }}" class="{{ Request::is('barang-keluar') && !Request::is('barang-keluar/riwayat') ? 'active' : '' }}">
                        <i class="bi bi-dash-circle"></i> Input Keluar
                    </a></li>
                    <li><a href="{{ route('barang.keluar.riwayat') }}" class="{{ Request::is('barang-keluar/riwayat') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> Riwayat
                    </a></li>
                </ul>
            </li>
            @endif
            
            @if(Session::get('user_role') === 'admin' || (is_array(Session::get('user_menu_permissions', [])) && in_array('barang_retur', Session::get('user_menu_permissions', []))))
            <li class="sidebar-menu-item menu-retur">
                <a class="{{ Request::is('barang-retur*') ? 'active' : '' }}" href="{{ route('barang.retur') }}">
                    <i class="bi bi-arrow-return-left"></i>
                    <span>Barang Retur</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('barang.retur') }}" class="{{ Request::is('barang-retur') && !Request::is('barang-retur/riwayat') ? 'active' : '' }}">
                        <i class="bi bi-plus-circle"></i> Input Retur
                    </a></li>
                    <li><a href="{{ route('barang.retur.riwayat') }}" class="{{ Request::is('barang-retur/riwayat') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> Riwayat
                    </a></li>
                </ul>
            </li>
            @endif

            @if(Session::get('user_role') === 'admin' || (is_array(Session::get('user_menu_permissions', [])) && in_array('barang_rusak', Session::get('user_menu_permissions', []))))
            <li class="sidebar-menu-item menu-rusak">
                <a class="{{ Request::is('barang-rusak*') ? 'active' : '' }}" href="{{ route('barang.rusak') }}">
                    <i class="bi bi-exclamation-triangle"></i>
                    <span>Barang Rusak</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('barang.rusak') }}" class="{{ Request::is('barang-rusak') && !Request::is('barang-rusak/riwayat') ? 'active' : '' }}">
                        <i class="bi bi-plus-circle"></i> Input Rusak
                    </a></li>
                    <li><a href="{{ route('barang.rusak.riwayat') }}" class="{{ Request::is('barang-rusak/riwayat') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> Riwayat
                    </a></li>
                </ul>
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
    
    <!-- Global Search JS -->
    <script>
document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('globalSearchInput');
        const searchDropdown = document.getElementById('globalSearchDropdown');
        
        console.log('Global search init:', searchInput, searchDropdown);
        
        if (!searchInput || !searchDropdown) {
            console.log('Search elements not found');
            return;
        }
        
        let debounceTimer;
        let searchUrl = '{{ route("global.search") }}';
        
        // Get icon class based on type
        function getTypeIcon(type) {
            const icons = {
                'Master Barang': 'bi-box-seam',
                'Barang Masuk': 'bi-arrow-down-circle',
                'Barang Keluar': 'bi-arrow-up-circle',
                'Barang Rusak': 'bi-exclamation-triangle',
                'Barang Retur': 'bi-arrow-repeat'
            };
            return icons[type] || 'bi-search';
        }
        
        // Get icon class based on type
        function getTypeClass(type) {
            const classes = {
                'Master Barang': 'master',
                'Barang Masuk': 'masuk',
                'Barang Keluar': 'keluar',
                'Barang Rusak': 'rusak',
                'Barang Retur': 'retur'
            };
            return classes[type] || 'master';
        }
        
        // Render search results
        function renderResults(results) {
            if (!results || results.length === 0) {
                searchDropdown.innerHTML = '<div class="global-search-no-results">Tidak ada hasil pencarian</div>';
                return;
            }
            
            let html = '';
            results.forEach(function(item) {
                html += '<a href="' + item.url + '" class="global-search-result">' +
                    '<div class="global-search-result-icon ' + getTypeClass(item.type) + '">' +
                        '<i class="bi ' + getTypeIcon(item.type) + '"></i>' +
                    '</div>' +
                    '<div class="global-search-result-content">' +
                        '<div class="global-search-result-title">' + escapeHtml(item.title) + '</div>' +
                        '<div class="global-search-result-subtitle">' + escapeHtml(item.subtitle) + '</div>' +
                    '</div>' +
                    '<span class="global-search-type-badge bg-light text-dark">' + item.type + '</span>' +
                '</a>';
            });
            searchDropdown.innerHTML = html;
        }
        
        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Perform search
        function performSearch(query) {
            if (!query || query.length < 2) {
                searchDropdown.classList.remove('show');
                return;
            }
            
            searchDropdown.innerHTML = '<div class="global-search-loading"><i class="bi bi-hourglass-split animate-spin"></i> Mencari...</div>';
            searchDropdown.classList.add('show');
            
            fetch(searchUrl + '?q=' + encodeURIComponent(query), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'include'
            })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    renderResults(data);
                })
                .catch(function(error) {
                    searchDropdown.innerHTML = '<div class="global-search-no-results">Terjadi kesalahan</div>';
                });
        }
        
        // Input event with debounce
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const query = this.value.trim();
            
            if (query.length < 2) {
                searchDropdown.classList.remove('show');
                return;
            }
            
            debounceTimer = setTimeout(function() {
                performSearch(query);
            }, 300);
        });
        
        // Focus
        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length >= 2) {
                searchDropdown.classList.add('show');
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
                searchDropdown.classList.remove('show');
            }
        });
        
        // Handle keyboard navigation
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                searchDropdown.classList.remove('show');
                searchInput.blur();
            }
        });
    });
    </script>
    
    @yield('scripts')
</body>
</html>
