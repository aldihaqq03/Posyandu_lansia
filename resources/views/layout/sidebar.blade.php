<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
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
        <div class="logo-left">
            <div class="logo-icon"><x-logo style="width:100%; height:100%;" /></div>
            <div class="logo-text"><span class="brand-name">SIMPEL</span><span class="brand-tagline">Sistem Informasi Posyandu</span></div>
        </div>
        <button type="button" class="sidebar-toggle" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-title">MAIN MENU</div>
        <a href="/dashboard" class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}"><i class="fa-solid fa-chart-line"></i><span class="nav-text">Dashboard</span></a>
        @if(in_array($sidebarRole, ['kepala_kader', 'super_admin']))
            <a href="/data_petugas" class="nav-item {{ Request::is('data_petugas') ? 'active' : '' }}"><i class="fa-solid fa-user-nurse"></i><span class="nav-text">Data Petugas</span></a>
        @endif
        <a href="/data_lansia" class="nav-item {{ Request::is('data_lansia') ? 'active' : '' }}"><i class="fa-solid fa-users"></i><span class="nav-text">Data Lansia</span></a>
        <a href="/obat" class="nav-item {{ Request::is('obat') ? 'active' : '' }}"><i class="fa-solid fa-pills"></i><span class="nav-text">Data Obat</span></a>
        <a href="/skrining" class="nav-item {{ Request::is('skrining') ? 'active' : '' }}"><i class="fa-solid fa-notes-medical"></i><span class="nav-text">Input Skrining</span></a>
        @if(in_array($sidebarRole, ['kepala_kader', 'super_admin']))
            <a href="/laporan" class="nav-item {{ Request::is('laporan') ? 'active' : '' }}"><i class="fa-solid fa-file"></i><span class="nav-text">Laporan</span></a>
        @endif
        <a href="/jadwal_posyandu" class="nav-item {{ Request::is('jadwal_posyandu') ? 'active' : '' }}"><i class="fa-solid fa-calendar"></i><span class="nav-text">Jadwal Posyandu</span></a>
        <a href="/konten" class="nav-item {{ Request::is('konten*') ? 'active' : '' }}"><i class="fa-solid fa-photo-film"></i><span class="nav-text">Konten</span></a>

        <div class="nav-section-title">REFERENSI</div>
        <button type="button" class="nav-item nav-item-btn" onclick="openParamModal()"><i class="fa-solid fa-heart-pulse"></i><span class="nav-text">Parameter Kesehatan</span></button>

        <div class="nav-section-title">PROFIL</div>
        {{-- Ganti Pengaturan menjadi Profil + ikon profil --}}
        <a href="/pengaturan" class="nav-item {{ Request::is('pengaturan') ? 'active' : '' }}"><i class="fa-regular fa-circle-user"></i><span class="nav-text">Profil</span></a>
    </nav>

    <div class="sidebar-footer">
        @php
            $sidebarName = trim(Auth::user()->nama ?? 'User');
            $sidebarParts = preg_split('/\s+/', $sidebarName, -1, PREG_SPLIT_NO_EMPTY) ?: [];
            $sidebarInitials = collect($sidebarParts)->map(fn($p)=>strtoupper(substr($p,0,1)))->take(2)->implode('');
            if ($sidebarInitials === '') $sidebarInitials = strtoupper(substr($sidebarName,0,2));
            $sidebarPhoto = Auth::user()->petugas?->foto;
        @endphp
        @if($sidebarPhoto)
            <img src="{{ asset('storage/'.$sidebarPhoto) }}" class="user-avatar" alt="Foto Pengguna">
        @else
            <div class="user-avatar user-avatar-fallback">{{ $sidebarInitials }}</div>
        @endif
        <div class="user-info">
            <a href="/pengaturan" class="user-name">{{ Auth::user()->nama ?? 'Pengguna' }}</a>
            <span class="user-role">{{ str_replace('_',' ', Auth::user()->jabatan ?? 'Kader') }}</span>
        </div>
        <form action="/logout" method="POST" id="formLogoutSidebar">@csrf<button type="button" class="logout-btn" onclick="openLogoutModal()"><i class="fa-solid fa-right-from-bracket"></i></button></form>
    </div>
</aside>

<main class="main-content">@yield('content')</main>

<!-- LOGOUT MODAL -->
<div class="logout-modal-overlay" id="logoutModal"><div class="logout-modal-card"><div class="logout-modal-icon"><i class="fa-solid fa-right-from-bracket"></i></div><h3>Konfirmasi Keluar</h3><p>Apakah Anda yakin ingin mengakhiri sesi dan keluar dari aplikasi POSYANDU LANSIA?</p><div class="logout-modal-actions"><button type="button" class="btn-cancel" onclick="closeLogoutModal()">Batal</button><button type="button" class="btn-confirm" onclick="submitLogout()">Ya, Keluar</button></div></div></div>

<!-- TOAST CONTAINER -->
<div id="toast-container" aria-live="polite"></div>

{{-- INCLUDE PARAMETER MODAL (sudah terpisah) --}}
@includeIf('layout.parameter_kesehatan')

