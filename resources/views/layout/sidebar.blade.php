<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <title>SIMPEL - @yield('title', 'Dashboard')</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f4f8;
            color: #1a202c;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: 260px;
            height: 100vh;
            background: #ffffff; /* putih */
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 24px 20px 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .logo-icon {
            width: 38px; height: 38px;
            background: #129481;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .brand-name {
            display: block;
            font-size: 15px;
            font-weight: 800;
            color: #1a202c; /* hitam */
            letter-spacing: 0.05em;
        }

        .brand-tagline {
            display: block;
            font-size: 9px;
            color: #a0aec0;
            letter-spacing: 0.12em;
            margin-top: 1px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            overflow-y: auto;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 14px;
            border-radius: 10px;
            color: #4a5568; /* abu gelap */
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .nav-item i {
            width: 18px;
            font-size: 14px;
            text-align: center;
            color: #718096;
        }

        .nav-item:hover {
            background: #f0fdf4;
            color: #129481;
        }

        .nav-item:hover i {
            color: #129481;
        }

        .nav-item.active {
            background: #f0fdf4;
            color: #129481;
            font-weight: 600;
        }

        .nav-item.active i {
            color: #129481;
        }

        /* FOOTER */
        .sidebar-footer {
            padding: 14px 12px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 10px;
            text-decoration: none;
            transition: background 0.2s;
        }

        .user-card:hover { background: #f7fafc; }

        .user-avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e2e8f0;
            flex-shrink: 0;
        }

        .user-name {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #1a202c; /* hitam */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }

        .user-role-label {
            display: block;
            font-size: 11px;
            color: #a0aec0;
            margin-top: 1px;
        }

        .logout-btn {
            width: 100%;
            padding: 9px 14px;
            background: #fff5f5;
            color: #e53e3e;
            border: 1px solid #fed7d7;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .logout-btn:hover {
            background: #fed7d7;
            color: #c53030;
        }

        /* ===== MAIN ===== */
        .main-content {
            margin-left: 260px;
            padding: 40px;
            min-height: 100vh;
            background: #f0f4f8;
        }
    </style>

    @stack('styles')
</head>

<body>

<aside class="sidebar">

    {{-- Logo --}}
    <div class="logo-section">
        <div class="logo-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            </svg>
        </div>
        <div>
            <span class="brand-name">SIMPEL</span>
            <span class="brand-tagline">PEDULI LANSIA</span>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="sidebar-nav">
        <a href="{{ url('/dashboard') }}" class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i> Dashboard
        </a>
        <a href="{{ url('/pemeriksaan') }}" class="nav-item {{ Request::is('pemeriksaan*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-list"></i> Pemeriksaan
        </a>
        <a href="{{ url('/data_lansia') }}" class="nav-item {{ Request::is('data_lansia*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Data Lansia
        </a>
        <a href="{{ url('/profil') }}" class="nav-item {{ Request::is('profil*') ? 'active' : '' }}">
            <i class="fas fa-user-circle"></i> Profil Saya
        </a>
    </nav>

    {{-- Footer: info user + logout --}}
    <div class="sidebar-footer">
        <a href="{{ url('/profil') }}" class="user-card">
            @php
                $avatarUrl = Auth::user()->avatar
                    ? asset('storage/' . Auth::user()->avatar)
                    : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=129481&color=fff&size=200';
            @endphp
            <img src="{{ $avatarUrl }}" alt="Avatar" class="user-avatar">
            <div style="overflow:hidden;">
                <span class="user-name">{{ Auth::user()->name }}</span>
                <span class="user-role-label">
                    @switch(Auth::user()->role)
                        @case('Admin') Admin @break
                        @case('Kader') Kader Posyandu @break
                        @default {{ Auth::user()->role }}
                    @endswitch
                </span>
            </div>
        </a>

        <form action="{{ url('/logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-right-from-bracket"></i> Logout
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