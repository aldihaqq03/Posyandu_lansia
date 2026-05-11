@extends('layout.sidebar')

@push('styles')
    @vite([
        'resources/css/app.css',
        'resources/css/cssAdmin/monitoring.css'
    ])
@endpush

@section('content')
<main class="monitoring-wrap">

    {{-- ── HEADER ── --}}
    <div class="mon-header">
        <div>
            <h1 class="mon-title">Monitoring Kesehatan</h1>
            <p class="mon-sub">Perkembangan kondisi kesehatan lansia secara berkala</p>
        </div>
        <a href="{{ route('data_lansia') }}" class="mon-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- ── PROFIL LANSIA ── --}}
    <div class="mon-profile-card">
        <div class="mon-avatar">{{ strtoupper(substr($lansia->nama_lansia, 0, 2)) }}</div>
        <div class="mon-profile-info">
            <h2 class="mon-profile-name">{{ $lansia->nama_lansia }}</h2>
            <div class="mon-profile-meta">
                <span><i class="fa-solid fa-id-card"></i> {{ $lansia->nik }}</span>
                <span><i class="fa-solid fa-cake-candles"></i> {{ \Carbon\Carbon::parse($lansia->tanggal_lahir)->age }} tahun</span>
                <span>
                    <i class="fa-solid fa-venus-mars"></i>
                    {{ $lansia->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                </span>
                @if($lansia->riwayat_penyakit)
                    <span class="mon-riwayat">
                        <i class="fa-solid fa-notes-medical"></i> {{ $lansia->riwayat_penyakit }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Hidden fields untuk JS --}}
    <input type="hidden" id="lansia-id"     value="{{ $lansia->id_lansia }}">
    <input type="hidden" id="lansia-gender" value="{{ $lansia->jenis_kelamin }}">

    {{-- ── TEKANAN DARAH ── --}}
    <div class="mon-chart-card">
        <div class="mon-chart-header">
            <div>
                <h3 class="mon-chart-title">Tekanan Darah</h3>
                <p class="mon-chart-desc">Sistolik &amp; Diastolik (mmHg)</p>
            </div>
            <div class="mon-zones">
                <span class="mz mz-normal">Sis &lt;120 / Dias &lt;80</span>
                <span class="mz mz-waspada">Sis 120–130 / Dias 80–90</span>
                <span class="mz mz-bahaya">Sis &gt;130 / Dias &gt;90</span>
            </div>
        </div>
        <div class="mon-legend" id="legend-tensi"></div>
        <div class="mon-canvas-wrap" id="wrap-tensi" style="display:none;">
            <canvas id="chart-tensi" role="img" aria-label="Grafik tekanan darah">Data tekanan darah.</canvas>
        </div>
        <div class="mon-empty"   id="empty-tensi"   style="display:none;"><i class="fa-solid fa-heart-pulse"></i> Belum ada data tekanan darah.</div>
        <div class="mon-loading" id="loading-tensi"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</div>
    </div>

    {{-- ── GULA DARAH ── --}}
    <div class="mon-chart-card">
        <div class="mon-chart-header">
            <div>
                <h3 class="mon-chart-title">Gula Darah</h3>
                <p class="mon-chart-desc">Kadar glukosa darah (mg/dL)</p>
            </div>
            <div class="mon-zones">
                <span class="mz mz-normal">Normal &lt;100</span>
                <span class="mz mz-waspada">Pra-DM 100–125</span>
                <span class="mz mz-bahaya">Diabetes ≥126</span>
            </div>
        </div>
        <div class="mon-legend" id="legend-gula"></div>
        <div class="mon-canvas-wrap" id="wrap-gula" style="display:none;">
            <canvas id="chart-gula" role="img" aria-label="Grafik gula darah">Data gula darah.</canvas>
        </div>
        <div class="mon-empty"   id="empty-gula"   style="display:none;"><i class="fa-solid fa-droplet"></i> Belum ada data gula darah.</div>
        <div class="mon-loading" id="loading-gula"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</div>
    </div>

    {{-- ── KOLESTEROL ── --}}
    <div class="mon-chart-card">
        <div class="mon-chart-header">
            <div>
                <h3 class="mon-chart-title">Kolesterol</h3>
                <p class="mon-chart-desc">Kadar kolesterol total (mg/dL)</p>
            </div>
            <div class="mon-zones">
                <span class="mz mz-normal">Normal &lt;200</span>
                <span class="mz mz-waspada">Batas 200–239</span>
                <span class="mz mz-bahaya">Tinggi ≥240</span>
            </div>
        </div>
        <div class="mon-legend" id="legend-kolesterol"></div>
        <div class="mon-canvas-wrap" id="wrap-kolesterol" style="display:none;">
            <canvas id="chart-kolesterol" role="img" aria-label="Grafik kolesterol">Data kolesterol.</canvas>
        </div>
        <div class="mon-empty"   id="empty-kolesterol"   style="display:none;"><i class="fa-solid fa-vial"></i> Belum ada data kolesterol.</div>
        <div class="mon-loading" id="loading-kolesterol"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</div>
    </div>

    {{-- ── BERAT BADAN ── --}}
    <div class="mon-chart-card">
        <div class="mon-chart-header">
            <div>
                <h3 class="mon-chart-title">Berat Badan</h3>
                <p class="mon-chart-desc">Perkembangan berat badan (kg)</p>
            </div>
        </div>
        <div class="mon-legend" id="legend-bb"></div>
        <div class="mon-canvas-wrap" id="wrap-bb" style="display:none;">
            <canvas id="chart-bb" role="img" aria-label="Grafik berat badan">Data berat badan.</canvas>
        </div>
        <div class="mon-empty"   id="empty-bb"   style="display:none;"><i class="fa-solid fa-weight-scale"></i> Belum ada data berat badan.</div>
        <div class="mon-loading" id="loading-bb"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</div>
    </div>

    {{-- ── LINGKAR PERUT ── --}}
    <div class="mon-chart-card">
        <div class="mon-chart-header">
            <div>
                <h3 class="mon-chart-title">Lingkar Perut</h3>
                <p class="mon-chart-desc">Indikator risiko obesitas abdominal (cm)</p>
            </div>
            <div class="mon-zones" id="zone-lp">
                {{-- Diisi JS sesuai jenis kelamin --}}
            </div>
        </div>
        <div class="mon-legend" id="legend-lp"></div>
        <div class="mon-canvas-wrap" id="wrap-lp" style="display:none;">
            <canvas id="chart-lp" role="img" aria-label="Grafik lingkar perut">Data lingkar perut.</canvas>
        </div>
        <div class="mon-empty"   id="empty-lp"   style="display:none;"><i class="fa-solid fa-circle-dot"></i> Belum ada data lingkar perut.</div>
        <div class="mon-loading" id="loading-lp"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</div>
    </div>

    {{-- ── KELUHAN ── --}}
    <div class="dp-section">
        <div class="dp-section-header">
            <span class="dp-section-icon">🩺</span>
            <h4 class="dp-section-title">Riwayat Keluhan</h4>
            <button class="dp-btn-outline" id="btn-lihat-semua-keluhan">
                <i class="fa-solid fa-list"></i> Lihat Semua
            </button>
        </div>
        <div id="keluhan-loading" class="dp-loading-state" style="display:none;">
            <i class="fa-solid fa-spinner fa-spin"></i> Memuat data keluhan...
        </div>
        <div id="keluhan-empty" class="dp-empty-state" style="display:none;">
            <i class="fa-solid fa-notes-medical"></i><p>Belum ada riwayat keluhan tercatat.</p>
        </div>
        <div id="keluhan-latest" style="display:none;">
            <div class="keluhan-card latest">
                <div class="keluhan-meta">
                    <span class="keluhan-date" id="kl-tanggal">-</span>
                    <span class="keluhan-badge">Terakhir</span>
                </div>
                <p class="keluhan-text" id="kl-isi">-</p>
                <div class="keluhan-vitals" id="kl-vitals"></div>
            </div>
        </div>
        <div id="keluhan-all-wrapper" style="display:none; margin-top:16px;">
            <div class="keluhan-table-header">
                <span>Semua Riwayat Keluhan</span>
                <button class="dp-btn-sm" id="btn-tutup-keluhan">
                    <i class="fa-solid fa-xmark"></i> Tutup
                </button>
            </div>
            <div id="keluhan-all-list" class="keluhan-list-scroll"></div>
        </div>
    </div>

    {{-- ── SARAN ── --}}
    <div class="dp-section">
        <div class="dp-section-header">
            <span class="dp-section-icon">💡</span>
            <h4 class="dp-section-title">Saran untuk Lansia</h4>
            <button class="dp-btn-primary" id="dp-btn-add-saran">
                <i class="fa-solid fa-plus"></i> Tambah Saran
            </button>
        </div>
        <div id="dp-saran-loading" class="dp-loading-state" style="display:none;">
            <i class="fa-solid fa-spinner fa-spin"></i> Memuat data saran...
        </div>
        <div id="dp-saran-empty" class="dp-empty-state" style="display:none;">
            <i class="fa-solid fa-lightbulb"></i><p>Belum ada saran yang diberikan.</p>
        </div>
        <div id="dp-saran-list"></div>
        <div id="dp-saran-new-list"></div>
    </div>



@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    @vite('resources/js/jsAdmin/monitoring.js')
@endpush