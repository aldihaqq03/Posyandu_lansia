@extends('layout.sidebar')

@push('styles')
    @vite(['resources/css/app.css', 'resources/css/cssAdmin/data_lansia.css'])
@endpush

<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="route-store-lansia" content="{{ route('lansia.store') }}">

@section('content')
<main class="main-content">

    {{-- Flash & Validasi Errors --}}
    @if ($errors->any())
        <div class="alert-error">
            <strong>Terjadi Kesalahan:</strong>
            <ul>@foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="container">

        {{-- ── HEADER ─────────────────────────────────────────────── --}}
        <header class="page-header">
            <div class="header-info">
                <nav class="breadcrumb" aria-label="Breadcrumb">
                    <img class="icon" src="img/icon-2.svg" alt="">
                    <img class="separator" src="img/icon-6.svg" alt="">
                    <span class="text-muted">MANAJEMEN</span>
                </nav>
                <h1 class="page-title">Data Lansia</h1>
                <p class="page-subtitle">Kelola informasi kesehatan lansia untuk pelayanan Posyandu yang lebih baik.</p>
            </div>
            <button class="btn-primary" type="button" id="btn-tambah-lansia">
                <img src="img/icon-10.svg" alt="">
                <span>Tambah Lansia</span>
            </button>
        </header>

        {{-- ── STATISTIK ───────────────────────────────────────────── --}}
        <section class="stats-grid" aria-label="Statistik Lansia">
            <div class="stat-card">
                <h2 class="stat-label">TOTAL TERDAFTAR</h2>
                <div class="stat-content">
                    <span class="stat-number">{{ $total_lansia ?? 0 }}</span>
                    <img src="img/icon-4.svg" alt="" class="stat-icon">
                </div>
            </div>
            <div class="stat-card border-danger">
                <h2 class="stat-label text-danger">RESIKO TINGGI</h2>
                <div class="stat-content">
                    <span class="stat-number color-danger">{{ $resiko_tinggi ?? 0 }}</span>
                    <img src="img/image.svg" alt="" class="stat-icon">
                </div>
            </div>
            <div class="stat-card border-success">
                <h2 class="stat-label text-success">STATUS SEHAT</h2>
                <div class="stat-content">
                    <span class="stat-number color-success">{{ $status_sehat ?? 0 }}</span>
                    <img src="img/icon-3.svg" alt="" class="stat-icon">
                </div>
            </div>
            <div class="stat-card">
                <h2 class="stat-label">JADWAL PERIKSA</h2>
                <div class="stat-content">
                    <span class="stat-number">{{ $jadwal_periksa ?? 0 }}</span>
                    <img src="img/icon-11.svg" alt="" class="stat-icon">
                </div>
            </div>
        </section>

        {{-- ── TABEL LANSIA (4 kolom sesuai spesifikasi) ──────────── --}}
        <section class="table-container card">
            <div class="table-header-actions">
                <div class="search-wrapper">
                    <img src="img/icon-7.svg" alt="">
                    <input type="search" placeholder="Cari nama, NIK, atau alamat..." id="main-search">
                </div>
                <button class="btn-outline" id="btn-filter-lansia">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filter</span>
                </button>
            </div>

            <p class="row-hint"><i class="fa-solid fa-hand-pointer"></i> Klik baris untuk melihat ringkasan detail</p>

            <table class="custom-table">
                <thead>
                    <tr>
                        <th>NAMA LANSIA</th>
                        <th>UMUR</th>
                        <th>ALAMAT</th>
                        <th>NO. HANDPHONE</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($lansias as $lansia)
                    <tr class="table-row selectable-row"
                        title="Klik untuk melihat ringkasan detail"
                        data-id="{{ $lansia->id_lansia }}"
                        data-nama="{{ $lansia->nama_lansia }}"
                        data-nik="{{ $lansia->nik }}"
                        data-tanggal-lahir="{{ $lansia->tanggal_lahir }}"
                        data-alamat="{{ $lansia->alamat }}"
                        data-jenis-kelamin="{{ $lansia->jenis_kelamin }}"
                        data-no-hp="{{ $lansia->no_hp }}"
                        data-tempat-lahir="{{ $lansia->tempat_lahir }}"
                        data-status-perkawinan="{{ $lansia->status_perkawinan }}"
                        data-riwayat-penyakit="{{ $lansia->riwayat_penyakit }}"
                        data-tanggal-daftar="{{ $lansia->tanggal_daftar }}"
                        data-keterangan="{{ $lansia->keterangan }}"
                        data-email="{{ $lansia->email }}"
                        data-umur="{{ \Carbon\Carbon::parse($lansia->tanggal_lahir)->age }}"
                        data-format-tanggal="{{ \Carbon\Carbon::parse($lansia->tanggal_lahir)->format('d/m/Y') }}"
                    >
                        {{-- Nama --}}
                        <td>
                            <div class="user-cell">
                                <div class="avatar">{{ strtoupper(substr($lansia->nama_lansia, 0, 2)) }}</div>
                                <div class="user-text">
                                    <span class="user-name">{{ $lansia->nama_lansia }}</span>
                                    <span class="user-subtext">{{ $lansia->nik }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Umur --}}
                        <td>
                            <span class="main-text">{{ \Carbon\Carbon::parse($lansia->tanggal_lahir)->age }} Tahun</span>
                            <span class="sub-text">{{ \Carbon\Carbon::parse($lansia->tanggal_lahir)->format('d/m/Y') }}</span>
                        </td>

                        {{-- Alamat --}}
                        <td><address>{{ $lansia->alamat ?? '-' }}</address></td>

                        {{-- No. HP --}}
                        <td>{{ $lansia->no_hp ?? '-' }}</td>

                        {{-- Aksi --}}
                        <td class="aksi" onclick="event.stopPropagation()">
                            <button class="btn-icon edit-btn" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn-icon delete-btn" title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-state">Belum ada data lansia.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="pagination-wrapper">
                {{ $lansias->links() }}
            </div>
        </section>

        {{-- ── DETAIL PANEL (muncul saat baris diklik) ────────────── --}}
        <section class="detail-container card" id="detail-panel" style="display:none;">
            <div class="detail-header">
                <div class="header-content">
                    <div class="icon-box"><img src="img/icon.svg" alt=""></div>
                    <div>
                        <h3>Detail Lansia</h3>
                        <p>Informasi terkini untuk <strong id="dynamic-name">-</strong></p>
                    </div>
                </div>
                {{-- Tombol "Histori Skrining" sesuai spesifikasi --}}
                <a href="#" id="btn-histori-skrining" class="btn-outline-blue">
                    <i class="fa-solid fa-clock-rotate-left"></i> Histori Skrining
                </a>
            </div>

            <div class="detail-content-grid">
                {{-- Kiri: avatar & identitas --}}
                <div class="profile-side">
                    <div class="big-avatar" id="detail-avatar">--</div>
                    <h2 id="name-display">-</h2>
                    <p class="age-text" id="detail-umur">-</p>
                    <div class="status-pill" id="detail-jk">-</div>
                </div>

                {{-- Kanan: info pribadi + kesehatan --}}
                <div class="info-side-grid">

                    {{-- Data Pribadi --}}
                    <div class="info-column">
    <h4>👤 DATA PRIBADI</h4>

    <div class="data-grid">

        <div class="data-item"><label>NIK</label><p id="d-nik">-</p></div>
        <div class="data-item"><label>No HP</label><p id="d-hp">-</p></div>

        <div class="data-item"><label>Email</label><p id="d-email">-</p></div>
        <div class="data-item"><label>Status</label><p id="d-status">-</p></div>

        <div class="data-item"><label>TTL</label><p id="d-ttl">-</p></div>
        <div class="data-item"><label>Jenis Kelamin</label><p id="d-jk-text">-</p></div>

        <div class="data-item"><label>Alamat</label><p id="d-alamat">-</p></div>
        <div class="data-item"><label>Riwayat</label><p id="d-riwayat">-</p></div>

        <div class="data-item"><label>Keterangan</label><p id="d-keterangan">-</p></div>

    </div>
