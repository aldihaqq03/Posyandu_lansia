@extends('layout.sidebar')

@push('styles')
    @vite('resources/css/cssAdmin/dashboard.css')
    <style>
        .filter-tabs {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .filter-btn {
            background-color: white;
            color: var(--text-muted);
            border: 1px solid #e2e8f0;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .filter-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
        }
        .filter-btn.active {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
            box-shadow: 0 4px 10px rgba(14, 165, 233, 0.3);
        }
        .chart-container-wrapper {
            background: white;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            width: 100%;
            height: 400px; /* Fixed height for beautiful canvas container */
            position: relative;
        }
    </style>
@endpush

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-text">
            <h1>Laporan Kehadiran</h1>
            <p>Pantau laporan partisipasi dan kehadiran lansia per hari, minggu, dan tahun.</p>
        </div>
    </div>

    <!-- STATS GRID -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon icon-blue"><i class="fa-solid fa-calendar-day"></i></div>
            </div>
            <div class="stat-info">
                <span class="stat-label">Hadir Hari Ini</span>
                <h2 class="stat-value" data-target="{{ $summary['hari_ini'] }}">0</h2>
            </div>
        </div>

        <div class="stat-card success-card">
            <div class="stat-top">
                <div class="stat-icon icon-green"><i class="fa-solid fa-calendar-week"></i></div>
            </div>
            <div class="stat-info">
                <span class="stat-label">Hadir Minggu Ini</span>
                <h2 class="stat-value" data-target="{{ $summary['minggu_ini'] }}">0</h2>
            </div>
        </div>

        <div class="stat-card" style="border-left-color: #8b5cf6;">
            <div class="stat-top">
                <div class="stat-icon" style="background:#ede9fe; color:#8b5cf6;"><i class="fa-solid fa-calendar-days"></i></div>
            </div>
            <div class="stat-info">
                <span class="stat-label">Total Tahun Ini</span>
                <h2 class="stat-value" data-target="{{ $summary['tahun_ini'] }}">0</h2>
            </div>
        </div>
    </div>

    <!-- CHART AREA -->
    <div style="margin-top:24px;">
        <div class="filter-tabs">
            <button class="filter-btn active" onclick="updateChart('harian', this)">Harian (7 Hari)</button>
            <button class="filter-btn" onclick="updateChart('mingguan', this)">Mingguan (Bulan Ini)</button>
            <button class="filter-btn" onclick="updateChart('tahunan', this)">Tahunan (Tahun Ini)</button>
        </div>
        
        <div class="chart-container-wrapper">
            <canvas id="laporanChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Interaktif Counter Angka
        const counters = document.querySelectorAll('.stat-value');

        counters.forEach(counter => {
            const updateCount = () => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText;
                const inc = target / 30; 

                if (count < target) {
                    counter.innerText = Math.ceil(count + inc);
                    setTimeout(updateCount, 30);
                } else {
                    counter.innerText = target.toLocaleString('id-ID');
                }
            };

            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    updateCount();
                    observer.disconnect();
                }
            });

            observer.observe(counter);
        });

        // INIT CHART.JS
        const ctx = document.getElementById('laporanChart').getContext('2d');
        
        // Data Dummy dari Controller
        const chartData = {
            harian: @json($harian),
            mingguan: @json($mingguan),
            tahunan: @json($tahunan)
        };

        // Konfigurasi Chart Utama (Base)
        let laporanChart = new Chart(ctx, {
            type: 'line', // default
            data: {
                labels: chartData.harian.labels,
                datasets: [{
                    label: 'Jumlah Kehadiran',
                    data: chartData.harian.data,
                    backgroundColor: 'rgba(14, 165, 233, 0.2)',
                    borderColor: '#0ea5e9',
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#0ea5e9',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 14, family: 'Inter' },
                        bodyFont: { size: 14, family: 'Inter' },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' Orang Lansia';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9',
                            drawBorder: false,
                        },
                        ticks: {
                            font: { family: 'Inter' },
                            color: '#64748b',
                            stepSize: 10
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            font: { family: 'Inter' },
                            color: '#64748b'
                        }
                    }
                }
            }
        });

        // GLOBAL METHOD UNTUK UPDATE CHART DINAMIS
        window.updateChart = function(periode, element) {
            // Ubah class active di tombol
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            element.classList.add('active');

            // Ambil data yang sesuai
            const selectedData = chartData[periode];

            // Tentukan tipe grafik: Semua menggunakan Line Chart untuk konsistensi
            laporanChart.config.type = 'line';
            
            if (periode === 'mingguan') {
                laporanChart.data.datasets[0].backgroundColor = 'rgba(16, 185, 129, 0.2)'; // green
                laporanChart.data.datasets[0].borderColor = '#10b981';
                laporanChart.data.datasets[0].pointBorderColor = '#10b981';
                laporanChart.data.datasets[0].fill = true;
            } else if (periode === 'tahunan') {
                laporanChart.data.datasets[0].backgroundColor = 'rgba(139, 92, 246, 0.2)'; // purple
                laporanChart.data.datasets[0].borderColor = '#8b5cf6';
                laporanChart.data.datasets[0].pointBorderColor = '#8b5cf6';
                laporanChart.data.datasets[0].fill = true;
            } else {
                // harian
                laporanChart.data.datasets[0].backgroundColor = 'rgba(14, 165, 233, 0.2)'; // blue
                laporanChart.data.datasets[0].borderColor = '#0ea5e9';
                laporanChart.data.datasets[0].pointBorderColor = '#0ea5e9';
                laporanChart.data.datasets[0].fill = true;
            }

            // Update Label & Data
            laporanChart.data.labels = selectedData.labels;
            laporanChart.data.datasets[0].data = selectedData.data;

            // Animate Update
            laporanChart.update();
        };
    });
</script>
@endpush
