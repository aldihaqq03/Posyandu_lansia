@extends('layout.sidebar')

@push('styles')
    @vite('resources/css/app.css')
@endpush
@push('scripts')
    @vite('resources/js/jsAdmin/dashboard.js')
@endpush

{{-- Kirim CSS ke head di sidebar.blade.php --}}
@push('styles')
    @vite('resources/css/cssAdmin/dashboard.css')
@endpush
@section('content')

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Ringkasan Kesehatan</h1>
            <p>Pantau kesehatan dan risiko lansia di wilayah Anda secara real-time.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-icon bg-soft-green">🚶</div>
                    <span class="trend-badge positive">+3.2% ↗</span>
                </div>
                <span class="stat-label">TOTAL LANSIA</span>
                <h2 class="stat-value">1.248</h2>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 70%; background: #129481;"></div>
                </div>
            </div>

            <div class="stat-card border-left-red">
                <div class="stat-top">
                    <div class="stat-icon bg-soft-red">⚠️</div>
                    <span class="status-badge red">HATI-HATI</span>
                </div>
                <span class="stat-label">LANSIA RESIKO TINGGI</span>
                <h2 class="stat-value">42</h2>
                <div class="alert-box-mini">
                    <span>📢 8 lansia butuh pemeriksaan segera hari ini</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-icon bg-soft-yellow">📝</div>
                    <span class="time-badge">BULAN INI</span>
                </div>
                <span class="stat-label">PEMERIKSAAN SELESAI</span>
                <h2 class="stat-value">892</h2>
                <div class="avatar-group">
                    <img src="https://i.pravatar.cc/30?img=1" alt="user">
                    <img src="https://i.pravatar.cc/30?img=2" alt="user">
                    <span class="avatar-more">+800 Telah diperiksa</span>
                </div>
            </div>
        </div>

        <div class="dashboard-footer-grid">
            <div class="content-card">
                <div class="card-header">
                    <h3>Penyakit Terbanyak</h3>
                    <a href="/data_lansia" class="btn-detail">DETAIL</a>
                </div>




                <div class="chart-list">
                    <div class="chart-item">
                        <div class="chart-info"><span>Hipertensi</span> <strong>45%</strong></div>
                        <div class="progress-bar-thin">
                            <div class="fill" style="width: 45%; background: #129481;"></div>
                        </div>
                    </div>
                    <div class="chart-item">
                        <div class="chart-info"><span>Diabetes Melitus</span> <strong>28%</strong></div>
                        <div class="progress-bar-thin">
                            <div class="fill" style="width: 28%; background: #f59e0b;"></div>
                        </div>
                    </div>
                    <div class="chart-item">
                        <div class="chart-info"><span>Gula Darah</span> <strong>18%</strong></div>
                        <div class="progress-bar-thin">
                            <div class="fill" style="width: 18%; background: #10b981;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-card">
                <div class="card-header">
                    <h3>Catatan Terakhir</h3>
                    <button class="btn-filter">
                        <i class="fa-solid fa-arrows-rotate"></i>
                    </button>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>NAMA LANSIA</th>
                            <th>STATUS RISIKO</th>
                            <th>TANGGAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>BP</strong> Bapak Purnomo</td>
                            <td><span class="badge-risk high">RESIKO TINGGI</span></td>
                            <td>24 Okt 2023</td>
                        </tr>
                        <tr>
                            <td><strong>IK</strong> Ibu Kartini</td>
                            <td><span class="badge-risk normal">NORMAL</span></td>
                            <td>23 Okt 2023</td>
                        </tr>
                        
                    </tbody>
                </table>
                <a href="data_lansia" class="btn-view-all">TAMPILKAN SEMUA DATA</a>
            </div>
        </div>
    </div>
@endsection