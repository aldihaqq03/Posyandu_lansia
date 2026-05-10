@extends('layout.sidebar')

@push('styles')
    @vite([
        'resources/css/app.css',
        'resources/css/cssAdmin/monitoring.css'
    ])
@endpush

@section('content')

    <main class="main-content">

        <div class="monitoring-header">
            <div>
                <h2>Monitoring Kesehatan Lansia</h2>
                <p>
                    Grafik perkembangan, keluhan, dan saran kesehatan lansia
                </p>
            </div>

            <a href="{{ url()->previous() }}" class="btn-back">
                ← Kembali
            </a>
        </div>

        <input type="hidden" id="lansia-id" value="{{ $lansia->id_lansia }}">

        {{-- ═════════════════════════════════════════════════════ --}}
        {{-- GRAFIK PERKEMBANGAN KESEHATAN --}}
        {{-- ═════════════════════════════════════════════════════ --}}
        <div class="dp-section" id="section-grafik">
            <div class="dp-section-header">
                <span class="dp-section-icon">📊</span>
                <h4 class="dp-section-title">Perkembangan Kesehatan</h4>
                <div class="chart-tab-group">
                    <button class="chart-tab active" data-chart="tensi">Tensi</button>
                    <button class="chart-tab" data-chart="gula">Gula Darah</button>
                    <button class="chart-tab" data-chart="kolesterol">Kolesterol</button>
                    <button class="chart-tab" data-chart="semua">Semua</button>
                </div>
            </div>

            <div id="grafik-loading" class="dp-loading-state" style="display:none;">
                <i class="fa-solid fa-spinner fa-spin"></i> Memuat data grafik...
            </div>
            <div id="grafik-empty" class="dp-empty-state" style="display:none;">
                <i class="fa-solid fa-chart-line"></i>
                <p>Belum ada data kesehatan untuk ditampilkan.</p>
            </div>

            <div id="grafik-container" style="display:none;">
                <div id="chart-legend" class="chart-legend-row"></div>
                <div id="chart-zone-info" class="chart-zone-info"></div>
                <div style="position:relative; width:100%; height:280px;">
                    <canvas id="health-chart" role="img" aria-label="Grafik perkembangan kesehatan lansia">Data grafik
                        kesehatan.</canvas>
                </div>
            </div>
        </div>

        {{-- ═════════════════════════════════════════════════════ --}}
        {{-- KELUHAN TERAKHIR --}}
        {{-- ═════════════════════════════════════════════════════ --}}
        <div class="dp-section" id="section-keluhan">
            <div class="dp-section-header">
                <span class="dp-section-icon">🩺</span>
                <h4 class="dp-section-title">Keluhan Terakhir</h4>
                <button class="dp-btn-outline" id="btn-lihat-semua-keluhan">
                    <i class="fa-solid fa-list"></i> Lihat Semua
                </button>
            </div>

            <div id="keluhan-loading" class="dp-loading-state" style="display:none;">
                <i class="fa-solid fa-spinner fa-spin"></i> Memuat data keluhan...
            </div>
            <div id="keluhan-empty" class="dp-empty-state" style="display:none;">
                <i class="fa-solid fa-notes-medical"></i>
                <p>Belum ada riwayat keluhan tercatat.</p>
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
                    <span>Riwayat Keluhan Selengkapnya</span>
                    <button class="dp-btn-sm" id="btn-tutup-keluhan">
                        <i class="fa-solid fa-xmark"></i> Tutup
                    </button>
                </div>
                <div id="keluhan-all-list" class="keluhan-list-scroll"></div>
            </div>
        </div>

        {{-- ═════════════════════════════════════════════════════ --}}
        {{-- MANAJEMEN SARAN --}}
        {{-- ═════════════════════════════════════════════════════ --}}
        <div class="dp-section" id="section-saran">
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
                <i class="fa-solid fa-lightbulb"></i>
                <p>Belum ada saran yang diberikan.</p>
            </div>

            <div id="dp-saran-list"></div>
            <div id="dp-saran-new-list"></div>
        </div>
        </section>

    </main>

@endsection

@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>

    @vite('resources/js/jsAdmin/monitoring.js')

@endpush