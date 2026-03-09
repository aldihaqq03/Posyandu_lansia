<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fontawesome -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <title>SIMPEL - @yield('title', 'Dashboard')</title>

    @vite('resources/css/sidebar.css')
    @stack('styles')
</head>

<body>

<aside class="sidebar">

    <!-- LOGO -->
    <div class="logo-section">
        <div class="logo-icon">
            <i class="fa-solid fa-shield-heart"></i>
        </div>

        <div class="logo-text">
            <span class="brand-name">SIMPEL</span>
            <span class="brand-tagline">PEDULI LANSIA</span>
        </div>
    </div>

    <!-- MENU -->
    <nav class="sidebar-nav">

        <a href="/dashboard"
            class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i>
            Dashboard
        </a>

        <a href="/data_petugas"
            class="nav-item {{ Request::is('data_petugas') ? 'active' : '' }}">
            <i class="fa-solid fa-user-nurse"></i>
            Data Petugas
        </a>

        <a href="/data_lansia"
            class="nav-item {{ Request::is('data_lansia') ? 'active' : '' }}">
            <i class="fa-solid fa-users"></i>
            Data Lansia
        </a>

        <a href="/pemeriksaan"
            class="nav-item {{ Request::is('pemeriksaan') ? 'active' : '' }}">
            <i class="fa-solid fa-notes-medical"></i>
            Pemeriksaan
        </a>

        <a href="/laporan"
            class="nav-item {{ Request::is('laporan') ? 'active' : '' }}">
            <i class="fa-solid fa-file"></i>
            Laporan
        </a>

    </nav>

    <!-- PENGATURAN -->
    <div class="sidebar-setting">
        <a href="/pengaturan"
            class="nav-item {{ Request::is('pengaturan') ? 'active' : '' }}">
            <i class="fa-solid fa-gear"></i>
            Pengaturan
        </a>
    </div>

    <!-- FOOTER USER -->
    <div class="sidebar-footer">

        <img src="https://ui-avatars.com/api/?name=Siti+Aminah&background=129481&color=fff"
            class="user-avatar">

        <div class="user-info">
            <span class="user-name">Siti Aminah</span>
            <span class="user-role">Ketua Kader</span>
        </div>

        <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fa-solid fa-right-from-bracket"></i>
            </button>
        </form>

    </div>

</aside>


<main class="main-content">
    @yield('content')
</main>

@stack('scripts')
</body>
</html>