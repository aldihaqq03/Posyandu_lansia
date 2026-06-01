@extends('layout.sidebar')

@push('styles')
    @vite(['resources/css/app.css', 'resources/css/cssAdmin/data_lansia.css'])
@endpush

<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="route-store-lansia" content="{{ route('lansia.store') }}">

@section('content')
    <div class="lansia-page">

            {{-- ── HEADER ─────────────────────────────────────────────── --}}
           <div class="page-header">
    <div class="header-info">
        <h1 class="page-title">Data Lansia</h1>
        <p class="page-subtitle">Kelola data lansia yang terdaftar di posyandu beserta informasi kesehatannya.</p>
    </div>
    <button class="btn-primary" type="button" id="btn-tambah-lansia">
        <i class="fa-solid fa-plus"></i>
        <span>Tambah Lansia</span>
    </button>
</div>


            {{-- ── STATISTIK ───────────────────────────────────────────── --}}
            <section class="stats-grid" aria-label="Statistik Lansia">
                <div class="stat-card border-primary">
                    <span class="stat-label">TOTAL LANSIA</span>
                    <div class="stat-content">
                        <span class="stat-number color-primary">{{ $total_lansia ?? 0 }}</span>
                        <i class="fa-solid fa-users stat-icon-fa color-primary"></i>
                    </div>
                </div>
                <div class="stat-card border-success">
                    <span class="stat-label">KONDISI NORMAL</span>
                    <div class="stat-content">
                        <span class="stat-number color-success">{{ $kondisi_normal ?? 0 }}</span>
                        <i class="fa-solid fa-heart-pulse stat-icon-fa color-success"></i>
                    </div>
                </div>
                <div class="stat-card border-warning">
                    <span class="stat-label">WASPADA</span>
                    <div class="stat-content">
                        <span class="stat-number color-warning">{{ $waspada ?? 0 }}</span>
                        <i class="fa-solid fa-exclamation-circle stat-icon-fa color-warning"></i>
                    </div>
                </div>
                <div class="stat-card border-danger">
                    <span class="stat-label">PERLU PERHATIAN</span>
                    <div class="stat-content">
                        <span class="stat-number color-danger">{{ $perlu_perhatian ?? 0 }}</span>
                        <i class="fa-solid fa-triangle-exclamation stat-icon-fa color-danger"></i>
                    </div>
                </div>
            </section>

            {{-- ── TABEL LANSIA (4 kolom sesuai spesifikasi) ──────────── --}}
            <section class="table-container card">
                <div class="table-header-actions">
                    <div class="search-wrapper filter-search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="search" placeholder="Cari nama, NIK, atau alamat..." id="main-search">
                    </div>
                    <button class="btn-outline" id="btn-filter-lansia"
    style="{{ ($filterRisk !== 'semua' || $filterPenyakit !== '') ? 'border-color: #3b82f6; color: #3b82f6; background: #eff6ff;' : '' }}">
    <i class="fa-solid fa-filter"></i>
    <span>Filter</span>
    @if ($filterRisk !== 'semua' || $filterPenyakit !== '')
        <span style="
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #3b82f6;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            font-weight: 700;
            margin-left: 2px;
        ">
            {{ ($filterRisk !== 'semua' ? 1 : 0) + ($filterPenyakit !== '' ? 1 : 0) }}
        </span>
    @endif
