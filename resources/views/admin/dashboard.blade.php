@extends('layout.sidebar')

@push('styles')
    @vite('resources/css/cssAdmin/dashboard.css')
@endpush
@section('content')
<div class="dashboard-page">
    
    <div class="page-header">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Pemantauan kesehatan &amp; aktivitas posyandu lansia secara real-time</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card border-primary">
            <span class="stat-label">TOTAL LANSIA TERDAFTAR</span>
            <div class="stat-content">
                <span class="stat-number">{{ $total_lansia }}</span>
                <i class="fa fa-users stat-icon-fa color-primary"></i>
            </div>
        </div>
        <div class="stat-card border-success">
            <span class="stat-label">KONDISI NORMAL</span>
            <div class="stat-content">
                <span class="stat-number color-success">{{ $kondisi_normal }}</span>
                <i class="fa fa-heartbeat stat-icon-fa color-success"></i>
            </div>
        </div>
        <div class="stat-card border-warning">
            <span class="stat-label">STATUS WASPADA</span>
            <div class="stat-content">
                <span class="stat-number color-warning">{{ $waspada }}</span>
                <i class="fa fa-exclamation-triangle stat-icon-fa color-warning"></i>
            </div>
        </div>
        <div class="stat-card border-danger">
            <span class="stat-label">PERLU TINDAK LANJUT</span>
            <div class="stat-content">
                <span class="stat-number color-danger">{{ $perlu_perhatian }}</span>
                <i class="fa fa-medkit stat-icon-fa color-danger"></i>
            </div>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="dashboard-row row-top">
            <div class="dashboard-card">
                <div class="dashboard-card-title">
                    <i class="fa fa-bar-chart color-success"></i> Tren Kondisi Kesehatan Lansia
                </div>
                <div class="chart-wrapper" style="position: relative; width: 100%; height: 280px;">
                    <canvas id="chartTrenPenyakit"></canvas>
                </div>
                <p class="card-subtitle">Distribusi prevalensi kondisi kesehatan berdasarkan skrining terakhir.</p>
            </div>

            <div class="dashboard-card dashboard-card-scroll obat-card">
                <div class="dashboard-card-title">
                    <i class="fa fa-calendar-check-o var(--primary)"></i> Jadwal 30 Hari Ke Depan
                </div>
                <div class="list-scroll">
                    <div class="list-items dashboard-list-items">
                        @forelse($jadwalMendatang as $jadwal)
                            <div class="list-item-row">
                                <div>
                                    <span class="main-text">{{ $jadwal->tema ?? 'Posyandu Lansia' }}</span>
                                    <span class="sub-text"><i class="fa fa-map-marker"></i> {{ $jadwal->lokasi }} &bull; {{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->isoFormat('D MMM YYYY') }}</span>
                                </div>
                                <div>
                                    @if($jadwal->status == \App\Models\JadwalPosyandu::STATUS_BERLANGSUNG)
                                        <span class="badge-status success">Berlangsung</span>
                                    @else
                                        <span class="badge-status muted">Terjadwal</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="sub-text empty-state" style="text-align: center; padding: 20px 0;">Tidak ada jadwal dalam 30 hari ke depan.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-row row-bottom">
            <div class="dashboard-card">
                <div class="dashboard-card-title">
                    <i class="fa fa-pie-chart color-warning"></i> Status Risiko Kesehatan
                </div>
                <div class="list-items dashboard-risk-items" style="margin-top: 10px;">
                    @php
                        $pct_normal = $total_lansia > 0 ? round(($kondisi_normal / $total_lansia) * 100, 1) : 0;
                        $pct_waspada = $total_lansia > 0 ? round(($waspada / $total_lansia) * 100, 1) : 0;
                        $pct_perlu = $total_lansia > 0 ? round(($perlu_perhatian / $total_lansia) * 100, 1) : 0;
                    @endphp

                    <div>
                        <div class="flex justify-between" style="display:flex; justify-content:space-between; font-weight:700; font-size:13px;">
                            <span class="color-success">Normal</span>
                            <span>{{ $kondisi_normal }} Lansia <span class="sub-text" style="display:inline;">({{ $pct_normal }}%)</span></span>
                        </div>
                        <div class="risk-progress-bar">
                            <div class="progress-fill" style="width: {{ $pct_normal }}%; background-color: var(--success);"></div>
                        </div>
                    </div>

                    <div style="margin-top: 15px;">
                        <div class="flex justify-between" style="display:flex; justify-content:space-between; font-weight:700; font-size:13px;">
                            <span class="color-warning">Waspada</span>
                            <span>{{ $waspada }} Lansia <span class="sub-text" style="display:inline;">({{ $pct_waspada }}%)</span></span>
                        </div>
                        <div class="risk-progress-bar">
                            <div class="progress-fill" style="width: {{ $pct_waspada }}%; background-color: #d97706;"></div>
                        </div>
                    </div>

                    <div style="margin-top: 15px;">
                        <div class="flex justify-between" style="display:flex; justify-content:space-between; font-weight:700; font-size:13px;">
                            <span class="color-danger">Perlu Tindak Lanjut</span>
                            <span>{{ $perlu_perhatian }} Lansia <span class="sub-text" style="display:inline;">({{ $pct_perlu }}%)</span></span>
                        </div>
                        <div class="risk-progress-bar">
                            <div class="progress-fill" style="width: {{ $pct_perlu }}%; background-color: var(--danger);"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-card dashboard-card-scroll">
                <div class="dashboard-card-title">
                    <i class="fa fa-cubes color-danger"></i> Stok Obat Menipis (Sisa &lt; 10)
                </div>
                <div class="list-scroll">
                    <div class="list-items dashboard-list-items">
                        @forelse($obatMenipis as $obat)
                            <div class="list-item-row">
                                <div>
                                    <span class="main-text">{{ $obat->nama_obat }} {{ $obat->dosis }}</span>
                                    <span class="sub-text">{{ $obat->satuan ?? 'Tablet' }}</span>
                                </div>
                                <div>
                                    <span class="risk-badge risk-tinggi" style="border-radius: 8px; padding: 4px 10px;">{{ $obat->stock }} sisa</span>
                                </div>
                            </div>
                        @empty
                            <p class="sub-text empty-state" style="text-align: center; padding: 20px 0;">Semua stok obat dalam kondisi aman.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        // Passing data tren penyakit aman dari PHP array ke JavaScript Object
        const dataTrenPenyakit = @json($trenPenyakit);
        </script>
    @vite('resources/js/jsADMIN/dashboard.js')
@endpush