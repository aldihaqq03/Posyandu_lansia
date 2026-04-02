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
        </div>

        <!-- STATS GRID -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-icon icon-blue"><i class="fa-solid fa-users"></i></div>
                    <span class="trend-badge positive">+3.2% Bulan Ini</span>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Total Lansia Terdaftar</span>
                    <h2 class="stat-value" data-target="1248">0</h2>
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
                    <h2 class="stat-value" data-target="42">0</h2>
                </div>
                <div class="alert-box-mini">
                    <i class="fa-solid fa-circle-exclamation"></i> 8 Lansia butuh penanganan segera
                </div>
            </div>

            <div class="stat-card success-card">
                <div class="stat-top">
                    <div class="stat-icon icon-green"><i class="fa-solid fa-check-double"></i></div>
                    <span class="trend-badge positive">Bulan Ini</span>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Pemeriksaan Selesai</span>
                    <h2 class="stat-value" data-target="892">0</h2>
                </div>
                <div class="avatar-group">
                    <img src="https://ui-avatars.com/api/?name=SI&background=10b981&color=fff" alt="user">
                    <img src="https://ui-avatars.com/api/?name=BU&background=3b82f6&color=fff" alt="user">
                    <img src="https://ui-avatars.com/api/?name=AG&background=f59e0b&color=fff" alt="user">
                    <span class="avatar-more">+889 lainnya</span>
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
                    <div class="chart-item">
                        <div class="chart-info"><span>Hipertensi (Darah Tinggi)</span> <strong>45%</strong></div>
                        <div class="progress-bar-thin">
                            <div class="progress-fill" style="width: 45%; background: var(--danger);"></div>
                        </div>
                    </div>
                    <div class="chart-item">
                        <div class="chart-info"><span>Diabetes Melitus</span> <strong>28%</strong></div>
                        <div class="progress-bar-thin">
                            <div class="progress-fill" style="width: 28%; background: var(--warning);"></div>
                        </div>
                    </div>
                    <div class="chart-item">
                        <div class="chart-info"><span>Asam Urat</span> <strong>18%</strong></div>
                        <div class="progress-bar-thin">
                            <div class="progress-fill" style="width: 18%; background: var(--primary);"></div>
                        </div>
                    </div>
                    <div class="chart-item">
                        <div class="chart-info"><span>Kolesterol</span> <strong>9%</strong></div>
                        <div class="progress-bar-thin">
                            <div class="progress-fill" style="width: 9%; background: var(--success);"></div>
                        </div>
                    </div>
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
                            <tr>
                                <td>
                                    <div class="patient-info">
                                        <div class="patient-avatar">BP</div>
                                        <span class="patient-name">Bapak Purnomo</span>
                                    </div>
                                </td>
                                <td><span class="badge-risk high">Risiko Tinggi</span></td>
                                <td><span style="color:var(--text-muted); font-size: 13px;"><i
                                            class="fa-regular fa-clock"></i> 2 Jam yang lalu</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="patient-info">
                                        <div class="patient-avatar" style="background:#ecfdf5;color:#10b981;">IK</div>
                                        <span class="patient-name">Ibu Kartini Kusuma</span>
                                    </div>
                                </td>
                                <td><span class="badge-risk normal">Normal</span></td>
                                <td><span style="color:var(--text-muted); font-size: 13px;"><i
                                            class="fa-regular fa-calendar"></i> 23 Okt 2023</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="patient-info">
                                        <div class="patient-avatar" style="background:#fffbeb;color:#f59e0b;">SW</div>
                                        <span class="patient-name">Bapak Sri Widodo</span>
                                    </div>
                                </td>
                                <td><span class="badge-risk warning">Waspada</span></td>
                                <td><span style="color:var(--text-muted); font-size: 13px;"><i
                                            class="fa-regular fa-calendar"></i> 22 Okt 2023</span></td>
                            </tr>
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