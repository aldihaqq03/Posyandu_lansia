<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    
    

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <title>SIMPEL - @yield('title', 'Dashboard')</title>

    @vite('resources/css/sidebar.css')
    @stack('styles')
</head>

<body>

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
</body>

</html>