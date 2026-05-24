@extends('layout.sidebar')

@push('styles')
    @vite(['resources/css/app.css', 'resources/css/cssAdmin/profil_lengkap.css', 'resources/css/cssAdmin/monitoring.css', 'resources/css/cssAdmin/histori_skrining.css'])
        {{-- Jika Anda menaruh CSS modal modern di file terpisah, tambahkan juga --}}
        {{-- <link rel="stylesheet" href="{{ asset('css/modal-modern.css') }}"> --}}
@endpush

@section('title', 'Histori Skrining – ' . $lansia->nama_lansia)

@section('content')
    <main class="main-content">
        <div class="container">

            {{-- BREADCRUMB & HEADER --}}
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

            {{-- INFO SINGKAT LANSIA (sama seperti monitoring) --}}
            <div class="mon-profile-card" style="margin-bottom: 1.5rem;">
                <div class="mon-avatar">{{ strtoupper(substr($lansia->nama_lansia, 0, 2)) }}</div>
                <div class="mon-profile-info">
                    <h2 class="mon-profile-name">{{ $lansia->nama_lansia }}</h2>
                    <div class="mon-profile-meta">
                        <span><i class="fa-solid fa-id-card"></i> {{ $lansia->nik }}</span>
                        <span><i class="fa-solid fa-cake-candles"></i>
                            {{ \Carbon\Carbon::parse($lansia->tanggal_lahir)->age }} tahun
                        </span>
                        <span>
                            <i class="fa-solid fa-venus-mars"></i>
                            {{ $lansia->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                        <span>
                            <i class="fa-solid fa-ruler-vertical"></i>
                            Tinggi {{ $tinggiBadanTerakhir ? $tinggiBadanTerakhir . ' cm' : '-' }}
                        </span>
                        @if($lansia->no_hp)
                            <span><i class="fa-solid fa-phone"></i> {{ $lansia->no_hp }}</span>
                        @endif
                        @if($lansia->riwayat_penyakit)
                            <span class="mon-riwayat">
                                <i class="fa-solid fa-notes-medical"></i> {{ $lansia->riwayat_penyakit }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- TABEL 1 – SKRINING KUNJUNGAN --}}
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
                                <tr><th>Tanggal</th><th>Petugas</th><th>Tensi</th><th>Berat Badan</th><th>Tinggi Badan</th><th>Lingkar Perut</th><th>Keluhan</th><th>Diagnosis</th><th class="text-center">Aksi</th></tr>
                            </thead>
                            <tbody>
                                @foreach($kunjungans as $s)
                                    <tr
                                        class="table-row selectable-row"
                                        title="Klik untuk melihat detail lansia"
                                        data-bulan="{{ \Carbon\Carbon::parse($s->tanggal_skrining)->format('Y-m') }}"
                                        data-type="kunjungan"
                                        data-skrining-id="{{ $s->id_skrining }}"
                                        data-tanggal="{{ \Carbon\Carbon::parse($s->tanggal_skrining)->format('d M Y') }}"
                                        data-petugas="{{ $s->petugas?->nama ?? '-' }}"
                                        data-keluhan="{{ $s->keluhan ?? '-' }}"
                                        data-sistolik="{{ $s->kunjungan?->td_sistolik ?? '' }}"
                                        data-diastolik="{{ $s->kunjungan?->td_diastolik ?? '' }}"
                                        data-bb="{{ $s->kunjungan?->berat_badan ?? '' }}"
                                        data-tb="{{ $s->kunjungan?->tinggi_badan ?? '' }}"
                                        data-lp="{{ $s->kunjungan?->lingkar_perut ?? '' }}"
                                        data-keluhan-kunjungan="{{ $s->kunjungan?->keluhan ?? '' }}"
                                        data-diagnosis="{{ $s->kunjungan?->diagnosis ?? '' }}"
                                    >
                                        <td>{{ \Carbon\Carbon::parse($s->tanggal_skrining)->format('d M Y') }}</td>
                                        <td>{{ $s->petugas?->nama ?? '-' }}</td>
                                        <td>@if($s->kunjungan?->td_sistolik)<span class="val-badge red">{{ $s->kunjungan->td_sistolik }}/{{ $s->kunjungan->td_diastolik }} mmHg</span>@else-@endif</td>
                                        <td>{{ $s->kunjungan?->berat_badan ? $s->kunjungan->berat_badan . ' kg' : '-' }}</td>
                                        <td>{{ $s->kunjungan?->tinggi_badan ? $s->kunjungan->tinggi_badan . ' cm' : '-' }}</td>
                                        <td>{{ $s->kunjungan?->lingkar_perut ? $s->kunjungan->lingkar_perut . ' cm' : '-' }}</td>
                                        <td>{{ $s->kunjungan?->keluhan ?? $s->keluhan ?? '-' }}</td>
                                        <td>{{ $s->kunjungan?->diagnosis ?? '-' }}</td>
                                        <td class="text-center aksi-col">
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

            {{-- TABEL 2 – SKRINING UTAMA --}}
            <section class="card history-section">
                <div class="history-header">
                    <div class="history-title">
                        <div class="history-icon blue"><i class="fa-solid fa-vial"></i></div>
                        <div><h3>Skrining Utama</h3><p>Gula darah, kolesterol, IMT, SRQ-20, dan faktor risiko lainnya</p></div>
                    </div>
                    <div class="history-actions"><input type="month" class="filter-month" data-target="tbl-utama" title="Filter bulan"></div>
                </div>
                @if($utamas->isEmpty())
                    <div class="empty-state">Belum ada data skrining utama.</div>
                @else
                    <div class="table-scroll">
                        <table class="history-table" id="tbl-utama">
                            <thead><tr><th>Tanggal</th><th>Petugas</th><th>Gula Darah</th><th>Kategori GD</th><th>Kolesterol</th><th>Kategori Kol.</th><th>IMT</th><th>SRQ-20</th><th class="text-center">Aksi</th></tr></thead>
                            <tbody>
                                @php $katLabel = [1 => 'Normal', 2 => 'Waspada', 3 => 'Tinggi'];
                                $katClass = [1 => 'success', 2 => 'warning', 3 => 'high']; @endphp
                                @foreach($utamas as $s)
                                    <tr
                                        class="table-row selectable-row"
                                        title="Klik untuk melihat ringkasan detail"
                                        data-bulan="{{ \Carbon\Carbon::parse($s->tanggal_skrining)->format('Y-m') }}"
                                        data-type="utama"
                                        data-skrining-id="{{ $s->id_skrining }}"
                                        data-tanggal="{{ \Carbon\Carbon::parse($s->tanggal_skrining)->format('d M Y') }}"
                                        data-petugas="{{ $s->petugas?->nama ?? '-' }}"
                                        data-keluhan="{{ $s->keluhan ?? '-' }}"
                                    >
                                        <td>{{ \Carbon\Carbon::parse($s->tanggal_skrining)->format('d M Y') }}</td>
                                        <td>{{ $s->petugas?->nama ?? '-' }}</td>
                                        <td>{{ $s->utama?->gula_darah ? $s->utama->gula_darah . ' mg/dL' : '-' }}</td>
                                        <td>@if($s->utama?->gula_darah_kategori)<span class="badge-status {{ $katClass[$s->utama->gula_darah_kategori] ?? 'muted' }}">{{ $katLabel[$s->utama->gula_darah_kategori] ?? '-' }}</span>@else-@endif</td>
                                        <td>{{ $s->utama?->kolesterol ? $s->utama->kolesterol . ' mg/dL' : '-' }}</td>
                                        <td>@if($s->utama?->kolesterol_kategori)<span class="badge-status {{ $katClass[$s->utama->kolesterol_kategori] ?? 'muted' }}">{{ $katLabel[$s->utama->kolesterol_kategori] ?? '-' }}</span>@else-@endif</td>
                                        <td>{{ $s->utama?->imt ?? '-' }}</td>
                                        <td>{{ $s->utama?->srq_total ?? '-' }}</td>
                                        <td class="text-center aksi-col">
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

            {{-- TABEL 3 – SKRINING PPOK --}}
            <section class="card history-section">
                <div class="history-header">
                    <div class="history-title">
                        <div class="history-icon green"><i class="fa-solid fa-lungs"></i></div>
                        <div><h3>Skrining PPOK</h3><p>PUMA score, spirometri, dan faktor risiko paru</p></div>
                    </div>
                    <div class="history-actions"><input type="month" class="filter-month" data-target="tbl-ppok" title="Filter bulan"></div>
                </div>
                @if($ppoks->isEmpty())
                    <div class="empty-state">Belum ada data skrining PPOK.</div>
                @else
                    <div class="table-scroll">
                        <table class="history-table" id="tbl-ppok">
                            <thead><tr><th>Tanggal</th><th>Petugas</th><th>Skor PUMA</th><th>Hasil PUMA</th><th>Merokok</th><th>Kadar CO</th><th>Hasil Spirometri</th><th class="text-center">Aksi</th></tr></thead>
                            <tbody>
                                @foreach($ppoks as $s)
                                    @php $pumaHasil = match ($s->ppok?->puma_kategori_hasil) { 0 => 'Edukasi Gaya Hidup', 1 => 'Risiko PPOK', default => '-', }; @endphp
                                    <tr
                                        class="table-row selectable-row"
                                        title="Klik untuk melihat ringkasan detail"
                                        data-bulan="{{ \Carbon\Carbon::parse($s->tanggal_skrining)->format('Y-m') }}"
                                        data-type="ppok"
                                        data-skrining-id="{{ $s->id_skrining }}"
                                        data-tanggal="{{ \Carbon\Carbon::parse($s->tanggal_skrining)->format('d M Y') }}"
                                        data-petugas="{{ $s->petugas?->nama ?? '-' }}"
                                        data-keluhan="{{ $s->keluhan ?? '-' }}"
                                    >
                                        <td>{{ \Carbon\Carbon::parse($s->tanggal_skrining)->format('d M Y') }}</td>
                                        <td>{{ $s->petugas?->nama ?? '-' }}</td>
                                        <td>{{ $s->ppok?->puma_total_skor ?? '-' }}</td>
                                        <td>@if($s->ppok?->puma_kategori_hasil !== null)<span class="badge-status {{ $s->ppok->puma_kategori_hasil === 1 ? 'high' : 'success' }}">{{ $pumaHasil }}</span>@else-@endif</td>
                                        <td>@if(isset($s->ppok->merokok)) {{ $s->ppok->merokok ? 'Ya' : 'Tidak' }} @else - @endif</td>
                                        <td>{{ $s->ppok?->kadar_co_ppm ? $s->ppok->kadar_co_ppm . ' ppm' : '-' }}</td>
                                        <td>{{ $s->ppok?->hasil_spirometri ?? '-' }}</td>
                                        <td class="text-center aksi-col">
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

    {{-- MODAL DETAIL MODERN --}}
    <div class="modal-overlay" id="modal-detail-skrining">
        <div class="modal-box modern-modal">
            <div class="modal-modern-header">
                <div class="header-left">
                    <div class="health-icon-lg" id="modal-icon">❤️</div>
                    <div class="header-title">
                        <h3 id="modal-title">Detail Skrining</h3>
                        <div class="skrining-date" id="modal-date">-</div>
                    </div>
                    <span class="badge-modern" id="modal-type-badge">-</span>
                </div>
                <button class="btn-close-modern" id="btn-close-detail">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-modern-body" id="modal-body-content">
                {{-- Dinamis via JS --}}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
    <script>
        const LANSIA = {
            id: @json($lansia->id_lansia),
            nama: @json($lansia->nama_lansia),
            nik: @json($lansia->nik),
            alamat: @json($lansia->alamat ?? '-'),
            umur: @json(\Carbon\Carbon::parse($lansia->tanggal_lahir)->age),
        };

        // ========== FORMAT VALUE (array / JSON) ==========
        function formatValue(value) {
            if (value === null || value === undefined || value === '') return '-';
            if (Array.isArray(value)) return value.join(', ');
            if (typeof value === 'string' && value.startsWith('[') && value.endsWith(']')) {
                try {
                    const parsed = JSON.parse(value);
                    if (Array.isArray(parsed)) return parsed.join(', ');
                } catch(e) {}
            }
            return value;
        }

        // ========== TENTUKAN WARNA BERDASARKAN KATEGORI ==========
        function getColorForKategori(kategori) {
            if (!kategori) return 'black';
            const k = String(kategori).toLowerCase();
            if (k.includes('normal')) return '#10b981'; // hijau
            if (k.includes('waspada')) return '#f59e0b'; // kuning
            if (k.includes('tinggi') || k.includes('risiko')) return '#ef4444'; // merah
            return 'black';
        }

        function getColorForTensi(sistolik) {
            const s = parseInt(sistolik);
            if (isNaN(s)) return 'black';
            if (s >= 140) return '#ef4444';
            if (s >= 120) return '#f59e0b';
            return '#10b981';
        }

        // ========== RENDER ROW DENGAN WARNA (opsional) ==========
        function renderInfoRow(label, value, color = 'black') {
            const formatted = formatValue(value);
            const display = formatted !== '-' ? formatted : '-';
            return `<div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.7rem; text-transform: uppercase; font-weight: 600; color: #64748b;">${label}</div>
                        <div style="font-size: 1rem; color: ${color}; font-weight: 500;">${display}</div>
                    </div>`;
        }

        function renderMetric(label, value, unit = '', color = 'black') {
            const formatted = formatValue(value);
            const display = formatted !== '-' ? `${formatted} ${unit}` : '-';
            return `<div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.7rem; text-transform: uppercase; font-weight: 600; color: #64748b;">${label}</div>
                        <div style="font-size: 1rem; color: ${color}; font-weight: 500;">${display}</div>
                    </div>`;
        }

        // ========== RENDER MODAL ==========
        function renderInfoUmum(d) {
            return `<div class="health-card" style="background: white; border-radius: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); margin-bottom: 1.75rem;">
                        <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #f8fafc;">
                            <h4 style="margin:0; font-size:1.125rem; font-weight:600;">Informasi Umum</h4>
                        </div>
                        <div style="padding:1.5rem; display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:1rem;">
                            ${renderInfoRow('Tanggal Skrining', d.tanggal)}
                            ${renderInfoRow('Petugas', d.petugas)}
                            ${renderInfoRow('Keluhan', d.keluhan || '-')}
                        </div>
                    </div>`;
        }

        function renderKunjungan(d) {
            const tensiColor = getColorForTensi(d.sistolik);
            const tensiDisplay = (d.sistolik && d.diastolik) ? `${d.sistolik}/${d.diastolik}` : '-';
            return `<div class="health-card" style="background: white; border-radius: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); margin-bottom: 1.75rem;">
                        <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #f8fafc;">
                            <h4 style="margin:0; font-size:1.125rem; font-weight:600;">Pemeriksaan Fisik & Antropometri</h4>
                        </div>
                        <div style="padding:1.5rem; display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:1rem;">
                            ${renderMetric('Tekanan Darah', tensiDisplay, 'mmHg', tensiColor)}
                            ${renderMetric('Berat Badan', d.bb, 'kg')}
                            ${renderMetric('Tinggi Badan', d.tb, 'cm')}
                            ${renderMetric('Lingkar Perut', d.lp, 'cm')}
                            ${renderInfoRow('Keluhan Kunjungan', d.keluhanKunjungan)}
                            ${renderInfoRow('Diagnosis', d.diagnosis)}
                        </div>
                    </div>`;
        }

        async function fetchAndRenderUtama(d, modalBody) {
            try {
                const res = await fetch(`/lansia/${LANSIA.id}/skrining-utama/${d.skriningId}`);
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                const data = await res.json();
                let html = renderInfoUmum(d);
                for (const [section, fields] of Object.entries(data)) {
                    html += `<div class="health-card" style="background:white; border-radius:24px; box-shadow:0 4px 20px rgba(0,0,0,0.05); margin-bottom:1.75rem;">
                                <div style="padding:1rem 1.5rem; border-bottom:1px solid #f1f5f9; background:#f8fafc;">
                                    <h4 style="margin:0;">${section}</h4>
                                </div>
                                <div style="padding:1.5rem; display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:1rem;">`;
                    fields.forEach(f => {
                        let color = 'black';
                        const labelLow = f.label.toLowerCase();
                        if (labelLow.includes('gula darah') || labelLow.includes('kolesterol')) {
                            const kategori = fields.find(f2 => f2.label.toLowerCase().includes('kategori'))?.value;
                            if (kategori) color = getColorForKategori(kategori);
                        }
                        html += renderInfoRow(f.label, f.value, color);
                    });
                    html += `</div></div>`;
                }
                modalBody.innerHTML = html;
            } catch (err) {
                modalBody.innerHTML = `<div style="background:#fef2f2; border-left:4px solid #dc2626; padding:1rem;">Error: ${err.message}</div>`;
            }
        }

        async function fetchAndRenderPpok(d, modalBody) {
            try {
                const res = await fetch(`/lansia/${LANSIA.id}/skrining-ppok/${d.skriningId}`);
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                const data = await res.json();
                let html = renderInfoUmum(d);
                for (const [section, fields] of Object.entries(data)) {
                    html += `<div class="health-card" style="background:white; border-radius:24px; box-shadow:0 4px 20px rgba(0,0,0,0.05); margin-bottom:1.75rem;">
                                <div style="padding:1rem 1.5rem; border-bottom:1px solid #f1f5f9; background:#f8fafc;">
                                    <h4 style="margin:0;">${section}</h4>
                                </div>
                                <div style="padding:1.5rem; display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:1rem;">`;
                    fields.forEach(f => {
                        let color = 'black';
                        if (f.label.toLowerCase().includes('risiko') || f.label.toLowerCase().includes('hasil puma')) {
                            const val = String(f.value).toLowerCase();
                            if (val.includes('risiko')) color = '#ef4444';
                            else if (val.includes('edukasi')) color = '#10b981';
                        }
                        html += renderInfoRow(f.label, f.value, color);
                    });
                    html += `</div></div>`;
                }
                modalBody.innerHTML = html;
            } catch (err) {
                modalBody.innerHTML = `<div style="background:#fef2f2; border-left:4px solid #dc2626; padding:1rem;">Error: ${err.message}</div>`;
            }
        }

        // ========== PDF GENERATOR (tetap polos) ==========
        function sanitizePdfText(str) {
            if (!str) return '-';
            return String(str).replace(/[^\x00-\xFF]/g, '?');
        }

        async function generatePdf(jenisLabel, tanggal, rows, type, detailData = null) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'portrait' });
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(16);
            doc.text('SIMPEL – Posyandu Lansia', 14, 18);
            doc.setFontSize(12);
            doc.text(jenisLabel, 14, 26);
            doc.setFont('helvetica', 'normal');
            doc.setFontSize(10);
            doc.text(`Nama    : ${sanitizePdfText(LANSIA.nama)}`, 14, 34);
            doc.text(`NIK     : ${sanitizePdfText(LANSIA.nik)}`, 14, 40);
            doc.text(`Umur    : ${LANSIA.umur} Tahun`, 14, 46);
            doc.text(`Alamat  : ${sanitizePdfText(LANSIA.alamat)}`, 14, 52);
            doc.text(`Tanggal : ${sanitizePdfText(tanggal)}`, 14, 58);
            doc.text(`Dicetak : ${new Date().toLocaleDateString('id-ID')}`, 14, 64);
            doc.line(14, 68, 196, 68);

            let bodyRows = [];
            if (type === 'kunjungan') {
                bodyRows = [
                    ['Informasi Umum', ''],
                    ['Tanggal Skrining', sanitizePdfText(detailData.tanggal)],
                    ['Petugas', sanitizePdfText(detailData.petugas)],
                    ['Keluhan', sanitizePdfText(detailData.keluhan)],
                    ['— Pemeriksaan Fisik —', ''],
                    ['Tekanan Darah', (detailData.sistolik && detailData.diastolik) ? `${detailData.sistolik}/${detailData.diastolik} mmHg` : '-'],
                    ['Berat Badan', detailData.bb ? `${detailData.bb} kg` : '-'],
                    ['Tinggi Badan', detailData.tb ? `${detailData.tb} cm` : '-'],
                    ['Lingkar Perut', detailData.lp ? `${detailData.lp} cm` : '-'],
                    ['Keluhan Kunjungan', sanitizePdfText(detailData.keluhanKunjungan)],
                    ['Diagnosis', sanitizePdfText(detailData.diagnosis)],
                ];
            } else {
                bodyRows = rows;
            }
            doc.autoTable({
                head: [['Keterangan', 'Nilai']],
                body: bodyRows,
                startY: 78,
                styles: { fontSize: 9, cellPadding: 3 },
                headStyles: { fillColor: [37, 99, 235], textColor: 255, fontStyle: 'bold' },
                alternateRowStyles: { fillColor: [248, 250, 252] },
                columnStyles: { 0: { fontStyle: 'bold', cellWidth: 70 } },
            });
            doc.save(`skrining_${jenisLabel.replace(/\s+/g, '_')}_${LANSIA.nama.replace(/\s+/g, '_')}_${tanggal.replace(/\s+/g, '_')}.pdf`);
        }

        // ========== EVENT LISTENER ==========
        document.addEventListener('DOMContentLoaded', function () {
            // Filter bulan
            document.querySelectorAll('.filter-month').forEach(input => {
                input.addEventListener('change', function () {
                    const tblId = this.dataset.target;
                    const val = this.value;
                    document.querySelectorAll(`#${tblId} tbody tr`).forEach(tr => {
                        tr.style.display = (!val || tr.dataset.bulan === val) ? '' : 'none';
                    });
                });
            });

            const modal = document.getElementById('modal-detail-skrining');
            const modalTitle = document.getElementById('modal-title');
            const modalDate = document.getElementById('modal-date');
            const modalBadge = document.getElementById('modal-type-badge');
            const modalIcon = document.getElementById('modal-icon');
            const modalBody = document.getElementById('modal-body-content');

            if (modalIcon) modalIcon.style.display = 'none';

            // Klik baris buka modal
            document.querySelectorAll('.history-table tbody tr').forEach(tr => {
                tr.style.cursor = 'pointer';
                tr.addEventListener('click', function (e) {
                    if (e.target.closest('.aksi-col')) return;
                    const d = Object.assign({}, this.dataset);
                    const type = d.type;
                    modalTitle.textContent = 'Detail Skrining';
                    modalDate.textContent = d.tanggal || '-';
                    const badgeMap = { kunjungan: 'Skrining Kunjungan', utama: 'Skrining Utama', ppok: 'Skrining PPOK' };
                    modalBadge.textContent = badgeMap[type] || type;
                    modalBadge.className = `badge-modern ${type}`;

                    if (type === 'kunjungan') {
                        modalBody.innerHTML = renderInfoUmum(d) + renderKunjungan(d);
                    } else if (type === 'utama') {
                        modalBody.innerHTML = renderInfoUmum(d) + '<div style="text-align:center; padding:2rem;"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</div>';
                        fetchAndRenderUtama(d, modalBody);
                    } else if (type === 'ppok') {
                        modalBody.innerHTML = renderInfoUmum(d) + '<div style="text-align:center; padding:2rem;"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</div>';
                        fetchAndRenderPpok(d, modalBody);
                    }
                    modal.classList.add('active');
                });
            });

            document.getElementById('btn-close-detail')?.addEventListener('click', () => modal.classList.remove('active'));
            modal.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('active'); });

            // PDF
            document.querySelectorAll('.btn-pdf').forEach(btn => {
                btn.addEventListener('click', async function (e) {
                    e.stopPropagation();
                    const row = this.closest('tr');
                    if (!row) return;
                    const d = Object.assign({}, row.dataset);
                    const type = d.type;
                    const typeLabel = { kunjungan: 'Skrining Kunjungan', utama: 'Skrining Utama', ppok: 'Skrining PPOK' };

                    if (type === 'kunjungan') {
                        generatePdf(typeLabel[type], d.tanggal, null, 'kunjungan', d);
                    } else {
                        try {
                            const endpoint = type === 'utama' ? 'skrining-utama' : 'skrining-ppok';
                            const res = await fetch(`/lansia/${LANSIA.id}/${endpoint}/${d.skriningId}`);
                            if (!res.ok) throw new Error(`HTTP ${res.status}`);
                            const apiData = await res.json();
                            let rows = [
                                ['Informasi Umum', ''],
                                ['Tanggal Skrining', sanitizePdfText(d.tanggal)],
                                ['Petugas', sanitizePdfText(d.petugas)],
                                ['Keluhan', sanitizePdfText(d.keluhan)],
                            ];
                            for (const [section, fields] of Object.entries(apiData)) {
                                rows.push([`-- ${section} --`, '']);
                                fields.forEach(f => {
                                    rows.push([sanitizePdfText(f.label), sanitizePdfText(formatValue(f.value))]);
                                });
                            }
                            generatePdf(typeLabel[type], d.tanggal, rows, 'other');
                        } catch (err) {
                            alert('Gagal generate PDF: ' + err.message);
                        }
                    }
                });
            });
        });
    </script>

@endpush