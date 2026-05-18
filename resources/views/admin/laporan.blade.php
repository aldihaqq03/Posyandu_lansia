@extends('layout.sidebar')

@push('styles')
    @vite('resources/css/cssAdmin/dashboard.css')
    <style>
        .badge-normal{
    background:#dcfce7;
    color:#16a34a;
    padding:6px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
}

.badge-warning{
    background:#fef3c7;
    color:#d97706;
    padding:6px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
}

.badge-danger{
    background:#fee2e2;
    color:#dc2626;
    padding:6px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
}

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

        /* CSS BARU TARUH SINI */
        .laporan-table{
            width:100%;
            border-collapse: collapse;
        }

        .laporan-table thead{
            background:#f8fafc;
        }

        .laporan-table th{
            padding:14px;
            font-size:13px;
            font-weight:700;
            color:#475569;
            border-bottom:1px solid #e2e8f0;
            text-align:left;
        }

        .laporan-table td{
            padding:14px;
            border-bottom:1px solid #f1f5f9;
            font-size:14px;
            color:#334155;
        }

        .laporan-table tr:hover{
            background:#f8fafc;
        }

        .badge-hadir{
            background:#dcfce7;
            color:#16a34a;
            padding:6px 12px;
            border-radius:999px;
            font-size:12px;
            font-weight:600;
        }

        .table-responsive{
            overflow-x:auto;
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

    <!-- TABLE AREA -->
    <div style="margin-top:24px;">
        <div class="filter-tabs">
            <button class="filter-btn active" onclick="updateChart('harian', this)">Harian (7 Hari)</button>
            <button class="filter-btn" onclick="updateChart('mingguan', this)">Mingguan (Bulan Ini)</button>
            <button class="filter-btn" onclick="updateChart('tahunan', this)">Tahunan (Tahun Ini)</button>
        </div>
        
        <div class="chart-container-wrapper">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h3 style="font-size:18px; font-weight:700;">Tabel Laporan Kehadiran</h3>

        <button class="filter-btn">
            <i class="fa-solid fa-download"></i>
            Export
        </button>
    </div>

    <div class="table-responsive">
        <table class="laporan-table">
            <thead>
               <tr>
    <th>No</th>
    <th>Tanggal Kunjungan</th>
    <th>Nama Kegiatan</th>
    <th>Aksi</th>
</tr>
            </thead>

            <tbody id="laporan-body">
                @forelse($laporan as $item)
                    <tr>
    <td>{{ $loop->iteration }}</td>

    <td>
        {{ \Carbon\Carbon::parse($item->tanggal_kunjungan)->format('d M Y') }}
    </td>

    <td>Posyandu Lansia Bulanan</td>

    <td>
        <button class="filter-btn">
            Detail
        </button>
    </td>
</tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;">
                            Tidak ada data laporan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {

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

        updateCount();

    });

});
</script>
@endpush
