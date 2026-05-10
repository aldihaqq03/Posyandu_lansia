<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
<<<<<<< HEAD
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

=======

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    
    

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <title>SIMPEL - @yield('title', 'Dashboard')</title>

    @vite('resources/css/sidebar.css')
>>>>>>> aldi
    @stack('styles')
</head>

<body>

<<<<<<< HEAD
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
=======
    <aside class="sidebar" id="sidebar">

        <!-- LOGO -->
        <div class="logo-section">
            <div class="logo-icon" style="background: transparent; color: #2563eb;">
                <x-logo style="width: 100%; height: 100%;" />
            </div>
            <div class="logo-text">
                <span class="brand-name">SIMPEL</span>
                <!-- <span class="brand-tagline">PEDULI LANSIA</span> -->
            </div>
            <button type="button" class="sidebar-toggle" id="sidebarToggle" title="Lebarkan/Sempitkan">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <!-- MENU -->
        <nav class="sidebar-nav">

            <a href="/dashboard" class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}" title="Dashboard">
                <i class="fa-solid fa-chart-line"></i>
                <span class="nav-text">Dashboard</span>
            </a>

            @if(strtolower(Auth::user()->jabatan) === 'kepala_kader')
                <a href="/data_petugas" class="nav-item {{ Request::is('data_petugas') ? 'active' : '' }}"
                    title="Data Petugas">
                    <i class="fa-solid fa-user-nurse"></i>
                    <span class="nav-text">Data Petugas</span>
                </a>
            @endif

            <a href="/data_lansia" class="nav-item {{ Request::is('data_lansia') ? 'active' : '' }}"
                title="Data Lansia">
                <i class="fa-solid fa-users"></i>
                <span class="nav-text">Data Lansia</span>
            </a>
             
             <a href="/obat" class="nav-item {{ Request::is('obat') ? 'active' : '' }}"
                title="Data Obat">
                <i class="fa-solid fa-pills"></i>
                <span class="nav-text">Data Obat</span>
            </a>
           


            <!-- <div class="nav-dropdown">

                <div class="nav-item dropdown-toggle" title="Pemeriksaan">
                    <i class="fa-solid fa-notes-medical"></i>
                    <span class="nav-text">Pemeriksaan</span>
                    <i class="fa-solid fa-chevron-down dropdown-icon"></i>
                </div>

                <div class="dropdown-menu">
                    <a href="/pemeriksaan" class="{{ Request::is('pemeriksaan') ? 'active' : '' }}">
                        pemeriksaan mingguan
                    </a>

                    <a href="/skrining_utama" class="{{ Request::is('skrining_utama') ? 'active' : '' }}">
                        skrining utama
                    </a>

                    <a href="/pemeriksaan/create" class="{{ Request::is('pemeriksaan/create') ? 'active' : '' }}">
                        skrining ppok
                    </a>
                </div>

            </div> -->

            <a href="/skrining" class="nav-item {{ Request::is('skrining') ? 'active' : '' }}" title="Input Skrining">
    <i class="fa-solid fa-notes-medical"></i>
    <span class="nav-text">Input Skrining</span>
</a>

            @if(strtolower(Auth::user()->jabatan) === 'kepala_kader')
                <a href="/laporan" class="nav-item {{ Request::is('laporan') ? 'active' : '' }}" title="Laporan">
                    <i class="fa-solid fa-file"></i>
                    <span class="nav-text">Laporan</span>
                </a>
            @endif

            <a href="/jadwal_posyandu" class="nav-item {{ Request::is('jadwal_posyandu') ? 'active' : '' }}"
                title="Jadwal Posyandu">
                <i class="fa-solid fa-calendar"></i>
                <span class="nav-text">Jadwal Posyandu</span>
            </a>

        </nav>

        <!-- PENGATURAN -->
        <div class="sidebar-setting">
            <a href="/pengaturan" class="nav-item {{ Request::is('pengaturan') ? 'active' : '' }}" title="Pengaturan">
                <i class="fa-solid fa-gear"></i>
                <span class="nav-text">Pengaturan</span>
            </a>
        </div>

        <!-- FOOTER USER -->
        <div class="sidebar-footer">

            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama ?? 'User') }}&background=3b82f6&color=fff"
                class="user-avatar">

            <div class="user-info">
                <a href="/pengaturan" class="user-name" style="text-decoration: none; color: inherit; cursor: pointer;"
                    title="Pergi ke Pengaturan">
                    {{ Auth::user()->nama ?? 'Pengguna' }}
                </a>
                <span class="user-role"
                    style="text-transform: capitalize;">{{ str_replace('_', ' ', Auth::user()->jabatan ?? 'Kader') }}</span>
            </div>

            <form action="/logout" method="POST" id="formLogoutSidebar">
                @csrf
                <button type="button" class="logout-btn" title="Keluar" onclick="openLogoutModal()">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>

        </div>

    </aside>


    <main class="main-content">
        @yield('content')
    </main>

    <!-- LOGOUT MODAL -->
    <div class="logout-modal-overlay" id="logoutModal">
        <div class="logout-modal-card">
            <div class="logout-modal-icon">
                <i class="fa-solid fa-right-from-bracket"></i>
            </div>
            <h3>Konfirmasi Keluar</h3>
            <p>Apakah Anda yakin ingin mengakhiri sesi dan keluar dari aplikasi POSYANDU LANSIA?</p>
            <div class="logout-modal-actions">
                <button type="button" class="btn-cancel" onclick="closeLogoutModal()">Batal</button>
                <button type="button" class="btn-confirm" onclick="submitLogout()">Ya, Keluar</button>
            </div>
        </div>
    </div>

    @stack('scripts')
    <script>
        // INIT SIDEBAR STATE BEFORE MOUNT TO PREVENT FLICKER
        document.addEventListener('DOMContentLoaded', () => {
            const sidebarBtn = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');

            // Set state based on local storage
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                sidebar.classList.add('collapsed');
            }

            sidebarBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                // Save state
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            });
        });

        document.querySelectorAll('.dropdown-toggle').forEach(item => {
            item.addEventListener('click', () => {
                item.parentElement.classList.toggle('open');
            });
        });

        function openLogoutModal() {
            document.getElementById('logoutModal').classList.add('active');
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.remove('active');
        }

        function submitLogout() {
            document.getElementById('formLogoutSidebar').submit();
        }
    </script>
>>>>>>> aldi
</body>
</html>