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
                    <span><i class="fa-solid fa-cake-candles"></i> {{ \Carbon\Carbon::parse($lansia->tanggal_lahir)->age }}
                        tahun</span>
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
        <input type="hidden" id="lansia-id" value="{{ $lansia->id_lansia }}">
        <input type="hidden" id="lansia-gender" value="{{ $lansia->jenis_kelamin }}">


        {{-- grafik --}}
        <div class="mon-grafik-container">
            {{-- ── FILTER TANGGAL ── --}}
            <div class="mon-filter-bar">
                <div class="mon-filter-preset">
                    <button class="mon-filter-btn" data-filter="6">6 Bulan Terakhir</button>
                    <button class="mon-filter-btn" data-filter="12">1 Tahun Terakhir</button>
                    <button class="mon-filter-btn" data-filter="24">2 Tahun Terakhir</button>
                    <button class="mon-filter-btn" data-filter="all">Semua Data</button>
                </div>
                <div class="mon-filter-custom">
                    <span class="filter-label">Dari:</span>
                    <input type="month" id="filter-start-month" class="mon-month-input">
                    <span class="filter-label">Sampai:</span>
                    <input type="month" id="filter-end-month" class="mon-month-input">
                    <button id="apply-custom-filter" class="mon-filter-apply">Terapkan</button>
                </div>
                <div class="mon-filter-info" id="filter-info">
                    <i class="fa-regular fa-calendar"></i> <span id="filter-range-text">Menampilkan semua data</span>
                </div>
            </div>
            {{-- ── TAB NAVIGASI GRAFIK ── --}}
            <div class="mon-tabs">
                <button class="mon-tab-btn active" data-tab="tensi"><i class="fa-solid fa-heart-pulse"></i> Tekanan
                    Darah</button>
                <button class="mon-tab-btn" data-tab="gula"><i class="fa-solid fa-droplet"></i> Gula Darah</button>
                <button class="mon-tab-btn" data-tab="kolesterol"><i class="fa-solid fa-vial"></i> Kolesterol</button>
                <button class="mon-tab-btn" data-tab="bb"><i class="fa-solid fa-weight-scale"></i> Berat Badan</button>
                <button class="mon-tab-btn" data-tab="lp"><i class="fa-solid fa-circle-dot"></i> Lingkar Perut</button>
                <button class="mon-tab-btn" data-tab="imt"><i class="fa-solid fa-calculator"></i> IMT</button>
            </div>

            {{-- ── TAB CONTENT: TEKANAN DARAH ── --}}
            <div class="mon-tab-content active" id="tab-tensi">
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
                            <button class="mon-btn-detail" onclick="openDetailModal('tensi')" title="Lihat Detail Data"><i
                                    class="fa-solid fa-table"></i> Lihat Semua Data</button>
                        </div>
                    </div>
                    <div class="mon-legend" id="legend-tensi"></div>
                    <div class="mon-canvas-wrap" id="wrap-tensi" style="display:none;">
                        <canvas id="chart-tensi" role="img" aria-label="Grafik tekanan darah">Data tekanan darah.</canvas>
                    </div>
                    <div class="mon-empty" id="empty-tensi" style="display:none;">
                        <i class="fa-solid fa-heart-pulse"></i>
                        <div class="mon-empty-text">
                            <strong>Belum ada data</strong>
                            <span>Data pemeriksaan tekanan darah belum tersedia.</span>
                        </div>
                    </div>
                    <div class="mon-loading" id="loading-tensi"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</div>
                </div>
            </div>

            {{-- ── TAB CONTENT: GULA DARAH ── --}}
            <div class="mon-tab-content" id="tab-gula">
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
                            <button class="mon-btn-detail" onclick="openDetailModal('gula')" title="Lihat Detail Data"><i
                                    class="fa-solid fa-table"></i> Lihat Detail</button>
                        </div>
                    </div>
                    <div class="mon-legend" id="legend-gula"></div>
                    <div class="mon-canvas-wrap" id="wrap-gula" style="display:none;">
                        <canvas id="chart-gula" role="img" aria-label="Grafik gula darah">Data gula darah.</canvas>
                    </div>
                    <div class="mon-empty" id="empty-gula" style="display:none;">
                        <i class="fa-solid fa-droplet"></i>
                        <div class="mon-empty-text">
                            <strong>Belum ada data</strong>
                            <span>Data pemeriksaan gula darah belum tersedia.</span>
                        </div>
                    </div>
                    <div class="mon-loading" id="loading-gula"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</div>
                </div>
            </div>

            {{-- ── TAB CONTENT: KOLESTEROL ── --}}
            <div class="mon-tab-content" id="tab-kolesterol">
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
                            <button class="mon-btn-detail" onclick="openDetailModal('kolesterol')"
                                title="Lihat Detail Data"><i class="fa-solid fa-table"></i> Lihat Detail</button>
                        </div>
                    </div>
                    <div class="mon-legend" id="legend-kolesterol"></div>
                    <div class="mon-canvas-wrap" id="wrap-kolesterol" style="display:none;">
                        <canvas id="chart-kolesterol" role="img" aria-label="Grafik kolesterol">Data kolesterol.</canvas>
                    </div>
                    <div class="mon-empty" id="empty-kolesterol" style="display:none;">
                        <i class="fa-solid fa-vial"></i>
                        <div class="mon-empty-text">
                            <strong>Belum ada data</strong>
                            <span>Data pemeriksaan kolesterol belum tersedia.</span>
                        </div>
                    </div>
                    <div class="mon-loading" id="loading-kolesterol"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...
                    </div>
                </div>
            </div>

            {{-- ── TAB CONTENT: BERAT BADAN ── --}}
            <div class="mon-tab-content" id="tab-bb">
                <div class="mon-chart-card">
                    <div class="mon-chart-header">
                        <div>
                            <h3 class="mon-chart-title">Berat Badan</h3>
                            <p class="mon-chart-desc">Perkembangan berat badan (kg)</p>
                        </div>
                        <div class="mon-zones">
                            <button class="mon-btn-detail" onclick="openDetailModal('bb')" title="Lihat Detail Data"><i
                                    class="fa-solid fa-table"></i> Lihat Detail</button>
                        </div>
                    </div>
                    <div class="mon-legend" id="legend-bb"></div>
                    <div class="mon-canvas-wrap" id="wrap-bb" style="display:none;">
                        <canvas id="chart-bb" role="img" aria-label="Grafik berat badan">Data berat badan.</canvas>
                    </div>
                    <div class="mon-empty" id="empty-bb" style="display:none;">
                        <i class="fa-solid fa-weight-scale"></i>
                        <div class="mon-empty-text">
                            <strong>Belum ada data</strong>
                            <span>Data pemeriksaan berat badan belum tersedia.</span>
                        </div>
                    </div>
                    <div class="mon-loading" id="loading-bb"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</div>
                </div>
            </div>

            {{-- ── TAB CONTENT: LINGKAR PERUT ── --}}
            <div class="mon-tab-content" id="tab-lp">
                <div class="mon-chart-card">
                    <div class="mon-chart-header">
                        <div>
                            <h3 class="mon-chart-title">Lingkar Perut</h3>
                            <p class="mon-chart-desc">Indikator risiko obesitas abdominal (cm)</p>
                        </div>
                        <div class="mon-zones" id="zone-lp">
                            {{-- Diisi JS sesuai jenis kelamin, tapi Lihat Detail tetap ada --}}
                        </div>
                        <button class="mon-btn-detail" onclick="openDetailModal('lp')" title="Lihat Detail Data"><i
                                class="fa-solid fa-table"></i> Lihat Detail</button>
                    </div>
                    <div class="mon-legend" id="legend-lp"></div>
                    <div class="mon-canvas-wrap" id="wrap-lp" style="display:none;">
                        <canvas id="chart-lp" role="img" aria-label="Grafik lingkar perut">Data lingkar perut.</canvas>
                    </div>
                    <div class="mon-empty" id="empty-lp" style="display:none;">
                        <i class="fa-solid fa-circle-dot"></i>
                        <div class="mon-empty-text">
                            <strong>Belum ada data</strong>
                            <span>Data pemeriksaan lingkar perut belum tersedia.</span>
                        </div>
                    </div>
                    <div class="mon-loading" id="loading-lp"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</div>
                </div>
            </div>

            {{-- ── TAB CONTENT: IMT ── --}}
            <div class="mon-tab-content" id="tab-imt">
                <div class="mon-chart-card">
                    <div class="mon-chart-header">
                        <div>
                            <h3 class="mon-chart-title">Indeks Massa Tubuh (IMT)</h3>
                            <p class="mon-chart-desc">Perkembangan IMT lansia (kg/m²)</p>
                        </div>
                        <div class="mon-zones">
                            <span class="mz mz-bahaya">&lt;18.5 / ≥30</span>
                            <span class="mz mz-waspada">18.5–21.9 / 27.1–29.9</span>
                            <span class="mz mz-normal">Normal 22–27</span>
                            <button class="mon-btn-detail" onclick="openDetailModal('imt')" title="Lihat Detail Data"><i
                                    class="fa-solid fa-table"></i> Lihat Detail</button>
                        </div>
                    </div>
                    <div class="mon-legend" id="legend-imt"></div>
                    <div class="mon-canvas-wrap" id="wrap-imt" style="display:none;">
                        <canvas id="chart-imt" role="img" aria-label="Grafik IMT">Data IMT.</canvas>
                    </div>
                    <div class="mon-empty" id="empty-imt" style="display:none;">
                        <i class="fa-solid fa-calculator"></i>
                        <div class="mon-empty-text">
                            <strong>Belum ada data</strong>
                            <span>Data pemeriksaan IMT belum tersedia.</span>
                        </div>
                    </div>
                    <div class="mon-loading" id="loading-imt"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</div>
                </div>
            </div>

            {{-- ── MODAL DETAIL DATA ── --}}
            <div class="mon-modal-overlay" id="detail-modal" style="display:none;">
                <div class="mon-modal-content">
                    <div class="mon-modal-header">
                        <h3 class="mon-modal-title" id="modal-title">Riwayat Pemeriksaan</h3>
                        <button class="mon-modal-close" onclick="closeDetailModal()"><i
                                class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="mon-modal-body">
                        <table class="mon-table" id="detail-table">
                            <thead>
                                <tr id="detail-thead">
                                    <!-- Diisi oleh JS -->
                                </tr>
                            </thead>
                            <tbody id="detail-tbody">
                                <!-- Diisi oleh JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        {{-- ── KELUHAN ── --}}
        <div class="dp-section">
            <div class="dp-section-header">
                <div class="dp-section-title-wrapper">
                    <span class="dp-section-icon"><i class="fa-solid fa-stethoscope"></i></span>
                    <h4 class="dp-section-title">Riwayat Keluhan</h4>
                </div>
                <button class="dp-btn-action" id="btn-lihat-semua-keluhan" title="Lihat Semua">
                    <i class="fa-solid fa-list-ul"></i>
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
                        <span class="keluhan-badge">Terbaru</span>
                    </div>
                    <div class="keluhan-latest-grid" style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:12px;">
                        <div>
                            <div style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:#6b7280; font-weight:700; margin-bottom:6px;">Keluhan</div>
                            <p class="keluhan-text" id="kl-isi" style="margin:0;">-</p>
                        </div>
                        <div>
                            <div style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:#6b7280; font-weight:700; margin-bottom:6px;">Diagnosis</div>
                            <p class="keluhan-text" id="kl-diagnosis" style="margin:0;">-</p>
                        </div>
                    </div>
                </div>
            </div>
            <div id="keluhan-all-wrapper" style="display:none; margin-top:16px;">
                <div class="keluhan-table-header">
                    <span>Semua Riwayat Keluhan</span>
                    <button class="dp-btn-action-sm" id="btn-tutup-keluhan" title="Tutup">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div id="keluhan-all-list" class="keluhan-list-scroll"></div>
            </div>
        </div>

        {{-- ── SARAN ── --}}
        <div class="dp-section">
            <div class="dp-section-header">
                <div class="dp-section-title-wrapper">
                    <span class="dp-section-icon"><i class="fa-solid fa-lightbulb"></i></span>
                    <h4 class="dp-section-title">Saran untuk Lansia</h4>
                </div>
                <button class="dp-btn-action primary" id="dp-btn-add-saran" title="Tambah Saran">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
            <div id="dp-saran-loading" class="dp-loading-state" style="display:none;">
                <i class="fa-solid fa-spinner fa-spin"></i> Memuat data saran...
            </div>
            <div id="dp-saran-empty" class="dp-empty-state" style="display:none;">
                <i class="fa-solid fa-comment-medical"></i>
                <p>Belum ada saran yang diberikan.</p>
            </div>
            <div id="dp-saran-list"></div>
            <div id="dp-saran-new-list"></div>
        </div>
    </main>

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    @vite('resources/js/jsAdmin/monitoring.js')
@endpush