</div>
                    {{-- Data Kesehatan Terakhir --}}
                    <div class="info-column">
                        <h4>📈 KESEHATAN TERAKHIR</h4>
                        <div class="health-cards">
                            <div class="h-card red">
                                <span>TENSI SISTOLIK</span>
                                <strong id="d-sistolik">-</strong>
                                <small>mmHg</small>
                            </div>
                            <div class="h-card orange">
                                <span>TENSI DIASTOLIK</span>
                                <strong id="d-diastolik">-</strong>
                                <small>mmHg</small>
                            </div>
                            <div class="h-card blue">
                                <span>GULA DARAH</span>
                                <strong id="d-gula">-</strong>
                                <small>mg/dL</small>
                            </div>
                            <div class="h-card purple">
                                <span>KOLESTEROL</span>
                                <strong id="d-kolesterol">-</strong>
                                <small>mg/dL</small>
                            </div>
                        </div>
                      
                    </div>
                </div>
            </div>

            {{-- Informasi Keluarga Section --}}
            <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e0e0e0;">
                <h4 style="margin-bottom: 15px; color: #333;">👨‍👩‍👧‍👦 Informasi Keluarga</h4>
                <div id="keluarga-info-section">
                    <p style="color: #999; text-align: center; padding: 20px;">Tidak ada data keluarga</p>
                </div>
            </div>
        </section>

    </div>

    @include('modal.M_tambahlansia')
    @include('modal.M_editlansia')
    @include('modal.M_hapus')
    @include('modal.M_filter')

</main>
@endsection

@push('scripts')
    @vite('resources/js/jsAdmin/data_lansia.js')
@endpush