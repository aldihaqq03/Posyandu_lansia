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

    @php
        $sidebarRole = strtolower(Auth::user()->jabatan ?? '');
    @endphp

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

            @if(in_array($sidebarRole, ['kepala_kader', 'super_admin']))
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

            @if(in_array($sidebarRole, ['kepala_kader', 'super_admin']))
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

            <a href="/konten" class="nav-item {{ Request::is('konten*') ? 'active' : '' }}"
                title="Konten">
                <i class="fa-solid fa-photo-film"></i>
                <span class="nav-text">Konten</span>
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

            @php
                $sidebarName = trim(Auth::user()->nama ?? 'User');
                $sidebarParts = preg_split('/\s+/', $sidebarName, -1, PREG_SPLIT_NO_EMPTY) ?: [];
                $sidebarInitials = collect($sidebarParts)
                    ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
                    ->take(2)
                    ->implode('');
                if ($sidebarInitials === '') {
                    $sidebarInitials = strtoupper(substr($sidebarName, 0, 2));
                }
            @endphp

            @php
                $sidebarPhoto = Auth::user()->petugas?->foto;
            @endphp

            @if($sidebarPhoto)
                <img src="{{ asset('storage/' . $sidebarPhoto) }}" class="user-avatar" alt="Foto Pengguna">
            @else
                <div class="user-avatar user-avatar-fallback">{{ $sidebarInitials }}</div>
            @endif

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

    {{-- ══════════════════════════════════════════════
         GLOBAL TOAST NOTIFICATION SYSTEM
         Gunakan: showToast('Pesan', 'success'|'error'|'warning'|'info')
    ══════════════════════════════════════════════ --}}
    <div id="toast-container" aria-live="polite" aria-atomic="false"></div>

    <style>
        /* ── Toast Container ───────────────────────────── */
        #toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            gap: 12px;
            pointer-events: none;
        }

        /* ── Toast Item ────────────────────────────────── */
        .toast-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            min-width: 300px;
            max-width: 420px;
            padding: 14px 16px;
            border-radius: 14px;
            background: #ffffff;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.06);
            border-left: 4px solid #3b82f6;
            pointer-events: all;
            cursor: pointer;
            animation: toast-in 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .toast-item.toast-hiding {
            animation: toast-out 0.3s ease forwards;
        }
        .toast-item.toast-success { border-left-color: #10b981; }
        .toast-item.toast-error   { border-left-color: #ef4444; }
        .toast-item.toast-warning { border-left-color: #f59e0b; }
        .toast-item.toast-info    { border-left-color: #3b82f6; }

        /* ── Icon ──────────────────────────────────────── */
        .toast-icon {
            flex-shrink: 0;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }
        .toast-success .toast-icon { background: #d1fae5; color: #059669; }
        .toast-error   .toast-icon { background: #fee2e2; color: #dc2626; }
        .toast-warning .toast-icon { background: #fef3c7; color: #d97706; }
        .toast-info    .toast-icon { background: #dbeafe; color: #2563eb; }

        /* ── Body ──────────────────────────────────────── */
        .toast-body { flex: 1; min-width: 0; }
        .toast-title {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 2px;
            line-height: 1.3;
        }
        .toast-message {
            font-size: 12.5px;
            color: #475569;
            margin: 0;
            line-height: 1.5;
            word-break: break-word;
        }

        /* ── Close Button ──────────────────────────────── */
        .toast-close {
            flex-shrink: 0;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 14px;
            padding: 2px;
            line-height: 1;
            transition: color 0.2s;
            align-self: flex-start;
        }
        .toast-close:hover { color: #475569; }

        /* ── Progress Bar ──────────────────────────────── */
        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            border-radius: 0 0 14px 14px;
            background: currentColor;
            opacity: 0.25;
            animation: toast-progress linear forwards;
        }
        .toast-item { position: relative; overflow: hidden; }
        .toast-success .toast-progress { color: #10b981; }
        .toast-error   .toast-progress { color: #ef4444; }
        .toast-warning .toast-progress { color: #f59e0b; }
        .toast-info    .toast-progress { color: #3b82f6; }

        /* ── Animations ────────────────────────────────── */
        @keyframes toast-in {
            from { opacity: 0; transform: translateX(60px) scale(0.92); }
            to   { opacity: 1; transform: translateX(0) scale(1); }
        }
        @keyframes toast-out {
            from { opacity: 1; transform: translateX(0) scale(1); max-height: 200px; margin-bottom: 0; }
            to   { opacity: 0; transform: translateX(60px) scale(0.92); max-height: 0; margin-bottom: -12px; }
        }
        @keyframes toast-progress {
            from { width: 100%; }
            to   { width: 0%; }
        }
    </style>

    <script>
        /* ── showToast(message, type, duration) ──────────────────────
           type    : 'success' | 'error' | 'warning' | 'info'
           duration: ms, default 4500
        ────────────────────────────────────────────────────────── */
        window.showToast = function(message, type = 'info', duration = 4500) {
            const icons = {
                success: '<i class="fa-solid fa-circle-check"></i>',
                error  : '<i class="fa-solid fa-circle-exclamation"></i>',
                warning: '<i class="fa-solid fa-triangle-exclamation"></i>',
                info   : '<i class="fa-solid fa-circle-info"></i>',
            };
            const titles = {
                success: 'Berhasil',
                error  : 'Gagal',
                warning: 'Perhatian',
                info   : 'Informasi',
            };

            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast-item toast-${type}`;
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `
                <div class="toast-icon">${icons[type] || icons.info}</div>
                <div class="toast-body">
                    <p class="toast-title">${titles[type] || 'Informasi'}</p>
                    <p class="toast-message">${message}</p>
                </div>
                <button class="toast-close" title="Tutup"><i class="fa-solid fa-xmark"></i></button>
                <div class="toast-progress" style="animation-duration: ${duration}ms;"></div>
            `;

            container.appendChild(toast);

            const dismiss = () => {
                toast.classList.add('toast-hiding');
                toast.addEventListener('animationend', () => toast.remove(), { once: true });
            };

            toast.querySelector('.toast-close').addEventListener('click', dismiss);
            toast.addEventListener('click', dismiss);

            const timer = setTimeout(dismiss, duration);
            toast.addEventListener('mouseenter', () => clearTimeout(timer));
        };

        /* ── Auto-display Laravel flash messages ──────────────── */
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                showToast(@json(session('success')), 'success');
            @endif
            @if (session('error'))
                showToast(@json(session('error')), 'error');
            @endif
            @if (session('warning'))
                showToast(@json(session('warning')), 'warning');
            @endif
            @if (session('info'))
                showToast(@json(session('info')), 'info');
            @endif
            @if ($errors->any())
                @foreach ($errors->all() as $err)
                    showToast(@json($err), 'error');
                @endforeach
            @endif
        });
    </script>

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