@extends('layout.sidebar')

@push('styles')
@vite('resources/css/cssAdmin/dashboard.css')
<style>
/* ── Stat Cards ── */
.laporan-stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    flex-shrink: 0;
}

/* ── Card pembungkus filter + tabel (seperti jadwal) ── */
.laporan-main-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    flex: 1;
    min-height: 0;
}

.laporan-filter-bar {
    padding: 16px 20px;
    border-bottom: 1px solid #e5e7eb;
    background: #fefefe;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    flex-shrink: 0;
}

.laporan-filter-bar label {
    font-size: 12px;
    font-weight: 600;
    color: #4b5563;
    white-space: nowrap;
}

.laporan-filter-select {
    padding: 7px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 13px;
    font-family: inherit;
    background: #fff;
    outline: none;
    color: #1e293b;
    cursor: pointer;
}

.laporan-filter-select:focus {
    border-color: #3b82f6;
}

.laporan-table-wrap {
    flex: 1;
    min-height: 0;
    overflow-x: auto;
    overflow-y: auto;
}

/* ── Tabel style sama dengan custom-table data lansia ── */
.laporan-table {
    width: 100%;
    border-collapse: collapse;
}

.laporan-table thead th {
    position: sticky;
    top: 0;
    z-index: 10;
    background-color: #ffffff;
    box-shadow: 0 1px 0 #e5e7eb;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: #94a3b8;
    padding: 14px 16px;
    border-bottom: 2px solid #f1f5f9;
    white-space: nowrap;
}

.laporan-table tbody td {
    padding: 14px 16px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    font-size: 13px;
    color: #1e293b;
}

.laporan-table tbody tr {
    transition: background 0.15s ease;
}

.laporan-table tbody tr:hover {
    background: #f8fafc;
}

/* ── Aksi tombol ── */
.laporan-aksi-wrap {
    display: flex;
    gap: 6px;
    align-items: center;
}

.laporan-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 6px;
    cursor: pointer;
    border: 1px solid transparent;
    transition: opacity 0.15s;
    text-decoration: none;
    line-height: 1;
}

.laporan-btn:hover { opacity: 0.82; }

.laporan-btn-detail {
    background: #eff6ff;
    color: #2563eb;
    border-color: #bfdbfe;
}

.laporan-btn-export {
    background: #2563eb;
    color: #fff;
    border-color: #2563eb;
}