<style>
    /* Gaya global dan sidebar (sama seperti sebelumnya) */
    * { margin:0; padding:0; box-sizing:border-box; font-family:"Inter",sans-serif; }
    html,body { width:100%; min-height:100vh; background:#f8fafc; }
    body { display:flex; }
    .sidebar { width:280px; height:100vh; position:fixed; left:0; top:0; background:linear-gradient(180deg,#0c1835 25%,#3b82f6 75%); border-right:1px solid rgba(255,255,255,0.06); display:flex; flex-direction:column; padding:20px 16px; transition:width 0.3s ease; z-index:999; border-radius:0 24px 24px 0; }
    .logo-left { display:flex; align-items:center; gap:14px; }
    .logo-section { display:flex; align-items:center; justify-content:space-between; margin-bottom:30px; }
    .logo-icon { width:52px; height:52px; border-radius:16px; background:linear-gradient(135deg,#2563eb,#3b82f6); display:flex; align-items:center; justify-content:center; padding:10px; box-shadow:0 10px 25px rgba(37,99,235,.35); }
    .brand-name { font-size:22px; font-weight:700; color:white; }
    .brand-tagline { font-size:11px; color:#9ca3af; }
    .sidebar-nav { flex:1; overflow-y:auto; scrollbar-width:none; }
    .sidebar-nav::-webkit-scrollbar { display:none; }
    .nav-item { display:flex; align-items:center; gap:14px; padding:14px 16px; border-radius:16px; color:#cbd5e1; text-decoration:none; margin-bottom:8px; transition:0.25s; font-weight:500; font-size:14px; background:none; border:none; cursor:pointer; width:100%; text-align:left; }
    .nav-item i { width:20px; text-align:center; font-size:16px; }
    .nav-item:hover { background:rgba(255,255,255,0.06); color:white; transform:translateX(4px); }
    .nav-item.active { background:linear-gradient(135deg,#2563eb,#3b82f6); color:white; box-shadow:0 10px 25px rgba(37,99,235,.35); }
    .nav-section-title { font-size:11px; font-weight:700; letter-spacing:1.5px; color:rgba(255,255,255,0.55); margin:16px 14px 10px; padding-bottom:10px; border-bottom:1px solid rgba(255,255,255,0.12); white-space:nowrap; overflow:hidden; }
    .sidebar-footer { margin-top:auto; padding:12px 14px; border-radius:16px; background:rgba(255,255,255,0.08); display:flex; align-items:center; gap:12px; backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,0.1); }
    .user-avatar, .user-avatar-fallback { width:40px; height:40px; min-width:40px; border-radius:12px; background:rgba(255,255,255,0.16); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; object-fit:cover; }
    .user-info { flex:1; min-width:0; }
    .user-name { font-size:14px; font-weight:600; color:#fff; text-decoration:none; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block; }
    .user-role { font-size:12px; color:rgba(255,255,255,0.6); text-transform:capitalize; }
    .logout-btn { margin-left:auto; width:32px; height:32px; border:none; border-radius:8px; background:rgba(255,255,255,0.08); color:rgba(255,255,255,0.8); cursor:pointer; }
    .logout-btn:hover { background:rgba(255,255,255,0.16); color:#fff; }
    .main-content { margin-left:280px; width:calc(100% - 280px); padding:24px 28px; min-height:100vh; transition:margin-left 0.3s ease, width 0.3s ease; }
    .sidebar-toggle { width:32px; height:32px; border:none; border-radius:10px; background:rgba(255,255,255,0.08); color:white; cursor:pointer; }
    .sidebar.collapsed { width:85px; }
    .sidebar.collapsed .logo-text, .sidebar.collapsed .nav-text, .sidebar.collapsed .nav-section-title, .sidebar.collapsed .user-info { display:none; }
    .sidebar.collapsed .sidebar-nav { align-items:center; }
    .sidebar.collapsed .nav-item { width:52px; height:52px; padding:0; justify-content:center; margin-bottom:10px; gap:0; }
    .sidebar.collapsed .sidebar-footer { flex-direction:column; width:52px; padding:10px 0; gap:8px; }
    .sidebar.collapsed ~ .main-content { margin-left:85px; width:calc(100% - 85px); }

    /* Toast */
    #toast-container { position:fixed; top:24px; right:24px; z-index:99999; display:flex; flex-direction:column; gap:12px; pointer-events:none; }
    .toast-item { display:flex; align-items:flex-start; gap:12px; min-width:300px; max-width:420px; padding:14px 16px; border-radius:14px; background:#fff; box-shadow:0 8px 30px rgba(0,0,0,.12); border-left:4px solid #3b82f6; pointer-events:auto; cursor:pointer; animation:toast-in .35s cubic-bezier(.34,1.56,.64,1); position:relative; }
    .toast-item.toast-success { border-left-color:#10b981; }
    .toast-item.toast-error { border-left-color:#ef4444; }
    .toast-item.toast-warning { border-left-color:#f59e0b; }
    .toast-icon { flex-shrink:0; width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; }
    .toast-success .toast-icon { background:#d1fae5; color:#059669; }
    .toast-error .toast-icon { background:#fee2e2; color:#dc2626; }
    .toast-body { flex:1; }
    .toast-title { font-size:13px; font-weight:700; color:#0f172a; margin-bottom:2px; }
    .toast-message { font-size:12.5px; color:#475569; }
    .toast-close { background:none; border:none; color:#94a3b8; cursor:pointer; }
    .toast-progress { position:absolute; bottom:0; left:0; height:3px; background:currentColor; opacity:.25; animation:toast-progress linear forwards; }
    @keyframes toast-in { from{opacity:0;transform:translateX(60px) scale(.92)} to{opacity:1;transform:translateX(0) scale(1)} }
    @keyframes toast-progress { from{width:100%} to{width:0%} }

    /* Logout modal */
    .logout-modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,.5); backdrop-filter:blur(4px); display:flex; align-items:center; justify-content:center; z-index:9999; opacity:0; pointer-events:none; transition:opacity .3s; }
    .logout-modal-overlay.active { opacity:1; pointer-events:auto; }
    .logout-modal-card { background:#fff; width:90%; max-width:380px; border-radius:16px; padding:30px; text-align:center; transform:translateY(20px); transition:transform .3s cubic-bezier(.175,.885,.32,1.275); }
    .logout-modal-overlay.active .logout-modal-card { transform:translateY(0); }
    .logout-modal-icon { width:60px; height:60px; background:#fef2f2; color:#ef4444; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:28px; margin:0 auto 20px; }
    .logout-modal-card h3 { margin-bottom:10px; color:#1f2937; }
    .logout-modal-actions { display:flex; gap:12px; justify-content:center; }
    .logout-modal-actions button { flex:1; padding:12px; border-radius:8px; font-weight:600; cursor:pointer; border:none; }
    .btn-cancel { background:#f3f4f6; color:#374151; }
    .btn-confirm { background:#ef4444; color:white; }
</style>

<script>
    window.showToast = function(message, type='info', duration=4500) {
        const icons={success:'<i class="fa-solid fa-circle-check"></i>',error:'<i class="fa-solid fa-circle-exclamation"></i>',warning:'<i class="fa-solid fa-triangle-exclamation"></i>',info:'<i class="fa-solid fa-circle-info"></i>'};
        const titles={success:'Berhasil',error:'Gagal',warning:'Perhatian',info:'Informasi'};
        const container=document.getElementById('toast-container');
        const toast=document.createElement('div');
        toast.className=`toast-item toast-${type}`;
        toast.innerHTML=`<div class="toast-icon">${icons[type]||icons.info}</div><div class="toast-body"><p class="toast-title">${titles[type]||'Informasi'}</p><p class="toast-message">${message}</p></div><button class="toast-close"><i class="fa-solid fa-xmark"></i></button><div class="toast-progress" style="animation-duration:${duration}ms;"></div>`;
        container.appendChild(toast);
        const dismiss=()=>{ toast.classList.add('toast-hiding'); toast.addEventListener('animationend',()=>toast.remove(),{once:true}); };
        toast.querySelector('.toast-close').addEventListener('click',dismiss);
        toast.addEventListener('click',dismiss);
        setTimeout(dismiss,duration);
    };
    document.addEventListener('DOMContentLoaded',function(){
        @if(session('success')) showToast(@json(session('success')),'success'); @endif
        @if(session('error')) showToast(@json(session('error')),'error'); @endif
        @if(session('warning')) showToast(@json(session('warning')),'warning'); @endif
        @if(session('info')) showToast(@json(session('info')),'info'); @endif
        @if($errors->any()) @foreach($errors->all() as $err) showToast(@json($err),'error'); @endforeach @endif
    });
    function openLogoutModal() { document.getElementById('logoutModal').classList.add('active'); }
    function closeLogoutModal() { document.getElementById('logoutModal').classList.remove('active'); }
    function submitLogout() { document.getElementById('formLogoutSidebar').submit(); }
    // Fungsi untuk membuka modal parameter (didefinisikan juga di parameter-modal.blade.php)
    window.openParamModal = function() { const modal = document.getElementById('paramModal'); if(modal) modal.classList.add('active'); };
    window.closeParamModal = function() { const modal = document.getElementById('paramModal'); if(modal) modal.classList.remove('active'); };
    window.closeParamIfOutside = function(e) { if(e.target === document.getElementById('paramModal')) closeParamModal(); };
    // Sidebar collapse
    document.addEventListener('DOMContentLoaded',()=>{
        const btn=document.getElementById('sidebarToggle'), sidebar=document.getElementById('sidebar');
        if(localStorage.getItem('sidebarCollapsed')==='true') sidebar.classList.add('collapsed');
        btn.addEventListener('click',()=>{ sidebar.classList.toggle('collapsed'); localStorage.setItem('sidebarCollapsed',sidebar.classList.contains('collapsed')); });
    });
</script>
@stack('scripts')
</body>
</html>