<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <title>SIMPEL - @yield('title', 'Dashboard')</title>

    @vite('resources/css/sidebar.css')

    @stack('styles')
</head>

<body>

    <aside class="sidebar">
        <div class="logo-section">
            <div class="logo-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
            </div>
            <div class="logo-text">
                <span class="brand-name">SIMPEL</span>
                <span class="brand-tagline">PEDULI LANSIA</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="/dashboard" class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
                <i class="icon-dash">📊</i> Dashboard
            </a>
            <a href="/pemeriksaan" class="nav-item {{ Request::is('pemeriksaan') ? 'active' : '' }}">
                <i class="icon-dash">📋</i> Pemeriksaan
            </a>
            <a href="/data_lansia" class="nav-item {{ Request::is('lansia') ? 'active' : '' }}">
                <i class="icon-dash">👥</i> Data Lansia
            </a>
        </nav>

        <div class="sidebar-footer">
            <img src="https://ui-avatars.com/api/?name=Siti+Aminah&background=129481&color=fff" alt="User Profile"
                class="user-avatar">
            <div class="user-info">
                <span class="user-name">Siti Aminah</span>
                <span class="user-role">Ketua Kader</span>
            </div>
        </div>
    </aside>

    <main class="main-content" style="margin-left: 260px; padding: 40px; background-color: #f8fafc; min-height: 100vh;">
        @yield('content')
    </main>
    @stack('scripts')
</body>

</html>