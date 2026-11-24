<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Laundry' }} - Laundry IMZ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root{--laundry-blue:#4b86b4;--laundry-blue-soft:#edf4fa;--laundry-text:#334155}
        body{background:#f6f8fb;color:var(--laundry-text)}
        .sidebar{width:238px;min-height:100vh;background:#fff;border-right:1px solid #edf0f4!important}
        .sidebar-brand{letter-spacing:.1px;padding:1.05rem .7rem 1.2rem}
        .nav-link{color:#748196;border-radius:.55rem;margin:.18rem 0;padding:.72rem .85rem}
        .nav-link:hover{background:#f4f7fa;color:#4e7fa7}
        .nav-link.active{background:var(--laundry-blue-soft);color:#397aa9;font-weight:600}
        .main{min-width:0}
        .card{border:1px solid #edf0f4!important;border-radius:.75rem!important;box-shadow:0 5px 20px rgba(51,65,85,.038)!important}
        .content-card{border:1px solid #edf0f4!important;box-shadow:0 4px 18px rgba(51,65,85,.035)!important}
        .table thead th{font-size:.78rem;color:#7a8798;font-weight:600;border-bottom-width:1px}
        .table tbody td{padding-top:.9rem;padding-bottom:.9rem;border-color:#f0f2f5}
        .card-header{border-bottom:1px solid #eef1f4}
        .btn-primary{background:var(--laundry-blue);border-color:var(--laundry-blue);border-radius:.55rem}
        .btn-primary:hover{background:#3477ad;border-color:#3477ad}
        .form-control,.form-select{border-color:#dde4eb;border-radius:.55rem}
        .topbar{min-height:72px}
        .page-title{font-size:1.28rem;font-weight:700;color:#2f4052}
        .user-avatar{width:38px;height:38px;border-radius:50%;background:#edf4fa;color:#4b86b4}
        .sidebar-profile{width:238px!important}
        .form-control:focus,.form-select:focus{border-color:#91b7d5;box-shadow:0 0 0 .2rem rgba(59,130,196,.09)}
        .mobile-bar{display:none}
        @media(max-width:768px){
            .sidebar{position:fixed;z-index:1000;transform:translateX(-100%);transition:.2s;width:238px}
            .sidebar.show{transform:translateX(0)}
            .main{width:100%;padding:1rem!important}
            .mobile-bar{display:flex}
            .overlay.show{display:block}
            .overlay{display:none;position:fixed;inset:0;background:#0005;z-index:900}
        }
    </style>
</head>
<body>
<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>
<div class="d-flex">
    <aside class="sidebar border-end p-3" id="sidebar">
        <div class="sidebar-brand fw-bold fs-5 px-2 py-3" style="color:#397aa9"><i class="bi bi-droplet-fill me-2"></i>Laundry IMZ</div>
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('dashboard','admin.dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            @if(auth()->user()->role === 'admin')
            <a class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}"><i class="bi bi-grid me-2"></i>Data Layanan</a>
            <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}"><i class="bi bi-person-badge me-2"></i>Data Pelanggan</a>
            @endif
            <a class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}" href="{{ route('admin.transactions.index') }}"><i class="bi bi-receipt me-2"></i>Transaksi Laundry</a>
            <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}"><i class="bi bi-bar-chart me-2"></i>Laporan Keuangan</a>
        </nav>
        <div class="position-absolute bottom-0 start-0 p-3 sidebar-profile">
            <div class="d-flex align-items-center gap-2 mb-2 px-1">
                <div class="user-avatar d-flex align-items-center justify-content-center fw-bold">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                <div class="lh-sm"><div class="small fw-semibold">{{ auth()->user()->name }}</div><div class="small text-secondary">{{ ucfirst(auth()->user()->role) }}</div></div>
            </div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-light border btn-sm w-100 text-secondary"><i class="bi bi-box-arrow-right me-1"></i>Keluar</button></form>
        </div>
    </aside>
    <main class="main flex-grow-1">
        <header class="topbar bg-white border-bottom d-none d-md-flex align-items-center justify-content-between px-4">
            <div class="page-title">{{ $title ?? 'Laundry IMZ' }}</div>
            <div class="d-flex align-items-center gap-2 text-secondary small"><i class="bi bi-calendar3"></i>{{ now()->translatedFormat('d M Y') }}</div>
        </header>
        <div class="mobile-bar align-items-center justify-content-between mb-3 px-3 pt-3"><button class="btn btn-primary" onclick="toggleSidebar()"><i class="bi bi-list"></i></button><strong class="text-secondary">Laundry IMZ</strong></div>
        <div class="p-3 p-md-4">
