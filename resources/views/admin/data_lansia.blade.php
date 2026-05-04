{{-- @extends('layout.sidebar')

@push('styles')
    @vite('resources/css/app.css')
@endpush


@push('styles')
    @vite('resources/css/cssAdmin/data_lansia.css')
@endpush

<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="route-store-lansia" content="{{ route('lansia.store') }}">

@section('content')
    <main class="main-content">

        <!-- Tampilkan Error Validasi Jika Ada -->
        @if ($errors->any())
            <div style="background: #ffebe9; border: 1px solid rgba(255,129,130,0.4); border-radius: 6px; padding: 15px; margin-bottom: 20px;">
                <h4 style="color: #cf222e; margin-bottom: 5px;">Terjadi Kesalahan:</h4>
                <ul style="color: #cf222e; padding-left: 20px; font-size: 14px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="container">
            <header class="page-header">
                <div class="header-info">
                    <nav class="breadcrumb" aria-label="Breadcrumb">
                        <img class="icon" src="img/icon-2.svg" alt="Home" />
                        <img class="separator" src="img/icon-6.svg" alt="" />
                        <span class="text-muted">MANAJEMEN</span>
                    </nav>
                    <h1 class="page-title">Data Lansia</h1>
                    <p class="page-subtitle">Kelola informasi kesehatan lansia untuk pelayanan Posyandu yang lebih baik.</p>
                </div>
                <button class="btn-primary" type="button" id="btn-tambah-lansia">
                    <img src="img/icon-10.svg" alt="" />
                    <span>Tambah Lansia</span>
                </button>
            </header>

            <section class="stats-grid" aria-label="Statistik Lansia">
                <div class="stat-card">
                    <h2 class="stat-label">TOTAL TERDAFTAR</h2>
                    <div class="stat-content">
                        <span class="stat-number">{{ $total_lansia ?? 0 }}</span>
                        <img src="img/icon-4.svg" alt="" class="stat-icon" />
                    </div>
                </div>
                <div class="stat-card border-danger">
                    <h2 class="stat-label text-danger">RESIKO TINGGI</h2>
                    <div class="stat-content">
                        <span class="stat-number color-danger">{{ $resiko_tinggi ?? 0 }}</span>
                        <img src="img/image.svg" alt="" class="stat-icon" />
                    </div>
                </div>
                <div class="stat-card border-success">
                    <h2 class="stat-label text-success">STATUS SEHAT</h2>
                    <div class="stat-content">
                        <span class="stat-number color-success">{{ $status_sehat ?? 0 }}</span>
                        <img src="img/icon-3.svg" alt="" class="stat-icon" />
                    </div>
                </div>
                <div class="stat-card">
                    <h2 class="stat-label">JADWAL PERIKSA</h2>
                    <div class="stat-content">
                        <span class="stat-number">{{ $jadwal_periksa ?? 0 }}</span>
                        <img src="img/icon-11.svg" alt="" class="stat-icon" />
                    </div>
                </div>
            </section>

            <section class="table-container card">
                <div class="table-header-actions">
                    <div class="search-wrapper">
                        <img src="img/icon-7.svg" alt="" />
                        <input type="search" placeholder="Cari nama, NIK, atau alamat..." id="main-search" />
                    </div>
                    <button class="btn-outline" id="btn-filter-lansia">
                        <i class="fa-solid fa-filter"></i>
                        <span>Filter</span>
                    </button>
                </div>

                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>NAMA LENGKAP</th>
                            <th>TANGGAL LAHIR</th>
                            <th>ALAMAT</th>
                            <th>PENYAKIT</th>
                            <th>STATUS RISIKO</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lansias as $lansia)
                        <tr class="table-row selectable-row active"
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
                            <td>
                                <div class="user-cell">
                                    <div class="avatar">{{ strtoupper(substr($lansia->nama_lansia, 0, 2)) }}</div>
                                    <div class="user-text">
                                        <span class="user-name">{{ $lansia->nama_lansia }}</span>
                                        <span class="user-subtext">{{ $lansia->nik }}</span>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="main-text">{{ \Carbon\Carbon::parse($lansia->tanggal_lahir)->age }} Tahun</span>
                                <span class="sub-text">{{ \Carbon\Carbon::parse($lansia->tanggal_lahir)->format('d/m/Y') }}</span>
                            </td>

                            <td>
                                <address>{{ $lansia->alamat }}</address>
                            </td>

                            <td>
                                <span class="badge-pill">{{ $lansia->riwayat_penyakit ?? '-' }}</span>
                            </td>
                            <td>
                                @php
                                    $penyakit_berat = ['Hipertensi', 'Diabetes', 'Jantung', 'Stroke', 'PPOK'];
                                    $punya_penyakit_berat = false;
                                    foreach($penyakit_berat as $pb) {
                                        if(stripos($lansia->riwayat_penyakit, $pb) !== false) {
                                            $punya_penyakit_berat = true;
                                            break;
                                        }
                                    }

                                    $is_high_risk = $punya_penyakit_berat || ($lansia->latestSkriningUtama && ($lansia->latestSkriningUtama->gula_darah_kategori == 3 || $lansia->latestSkriningUtama->kolesterol_kategori == 3));
                                    $is_warning = $lansia->latestSkriningUtama && ($lansia->latestSkriningUtama->gula_darah_kategori == 2 || $lansia->latestSkriningUtama->kolesterol_kategori == 2);
                                @endphp

                                @if($is_high_risk)
                                    <span class="badge-status high">RESIKO TINGGI</span>
                                @elseif($is_warning)
                                    <span class="badge-status warning">WASPADA</span>
                                @elseif($lansia->latestSkriningUtama)
                                    <span class="badge-status success">NORMAL</span>
                                @else
                                    <span class="badge-status muted">BELUM PERIKSA</span>
                                @endif
                            </td>

                            <td class="aksi">

                                <!-- tombol detail -->
                                <button class="btn-icon view-btn" title="Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </button>

                                <!-- tombol edit -->
                                <button class="btn-icon edit-btn" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                <!-- tombol hapus -->
                                <button class="btn-icon delete-btn" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">Belum ada data lansia.</td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </section>

            <section class="detail-container card">
                <div class="detail-header">
                    <div class="header-content">
                        <div class="icon-box"><img src="img/icon.svg" alt=""></div>
                        <div>
                            <h3>Ringkasan Detail Lansia</h3>
                            <p>Informasi terkini untuk <strong id="dynamic-name">-</strong></p>
                        </div>
                    </div>
                    <a href="#" id="btn-profil-lengkap" class="btn-outline-blue">Profil Lengkap <i class="fa-solid fa-arrow-right"></i></a>
                </div>

                <div class="detail-content-grid">
                    <div class="profile-side">
                        <div class="profile-photo">
                            <img src="img/icon-9.svg" alt="User">
                        </div>
                        <span class="badge-risk high">-</span>
                        <h2 id="name-display">-</h2>
                        <p class="age-text">-</p>
                        <div class="status-pill live">-</div>
                    </div>

                    <div class="info-side-grid">
                        <div class="info-column">
                            <h4>👤 INFORMASI PRIBADI</h4>
                            <div class="data-item">
                                <label>NIK</label>
                                <p>-</p>
                            </div>
                            <div class="data-item">
                                <label>NOMOR HANDPHONE</label>
                                <p>-</p>
                            </div>
                            <div class="data-item">
                                <label>ALAMAT LENGKAP</label>
                                <p>-</p>
                            </div>
                        </div>

                        <div class="info-column">
                            <h4>📈 KESEHATAN TERAKHIR</h4>
                            <div class="health-cards">
                                <div class="h-card red">
                                    <span>TENSI</span>
                                    <strong>-</strong>
                                    <small></small>
                                </div>
                                <div class="h-card blue">
                                    <span>GULA DARAH</span>
                                    <strong>-</strong>
                                    <small></small>
                                </div>
                            </div>
                            <div class="medical-note">
                                <label>CATATAN MEDIS</label>
                                <blockquote>"-"</blockquote>
                            </div>
                        </div>
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
@endpush --}}@extends('layout.sidebar')

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
                        <div class="data-item"><label>NIK</label><p id="d-nik">-</p></div>
                        <div class="data-item"><label>NO. HANDPHONE</label><p id="d-hp">-</p></div>
                        <div class="data-item"><label>ALAMAT LENGKAP</label><p id="d-alamat">-</p></div>
                        <div class="data-item">
                            <label>RIWAYAT PENYAKIT</label>
                            <p id="d-riwayat">-</p>
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
                        <p class="health-note">
                            <i class="fa-solid fa-circle-info"></i>
                            Tensi dari skrining kunjungan terakhir. Gula darah &amp; kolesterol dari skrining utama terakhir.
                        </p>
                    </div>
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