<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TOS-MERL RG HY')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/Logo.png') }}">
    <meta name="user-profile-image" content="{{ asset('images/Logo.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 for better modals -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Custom User Management CSS -->
    <link rel="stylesheet" href="{{ asset('css/user-management.css') }}">
    <!-- Responsive Design Framework -->
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <!-- Dropdown No-Scroll Styles -->
    <link rel="stylesheet" href="{{ asset('css/dropdown-no-scroll.css') }}">
    <style>
        :root {
            --primary: #3f6ad8;
            --secondary: #6c757d;
            --success: #3ac47d;
            --info: #16aaff;
            --warning: #f7b924;
            --danger: #d92550;
            --light: #f8f9fa;
            --dark: #343a40;
            --body-bg: #f5f6f8;
            --card-bg: #ffffff;
            --border-color: #e9ecef;
            --sidebar-bg:rgba(44, 62, 80, 0.72);
            --sidebar-color: #fff;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 80px;
            --header-height: 60px;
            --card-border-radius: 0.5rem;
            --card-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
            --transition-speed: 0.3s;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--body-bg);
            color: var(--dark);
            overflow-x: hidden;
            font-size: 0.9rem;
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
        
        /* Layout */
        .app-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
            overflow-x: hidden;
            position: relative;
        }

        /* Responsive layout adjustments */
        @media (max-width: 767px) {
            .app-container {
                flex-direction: column;
            }
        }

        @media (min-width: 768px) {
            .app-container {
                flex-direction: row;
            }
        }



        /* Dashboard content protection */
        .dashboard-content,
        .page-content,
        .main-content > .container-fluid,
        .main-content > .container {
            position: relative;
            z-index: 2;
        }

        /* Main content positioning */
        .main-content {
            flex: 1;
            min-height: 100vh;
            padding: 1rem;
            box-sizing: border-box;
            transition: margin-left 0.3s ease, width 0.3s ease;
        }

        /* Mobile: full width */
        @media (max-width: 767px) {
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }

        /* Desktop: account for sidebar */
        @media (min-width: 768px) {
            .main-content {
                margin-left: 280px;
                width: calc(100% - 280px);
            }

            body.sidebar-collapsed .main-content {
                margin-left: 80px;
                width: calc(100% - 80px);
            }
        }

        @media (min-width: 992px) {
            .main-content {
                margin-left: 300px;
                width: calc(100% - 300px);
            }
        }

        /* Sidebar styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: -100%;
            width: 280px;
            height: 100vh;
            z-index: 1000;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Mobile: show/hide sidebar */
        @media (max-width: 767px) {
            .sidebar.show {
                left: 0;
            }
        }

        /* Desktop: always visible */
        @media (min-width: 768px) {
            .sidebar {
                left: 0;
                width: 280px;
            }

            body.sidebar-collapsed .sidebar {
                width: 80px;
            }
        }

        @media (min-width: 992px) {
            .sidebar {
                width: 300px;
            }
        }
        }

        .sidebar-collapsed {
            width: var(--sidebar-collapsed-width);
        }

        /* Small screens - Mobile */
        @media (max-width: 767px) {
            .sidebar {
                width: 100%;
                left: -100%;
            }

            .sidebar.show {
                left: 0;
            }
        }



        /* Large screens */
        @media (min-width: 1200px) {
            .sidebar {
                width: 300px;
            }
        }
        
        .logo-container {
            background: #2c3e50;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .logo {
            color: var(--sidebar-color);
            font-size: 1.25rem;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .logo:hover {
            color: var(--light);
        }
        
        .logo i {
            margin-right: 0.75rem;
            font-size: 1.5rem;
        }
        
        .logo-image {
            width: 85px;
            height: 85px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }
        
        .logo-text {
            font-size: 1.4rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .text-blue {
            color: #4169e1;
        }
        
        .text-green {
            color: #2ecc71;
        }
        
        .logo:hover {
            text-decoration: none;
        }
        
        .logo:hover .logo-image {
            transform: scale(1.1);
        }
        
        .logo:hover .text-blue {
            color: #5579e5;
            transition: color 0.3s ease;
        }
        
        .logo:hover .text-green {
            color: #40dd81;
            transition: color 0.3s ease;
        }
        
        .sidebar-collapsed .logo span {
            display: none;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .nav-section {
            padding: 0 1rem;
            margin-bottom: 0.5rem;
        }
        
        .nav-header {
            font-size: 0.7rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0.75rem 1rem;
        }
        
        .sidebar-collapsed .nav-header {
            display: none;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            margin: 0.1rem 0;
            border-radius: 0.3rem;
            transition: all 0.2s;
            white-space: nowrap;
        }
        
        .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            width: calc(var(--sidebar-width) + 20px);
            margin-right: -20px;
        }
        
        .nav-link.active {
            color: #fff;
            background-color: var(--primary);
            width: calc(var(--sidebar-width) + 20px);
            margin-right: -20px;
        }
        
        .nav-link i {
            font-size: 1rem;
            margin-right: 0.75rem;
            width: 1.25rem;
            text-align: center;
        }
        
        .sidebar-collapsed .nav-link span {
            display: none;
        }
        
        .badge-counter {
            position: absolute;
            right: 1rem;
            background-color: var(--danger);
            color: white;
            font-size: 0.65rem;
            padding: 0.2rem 0.45rem;
            border-radius: 10px;
        }
        
        .sidebar-collapsed .badge-counter {
            position: relative;
            top: -8px;
            right: -12px;
            margin-left: -1rem;
        }
        
        /* Main content styles - Mobile First */
        .main-content {
            margin-left: 0;
            flex: 1;
            transition: margin var(--transition-speed) ease, width var(--transition-speed) ease;
            overflow-x: hidden;
            padding-top: 60px; /* Mobile header height */
            min-height: 100vh;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        .main-header {
            height: 60px; /* Mobile header height */
            background: var(--card-bg);
            box-shadow: var(--card-shadow);
            display: flex;
            align-items: center;
            padding: 0 1rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            justify-content: space-between;
            width: 100%;
        }

        /* Small screens - Mobile */
        @media (max-width: 767px) {
            .main-content {
                margin-left: 0;
                width: 100%;
                padding-top: 60px;
            }

            .main-header {
                left: 0;
                width: 100%;
                height: 60px;
            }
        }




        
        .toggle-sidebar {
            background: none;
            border: none;
            color: var(--secondary);
            cursor: pointer;
            transition: color 0.2s;
            padding: 0.5rem;
            border-radius: 0.25rem;
            font-size: 1.25rem;
            display: block;
        }

        .toggle-sidebar:hover {
            color: var(--primary);
            background-color: rgba(0, 0, 0, 0.05);
        }

        .header-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
            flex-grow: 1;
            text-align: center;
        }

        /* Mobile Overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1049;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Desktop adjustments */
        @media (min-width: 768px) {
            .toggle-sidebar {
                margin-right: 1rem;
                font-size: 1rem;
            }

            .header-title {
                font-size: 1.1rem;
                text-align: left;
            }

            .overlay {
                display: none;
            }
        }
        
        .header-user {
            display: flex;
            align-items: center;
        }
        
        .user-dropdown {
            margin-left: 1rem;
        }
        
        .user-dropdown .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px;
            border: none;
            background: transparent;
        }
        
        .user-dropdown .user-avatar {
            width: 40px;
            height: 40px;
            overflow: hidden;
        }
        
        .user-dropdown .user-info {
            display: flex;
            flex-direction: column;
            align-items: start;
            margin-left: 5px;
        }
        
        .user-dropdown .user-name {
            font-weight: 600;
            color: #2C3E50;
        }
        
        .user-dropdown .user-role {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.08);
            max-height: none !important; /* Remove height restriction */
            overflow: visible !important; /* Remove scroll */
        }

        /* Global dropdown no-scroll styles - ULTRA AGGRESSIVE */
        .dropdown-menu,
        .dropdown-menu.show,
        .dropdown-menu[data-bs-popper],
        .dropdown-menu[data-bs-popper="static"],
        .dropdown-menu[data-bs-popper="none"],
        ul.dropdown-menu,
        div.dropdown-menu,
        .form-select {
            max-height: none !important;
            height: auto !important;
            overflow: visible !important;
            overflow-y: visible !important;
            overflow-x: visible !important;
        }

        /* Override any inline styles */
        .dropdown-menu[style*="max-height"],
        .dropdown-menu[style*="overflow"] {
            max-height: none !important;
            height: auto !important;
            overflow: hidden !important;
            overflow-y: hidden !important;
        }

        /* Hide scrollbars completely on all dropdowns */
        .dropdown-menu::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }

        .dropdown-menu {
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
        }

        /* Ensure Bootstrap dropdowns don't scroll */
        .dropdown-menu {
            max-height: unset !important;
            overflow-y: visible !important;
        }

        /* Custom select styling to prevent scroll */
        select.form-select {
            overflow: visible !important;
        }

        /* For mobile devices - ensure dropdowns are still usable */
        @media (max-width: 768px) {
            .dropdown-menu {
                position: fixed !important;
                top: auto !important;
                bottom: 1rem !important;
                left: 1rem !important;
                right: 1rem !important;
                max-height: 50vh !important;
                overflow-y: auto !important;
                z-index: 1050 !important;
            }
        }
        
        .dropdown-item {
            padding: 8px 16px;
            transition: all 0.2s;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        
        .dropdown-item i {
            width: 20px;
            text-align: center;
        }
        
        .content-container {
            padding: 1.5rem;
        }
        
        /* Card styles */
        .card {
            background: var(--card-bg);
            border: none;
            border-radius: var(--card-border-radius);
            box-shadow: var(--card-shadow);
            transition: box-shadow 0.3s;
            margin-bottom: 1.5rem;
        }
        
        .card:hover {
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.15);
        }
        
        .card-header {
            font-weight: 600;
            padding: 1rem 1.25rem;
            background-color: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
        }
        
        .card-title {
            margin-bottom: 0;
            font-size: 1rem;
            font-weight: 600;
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        /* Alert styles */
        .alert {
            border-radius: 0.25rem;
            border: 1px solid transparent;
        }
        
        /* Button styles */
        .btn {
            font-weight: 500;
            padding: 0.375rem 0.75rem;
            border-radius: 0.25rem;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: #3257be;
            border-color: #3257be;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .btn-success {
            background-color: var(--success);
            border-color: var(--success);
        }
        
        .btn-success:hover {
            background-color: #2f9e65;
            border-color: #2f9e65;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .btn-danger {
            background-color: var(--danger);
            border-color: var(--danger);
        }
        
        .btn-danger:hover {
            background-color: #bd1e44;
            border-color: #bd1e44;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .btn-secondary {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #5a6268;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        /* Table styles */
        .table {
            margin-bottom: 0;
            color: var(--dark);
        }
        
        .table thead th {
            font-weight: 600;
            background-color: #f8f9fa;
            border-bottom: 1px solid var(--border-color);
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        /* Badge styles */
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: 0.25rem;
        }
        
        /* Stat cards */
        .stat-card {
            background: var(--card-bg);
            border-radius: var(--card-border-radius);
            padding: 1.25rem;
            height: 100%;
            box-shadow: var(--card-shadow);
            transition: box-shadow 0.3s;
            border-left: 3px solid var(--primary);
        }
        
        .stat-card:hover {
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.15);
        }
        
        .stat-icon {
            width: 45px;
            height: 45px;
            border-radius: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            background-color: rgba(63, 106, 216, 0.1);
            color: var(--primary);
        }
        
        .stat-icon i {
            font-size: 1.25rem;
        }
        
        .stat-title {
            color: var(--secondary);
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            font-weight: 600;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0;
        }
        
        /* Progress bar */
        .progress {
            background-color: rgba(0, 0, 0, 0.05);
            height: 0.5rem;
            border-radius: 0.25rem;
        }
        
        .progress-bar {
            background-color: var(--primary);
        }
        
        /* Actions container */
        .actions-container {
            background-color: rgba(63, 106, 216, 0.05);
            border-radius: 0.25rem;
            padding: 1rem;
            text-align: center;
            transition: all 0.3s;
        }
        
        .actions-container:hover {
            background-color: rgba(63, 106, 216, 0.1);
        }
        
        .actions-container i {
            color: var(--primary);
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }
        
        .actions-container p {
            color: var(--dark);
            font-weight: 500;
            margin-bottom: 0;
            font-size: 0.85rem;
        }
        
        /* Header actions buttons */
        .header-actions .btn {
            margin-left: 0.5rem;
            padding: 0.375rem 1rem;
            border-radius: 0.3rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .header-actions .btn i {
            margin-right: 0.35rem;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                margin-left: calc(var(--sidebar-width) * -1);
            }
            
            .sidebar-open .sidebar {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar-open .main-content {
                margin-left: var(--sidebar-width);
            }
            
            .sidebar-collapsed .main-content {
                margin-left: 0;
            }
            
            .sidebar-collapsed .sidebar {
                margin-left: calc(var(--sidebar-collapsed-width) * -1);
            }
            
            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1025;
                display: none;
            }
            
            .sidebar-open .overlay {
                display: block;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar-collapsed .sidebar {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .btn-primary span {
                display: none;
            }
            
            .btn-primary {
                padding: 0.5rem;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .btn-primary i {
                margin: 0 !important;
            }
            
            .navbar-brand {
                font-size: 1.1rem;
            }
        }
        
        @media (max-width: 576px) {
            .container-fluid {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            
            .navbar {
                padding: 0.5rem 0;
            }
            
            .navbar-brand {
                font-size: 1rem;
            }
            
            .profile-image,
            .profile-placeholder {
                width: 35px;
                height: 35px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div id="app" class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo-container text-center py-4">
                <a href="{{ route('dashboard') }}" class="logo d-flex flex-column align-items-center text-decoration-none">
                    <img src="{{ asset('images/logo.png') }}" alt="RD Logo" class="logo-image mb-3">
                    <span class="logo-text">
                        <span class="text-blue">TOS-MERL</span><span class="text-green"> RG HY</span>
                    </span>
                </a>
            </div>
            
            <div class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-header">Dashboard</div>
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Overview</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-header">Cinema</div>
                    <a href="{{ route('dashboard.movies') }}" class="nav-link {{ request()->routeIs('dashboard.movies') || request()->routeIs('movies.*') ? 'active' : '' }}">
                        <i class="fas fa-film"></i>
                        <span>Movies</span>
                        <span class="badge-counter">{{ \App\Models\Movie::count() }}</span>
                    </a>
                    <a href="{{ route('dashboard.halls') }}" class="nav-link {{ request()->routeIs('dashboard.halls') || request()->routeIs('halls.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span>Halls</span>
                        <span class="badge-counter">{{ \App\Models\Hall::count() }}</span>
                    </a>
                    <a href="{{ route('dashboard.seats') }}" class="nav-link {{ request()->routeIs('dashboard.seats') || request()->routeIs('seats.*') ? 'active' : '' }}">
                        <i class="fas fa-chair"></i>
                        <span>Seats</span>
                        <span class="badge-counter">{{ \App\Models\Seat::count() }}</span>
                    </a>
                    <a href="{{ route('dashboard.pos') }}" class="nav-link {{ request()->routeIs('dashboard.pos') ? 'active' : '' }}">
                        <i class="fas fa-cash-register"></i>
                        <span>POS</span>
                    </a>
                    <a href="{{ route('dashboard.movieHallAssignments') }}" class="nav-link {{ request()->routeIs('dashboard.movieHallAssignments') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Showtimes</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-header">Food & Drinks</div>
                    <a href="{{ route('dashboard.food-items') }}" class="nav-link {{ request()->routeIs('dashboard.food-items') || request()->routeIs('food-items.*') ? 'active' : '' }}">
                        <i class="fas fa-utensils"></i>
                        <span>Food Items</span>
                        <span class="badge-counter">{{ \App\Models\FoodItem::count() }}</span>
                    </a>
                    <a href="{{ route('dashboard.drinks') }}" class="nav-link {{ request()->routeIs('dashboard.drinks') || request()->routeIs('drinks.*') ? 'active' : '' }}">
                        <i class="fas fa-glass-martini-alt"></i>
                        <span>Drinks</span>
                        <span class="badge-counter">{{ \App\Models\Drink::count() }}</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-header">Reports</div>
                    <a href="{{ route('dashboard.reports.bookings') }}" class="nav-link {{ request()->routeIs('dashboard.reports.bookings') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Booking Reports</span>
                    </a>
                    <a href="{{ route('dashboard.users.index') }}" class="nav-link {{ request()->routeIs('dashboard.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users me-2"></i>
                        <span>User Management</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-header">Account</div>
                    <a class="nav-link" href="{{ route('admin.profile.show') }}">
                        <i class="fas fa-user me-2"></i>
                        <span>Profile</span>
                    </a>
                    <a href="{{ route('admin.logout') }}" class="nav-link" onclick="event.preventDefault(); performLogout();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </aside>
        
        <!-- Overlay for mobile -->
        <div class="overlay"></div>
        
        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <button class="toggle-sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                
                <h1 class="header-title">@yield('page-title', 'Dashboard')</h1>
                
                <div class="header-user">
                    <div class="header-actions">
                        @yield('actions')
                    </div>
                    
                    <div class="user-dropdown dropdown">
                        <button class="dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar">
                                @if(Auth::guard('admin')->user()->profile_image)
                                    <img src="{{ asset(Auth::guard('admin')->user()->profile_image) }}" alt="Profile Image" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; border: 4px solid #fff;">
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-secondary"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="user-info d-none d-sm-flex">
                                <span class="user-name">{{ Auth::guard('admin')->user()->name }}</span>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('admin.profile.show') }}"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.profile.settings') }}"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('admin.logout') }}" onclick="event.preventDefault(); performLogout();"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </header>
            
            <div class="content-container">
                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function performLogout() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('admin.logout') }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            document.body.appendChild(form);
            form.submit();
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Force remove scroll from all dropdowns - AGGRESSIVE APPROACH
            function forceRemoveDropdownScroll() {
                const dropdowns = document.querySelectorAll('.dropdown-menu');
                dropdowns.forEach(dropdown => {
                    dropdown.style.setProperty('max-height', 'none', 'important');
                    dropdown.style.setProperty('height', 'auto', 'important');
                    dropdown.style.setProperty('overflow', 'visible', 'important');
                    dropdown.style.setProperty('overflow-y', 'visible', 'important');
                    dropdown.style.setProperty('overflow-x', 'visible', 'important');

                    // Remove Bootstrap's data attributes that cause scroll
                    dropdown.removeAttribute('data-bs-popper');
                    dropdown.style.removeProperty('max-height');
                    dropdown.style.removeProperty('overflow');
                    dropdown.style.removeProperty('overflow-y');
                });
            }

            // Run immediately
            forceRemoveDropdownScroll();

            // Run after Bootstrap loads
            setTimeout(forceRemoveDropdownScroll, 100);
            setTimeout(forceRemoveDropdownScroll, 500);

            // Listen for dropdown events
            document.addEventListener('show.bs.dropdown', forceRemoveDropdownScroll);
            document.addEventListener('shown.bs.dropdown', function() {
                setTimeout(forceRemoveDropdownScroll, 10);
            });

            // Continuous monitoring
            setInterval(forceRemoveDropdownScroll, 200);

            const toggleBtn = document.querySelector('.toggle-sidebar');
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.overlay');
            const app = document.getElementById('app');
            const mainContent = document.querySelector('.main-content');
            const mainHeader = document.querySelector('.main-header');

            // Check if we're on mobile
            function isMobile() {
                return window.innerWidth < 768;
            }

            // Update layout based on screen size
            function updateLayout() {
                if (isMobile()) {
                    // Mobile layout
                    sidebar.classList.remove('collapsed');
                    document.body.classList.remove('sidebar-collapsed');
                    if (app) {
                        app.classList.remove('sidebar-collapsed');
                    }
                } else {
                    // Desktop layout
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                    document.body.style.overflow = '';
                }
            }

            // Toggle sidebar function
            function toggleSidebar() {
                if (isMobile()) {
                    // Mobile: Show/hide sidebar with overlay
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                    document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
                } else {
                    // Desktop: Collapse/expand sidebar
                    sidebar.classList.toggle('collapsed');
                    document.body.classList.toggle('sidebar-collapsed');
                    if (app) {
                        app.classList.toggle('sidebar-collapsed');
                    }
                }
            }

            // Close mobile sidebar
            function closeMobileSidebar() {
                if (isMobile()) {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                    document.body.style.overflow = '';
                }
            }

            // Toggle sidebar on button click
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    toggleSidebar();
                });
            }

            // Close mobile sidebar when clicking overlay
            if (overlay) {
                overlay.addEventListener('click', closeMobileSidebar);
            }

            // Close mobile sidebar when clicking nav links
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', closeMobileSidebar);
            });

            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    updateLayout();
                }, 250);
            });

            // Handle escape key to close mobile sidebar
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeMobileSidebar();
                }
            });

            // Initialize layout
            updateLayout();
        });
    </script>

    <!-- SweetAlert2 for better modals -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('scripts')
</body>
</html>