</button>
                </div>

                <p class="row-hint"><i class="fa-solid fa-hand-pointer"></i> Klik baris untuk melihat ringkasan detail</p>

                {{-- Scroll container: tabel di sini, sticky thead bekerja & tinggi mengisi sisa layar --}}
                <div class="lansia-table-scroll">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>NAMA LANSIA</th>
                                <th>NIK</th>
                                <th>UMUR</th>
                                <th>ALAMAT</th>
                                <th>RISIKO</th>
                                <th>NO. HANDPHONE</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lansias as $lansia)
                                <tr class="table-row selectable-row" title="Klik untuk melihat ringkasan detail"
                                    data-id="{{ $lansia->id_lansia }}" data-nama="{{ $lansia->nama_lansia }}"
                                    data-nik="{{ $lansia->nik }}" data-tanggal-lahir="{{ $lansia->tanggal_lahir }}"
                                    data-alamat="{{ $lansia->alamat }}" data-jenis-kelamin="{{ $lansia->jenis_kelamin }}"
                                    data-no-hp="{{ $lansia->no_hp }}" data-tempat-lahir="{{ $lansia->tempat_lahir }}"
                                    data-status-perkawinan="{{ $lansia->status_perkawinan }}"
                                    data-riwayat-penyakit="{{ $lansia->riwayat_penyakit }}"
                                    data-tanggal-daftar="{{ $lansia->tanggal_daftar }}" data-keterangan="{{ $lansia->keterangan }}"
                                    data-email="{{ $lansia->email }}" data-kode-unik="{{ $lansia->kode_unik ?? '' }}"
                                    data-pekerjaan="{{ $lansia->pekerjaan ?? '' }}"
                                    data-umur="{{ \Carbon\Carbon::parse($lansia->tanggal_lahir)->age }}"
                                    data-format-tanggal="{{ \Carbon\Carbon::parse($lansia->tanggal_lahir)->format('d/m/Y') }}"
                                    data-risk-level="{{ $lansia->risk_level ?? '' }}">
                                    {{-- Nama --}}
                                    <td>
                                        <div class="user-cell">
                                            <div class="user-text">
                                                <span class="user-name">{{ $lansia->nama_lansia }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- NIK --}}
                                    <td>
                                        <span class="main-text">{{ $lansia->nik }}</span>
                                    </td>

                                    {{-- Umur --}}
                                    <td>
                                        <span class="main-text">{{ \Carbon\Carbon::parse($lansia->tanggal_lahir)->age }} Tahun</span>
                                        <span class="sub-text">{{ \Carbon\Carbon::parse($lansia->tanggal_lahir)->format('d/m/Y') }}</span>
                                    </td>

                                    {{-- Alamat --}}
                                    <td>
                                        <address>{{ $lansia->alamat ?? '-' }}</address>
                                    </td>

                                    {{-- Risiko --}}
                                    <td>
                                        @if($lansia->risk_level)
                                            @php
                                                $riskLevel = $lansia->risk_level;
                                                $riskConfig = [
                                                    'normal' => ['label' => 'Normal', 'class' => 'risk-normal'],
                                                    'waspada' => ['label' => 'Waspada', 'class' => 'risk-waspada'],
                                                    'tinggi' => ['label' => 'Perlu Tindak Lanjut', 'class' => 'risk-tinggi'],
                                                ];
                                                $risk = $riskConfig[$riskLevel] ?? null;
                                            @endphp
                                            @if($risk)
                                                <span class="risk-badge {{ $risk['class'] }}">{{ $risk['label'] }}</span>
                                            @else
                                                -
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- No. HP --}}
                                    <td>{{ $lansia->no_hp ?? '-' }}</td>

                                    {{-- Aksi --}}
                                    <td class="aksi" onclick="event.stopPropagation()">
                                        <button class="edit-btn" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </button>
                                        <button class="delete-btn" title="Hapus">
                                            <i class="fa-solid fa-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="empty-state">Belum ada data lansia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>{{-- /.lansia-table-scroll --}}

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
                    <div class="detail-header-actions">
                        {{-- Tombol "Histori Skrining" --}}
                        <a href="#" id="btn-histori-skrining" class="btn-outline-blue">
                            <i class="fa-solid fa-clock-rotate-left"></i> Histori Skrining
                        </a>
                        {{-- Tombol "Monitoring Kesehatan" di kanan --}}
                        <a href="#" id="btn-monitoring-kesehatan" class="btn btn-primary">
                            <i class="fa-solid fa-chart-line"></i> Monitoring Kesehatan
                        </a>
                    </div>
                </div>

                <div class="detail-content-grid">
                    {{-- Kiri: QR di posisi paling atas, selalu tampil jika tersedia --}}
                    <div class="profile-side">
                        <div style="display:flex; flex-direction:column; align-items:center; gap:8px;">
                            <div style="margin-top:0; text-align:center;">
                                <img id="detail-qr-telegram" src="" alt="QR Telegram" style="width:120px; height:120px; display:none; margin:0 auto;">
                                <p id="detail-kode-telegram" style="font-size:11px; margin-top:8px; color:#555;">-</p>
                            </div>
                        </div>
                        <h2 id="name-display">-</h2>
                        <p class="age-text" id="detail-umur">-</p>
                        <div class="status-pill" id="detail-jk">-</div>
                    </div>

                    {{-- Kanan: info pribadi + kesehatan --}}
                    <div class="info-side-grid">

                        {{-- Data Pribadi --}}
                        <div class="info-column">
                            <h4>DATA PRIBADI</h4>

                            <div class="data-grid">

                                <div class="data-item"><label>NIK</label>
                                    <p id="d-nik">-</p>
                                </div>
                                <div class="data-item"><label>No HP</label>
                                    <p id="d-hp">-</p>
                                </div>

                                <div class="data-item"><label>Email</label>
                                    <p id="d-email">-</p>
                                </div>
                                <div class="data-item"><label>Pekerjaan</label>
                                    <p id="d-pekerjaan">-</p>
                                </div>
                                <div class="data-item"><label>Status</label>
                                    <p id="d-status">-</p>
                                </div>

                                <div class="data-item"><label>TTL</label>
                                    <p id="d-ttl">-</p>
                                </div>
                                <div class="data-item"><label>Jenis Kelamin</label>
                                    <p id="d-jk-text">-</p>
                                </div>

                                <div class="data-item"><label>Alamat</label>
                                    <p id="d-alamat">-</p>
                                </div>
                                <div class="data-item"><label>Riwayat</label>
                                    <p id="d-riwayat">-</p>
                                </div>

                                <div class="data-item"><label>Keterangan</label>
                                    <p id="d-keterangan">-</p>
                                </div>

                            </div>
                        </div>
                        {{-- Data Kesehatan Terakhir --}}
                        <div class="info-column">
                            <h4>KESEHATAN TERAKHIR</h4>
                            <div class="health-cards">
                                <div class="h-card" id="hcard-sistolik">
                                    <span class="h-card-label">TENSI SISTOLIK</span>
                                    <strong class="h-card-value" id="d-sistolik">-</strong>
                                    <small class="h-card-unit">mmHg</small>
                                </div>
                                <div class="h-card" id="hcard-diastolik">
                                    <span class="h-card-label">TENSI DIASTOLIK</span>
                                    <strong class="h-card-value" id="d-diastolik">-</strong>
                                    <small class="h-card-unit">mmHg</small>
                                </div>
                                <div class="h-card" id="hcard-gula">
                                    <span class="h-card-label">GULA DARAH</span>
                                    <strong class="h-card-value" id="d-gula">-</strong>
                                    <small class="h-card-unit">mg/dL</small>
                                </div>
                                <div class="h-card" id="hcard-kolesterol">
                                    <span class="h-card-label">KOLESTEROL</span>
                                    <strong class="h-card-value" id="d-kolesterol">-</strong>
                                    <small class="h-card-unit">mg/dL</small>
                                </div>
                                <div class="h-card" id="hcard-imt">
                                    <span class="h-card-label">IMT</span>
                                    <strong class="h-card-value" id="d-imt">-</strong>
                                    <small class="h-card-unit">kg/m²</small>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Informasi Keluarga Section --}}
                <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e0e0e0;">
                    <h4 style="margin-bottom: 15px; color: #333;">Informasi Keluarga</h4>
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

        <!-- GLOBAL LOADING OVERLAY -->
        <div id="global-loading-overlay"
            style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); z-index: 9999; justify-content: center; align-items: center; flex-direction: column;">
            <div class="spinner"
                style="border: 4px solid #f3f3f3; border-top: 4px solid #2563eb; border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite;">
            </div>
            <p style="margin-top: 15px; font-weight: bold; color: #2563eb; font-family: 'Inter', sans-serif;">Sedang
                Memproses...</p>
            <style>
                @keyframes spin {
                    0% {
                        transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                    }
                }
            </style>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
    @vite('resources/js/jsAdmin/data_lansia.js')
@endpush