/* ── Badge ── */
.badge-normal { background:#dcfce7; color:#16a34a; padding:4px 10px; border-radius:999px; font-size:11px; font-weight:600; }
.badge-warning { background:#fef3c7; color:#d97706; padding:4px 10px; border-radius:999px; font-size:11px; font-weight:600; }
.badge-danger  { background:#fee2e2; color:#dc2626; padding:4px 10px; border-radius:999px; font-size:11px; font-weight:600; }
.badge-hadir   { background:#dcfce7; color:#16a34a; padding:4px 10px; border-radius:999px; font-size:11px; font-weight:600; }

/* ── Layout halaman ── */
body:has(.laporan-page) .main-content {
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.laporan-page {
    display: flex;
    flex-direction: column;
    flex: 1;
    height: 100%;
    overflow: hidden;
    gap: 20px;
}

/* ── Modal ── */
.modal-detail {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,0.5);
    z-index: 999;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(4px);
    padding: 20px;
}

.modal-detail-content {
    width: 100%;
    max-width: 560px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 25px 50px rgba(0,0,0,0.15);
    max-height: 88vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: modalFade .22s ease;
}

@keyframes modalFade {
    from { opacity:0; transform:translateY(16px); }
    to   { opacity:1; transform:translateY(0); }
}

.modal-detail-header {
    padding: 18px 20px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
}

.modal-detail-header h3 {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.modal-detail-body {
    flex: 1;
    overflow-y: auto;
    padding: 16px 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.modal-tabs {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.modal-tab-btn {
    padding: 6px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    background: #f9fafb;
    color: #6b7280;
    cursor: pointer;
    transition: all 0.15s;
}

.modal-tab-btn.active {
    background: #2563eb;
    color: #fff;
    border-color: #2563eb;
}

.modal-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.modal-info-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 10px 14px;
}

.modal-info-item span {
    display: block;
    font-size: 11px;
    color: #6b7280;
    margin-bottom: 3px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.modal-info-item strong {
    font-size: 13px;
    color: #111827;
    font-weight: 600;
}

.btn-close-modal {
    width: 32px;
    height: 32px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: #f9fafb;
    color: #6b7280;
    cursor: pointer;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s;
}

.btn-close-modal:hover { background: #fee2e2; color: #dc2626; }

.export-modal-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    background: #2563eb;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.15s;
}

.export-modal-btn:hover { background: #1d4ed8; }
</style>
@endpush

@section('content')
<div class="laporan-page">

    {{-- ── HEADER ── --}}
    <div class="page-header" style="flex-shrink:0;">
        <div>
            <h1 class="page-title">Laporan</h1>
            <p class="page-subtitle">Pantau laporan partisipasi dan kehadiran lansia per hari, minggu, dan tahun.</p>
        </div>
    </div>

    {{-- ── STAT CARDS ── --}}
    <div class="laporan-stats-grid" style="flex-shrink:0;">
        <div class="stat-card" style="border-left-color:#3b82f6;">
            <span class="stat-label">Hadir Hari Ini</span>
            <div class="stat-content">
                <span class="stat-number color-primary" data-target="{{ $summary['hari_ini'] }}">0</span>
                <i class="fa-solid fa-calendar-day stat-icon-fa color-primary"></i>
            </div>
        </div>
        <div class="stat-card" style="border-left-color:#10b981;">
            <span class="stat-label">Hadir Minggu Ini</span>
            <div class="stat-content">
                <span class="stat-number color-success" data-target="{{ $summary['minggu_ini'] }}">0</span>
                <i class="fa-solid fa-calendar-week stat-icon-fa color-success"></i>
            </div>
        </div>
        <div class="stat-card" style="border-left-color:#8b5cf6;">
            <span class="stat-label">Total Tahun Ini</span>
            <div class="stat-content">
                <span class="stat-number" style="color:#8b5cf6;" data-target="{{ $summary['tahun_ini'] }}">0</span>
                <i class="fa-solid fa-calendar-days stat-icon-fa" style="color:#8b5cf6;"></i>
            </div>
        </div>
    </div>

    {{-- ── CARD UTAMA: FILTER + TABEL ── --}}
    <div class="laporan-main-card">

        {{-- Filter Bar (seperti jadwal) --}}
        <div class="laporan-filter-bar">
            <div style="display:flex; align-items:center; gap:8px; background:#f8fafc; border:1px solid #d1d5db; border-radius:8px; padding:7px 12px; flex:1; min-width:180px;">
        <i class="fa-solid fa-magnifying-glass" style="color:#9ca3af; font-size:13px;"></i>
        <input type="text" id="searchKegiatan" placeholder="Cari nama kegiatan..."
            style="border:none; background:transparent; outline:none; font-size:13px; width:100%; font-family:inherit; color:#1e293b;">
    </div>
            <select id="filterBulan" class="laporan-filter-select">
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
            <select id="filterTahun" class="laporan-filter-select">
                <option value="">Semua Tahun</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
            </select>
        </div>

        {{-- Tabel --}}
        <div class="laporan-table-wrap">
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
                        data-bulan="{{ \Carbon\Carbon::parse($item->tanggal_pelaksanaan)->format('n') }}"
                        data-tahun="{{ \Carbon\Carbon::parse($item->tanggal_pelaksanaan)->format('Y') }}"
                    >
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_pelaksanaan)->format('d M Y') }}</td>
                        <td>{{ $item->tema }}</td>
                        <td>
                            <div class="laporan-aksi-wrap">
                                <button class="laporan-btn laporan-btn-detail btn-detail"
                                    data-id="{{ $item->id_jadwal_posyandu }}">
                                    <i class="fa-solid fa-eye"></i> Detail
                                </button>
                                <a href="{{ route('laporan.export', $item->id_jadwal_posyandu) }}"
                                    target="_blank"
                                    class="laporan-btn laporan-btn-export">
                                    <i class="fa-solid fa-download"></i> Export
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:40px; color:#94a3b8;">
                            <i class="fa-solid fa-folder-open" style="font-size:24px; display:block; margin-bottom:8px; opacity:0.4;"></i>
                            Tidak ada data laporan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

{{-- ── MODAL DETAIL ── --}}
<div id="modalDetail" class="modal-detail">
    <div class="modal-detail-content">

        <div class="modal-detail-header">
            <h3>Detail Laporan</h3>
            <button class="btn-close-modal" onclick="closeModalDetail()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="modal-detail-body">

            {{-- Info jadwal --}}
            <div class="modal-info-grid">
                <div class="modal-info-item">
                    <span>Tanggal Posyandu</span>
                    <strong id="detailTanggal">-</strong>
                </div>
                <div class="modal-info-item" style="grid-column: span 1;">
                    <span>Tema Kegiatan</span>
                    <strong id="detailTema">-</strong>
                </div>
            </div>

            {{-- Tab navigasi --}}
            <div class="modal-tabs">
                <button class="modal-tab-btn active" id="btnStatus" onclick="showTab('status')">
                    <i class="fa-solid fa-users"></i> Status Kehadiran
                </button>
                <button class="modal-tab-btn" id="btnPetugas" onclick="showTab('petugas')">
                    <i class="fa-solid fa-user-nurse"></i> Petugas
                </button>
                <button class="modal-tab-btn" id="btnObat" onclick="showTab('obat')">
                    <i class="fa-solid fa-pills"></i> Obat Keluar
                </button>
            </div>

            {{-- Export obat (muncul saat tab obat aktif) --}}
            <div id="exportObatWrapper" style="display:none;">
                <a href="#" id="btnExportPdf" class="export-modal-btn">
                    <i class="fa-solid fa-download"></i> Export PDF
                </a>
            </div>

            {{-- Tabel Status --}}
            <div id="tabStatus">
                <div style="overflow-x:auto;">
                    <table class="laporan-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Lansia</th>
                                <th>Jenis Kelamin</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="statusBody"></tbody>
                    </table>
                </div>
            </div>

            {{-- Tabel Petugas --}}
            <div id="tabPetugas" style="display:none;">
                <div style="overflow-x:auto;">
                    <table class="laporan-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Petugas</th>
                                <th>Jumlah Lansia</th>
                            </tr>
                        </thead>
                        <tbody id="petugasBody"></tbody>
                    </table>
                </div>
            </div>

            {{-- Tabel Obat --}}
            <div id="tabObat" style="display:none;">
                <div style="overflow-x:auto;">
                    <table class="laporan-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Obat</th>
                                <th>Jumlah Keluar</th>
                            </tr>
                        </thead>
                        <tbody id="obatBody"></tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentJadwalId = null;

// Counter animasi
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.stat-number[data-target]').forEach(el => {
        const target = +el.dataset.target;
        let count = 0;
        const inc = target / 30;
        const tick = () => {
            count = Math.min(count + inc, target);
            el.innerText = Math.ceil(count).toLocaleString('id-ID');
            if (count < target) setTimeout(tick, 30);
        };
        tick();
    });

    // Filter
    const filterBulan = document.getElementById('filterBulan');
    const filterTahun = document.getElementById('filterTahun');
    const rows = document.querySelectorAll('#laporan-body tr[data-bulan]');

   const searchInput = document.getElementById('searchKegiatan');

function filterLaporan() {
    const bulan = filterBulan.value;
    const tahun = filterTahun.value;
    const keyword = searchInput.value.toLowerCase().trim();

    rows.forEach(row => {
        const matchBulan = !bulan || row.dataset.bulan === bulan;
        const matchTahun = !tahun || row.dataset.tahun === tahun;
        const tema = row.querySelector('td:nth-child(3)')?.innerText.toLowerCase() || '';
        const matchSearch = !keyword || tema.includes(keyword);
        row.style.display = (matchBulan && matchTahun && matchSearch) ? '' : 'none';
    });
}
searchInput.addEventListener('input', filterLaporan);
    filterBulan.addEventListener('change', filterLaporan);
    filterTahun.addEventListener('change', filterLaporan);

    // Modal detail
    const modal = document.getElementById('modalDetail');

    document.querySelectorAll('.btn-detail').forEach(btn => {
        btn.addEventListener('click', async () => {
            modal.style.display = 'flex';
            showTab('status');
            currentJadwalId = btn.dataset.id;
             document.getElementById('btnExportPdf').href =
            `/laporan/export-obat/${currentJadwalId}`;

            const res = await fetch(`/laporan/detail/${currentJadwalId}`);
            const data = await res.json();

            document.getElementById('detailTanggal').innerText = data.jadwal.tanggal;
            document.getElementById('detailTema').innerText = data.jadwal.tema;

            // Status
            document.getElementById('statusBody').innerHTML = data.status.map((item, i) => `
                <tr>
                    <td>${i + 1}</td>
                    <td>${item.nama_lansia}</td>
                    <td>${item.jenis_kelamin}</td>
                    <td><span class="${item.status_kehadiran === 'Hadir' ? 'badge-normal' : 'badge-danger'}">${item.status_kehadiran}</span></td>
                </tr>
            `).join('');

            // Petugas
            document.getElementById('petugasBody').innerHTML = data.petugas.map((item, i) => `
                <tr>
                    <td>${i + 1}</td>
                    <td>${item.nama}</td>
                    <td>${item.jumlah_lansia} Lansia</td>
                </tr>
            `).join('');

            // Obat
            document.getElementById('obatBody').innerHTML = data.obat.map((item, i) => `
                <tr>
                    <td>${i + 1}</td>
                    <td>${item.nama_obat}</td>
                    <td>${item.jumlah_keluar}</td>
                </tr>
            `).join('');
        });
    });

    modal.addEventListener('click', e => {
        if (e.target === modal) closeModalDetail();
    });
document.getElementById('btnExportPdf').addEventListener('click', function(e) {
    e.preventDefault();
    if (!currentJadwalId) return;
    window.open(`/laporan/export-obat/${currentJadwalId}`, '_blank');
});
});

function closeModalDetail() {
    document.getElementById('modalDetail').style.display = 'none';
}

function showTab(tab) {
    ['status', 'petugas', 'obat'].forEach(t => {
        document.getElementById('tab' + t.charAt(0).toUpperCase() + t.slice(1)).style.display = t === tab ? 'block' : 'none';
        document.getElementById('btn' + t.charAt(0).toUpperCase() + t.slice(1)).classList.toggle('active', t === tab);
    });
    document.getElementById('exportObatWrapper').style.display = tab === 'obat' ? 'flex' : 'none';
}
</script>
@endpush