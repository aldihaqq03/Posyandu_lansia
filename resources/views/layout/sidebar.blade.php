<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

            <a href="/dashboard" class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i>
                Dashboard
            </a>

            @if(strtolower(Auth::user()->jabatan) === 'kepala_kader')
                <a href="/data_petugas" class="nav-item {{ Request::is('data_petugas') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-nurse"></i>
                    Data Petugas
                </a>
            @endif

            <a href="/data_lansia" class="nav-item {{ Request::is('data_lansia') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i>
                Data Lansia
            </a>

            <div class="nav-dropdown">

                <div class="nav-item dropdown-toggle">
                    <i class="fa-solid fa-notes-medical"></i>
                    Pemeriksaan
                    <i class="fa-solid fa-chevron-down dropdown-icon"></i>
                </div>

                <div class="dropdown-menu">
                    <a href="/skrining_utama" class="{{ Request::is('skrining_utama') ? 'active' : '' }}">
                        skrining utama
                    </a>

                    <a href="/pemeriksaan/create" class="{{ Request::is('pemeriksaan/create') ? 'active' : '' }}">
                        skrining ppok
                    </a>
                </div>

            </div>

            @if(strtolower(Auth::user()->jabatan) === 'kepala_kader')
                <a href="/laporan" class="nav-item {{ Request::is('laporan') ? 'active' : '' }}">
                    <i class="fa-solid fa-file"></i>
                    Laporan
                </a>
            @endif

            <a href="/jadwal_posyandu" class="nav-item {{ Request::is('jadwal_posyandu') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar"></i>
                Jadwal Posyandu
            </a>

        </nav>

        <!-- PENGATURAN -->
        <div class="sidebar-setting">
            <a href="/pengaturan" class="nav-item {{ Request::is('pengaturan') ? 'active' : '' }}">
                <i class="fa-solid fa-gear"></i>
                Pengaturan
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