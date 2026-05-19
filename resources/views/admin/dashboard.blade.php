@extends('layout.sidebar')

@push('styles')
    @vite('resources/css/cssAdmin/dashboard.css')
@endpush

@push('scripts')
    @vite('resources/js/jsAdmin/dashboard.js')
@endpush

@section('content')

    <div class="dashboard-container">
        <!-- HEADER -->
        <div class="dashboard-header">
            <div class="header-text">
                <h1>Ringkasan Kesehatan</h1>
                <p>Pantau status kesehatan dan pergerakan risiko lansia secara real-time.</p>
            </div>
            <div class="header-action" style="margin-top: 15px;">
                <form action="{{ route('test.notification') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary"
                        style="background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                        <i class="fa-solid fa-bell"></i> Uji Coba Notifikasi
                    </button>
                </form>
            </div>
        </div>



        <!-- STATS GRID -->
        <div class="stats-grid">
            <div class="stat-card" onclick="window.location.href='/data_lansia'" style="cursor: pointer;"
                title="Lihat Data Lansia">
                <div class="stat-top">
                    <div class="stat-icon icon-blue"><i class="fa-solid fa-users"></i></div>
                    <span class="trend-badge positive">+3.2% Bulan Ini</span>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Total Lansia Terdaftar</span>
                    <h2 class="stat-value" data-target="{{ $total_lansia }}">0</h2>
                </div>
                <div class="progress-container">
                    <div class="progress-bar-thin">
                        <div class="progress-fill" style="width: 75%; background: var(--primary);"></div>
                    </div>
                </div>
            </div>

            <div class="stat-card danger-card">
                <div class="stat-top">
                    <div class="stat-icon icon-red"><i class="fa-solid fa-heart-pulse"></i></div>
                    <span class="trend-badge negative">Perlu Perhatian</span>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Lansia Risiko Tinggi</span>
                    <h2 class="stat-value" data-target="{{ $resiko_tinggi }}">0</h2>
                </div>
                <div class="alert-box-mini">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ $resiko_tinggi }} Lansia butuh penanganan segera
                </div>
            </div>

            <div class="stat-card success-card">
                <div class="stat-top">
                    <div class="stat-icon icon-green"><i class="fa-solid fa-check-double"></i></div>
                    <span class="trend-badge positive">Bulan Ini</span>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Pemeriksaan Selesai</span>
                    <h2 class="stat-value" data-target="{{ $pemeriksaan_selesai }}">0</h2>
                </div>
                <div class="avatar-group">
                    @foreach($lansia_checked as $skrining)
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($skrining->lansia->nama_lansia) }}&background=random&color=fff"
                            title="{{ $skrining->lansia->nama_lansia }}" alt="user">
                    @endforeach
                    @if($pemeriksaan_selesai > 3)
                        <span class="avatar-more">+{{ $pemeriksaan_selesai - 3 }} lainnya</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="dashboard-footer-grid">
            <!-- CHARTS SECTION -->
            <div class="content-card">
                <div class="card-header">
                    <h3>Tren Keluhan Terbanyak</h3>
                    <a href="/data_lansia" class="btn-detail">Lihat Detail <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="chart-list">
                    @foreach($tren_keluhan as $item)
                        <div class="chart-item">
                            <div class="chart-info"><span>{{ $item['nama'] }}</span> <strong>{{ $item['persen'] }}%</strong>
                            </div>
                            <div class="progress-bar-thin">
                                <div class="progress-fill"
                                    style="width: {{ $item['persen'] }}%; background: {{ $item['color'] }};"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- DATA TABLE SECTION -->
            <div class="content-card">
                <div class="card-header">
                    <h3>Riwayat Pemeriksaan Terakhir</h3>
                    <a href="javascript:void(0)" class="btn-detail" onclick="location.reload()"
                        style="background:transparent; border: 1px solid #e2e8f0; color:#64748b;">
                        <i class="fa-solid fa-arrows-rotate"></i> Muat Ulang
                    </a>
                </div>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nama Lansia</th>
                                <th>Status Risiko</th>
                                <th>Terakhir Diperiksa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat_terakhir as $skrining)
                                <tr>
                                    <td>
                                        <div class="patient-info">
                                            <div class="patient-avatar">
                                                {{ strtoupper(substr($skrining->lansia->nama_lansia, 0, 2)) }}
                                            </div>
                                            <span class="patient-name">{{ $skrining->lansia->nama_lansia }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($skrining->lansia->latestSkriningUtama)
                                            @if($skrining->lansia->latestSkriningUtama->gula_darah_kategori == 3 || $skrining->lansia->latestSkriningUtama->kolesterol_kategori == 3)
                                                <span class="badge-risk high">Risiko Tinggi</span>
                                            @elseif($skrining->lansia->latestSkriningUtama->gula_darah_kategori == 2 || $skrining->lansia->latestSkriningUtama->kolesterol_kategori == 2)
                                                <span class="badge-risk warning">Waspada</span>
                                            @else
                                                <span class="badge-risk normal">Normal</span>
                                            @endif
                                        @else
                                            <span class="badge-risk normal">Normal</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span style="color:var(--text-muted); font-size: 13px;">
                                            <i class="fa-regular fa-clock"></i>
                                            {{ \Carbon\Carbon::parse($skrining->tanggal_skrining)->diffForHumans() }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; padding: 20px;">Belum ada riwayat pemeriksaan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <a href="/data_lansia" class="btn-view-all">Buka Semua Data <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Interaktif Counter Angka
            const counters = document.querySelectorAll('.stat-value');

            counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText;
                    const inc = target / 40; // Kecepatan counter

                    if (count < target) {
                        counter.innerText = Math.ceil(count + inc);
                        setTimeout(updateCount, 30);
                    } else {
                        counter.innerText = target.toLocaleString('id-ID');
                    }
                };

                // Cek jika elemen terlihat di layar
                const observer = new IntersectionObserver((entries) => {
                    if (entries[0].isIntersecting) {
                        updateCount();
                        observer.disconnect();
                    }
                });

                observer.observe(counter);
            });
        });
    </script>
@endpush