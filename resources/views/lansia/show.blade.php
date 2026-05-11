@extends('layout.sidebar')

@push('styles')
    @vite(['resources/css/app.css', 'resources/css/cssAdmin/profil_lengkap.css'])
@endpush

@section('title', 'Histori Skrining – ' . $lansia->nama_lansia)

@section('content')
    <main class="main-content">
        <div class="container">

            {{-- ── BREADCRUMB & HEADER ─────────────────────────────────── --}}
            <header class="page-header">
                <div class="header-info">
                    <nav class="breadcrumb">
                        <a href="{{ route('data_lansia') }}" class="breadcrumb-link">
                            <i class="fa-solid fa-users"></i> Data Lansia
                        </a>
                        <i class="fa-solid fa-chevron-right sep"></i>
                        <span>Histori Skrining</span>
                    </nav>
                    <h1 class="page-title">{{ $lansia->nama_lansia }}</h1>
                    <p class="page-subtitle">
                        {{ \Carbon\Carbon::parse($lansia->tanggal_lahir)->age }} Tahun &nbsp;·&nbsp;
                        {{ $lansia->alamat ?? '-' }}
                    </p>
                </div>
                <a href="{{ route('data_lansia') }}" class="btn-outline">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </header>

            {{-- ── INFO SINGKAT LANSIA ─────────────────────────────────── --}}
            <section class="card lansia-info-card">
                <div class="lansia-info-grid">
                    <div class="info-item">
                        <span class="info-label">NIK</span>
                        <span class="info-val">{{ $lansia->nik }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Jenis Kelamin</span>
                        <span class="info-val">{{ $lansia->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">No. HP</span>
                        <span class="info-val">{{ $lansia->no_hp ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Riwayat Penyakit</span>
                        <span class="info-val">{{ $lansia->riwayat_penyakit ?? '-' }}</span>
                    </div>
                </div>
            </section>

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- TABEL 1 – SKRINING KUNJUNGAN --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            <section class="card history-section">
                <div class="history-header">
                    <div class="history-title">
                        <div class="history-icon red"><i class="fa-solid fa-heart-pulse"></i></div>
                        <div>
                            <h3>Skrining Kunjungan</h3>
                            <p>Tekanan darah, berat badan, tinggi badan, lingkar perut</p>
                        </div>
                    </div>
                    <div class="history-actions">
                        <input type="month" class="filter-month" data-target="tbl-kunjungan" title="Filter bulan">
                    </div>
                </div>

                @if($kunjungans->isEmpty())
                    <div class="empty-state">Belum ada data skrining kunjungan.</div>
                @else
                    <div class="table-scroll">
                        <table class="history-table" id="tbl-kunjungan">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Petugas</th>
                                    <th>Tensi</th>
                                    <th>Berat Badan</th>
                                    <th>Tinggi Badan</th>
                                    <th>Lingkar Perut</th>
                                    <th>Keluhan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kunjungans as $s)
                                    <tr data-bulan="{{ \Carbon\Carbon::parse($s->tanggal_skrining)->format('Y-m') }}">
                                        <td>{{ \Carbon\Carbon::parse($s->tanggal_skrining)->format('d M Y') }}</td>
                                        <td>{{ $s->petugas?->nama ?? '-' }}</td>
                                        <td>
                                            @if($s->kunjungan?->td_sistolik)
                                                <span
                                                    class="val-badge red">{{ $s->kunjungan->td_sistolik }}/{{ $s->kunjungan->td_diastolik }}
                                                    mmHg</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $s->kunjungan?->berat_badan ? $s->kunjungan->berat_badan . ' kg' : '-' }}</td>
                                        <td>{{ $s->kunjungan?->tinggi_badan ? $s->kunjungan->tinggi_badan . ' cm' : '-' }}</td>
                                        <td>{{ $s->kunjungan?->lingkar_perut ? $s->kunjungan->lingkar_perut . ' cm' : '-' }}</td>
                                        <td>{{ $s->kunjungan?->keluhan ?? $s->keluhan ?? '-' }}</td>
                                        <td class="text-center aksi-col" onclick="event.stopPropagation()">
                                            {{-- ✅ Unified class: btn-detail + data-type="kunjungan" --}}
                                            <button class="btn-icon btn-detail" data-type="kunjungan" title="Lihat Detail"
                                                data-skrining-id="{{ $s->id_skrining }}"
                                                data-tanggal="{{ \Carbon\Carbon::parse($s->tanggal_skrining)->format('d M Y') }}"
                                                data-petugas="{{ $s->petugas?->nama ?? '-' }}"
                                                data-keluhan="{{ $s->keluhan ?? '-' }}"
                                                data-sistolik="{{ $s->kunjungan?->td_sistolik ?? '' }}"
                                                data-diastolik="{{ $s->kunjungan?->td_diastolik ?? '' }}"
                                                data-bb="{{ $s->kunjungan?->berat_badan ?? '' }}"
                                                data-tb="{{ $s->kunjungan?->tinggi_badan ?? '' }}"
                                                data-lp="{{ $s->kunjungan?->lingkar_perut ?? '' }}"
                                                data-keluhan-kunjungan="{{ $s->kunjungan?->keluhan ?? '' }}">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            {{-- ✅ Unified class: btn-pdf --}}
                                            <button class="btn-icon btn-pdf" data-type="kunjungan" title="Download PDF"
                                                data-tanggal="{{ \Carbon\Carbon::parse($s->tanggal_skrining)->format('d M Y') }}">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- TABEL 2 – SKRINING UTAMA --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            <section class="card history-section">
                <div class="history-header">
                    <div class="history-title">
                        <div class="history-icon blue"><i class="fa-solid fa-vial"></i></div>
                        <div>
                            <h3>Skrining Utama</h3>
                            <p>Gula darah, kolesterol, IMT, SRQ-20, dan faktor risiko lainnya</p>
                        </div>
                    </div>
                    <div class="history-actions">
                        <input type="month" class="filter-month" data-target="tbl-utama" title="Filter bulan">
                    </div>
                </div>

                @if($utamas->isEmpty())
                    <div class="empty-state">Belum ada data skrining utama.</div>
                @else
                    <div class="table-scroll">
                        <table class="history-table" id="tbl-utama">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Petugas</th>
                                    <th>Gula Darah</th>
                                    <th>Kategori GD</th>
                                    <th>Kolesterol</th>
                                    <th>Kategori Kol.</th>
                                    <th>IMT</th>
                                    <th>SRQ-20</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $katLabel = [1 => 'Normal', 2 => 'Waspada', 3 => 'Tinggi'];
                                    $katClass = [1 => 'success', 2 => 'warning', 3 => 'high'];
                                @endphp
                                @foreach($utamas as $s)
                                    <tr data-bulan="{{ \Carbon\Carbon::parse($s->tanggal_skrining)->format('Y-m') }}">
                                        <td>{{ \Carbon\Carbon::parse($s->tanggal_skrining)->format('d M Y') }}</td>
                                        <td>{{ $s->petugas?->nama ?? '-' }}</td>
                                        <td>{{ $s->utama?->gula_darah ? $s->utama->gula_darah . ' mg/dL' : '-' }}</td>
                                        <td>
                                            @if($s->utama?->gula_darah_kategori)
                                                <span class="badge-status {{ $katClass[$s->utama->gula_darah_kategori] ?? 'muted' }}">
                                                    {{ $katLabel[$s->utama->gula_darah_kategori] ?? '-' }}
                                                </span>
                                            @else -
                                            @endif
                                        </td>
                                        <td>{{ $s->utama?->kolesterol ? $s->utama->kolesterol . ' mg/dL' : '-' }}</td>
                                        <td>
                                            @if($s->utama?->kolesterol_kategori)
                                                <span class="badge-status {{ $katClass[$s->utama->kolesterol_kategori] ?? 'muted' }}">
                                                    {{ $katLabel[$s->utama->kolesterol_kategori] ?? '-' }}
                                                </span>
                                            @else -
                                            @endif
                                        </td>
                                        <td>{{ $s->utama?->imt ?? '-' }}</td>
                                        <td>{{ $s->utama?->srq_total ?? '-' }}</td>
                                        <td class="text-center aksi-col" onclick="event.stopPropagation()">
                                            <button class="btn-icon btn-detail" data-type="utama" title="Lihat Detail"
                                                data-skrining-id="{{ $s->id_skrining }}"
                                                data-tanggal="{{ \Carbon\Carbon::parse($s->tanggal_skrining)->format('d M Y') }}"
                                                data-petugas="{{ $s->petugas?->nama ?? '-' }}"
                                                data-keluhan="{{ $s->keluhan ?? '-' }}">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <button class="btn-icon btn-pdf" data-type="utama" title="Download PDF"
                                                data-tanggal="{{ \Carbon\Carbon::parse($s->tanggal_skrining)->format('d M Y') }}">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- TABEL 3 – SKRINING PPOK --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            <section class="card history-section">
                <div class="history-header">
                    <div class="history-title">
                        <div class="history-icon green"><i class="fa-solid fa-lungs"></i></div>
                        <div>
                            <h3>Skrining PPOK</h3>
                            <p>PUMA score, spirometri, dan faktor risiko paru</p>
                        </div>
                    </div>
                    <div class="history-actions">
                        <input type="month" class="filter-month" data-target="tbl-ppok" title="Filter bulan">
                    </div>
                </div>

                @if($ppoks->isEmpty())
                    <div class="empty-state">Belum ada data skrining PPOK.</div>
                @else
                    <div class="table-scroll">
                        <table class="history-table" id="tbl-ppok">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Petugas</th>
                                    <th>Skor PUMA</th>
                                    <th>Hasil PUMA</th>
                                    <th>Merokok</th>
                                    <th>Kadar CO</th>
                                    <th>Hasil Spirometri</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ppoks as $s)
                                    @php
                                        $pumaHasil = match ($s->ppok?->puma_kategori_hasil) {
                                            0 => 'Edukasi Gaya Hidup',
                                            1 => 'Risiko PPOK',
                                            default => '-',
                                        };
                                    @endphp
                                    <tr data-bulan="{{ \Carbon\Carbon::parse($s->tanggal_skrining)->format('Y-m') }}">
                                        <td>{{ \Carbon\Carbon::parse($s->tanggal_skrining)->format('d M Y') }}</td>
                                        <td>{{ $s->petugas?->nama ?? '-' }}</td>
                                        <td>{{ $s->ppok?->puma_total_skor ?? '-' }}</td>
                                        <td>
                                            @if($s->ppok?->puma_kategori_hasil !== null)
                                                <span
                                                    class="badge-status {{ $s->ppok->puma_kategori_hasil === 1 ? 'high' : 'success' }}">
                                                    {{ $pumaHasil }}
                                                </span>
                                            @else -
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($s->ppok->merokok))
                                                {{ $s->ppok->merokok ? 'Ya' : 'Tidak' }}
                                            @else -
                                            @endif
                                        </td>
                                        <td>{{ $s->ppok?->kadar_co_ppm ? $s->ppok->kadar_co_ppm . ' ppm' : '-' }}</td>
                                        <td>{{ $s->ppok?->hasil_spirometri ?? '-' }}</td>
                                        <td class="text-center aksi-col" onclick="event.stopPropagation()">
                                            <button class="btn-icon btn-detail" data-type="ppok" title="Lihat Detail"
                                                data-skrining-id="{{ $s->id_skrining }}"
                                                data-tanggal="{{ \Carbon\Carbon::parse($s->tanggal_skrining)->format('d M Y') }}"
                                                data-petugas="{{ $s->petugas?->nama ?? '-' }}"
                                                data-keluhan="{{ $s->keluhan ?? '-' }}">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <button class="btn-icon btn-pdf" data-type="ppok" title="Download PDF"
                                                data-tanggal="{{ \Carbon\Carbon::parse($s->tanggal_skrining)->format('d M Y') }}">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>

        </div>
    </main>
    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- MODAL DETAIL SKRINING (read-only)                          --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="modal-overlay" id="modal-detail-skrining">
        <div class="modal-box modal-lg">
            <div class="modal-header">
                <div class="modal-header-left">
                    <span class="modal-type-badge" id="modal-type-badge">-</span>
                    <h3 id="modal-title">Detail Skrining</h3>
                </div>
                <button class="btn-close-modal" id="btn-close-detail">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body" id="modal-body-content">
                {{-- Diisi dinamis via JS --}}
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
    <script>
    // ─────────────────────────────────────────────────────────────────────
    // Data lansia (dari Blade, diteruskan ke JS)
    // ─────────────────────────────────────────────────────────────────────
    const LANSIA = {
        id    : @json($lansia->id_lansia),
        nama  : @json($lansia->nama_lansia),
        nik   : @json($lansia->nik),
        alamat: @json($lansia->alamat ?? '-'),
        umur  : @json(\Carbon\Carbon::parse($lansia->tanggal_lahir)->age),
    };
    document.addEventListener('DOMContentLoaded', function () {

        // ─── 1. Filter Bulan ───────────────────────────────────────
        document.querySelectorAll('.filter-month').forEach(input => {
            input.addEventListener('change', function () {
                const tblId = this.dataset.target;
                const val   = this.value;
                document.querySelectorAll(`#${tblId} tbody tr`).forEach(tr => {
                    tr.style.display = (!val || tr.dataset.bulan === val) ? '' : 'none';
                });
            });
        });

        // ─── 2. Klik Baris → Buka Modal ───────────────────────────
        // Tambahkan tooltip + cursor pada setiap baris (kecuali kolom aksi)
        document.querySelectorAll('.history-table tbody tr').forEach(tr => {
            tr.style.cursor = 'pointer';
            tr.title = 'Klik untuk melihat detail';
            tr.addEventListener('click', function (e) {
                // Abaikan klik di kolom aksi
                if (e.target.closest('.aksi-col')) return;
                // Cari btn-detail di baris ini dan trigger klik
                const btn = this.querySelector('.btn-detail');
                if (btn) btn.click();
            });
        });

        // ─── 3. Modal Detail ──────────────────────────────────────
        const modal      = document.getElementById('modal-detail-skrining');
        const modalTitle = document.getElementById('modal-title');
        const modalBadge = document.getElementById('modal-type-badge');
        const modalBody  = document.getElementById('modal-body-content');

        document.querySelectorAll('.btn-detail').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                // Salin semua dataset ke objek biasa agar tidak hilang saat async
                const d = Object.assign({}, this.dataset);
                const type = d.type;

                modalTitle.textContent = `Detail Skrining – ${d.tanggal}`;

                const badgeMap = {
                    kunjungan: { label: 'Kunjungan', cls: 'badge-kunjungan' },
                    utama    : { label: 'Utama',     cls: 'badge-utama'     },
                    ppok     : { label: 'PPOK',      cls: 'badge-ppok'      },
                };
                const badge = badgeMap[type] ?? { label: type, cls: '' };
                modalBadge.textContent = badge.label;
                modalBadge.className   = `modal-type-badge ${badge.cls}`;

                if (type === 'kunjungan') {
                    modalBody.innerHTML = renderInfoUmum(d) + renderKunjungan(d);
                } else if (type === 'utama') {
                    modalBody.innerHTML = renderInfoUmum(d) + renderLoading();
                    fetchAndRenderUtama(d, modalBody);   // ✅ pass d langsung
                } else if (type === 'ppok') {
                    modalBody.innerHTML = renderInfoUmum(d) + renderLoading();
                    fetchAndRenderPpok(d, modalBody);    // ✅ pass d langsung
                } else {
                    modalBody.innerHTML = '<p>Tipe skrining tidak dikenal.</p>';
                }

                modal.classList.add('active');
            });
        });

        document.getElementById('btn-close-detail')
            ?.addEventListener('click', () => modal.classList.remove('active'));
        modal.addEventListener('click', e => {
            if (e.target === modal) modal.classList.remove('active');
        });

        // ─── 4. PDF per record ─────────────────────────────────────
        document.querySelectorAll('.btn-pdf').forEach(btn => {
            btn.addEventListener('click', async function (e) {
                e.stopPropagation();
                const row       = this.closest('tr');
                const detailBtn = row?.querySelector('.btn-detail');
                if (!detailBtn) return;

                // Salin dataset ke objek biasa
                const d    = Object.assign({}, detailBtn.dataset);
                const type = d.type;

                const typeLabel = {
                    kunjungan: 'Skrining Kunjungan',
                    utama    : 'Skrining Utama',
                    ppok     : 'Skrining PPOK'
                };

                if (type === 'kunjungan') {
                    generatePdf(typeLabel[type], d.tanggal, buildPdfKunjungan(d));
                } else if (type === 'utama' || type === 'ppok') {
                    try {
                        const endpoint = type === 'utama' ? 'skrining-utama' : 'skrining-ppok';
                        const response = await fetch(`/lansia/${LANSIA.id}/${endpoint}/${d.skriningId}`);
                        if (!response.ok) throw new Error(`HTTP ${response.status}`);
                        const apiData = await response.json();

                        // ✅ Build rows langsung dari response API (label → value)
                        const rows = [
                            ['Petugas', d.petugas || '-'],
                            ['Keluhan', d.keluhan || '-'],
                        ];
                        for (const [section, fields] of Object.entries(apiData)) {
                            rows.push([`— ${section} —`, '']);
                            fields.forEach(f => rows.push([f.label, String(f.value ?? '-')]));
                        }
                        generatePdf(typeLabel[type], d.tanggal, rows);
                    } catch (err) {
                        alert('Gagal generate PDF: ' + err.message);
                    }
                }
            });
        });
    }); // end DOMContentLoaded

    // ─── ASYNC FETCHERS (terima d sebagai parameter eksplisit) ────────────
    async function fetchAndRenderUtama(d, modalBody) {
        try {
            const res = await fetch(`/lansia/${LANSIA.id}/skrining-utama/${d.skriningId}`);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const apiData = await res.json();
            let html = '';
            for (const [section, fields] of Object.entries(apiData)) {
                html += `<div class="modal-section">
                    <h4 class="modal-section-title">${section}</h4>
                    <div class="modal-grid-2">`;
                fields.forEach(f => { html += row(f.label, f.value ?? '-'); });
                html += `</div></div>`;
            }
            modalBody.innerHTML = renderInfoUmum(d) + html;
        } catch (err) {
            modalBody.innerHTML = `<div class="alert-danger">Error: ${err.message}</div>`;
        }
    }

    async function fetchAndRenderPpok(d, modalBody) {
        try {
            const res = await fetch(`/lansia/${LANSIA.id}/skrining-ppok/${d.skriningId}`);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const apiData = await res.json();
            let html = '';
            for (const [section, fields] of Object.entries(apiData)) {
                html += `<div class="modal-section">
                    <h4 class="modal-section-title">${section}</h4>
                    <div class="modal-grid-2">`;
                fields.forEach(f => { html += row(f.label, f.value ?? '-'); });
                html += `</div></div>`;
            }
            modalBody.innerHTML = renderInfoUmum(d) + html;
        } catch (err) {
            modalBody.innerHTML = `<div class="alert-danger">Error: ${err.message}</div>`;
        }
    }

    // ─── PDF GENERATOR ────────────────────────────────────────────────────
    function generatePdf(jenisLabel, tanggal, rows) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'portrait' });
        writePdfHeader(doc, jenisLabel, tanggal);
        doc.autoTable({
            head: [['Keterangan', 'Nilai']],
            body: rows,
            startY: 78,
            styles: { fontSize: 9, cellPadding: 3 },
            headStyles: { fillColor: [37, 99, 235], textColor: 255, fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [248, 250, 252] },
            columnStyles: { 0: { fontStyle: 'bold', cellWidth: 70 } },
        });
        const fileName = `skrining_${jenisLabel.replace(/\s+/g,'_')}_${LANSIA.nama.replace(/\s+/g,'_')}_${tanggal.replace(/\s+/g,'_')}.pdf`;
        doc.save(fileName);
    }

    // ─── RENDER HELPERS ───────────────────────────────────────────────────
    function renderLoading() {
        return `<div class="modal-loading"><i class="fa-solid fa-spinner fa-spin"></i> Memuat data...</div>`;
    }
    function renderInfoUmum(d) {
        return `<div class="modal-section-plain">
            ${row('Tanggal', d.tanggal || '-')}
            ${row('Petugas', d.petugas || '-')}
            ${row('Keluhan', d.keluhan || '-')}
        </div>`;
    }
    function renderKunjungan(d) {
        return `<div class="modal-section">
            <h4 class="modal-section-title"><i class="fa-solid fa-heart-pulse"></i> Data Kunjungan</h4>
            <div class="modal-grid-2">
                ${row('Tensi Sistolik',     d.sistolik  ? d.sistolik  + ' mmHg' : '-')}
                ${row('Tensi Diastolik',    d.diastolik ? d.diastolik + ' mmHg' : '-')}
                ${row('Berat Badan',        d.bb        ? d.bb        + ' kg'   : '-')}
                ${row('Tinggi Badan',       d.tb        ? d.tb        + ' cm'   : '-')}
                ${row('Lingkar Perut',      d.lp        ? d.lp        + ' cm'   : '-')}
                ${row('Keluhan Kunjungan',  d.keluhanKunjungan || '-')}
            </div>
        </div>`;
    }
    function buildPdfKunjungan(d) {
        return [
            ['Petugas',           d.petugas    || '-'],
            ['Keluhan',           d.keluhan    || '-'],
            ['— Data Kunjungan —',''],
            ['Tensi Sistolik',    d.sistolik   ? d.sistolik  + ' mmHg' : '-'],
            ['Tensi Diastolik',   d.diastolik  ? d.diastolik + ' mmHg' : '-'],
            ['Berat Badan',       d.bb         ? d.bb        + ' kg'   : '-'],
            ['Tinggi Badan',      d.tb         ? d.tb        + ' cm'   : '-'],
            ['Lingkar Perut',     d.lp         ? d.lp        + ' cm'   : '-'],
            ['Keluhan Kunjungan', d.keluhanKunjungan || '-'],
        ];
    }
    function row(label, value) {
        return `<div class="modal-info-row">
            <span class="modal-label">${label}</span>
            <span>${value ?? '-'}</span>
        </div>`;
    }
    function writePdfHeader(doc, jenisLabel, tanggal) {
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(16);
        doc.text('SIMPEL – Posyandu Lansia', 14, 18);
        doc.setFontSize(12);
        doc.text(jenisLabel, 14, 26);
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(10);
        doc.text(`Nama    : ${LANSIA.nama}`,        14, 34);
        doc.text(`NIK     : ${LANSIA.nik}`,         14, 40);
        doc.text(`Umur    : ${LANSIA.umur} Tahun`,  14, 46);
        doc.text(`Alamat  : ${LANSIA.alamat}`,       14, 52);
        doc.text(`Tanggal : ${tanggal}`,             14, 58);
        doc.text(`Dicetak : ${new Date().toLocaleDateString('id-ID', { dateStyle: 'long' })}`, 14, 64);
        doc.line(14, 68, 196, 68);
    }
    </script>
@endpush