@extends('layout.sidebar')

@section('title', 'Manajemen Konten')

@push('styles')
<style>
    .konten-container {
        padding: 2rem;
        background: #f8fafc;
        min-height: 100vh;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-title h1 {
        font-size: 1.875rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .page-title p {
        color: #64748b;
        margin-top: 0.25rem;
        margin-bottom: 0;
    }

    .btn-add {
        background: #2563eb;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        transition: all 0.2s;
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
        white-space: nowrap;
    }

    .btn-add:hover {
        background: #1d4ed8;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
    }

    .konten-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .konten-card {
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .konten-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
    }

    /* Bagian media bisa diklik */
    .card-media {
        height: 180px;
        background: #e2e8f0;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .card-media img,
    .card-media video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        pointer-events: none;
    }

    .card-media-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.25s;
    }

    .card-media:hover .card-media-overlay {
        background: rgba(0,0,0,0.35);
    }

    .card-media-overlay .overlay-icon {
        color: white;
        font-size: 2.5rem;
        opacity: 0;
        transform: scale(0.7);
        transition: opacity 0.25s, transform 0.25s;
        text-shadow: 0 4px 12px rgba(0,0,0,0.4);
    }

    .card-media:hover .card-media-overlay .overlay-icon {
        opacity: 1;
        transform: scale(1);
    }

    /* Badge play untuk video */
    .video-play-badge {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        text-shadow: 0 4px 12px rgba(0,0,0,0.3);
        pointer-events: none;
    }

    .card-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.35rem 0.85rem;
        border-radius: 0.75rem;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        backdrop-filter: blur(8px);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        pointer-events: none;
        z-index: 2;
    }

    .badge-fisioterapi { background: rgba(37,99,235,0.9); color: white; }
    .badge-gizi        { background: rgba(22,163,74,0.9); color: white; }
    .badge-senam       { background: rgba(234,179,8,0.9); color: white; }
    .badge-ptm         { background: rgba(220,38,38,0.9); color: white; }
    .badge-jiwa        { background: rgba(147,51,234,0.9); color: white; }
    .badge-lainnya     { background: rgba(14,165,233,0.9); color: white; }
    .badge-default     { background: rgba(100,116,139,0.9); color: white; }

    /* Artikel placeholder */
    .artikel-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #2563eb;
        cursor: pointer;
    }

    .artikel-placeholder i { font-size: 3rem; opacity: 0.7; }
    .artikel-placeholder span { font-size: 0.75rem; font-weight: 700; margin-top: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.7; }

    .card-content {
        padding: 1.5rem;
        flex-grow: 1;
    }

    .card-content h3 {
        font-size: 1.125rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.75rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .card-content p {
        color: #475569;
        font-size: 0.875rem;
        line-height: 1.6;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .card-footer {
        padding: 1rem 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-actions { display: flex; gap: 0.5rem; }

    .btn-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.75rem;
        transition: all 0.2s;
        cursor: pointer;
        border: none;
        font-size: 0.9rem;
    }

    .btn-edit { background: #eff6ff; color: #2563eb; text-decoration: none; }
    .btn-edit:hover { background: #2563eb; color: white; }
    .btn-delete { background: #fff1f2; color: #e11d48; }
    .btn-delete:hover { background: #e11d48; color: white; }

    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        background: white;
        border-radius: 1.5rem;
        grid-column: 1 / -1;
        border: 2px dashed #e2e8f0;
    }

    /* ===================== MODAL ===================== */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(6px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .modal-overlay.active { display: flex; }

    .modal-box {
        background: white;
        border-radius: 1.25rem;
        width: 100%;
        max-width: 800px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
        animation: modalIn 0.25s ease;
    }

    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.93) translateY(20px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    .modal-header {
        padding: 1.5rem 1.5rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
        border-radius: 1.25rem 1.25rem 0 0;
    }

    .modal-header h2 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        line-height: 1.4;
    }

    .modal-close {
        background: #f1f5f9;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        flex-shrink: 0;
        font-size: 1rem;
        transition: all 0.2s;
    }

    .modal-close:hover { background: #e11d48; color: white; }

    .modal-body { padding: 1.5rem; }

    .modal-body img {
        width: 100%;
        border-radius: 0.75rem;
        max-height: 500px;
        object-fit: contain;
        background: #f8fafc;
    }

    .modal-body video {
        width: 100%;
        border-radius: 0.75rem;
        max-height: 450px;
        background: #000;
    }

    .modal-meta {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }

    .modal-meta span {
        padding: 0.25rem 0.75rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .meta-tipe { background: #f1f5f9; color: #475569; }
    .meta-kategori { background: #eff6ff; color: #2563eb; }

    .modal-deskripsi {
        margin-top: 1rem;
        color: #334155;
        font-size: 0.9rem;
        line-height: 1.8;
        border-top: 1px solid #f1f5f9;
        padding-top: 1rem;
    }

    /* CKEditor content styling di modal */
    .ck-content-preview h1, .ck-content-preview h2, .ck-content-preview h3 {
        font-weight: 700; color: #0f172a; margin: 1rem 0 0.5rem;
    }
    .ck-content-preview h1 { font-size: 1.5rem; }
    .ck-content-preview h2 { font-size: 1.25rem; }
    .ck-content-preview h3 { font-size: 1.1rem; }
    .ck-content-preview p  { margin-bottom: 0.75rem; }
    .ck-content-preview ul, .ck-content-preview ol { padding-left: 1.5rem; margin-bottom: 0.75rem; }
    .ck-content-preview strong { font-weight: 700; }
    .ck-content-preview em { font-style: italic; }
    .ck-content-preview a { color: #2563eb; }
    .ck-content-preview blockquote {
        border-left: 4px solid #2563eb;
        padding: 0.5rem 1rem;
        background: #f0f7ff;
        border-radius: 0 0.5rem 0.5rem 0;
        margin: 0.75rem 0;
    }
</style>
@endpush

@section('content')
<div class="konten-container">
    <div class="page-header">
        <div class="page-title">
            <h1>Manajemen Konten</h1>
            <p>Kelola konten edukasi, video senam, dan informasi gizi untuk lansia.</p>
        </div>
        <a href="{{ route('konten.create') }}" class="btn-add">
            <i class="fa-solid fa-plus"></i>
            Tambah Konten
        </a>
    </div>

    @if(session('success'))
        <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:0.875rem 1.25rem; border-radius:0.75rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:0.75rem;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="konten-grid">
        @php
            $categories = [1=>'Fisioterapi', 2=>'Gizi', 3=>'Senam', 4=>'Edukasi PTM', 5=>'Jiwa', 6=>'Lainnya'];
            $types      = [1=>'Video', 2=>'Gambar', 3=>'Artikel'];
            $catClasses = [1=>'badge-fisioterapi', 2=>'badge-gizi', 3=>'badge-senam', 4=>'badge-ptm', 5=>'badge-jiwa', 6=>'badge-lainnya'];
        @endphp

        @forelse($konten as $item)
            <div class="konten-card">
                {{-- Media area: klik buka modal --}}
                <div class="card-media" onclick="openModal({{ $item->id_konten }})">
                    @if($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}">
                        <div class="card-media-overlay">
                            <i class="fa-solid fa-magnifying-glass-plus overlay-icon"></i>
                        </div>
                    @elseif($item->video)
                        <video src="{{ asset('storage/' . $item->video) }}" muted preload="metadata"></video>
                        <div class="video-play-badge">
                            <i class="fa-solid fa-circle-play"></i>
                        </div>
                        <div class="card-media-overlay">
                            <i class="fa-solid fa-play overlay-icon"></i>
                        </div>
                    @elseif((int)$item->tipe_konten === 3)
                        <div class="artikel-placeholder">
                            <i class="fa-solid fa-file-lines"></i>
                            <span>Artikel</span>
                        </div>
                        <div class="card-media-overlay">
                            <i class="fa-solid fa-book-open overlay-icon"></i>
                        </div>
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,#f1f5f9 0%,#e2e8f0 100%); color:#94a3b8;">
                            <i class="fa-solid fa-file-lines fa-4x" style="opacity:0.5;"></i>
                        </div>
                    @endif

                    <div class="card-badge {{ $catClasses[$item->kategori_konten] ?? 'badge-default' }}">
                        {{ $categories[$item->kategori_konten] ?? 'Lainnya' }}
                    </div>
                </div>

                <div class="card-content">
                    <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.75rem;">
                        <span style="padding:0.2rem 0.5rem; background:#f1f5f9; border-radius:4px; font-size:0.65rem; color:#64748b; font-weight:800; text-transform:uppercase;">
                            {{ $types[$item->tipe_konten] ?? 'Konten' }}
                        </span>
                        <span style="font-size:0.75rem; color:#94a3b8;">
                            <i class="fa-regular fa-clock"></i>
                            {{ $item->created_at ? $item->created_at->diffForHumans() : '-' }}
                        </span>
                    </div>
                    <h3>{{ $item->judul }}</h3>
                    <p>{{ $item->deskripsi ? strip_tags($item->deskripsi) : '-' }}</p>
                </div>

                <div class="card-footer">
                    <span style="font-size:0.75rem; color:#64748b; font-weight:500;">
                        <i class="fa-regular fa-calendar-alt"></i>
                        {{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}
                    </span>
                    <div class="card-actions">
                        <a href="{{ route('konten.edit', $item->id_konten) }}" class="btn-icon btn-edit" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('konten.destroy', $item->id_konten) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus konten ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-delete" title="Hapus">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fa-solid fa-box-open" style="font-size:5rem; color:#e2e8f0;"></i>
                <h2 style="font-weight:700; color:#1e293b; margin:1rem 0 0.5rem;">Konten Masih Kosong</h2>
                <p style="color:#64748b; margin-bottom:2rem;">Belum ada konten edukasi yang ditambahkan ke sistem.</p>
                <a href="{{ route('konten.create') }}" class="btn-add" style="display:inline-flex; width:auto;">
                    <i class="fa-solid fa-plus"></i> Tambah Sekarang
                </a>
            </div>
        @endforelse
    </div>
</div>

{{-- ==================== MODAL PREVIEW ==================== --}}
<div class="modal-overlay" id="previewModal" onclick="closeModalOutside(event)">
    <div class="modal-box" id="modalBox">
        <div class="modal-header">
            <h2 id="modalTitle">-</h2>
            <button class="modal-close" onclick="closeModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body" id="modalBody">
            {{-- Diisi oleh JS --}}
        </div>
    </div>
</div>

{{-- Data konten untuk modal (JSON) --}}
<script>
    const kontenData = @json($kontenJson);

    const categories = { 1:'Fisioterapi', 2:'Gizi', 3:'Senam', 4:'Edukasi PTM', 5:'Jiwa', 6:'Lainnya' };
    const types      = { 1:'Video', 2:'Gambar', 3:'Artikel' };

    function openModal(id) {
        const item = kontenData.find(k => k.id === id);
        if (!item) return;

        document.getElementById('modalTitle').textContent = item.judul;

        let mediaHtml = '';
        if (item.tipe == 2 && item.gambar_url) {
            mediaHtml = `<img src="${item.gambar_url}" alt="${item.judul}">`;
        } else if (item.tipe == 1 && item.video_url) {
            mediaHtml = `<video src="${item.video_url}" controls autoplay muted style="width:100%;border-radius:0.75rem;max-height:450px;background:#000;"></video>`;
        }

        let deskripsiHtml = '';
        if (item.deskripsi) {
            if (item.tipe == 3) {
                // Artikel: render HTML dari CKEditor
                deskripsiHtml = `<div class="modal-deskripsi ck-content-preview">${item.deskripsi}</div>`;
            } else {
                deskripsiHtml = `<div class="modal-deskripsi">${item.deskripsi.replace(/</g,'&lt;')}</div>`;
            }
        }

        const metaHtml = `
            <div class="modal-meta" style="margin-bottom:1rem;">
                <span class="meta-tipe">${types[item.tipe] ?? 'Konten'}</span>
                <span class="meta-kategori">${categories[item.kategori] ?? 'Lainnya'}</span>
            </div>
        `;

        document.getElementById('modalBody').innerHTML = metaHtml + mediaHtml + deskripsiHtml;
        document.getElementById('previewModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('previewModal').classList.remove('active');
        document.getElementById('modalBody').innerHTML = '';
        document.body.style.overflow = '';
    }

    function closeModalOutside(e) {
        if (e.target === document.getElementById('previewModal')) closeModal();
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>
@endsection