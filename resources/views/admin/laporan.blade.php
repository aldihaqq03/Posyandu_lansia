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

        .modal-detail{
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(15,23,42,0.55);
    z-index:999;
    justify-content:center;
    align-items:center;
    backdrop-filter: blur(4px);
    padding:20px;
}

.modal-detail-content{
    width:100%;
    max-width:480px;
    background:#ffffff;
    border-radius:24px;
    padding:28px;
    box-shadow:0 25px 50px rgba(0,0,0,0.15);
    animation: modalFade .25s ease;
}

.modal-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:24px;
}

.modal-header h3{
    font-size:22px;
    font-weight:700;
    color:#0f172a;
}

.close-modal{
    width:38px;
    height:38px;
    border:none;
    border-radius:12px;
    background:#f1f5f9;
    cursor:pointer;
    font-size:22px;
    transition:.2s;
}

.close-modal:hover{
    background:#e2e8f0;
}

.modal-body{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.detail-item{
    background:#f8fafc;
    border:1px solid #e2e8f0;
    border-radius:16px;
    padding:16px;
}

.detail-item span{
    display:block;
    font-size:13px;
    color:#64748b;
    margin-bottom:6px;
}

.detail-item strong{
    font-size:16px;
    color:#0f172a;
    font-weight:700;
}

@keyframes modalFade{
    from{
        opacity:0;
        transform:translateY(20px);
    }

    to{
        opacity:1;
        transform:translateY(0);
    }
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
    {{ \Carbon\Carbon::parse($item->tanggal_skrining)->format('d M Y') }}
</td>

<td>
    {{ $item->tema }}
</td>

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

<!-- MODAL DETAIL -->
<div id="modalDetail" class="modal-detail">

    <div class="modal-detail-content">

        <div class="modal-header">
            <h3>Detail Laporan</h3>

            <button class="close-modal">
                &times;
            </button>
        </div>

        <div class="modal-body">

            <div class="detail-item">
                <span>Status Kehadiran</span>
                <strong>Hadir</strong>
            </div>

            <div class="detail-item">
                <span>Petugas</span>
                <strong>Bu Sinta</strong>
            </div>

            <div class="detail-item">
                <span>Obat Keluar</span>
                <strong>Paracetamol</strong>
            </div>

        </div>

    </div>

</div>

</div>
</div>
</div>

{{-- MODAL DETAIL --}}
<div id="modalDetail"
    style="
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.5);
    z-index:999;
    justify-content:center;
    align-items:center;
">

    <div style="
        background:white;
        width:450px;
        border-radius:16px;
        padding:24px;
        position:relative;
    ">

        <h3 style="font-size:20px; font-weight:700; margin-bottom:20px;">
            Detail Kehadiran
        </h3>

        <div style="display:flex; flex-direction:column; gap:14px;">

    <button class="filter-btn">
        Status Kehadiran
    </button>

    <button class="filter-btn">
        Petugas
    </button>

    <button class="filter-btn">
        Obat Keluar
    </button>

</div>

        <button onclick="closeModal()">
            Tutup
        </button>

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


/* MODAL */
const modal = document.getElementById('modalDetail');

const detailButtons = document.querySelectorAll('.filter-btn');

const closeModal = document.querySelector('.close-modal');

detailButtons.forEach(button => {

    button.addEventListener('click', () => {

        modal.style.display = 'flex';

    });

});

closeModal.addEventListener('click', () => {

    modal.style.display = 'none';

});
</script>

@endpush
