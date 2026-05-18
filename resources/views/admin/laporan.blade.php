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
        .filter-laporan-card{
    background:#ffffff;
    border:1px solid #e2e8f0;
    border-radius:24px;
    padding:24px;
    margin-bottom:24px;
}

.filter-title{
    font-size:14px;
    font-weight:700;
    letter-spacing:1px;
    color:#64748b;
    margin-bottom:24px;
}

.filter-grid{
    display:grid;
    grid-template-columns: 1fr 1fr 320px;
    gap:20px;
    align-items:end;
}

.filter-group{
    display:flex;
    flex-direction:column;
    gap:10px;
}

.filter-group label{
    font-size:15px;
    font-weight:600;
    color:#0f172a;
}

.filter-input{
    width:100%;
    height:52px;
    border:1px solid #dbe2ea;
    border-radius:14px;
    padding:0 16px;
    font-size:15px;
    outline:none;
    background:#f8fafc;
}

.filter-input:focus{
    border-color:#3b82f6;
    background:#fff;
}

.export-btn{
    height:52px;
    border:none;
    border-radius:14px;
    background:#2563eb;
    color:white;
    font-weight:600;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;
    transition:.2s;
}

.export-btn:hover{
    background:#1d4ed8;
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
    position:absolute;
    top:16px;
    right:16px;
    width:38px;
    height:38px;
    border:none;
    border-radius:12px;
    background:#f1f5f9;
    cursor:pointer;
    font-size:22px;
    display:flex;
    align-items:center;
    justify-content:center;
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
       <div class="filter-laporan-card">

    <h3 class="filter-title">
        FILTER LAPORAN
    </h3>

    <div class="filter-grid">

        {{-- BULAN --}}
        <div class="filter-group">
            <label>Bulan</label>

            <select id="filterBulan" class="filter-input">
                <option value="">Semua Bulan</option>
                <option value="1">Januari</option>
                <option value="2">Februari</option>
                <option value="3">Maret</option>
                <option value="4">April</option>
                <option value="5">Mei</option>
                <option value="6">Juni</option>
                <option value="7">Juli</option>
                <option value="8">Agustus</option>
                <option value="9">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>
        </div>

        {{-- TAHUN --}}
        <div class="filter-group">
            <label>Tahun</label>

            <select id="filterTahun" class="filter-input">
                <option value="">Semua Tahun</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
            </select>
        </div>

        {{-- EXPORT --}}
        <div class="filter-group">
            <label>Export Laporan</label>

            <button class="export-btn">
                <i class="fa-solid fa-download"></i>
                Export Laporan
            </button>
        </div>

    </div>

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

<tr
    data-bulan="{{ \Carbon\Carbon::parse($item->tanggal_pelaksanaan)->format('m') }}"
    data-tahun="{{ \Carbon\Carbon::parse($item->tanggal_pelaksanaan)->format('Y') }}"
>
    <td>{{ $loop->iteration }}</td>

    <td>
    {{ \Carbon\Carbon::parse($item->tanggal_pelaksanaan)->format('d M Y') }}
</td>

<td>
    {{ $item->tema }}
</td>

    <td>
        <button class="filter-btn btn-detail">
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
    <button class="close-modal" onclick="closeModalModal()">
    &times;
</button>

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

const detailButtons = document.querySelectorAll('.btn-detail');

const closeModal = document.querySelector('.close-modal');

detailButtons.forEach(button => {

    button.addEventListener('click', () => {

        modal.style.display = 'flex';

    });

});

closeModal.addEventListener('click', () => {

    modal.style.display = 'none';

});
function closeModalModal() {

    modal.style.display = 'none';

}
const filterBulan = document.getElementById('filterBulan');
const filterTahun = document.getElementById('filterTahun');

const rows = document.querySelectorAll('#laporan-body tr');

function filterLaporan() {

    const bulan = filterBulan.value;
    const tahun = filterTahun.value;

    rows.forEach(row => {

        const rowBulan = row.getAttribute('data-bulan');
        const rowTahun = row.getAttribute('data-tahun');

        let show = true;

        if (bulan && rowBulan !== bulan.padStart(2, '0')) {
            show = false;
        }

        if (tahun && rowTahun !== tahun) {
            show = false;
        }

        row.style.display = show ? '' : 'none';

    });

}

filterBulan.addEventListener('change', filterLaporan);
filterTahun.addEventListener('change', filterLaporan);
</script>
@endpush
