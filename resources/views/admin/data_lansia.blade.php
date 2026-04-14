@extends('layout.sidebar')

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
                                <span class="badge-pill">-</span>
                            </td>

                            <td>
                                <span class="badge-status muted">NORMAL</span>
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
@endpush