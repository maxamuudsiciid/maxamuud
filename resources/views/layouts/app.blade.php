<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'BloodBank MS')) — Blood Donation Management</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- DataTables Bootstrap5 CSS -->
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    @stack('styles')

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #b91c1c;
            --sidebar-dark-bg: #7f1d1d;
            --sidebar-hover: rgba(255,255,255,0.12);
            --sidebar-active: rgba(255,255,255,0.22);
            --topbar-height: 60px;
            --brand-red: #dc2626;
            --brand-dark-red: #991b1b;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            min-height: 100vh;
            margin: 0;
        }

        /* ===================== SIDEBAR ===================== */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #b91c1c 0%, #7f1d1d 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            z-index: 1040;
            transition: transform 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }

        #sidebar.sidebar-collapsed {
            transform: translateX(calc(-1 * var(--sidebar-width)));
        }

        /* Brand / Logo */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.15);
            text-decoration: none;
            color: #fff;
            min-height: var(--topbar-height);
        }
        .sidebar-brand .brand-icon {
            width: 38px; height: 38px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .sidebar-brand .brand-text { font-size: 16px; font-weight: 700; line-height: 1.2; }
        .sidebar-brand .brand-sub { font-size: 10px; opacity: 0.7; font-weight: 400; }

        /* Nav section headers */
        .sidebar-section {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.5);
            padding: 16px 20px 6px;
            margin-top: 4px;
        }

        /* Nav Items */
        .sidebar-nav { padding: 8px 0; flex: 1; }
        .sidebar-nav .nav-item { margin: 2px 12px; }
        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            color: rgba(255,255,255,0.85);
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .sidebar-nav .nav-link .nav-icon {
            width: 20px;
            text-align: center;
            font-size: 14px;
            flex-shrink: 0;
            opacity: 0.85;
        }
        .sidebar-nav .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }
        .sidebar-nav .nav-link.active {
            background: var(--sidebar-active);
            color: #fff;
            font-weight: 600;
        }
        .sidebar-nav .nav-link .badge {
            margin-left: auto;
            font-size: 10px;
        }

        /* Sidebar footer */
        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid rgba(255,255,255,0.15);
        }
        .sidebar-footer .user-info {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 10px;
            border-radius: 8px;
            background: rgba(255,255,255,0.1);
        }
        .sidebar-footer .user-avatar {
            width: 34px; height: 34px;
            background: rgba(255,255,255,0.25);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; flex-shrink: 0;
        }
        .sidebar-footer .user-name { font-size: 12.5px; font-weight: 600; color: #fff; }
        .sidebar-footer .user-role { font-size: 10px; color: rgba(255,255,255,0.6); }

        /* ===================== MAIN CONTENT ===================== */
        #main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }
        #main-wrapper.sidebar-collapsed {
            margin-left: 0;
        }

        /* ===================== TOPBAR ===================== */
        #topbar {
            position: sticky;
            top: 0;
            z-index: 1030;
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 20px;
            gap: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }

        #sidebarToggle {
            border: none;
            background: #f1f5f9;
            width: 36px; height: 36px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: #64748b; font-size: 14px;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        #sidebarToggle:hover { background: #e2e8f0; color: #1e293b; }

        .topbar-breadcrumb { flex: 1; }
        .topbar-breadcrumb .page-title {
            font-size: 16px; font-weight: 700; color: #1e293b; margin: 0; line-height: 1;
        }
        .topbar-breadcrumb .breadcrumb { margin: 0; font-size: 11px; }
        .topbar-breadcrumb .breadcrumb-item, .topbar-breadcrumb .breadcrumb-item a {
            color: #94a3b8; text-decoration: none;
        }
        .topbar-breadcrumb .breadcrumb-item.active { color: #dc2626; }

        .topbar-actions { display: flex; align-items: center; gap: 8px; }

        .topbar-icon-btn {
            position: relative;
            width: 36px; height: 36px;
            border-radius: 8px;
            border: none;
            background: #f1f5f9;
            display: flex; align-items: center; justify-content: center;
            color: #64748b; font-size: 14px; cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }
        .topbar-icon-btn:hover { background: #e2e8f0; color: #1e293b; }
        .topbar-icon-btn .badge-dot {
            position: absolute; top: 6px; right: 6px;
            width: 8px; height: 8px;
            background: #dc2626; border-radius: 50%;
            border: 2px solid #fff;
        }
        .topbar-icon-btn .badge-count {
            position: absolute; top: -2px; right: -2px;
            background: #dc2626; color: #fff;
            font-size: 9px; font-weight: 700;
            width: 16px; height: 16px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid #fff;
        }

        .topbar-user {
            display: flex; align-items: center; gap: 8px;
            padding: 4px 8px 4px 4px;
            border-radius: 10px; cursor: pointer;
            border: 1px solid #e2e8f0;
            text-decoration: none; color: inherit;
            transition: all 0.2s;
        }
        .topbar-user:hover { background: #f8fafc; border-color: #cbd5e1; }
        .topbar-user .user-ava {
            width: 30px; height: 30px;
            background: linear-gradient(135deg, #dc2626, #7f1d1d);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 12px; font-weight: 700;
        }
        .topbar-user .user-details { line-height: 1.2; }
        .topbar-user .user-name-top { font-size: 12.5px; font-weight: 600; color: #1e293b; }
        .topbar-user .user-role-top { font-size: 10px; color: #94a3b8; }

        /* ===================== PAGE CONTENT ===================== */
        #page-content {
            flex: 1;
            padding: 24px;
        }

        /* ===================== CARDS ===================== */
        .stat-card {
            border: none;
            border-radius: 14px;
            padding: 20px;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex; flex-direction: column; gap: 12px;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .stat-card .stat-icon {
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }
        .stat-card .stat-label { font-size: 12px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-card .stat-value { font-size: 28px; font-weight: 800; color: #1e293b; line-height: 1; }
        .stat-card .stat-meta { font-size: 11.5px; color: #94a3b8; }
        .stat-card .stat-meta .up { color: #16a34a; }
        .stat-card .stat-meta .down { color: #dc2626; }

        .card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            border-radius: 14px 14px 0 0 !important;
            padding: 16px 20px;
            font-weight: 700; color: #1e293b; font-size: 14px;
        }
        .card-body { padding: 20px; }

        /* ===================== TABLES ===================== */
        .table-card { border-radius: 14px; overflow: hidden; }
        .table thead th {
            background: #f8fafc;
            font-size: 11.5px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.5px;
            color: #64748b; border-bottom: 1px solid #e2e8f0;
            padding: 12px 16px;
        }
        .table tbody td { font-size: 13.5px; padding: 12px 16px; vertical-align: middle; color: #334155; }
        .table tbody tr:hover { background: #fafafa; }
        .table-striped tbody tr:nth-of-type(odd) { background-color: #fafbfc; }

        /* ===================== BADGES ===================== */
        .badge-status {
            font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 20px;
        }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-approved { background: #dcfce7; color: #166534; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }
        .badge-fulfilled { background: #dbeafe; color: #1e40af; }
        .badge-safe { background: #dcfce7; color: #166534; }
        .badge-unsafe { background: #fee2e2; color: #991b1b; }
        .badge-active { background: #dcfce7; color: #166534; }
        .badge-inactive { background: #f1f5f9; color: #64748b; }
        .badge-deferred { background: #fef3c7; color: #92400e; }
        .badge-urgent { background: #fee2e2; color: #991b1b; }
        .badge-emergency { background: #7f1d1d; color: #fff; }
        .badge-normal { background: #f0fdf4; color: #166534; }

        /* ===================== BUTTONS ===================== */
        .btn-blood { background: var(--brand-red); color: #fff; border: none; }
        .btn-blood:hover { background: var(--brand-dark-red); color: #fff; }
        .btn-blood-outline { border: 1.5px solid var(--brand-red); color: var(--brand-red); background: transparent; }
        .btn-blood-outline:hover { background: var(--brand-red); color: #fff; }

        /* ===================== FORMS ===================== */
        .form-control, .form-select {
            border-radius: 8px; border: 1.5px solid #e2e8f0;
            font-size: 13.5px; padding: 9px 13px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220,38,38,0.1);
        }
        .form-label { font-size: 12.5px; font-weight: 600; color: #374151; margin-bottom: 5px; }

        /* ===================== ALERTS ===================== */
        .alert { border: none; border-radius: 10px; font-size: 13.5px; }
        .alert-success { background: #f0fdf4; color: #166534; }
        .alert-danger { background: #fef2f2; color: #991b1b; }
        .alert-warning { background: #fffbeb; color: #92400e; }
        .alert-info { background: #eff6ff; color: #1e40af; }

        /* ===================== MODALS ===================== */
        .modal-content { border: none; border-radius: 16px; }
        .modal-header { border-bottom: 1px solid #f1f5f9; padding: 18px 22px; }
        .modal-footer { border-top: 1px solid #f1f5f9; }

        /* ===================== DARK MODE ===================== */
        [data-bs-theme="dark"] body { background-color: #0f172a !important; }
        [data-bs-theme="dark"] #topbar { background: #1e293b; border-color: #334155; }
        [data-bs-theme="dark"] #page-content { background: #0f172a; }
        [data-bs-theme="dark"] .card, [data-bs-theme="dark"] .stat-card { background: #1e293b; }
        [data-bs-theme="dark"] .card-header { background: #1e293b; border-color: #334155; color: #f1f5f9; }
        [data-bs-theme="dark"] .table { color: #e2e8f0; }
        [data-bs-theme="dark"] .table thead th { background: #0f172a; color: #94a3b8; border-color: #334155; }
        [data-bs-theme="dark"] .table tbody tr:hover { background: #1e293b; }
        [data-bs-theme="dark"] .stat-card .stat-value { color: #f1f5f9; }
        [data-bs-theme="dark"] #sidebarToggle { background: #334155; color: #94a3b8; }
        [data-bs-theme="dark"] .topbar-icon-btn { background: #334155; color: #94a3b8; }
        [data-bs-theme="dark"] .topbar-user { border-color: #334155; }
        [data-bs-theme="dark"] .form-control, [data-bs-theme="dark"] .form-select {
            background: #1e293b; border-color: #334155; color: #e2e8f0;
        }
        [data-bs-theme="dark"] .topbar-breadcrumb .page-title { color: #f1f5f9; }

        /* ===================== SIDEBAR OVERLAY (mobile) ===================== */
        #sidebar-overlay {
            display: none; position: fixed; top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); z-index: 1039;
        }
        #sidebar-overlay.show { display: block; }

        /* ===================== RESPONSIVE ===================== */
        @media (max-width: 991.98px) {
            #sidebar {
                transform: translateX(calc(-1 * var(--sidebar-width)));
            }
            #sidebar.mobile-open {
                transform: translateX(0);
            }
            #main-wrapper {
                margin-left: 0 !important;
            }
        }

        /* ===================== MISC ===================== */
        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 22px;
            flex-wrap: wrap; gap: 12px;
        }
        .page-header .page-heading { font-size: 22px; font-weight: 800; color: #1e293b; margin: 0; }
        .page-header .page-sub { font-size: 13px; color: #94a3b8; margin: 2px 0 0 0; }

        .empty-state { text-align: center; padding: 48px 24px; color: #94a3b8; }
        .empty-state i { font-size: 48px; margin-bottom: 12px; opacity: 0.4; }
        .empty-state p { font-size: 14px; }

        .blood-group-pill {
            display: inline-block; padding: 3px 10px;
            background: #fee2e2; color: #991b1b;
            border-radius: 20px; font-size: 12px; font-weight: 700;
        }

        /* DataTables override */
        div.dataTables_wrapper div.dataTables_length label,
        div.dataTables_wrapper div.dataTables_filter label { font-size: 13px; }
        div.dataTables_wrapper div.dataTables_info { font-size: 12.5px; color: #94a3b8; }
        div.dataTables_wrapper div.dataTables_paginate .paginate_button { border-radius: 6px !important; }
    </style>
</head>
<body>

@if(Auth::check())

{{-- Sidebar Overlay (mobile) --}}
<div id="sidebar-overlay"></div>

{{-- ===== SIDEBAR ===== --}}
<nav id="sidebar">
    {{-- Brand --}}
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <div class="brand-icon"><i class="fa-solid fa-droplet"></i></div>
        <div>
            <div class="brand-text">Blood MS</div>
            <div class="brand-sub">Management System</div>
        </div>
    </a>

    {{-- Navigation --}}
    <div class="sidebar-nav">
        <div class="sidebar-section">Main</div>

        <div class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-gauge-high nav-icon"></i> Dashboard
            </a>
        </div>
        @unless(Auth::user()->role === 'hospital')
        <div class="nav-item">
            <a href="{{ route('appointments.index') }}" class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check nav-icon"></i> Appointments
            </a>
        </div>
        @endunless

        @if(in_array(Auth::user()->role, ['admin', 'staff', 'donor']))
        <div class="sidebar-section">Donors & Collections</div>
        <div class="nav-item">
            <a href="{{ route('donors.index') }}" class="nav-link {{ request()->routeIs('donors.*') ? 'active' : '' }}">
                <i class="fas fa-users nav-icon"></i> Donors
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('blood-collections.index') }}" class="nav-link {{ request()->routeIs('blood-collections.*') ? 'active' : '' }}">
                <i class="fas fa-syringe nav-icon"></i> Blood Collections
            </a>
        </div>
        @endif

        @if(in_array(Auth::user()->role, ['admin', 'staff']))
        <div class="sidebar-section">Blood Bank</div>
        <div class="nav-item">
            <a href="{{ route('blood-inventory.index') }}" class="nav-link {{ request()->routeIs('blood-inventory.*') ? 'active' : '' }}">
                <i class="fas fa-box-open nav-icon"></i> Blood Inventory
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('blood-tests.dashboard') }}" class="nav-link {{ request()->routeIs('blood-tests.*') ? 'active' : '' }}">
                <i class="fas fa-microscope nav-icon"></i> Blood Testing
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('distributions.index') }}" class="nav-link {{ request()->routeIs('distributions.*') ? 'active' : '' }}">
                <i class="fas fa-truck-medical nav-icon"></i> Distributions
            </a>
        </div>
        @endif

        @if(in_array(Auth::user()->role, ['admin', 'staff', 'hospital']))
        <div class="sidebar-section">Hospitals & Requests</div>
        <div class="nav-item">
            <a href="{{ route('hospitals.index') }}" class="nav-link {{ request()->routeIs('hospitals.*') ? 'active' : '' }}">
                <i class="fas fa-hospital nav-icon"></i> Hospitals
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('blood-requests.index') }}" class="nav-link {{ request()->routeIs('blood-requests.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list nav-icon"></i> Blood Requests
                @php
                    $pendingCount = \App\Models\BloodRequest::where('status','Pending')->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="badge bg-warning text-dark">{{ $pendingCount }}</span>
                @endif
            </a>
        </div>
        @endif

        @if(in_array(Auth::user()->role, ['admin', 'staff']))
        <div class="sidebar-section">Analytics</div>
        <div class="nav-item">
            <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="fas fa-chart-pie nav-icon"></i> Reports
            </a>
        </div>
        @endif

        <div class="sidebar-section">Account</div>
        <div class="nav-item">
            <a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <i class="fas fa-bell nav-icon"></i> Notifications
                @php
                    $unreadCount = \App\Models\SystemNotification::where('is_read', false)
                        ->where(function($q) { $q->where('user_id', Auth::id())->orWhereNull('user_id'); })
                        ->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="badge bg-danger">{{ $unreadCount }}</span>
                @endif
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="fas fa-gear nav-icon"></i> Settings
            </a>
        </div>

        @if(Auth::user()->role === 'admin')
        <div class="sidebar-section">Administration</div>
        <div class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="fas fa-users-gear nav-icon"></i> User Management
            </a>
        </div>
        @endif
    </div>

    {{-- Sidebar Footer --}}
    <div class="sidebar-footer">
        <div class="user-info">
            @if(Auth::user()->image)
                <div class="user-avatar" style="padding: 0; overflow: hidden; background: transparent;">
                    <img src="{{ asset('storage/profiles/'.Auth::user()->image) }}" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" onclick="window.open(this.src, '_blank')">
                </div>
            @else
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            @endif
            <div class="flex-grow-1 overflow-hidden">
                <div class="user-name text-truncate">{{ Auth::user()->name }}</div>
                <div class="user-role">{{ ucfirst(Auth::user()->role) }}</div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="mb-0">
                @csrf
                <button type="submit" class="btn btn-sm p-1" style="background:rgba(255,255,255,0.15);color:#fff;border:none;border-radius:6px;" title="Logout">
                    <i class="fas fa-sign-out-alt fa-sm"></i>
                </button>
            </form>
        </div>
    </div>
</nav>

{{-- ===== MAIN WRAPPER ===== --}}
<div id="main-wrapper">

    {{-- ===== TOPBAR ===== --}}
    <div id="topbar">
        <button id="sidebarToggle" type="button" title="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>

        <div class="topbar-breadcrumb">
            <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>

        <div class="topbar-actions">
            {{-- Dark Mode Toggle --}}
            <button class="topbar-icon-btn" id="darkModeToggle" title="Toggle Dark Mode">
                <i class="fas fa-moon" id="darkModeIcon"></i>
            </button>

            {{-- Notifications --}}
            <a href="{{ route('notifications.index') }}" class="topbar-icon-btn" title="Notifications">
                <i class="fas fa-bell"></i>
                @if(isset($unreadCount) && $unreadCount > 0)
                    <span class="badge-count">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                @endif
            </a>

            {{-- User Dropdown --}}
            <div class="dropdown">
                <a href="#" class="topbar-user" data-bs-toggle="dropdown" aria-expanded="false">
                    @if(Auth::user()->image)
                        <div class="user-ava" style="padding: 0; overflow: hidden; background: transparent;">
                            <img src="{{ asset('storage/profiles/'.Auth::user()->image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    @else
                        <div class="user-ava">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                    @endif
                    <div class="user-details d-none d-md-block">
                        <div class="user-name-top">{{ Str::limit(Auth::user()->name, 16) }}</div>
                        <div class="user-role-top">{{ ucfirst(Auth::user()->role) }}</div>
                    </div>
                    <i class="fas fa-chevron-down fa-xs ms-1 text-muted d-none d-md-block"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end mt-2 shadow-sm" style="border-radius:12px;border:1px solid #e2e8f0;min-width:180px;">
                    <li><h6 class="dropdown-header">{{ Auth::user()->email }}</h6></li>
                    <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i class="fas fa-gear fa-sm me-2 text-muted"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt fa-sm me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- ===== PAGE CONTENT ===== --}}
    <div id="page-content">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-circle-xmark me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-triangle-exclamation me-2"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-circle-xmark me-2"></i> <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

@else
{{-- GUEST LAYOUT (Login/Register) --}}
@yield('content')
@endif

{{-- ===== SCRIPTS ===== --}}
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 5.3 JS Bundle (FIXED: was .css before) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ---- Sidebar Toggle ----
    const sidebar      = document.getElementById('sidebar');
    const mainWrapper  = document.getElementById('main-wrapper');
    const overlay      = document.getElementById('sidebar-overlay');
    const toggleBtn    = document.getElementById('sidebarToggle');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function () {
            const isMobile = window.innerWidth < 992;
            if (isMobile) {
                sidebar.classList.toggle('mobile-open');
                overlay && overlay.classList.toggle('show');
            } else {
                sidebar.classList.toggle('sidebar-collapsed');
                mainWrapper && mainWrapper.classList.toggle('sidebar-collapsed');
            }
        });

        if (overlay) {
            overlay.addEventListener('click', function () {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('show');
            });
        }
    }

    // ---- Dark Mode ----
    const htmlEl       = document.documentElement;
    const darkBtn      = document.getElementById('darkModeToggle');
    const darkIcon     = document.getElementById('darkModeIcon');
    const savedTheme   = localStorage.getItem('bdms-theme') || 'light';

    function applyTheme(theme) {
        htmlEl.setAttribute('data-bs-theme', theme);
        if (darkIcon) {
            darkIcon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }
        localStorage.setItem('bdms-theme', theme);
    }
    applyTheme(savedTheme);

    if (darkBtn) {
        darkBtn.addEventListener('click', function () {
            const current = htmlEl.getAttribute('data-bs-theme');
            applyTheme(current === 'dark' ? 'light' : 'dark');
        });
    }

    // ---- DataTables (only tables with .data-table class) ----
    if ($.fn.DataTable) {
        $('.data-table').each(function () {
            if (!$.fn.DataTable.isDataTable(this)) {
                $(this).DataTable({
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    responsive: true,
                    language: { search: '<i class="fas fa-search"></i>', searchPlaceholder: 'Search...' }
                });
            }
        });
    }

    // ---- SweetAlert2 for flash messages ----
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: @json(session('success')),
            timer: 3500,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: @json(session('error')),
            timer: 4000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    @endif

    // ---- Confirm Delete ----
    document.querySelectorAll('.confirm-delete').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete it!'
            }).then(function (result) {
                if (result.isConfirmed) { form.submit(); }
            });
        });
    });
});
</script>

@stack('scripts')
</body>
</html>
