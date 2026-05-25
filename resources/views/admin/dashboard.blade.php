@extends('layout.sidebar')

@push('styles')
    @vite('resources/css/cssAdmin/dashboard.css')
@endpush

@section('content')
<div class="dashboard-page">

    {{-- ── HEADER ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Pemantauan kesehatan &amp; inventaris posyandu lansia.</p>
        </div>
    </div>

    {{-- ── STAT CARDS ── --}}
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-label">Total Lansia</span>
            <div class="stat-content">
                <span class="stat-number">{{ $total_lansia }}</span>
                <i class="fa fa-users stat-icon-fa color-primary"></i>
            </div>
        </div>
        <div class="stat-card border-success">
            <span class="stat-label">Kondisi Normal</span>
            <div class="stat-content">
                <span class="stat-number color-success">{{ $kondisi_normal }}</span>
                <i class="fa fa-heartbeat stat-icon-fa color-success"></i>
            </div>
        </div>
        <div class="stat-card border-warning">
            <span class="stat-label">Status Waspada</span>
            <div class="stat-content">
                <span class="stat-number color-warning">{{ $waspada }}</span>
                <i class="fa fa-exclamation-triangle stat-icon-fa color-warning"></i>
            </div>
        </div>
        <div class="stat-card border-danger">
            <span class="stat-label">Perlu Tindak Lanjut</span>
            <div class="stat-content">
                <span class="stat-number color-danger">{{ $perlu_perhatian }}</span>
                <i class="fa fa-medkit stat-icon-fa color-danger"></i>
            </div>
        </div>
    </div>

    {{-- ── DASHBOARD CARDS GRID ── --}}
    <div class="dashboard-container">

        {{-- BARIS ATAS: Tren Penyakit & Status Risiko --}}
        <div class="dashboard-row row-top">
            <div class="dashboard-card">
                <div class="dashboard-card-title">
                    <i class="fa fa-bar-chart color-success"></i> Tren Kondisi Penyakit
                </div>
                <p class="card-subtitle">Distribusi kondisi kesehatan terurut dari jumlah terbanyak.</p>
                <div class="chart-wrapper">
                    <canvas id="chartTrenPenyakit"></canvas>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="dashboard-card-title">
                    <i class="fa fa-pie-chart color-warning"></i> Status Risiko Kesehatan
                </div>
                <p class="card-subtitle">Persentase lansia berdasarkan kategori tingkat risiko.</p>

                @php
                    $pct_normal  = $total_lansia > 0 ? round(($kondisi_normal / $total_lansia) * 100, 1) : 0;
                    $pct_waspada = $total_lansia > 0 ? round(($waspada / $total_lansia) * 100, 1) : 0;
                    $pct_perlu   = $total_lansia > 0 ? round(($perlu_perhatian / $total_lansia) * 100, 1) : 0;
                @endphp

                <div class="risk-section">
                    <div>
                        <div class="risk-row-label">
                            <div style="display:flex; align-items:center; gap:7px;">
                                <span class="risk-dot" style="background:var(--success);"></span>
                                <span class="main-text" style="color:var(--success); font-weight:700;">Normal</span>
                            </div>
                            <span class="main-text" style="font-weight:700;">{{ $kondisi_normal }} <span class="sub-text" style="display:inline;">({{ $pct_normal }}%)</span></span>
                        </div>
                        <div class="risk-progress-bar">
                            <div class="progress-fill" style="width:{{ $pct_normal }}%; background:var(--success);"></div>
                        </div>
                    </div>

                    <div>
                        <div class="risk-row-label">
                            <div style="display:flex; align-items:center; gap:7px;">
                                <span class="risk-dot" style="background:var(--warning);"></span>
                                <span class="main-text" style="color:var(--warning); font-weight:700;">Waspada</span>
                            </div>
                            <span class="main-text" style="font-weight:700;">{{ $waspada }} <span class="sub-text" style="display:inline;">({{ $pct_waspada }}%)</span></span>
                        </div>
                        <div class="risk-progress-bar">
                            <div class="progress-fill" style="width:{{ $pct_waspada }}%; background:var(--warning);"></div>
                        </div>
                    </div>

                    <div>
                        <div class="risk-row-label">
                            <div style="display:flex; align-items:center; gap:7px;">
                                <span class="risk-dot" style="background:var(--danger);"></span>
                                <span class="main-text" style="color:var(--danger); font-weight:700;">Perlu Tindak Lanjut</span>
                            </div>
                            <span class="main-text" style="font-weight:700;">{{ $perlu_perhatian }} <span class="sub-text" style="display:inline;">({{ $pct_perlu }}%)</span></span>
                        </div>
                        <div class="risk-progress-bar">
                            <div class="progress-fill" style="width:{{ $pct_perlu }}%; background:var(--danger);"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- BARIS BAWAH (70:30): Jadwal & Stok Obat --}}
        <div class="dashboard-row row-bottom">
            <div class="dashboard-card">
                <div class="dashboard-card-title">
                    <i class="fa fa-calendar-check-o color-primary"></i> Jadwal 1 bulan kedepan
                    <span class="badge-status muted" style="margin-left:auto;">{{ $jadwalMendatang->count() }} Jadwal</span>
                </div>
                <p class="card-subtitle">Agenda terdekat pelaksanaan posyandu lansia.</p>

                <div class="list-scroll">
                    @forelse($jadwalMendatang as $jadwal)
                        <div class="list-item-row">
                            <div>
                               <span class="main-text">{{ $jadwal->tema }}</span>
                                <span class="sub-text">
                                    <i class="fa fa-map-marker"></i> {{ $jadwal->lokasi }} &bull; {{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->isoFormat('D MMM YYYY') }}
                                </span>
                            </div>

                            @if($jadwal->status == 1)
                                <span class="badge-status success">Berlangsung</span>
                            @else
                                <span class="badge-status muted">Terjadwal</span>
                            @endif
                        </div>
                    @empty
                        <div class="empty-state"><i class="fa fa-calendar-o"></i> Tidak ada jadwal terdekat.</div>
                    @endforelse
                </div>
            </div>

            <div class="dashboard-card">
                <div class="dashboard-card-title">
                    <i class="fa fa-cubes color-danger"></i> Stok Obat Menipis
                </div>
                <p class="card-subtitle">Inventaris kritis restock.</p>

                <div class="list-scroll">
                    @forelse($obatMenipis as $obat)
                        <div class="list-item-row">
                            <div>
                                <span class="main-text">{{ $obat->nama_obat }}</span>
                                <span class="sub-text">{{ $obat->satuan ?? 'Tablet' }}</span>
                            </div>
                            <span class="risk-badge risk-tinggi">{{ $obat->stock }} sisa</span>
                        </div>
                    @empty
                        <div class="empty-state"><i class="fa fa-check-circle" style="color:var(--success);opacity:0.5;"></i> Stok obat aman.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        const dataTrenPenyakit = @json($trenPenyakit);
    </script>
    
    @vite('resources/js/jsAdmin/dashboard.js')
@endpush