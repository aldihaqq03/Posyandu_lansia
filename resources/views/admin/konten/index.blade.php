@extends('layout.sidebar')

@section('title', 'Manajemen Konten')

@push('styles')
<style>
/* ── Page ── */
.konten-page {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-shrink: 0;
}

.page-title {
    font-size: 22px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 2px;
}

.page-subtitle {
    font-size: 13px;
    color: #6b7280;
    margin: 0;
}

.btn-tambah {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: #2563eb;
    color: #fff;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.15s;
    white-space: nowrap;
    flex-shrink: 0;
}

.btn-tambah:hover { background: #1d4ed8; color: #fff; }

/* ── Seksi ── */
.konten-seksi {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.seksi-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e5e7eb;
}

.seksi-icon {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    flex-shrink: 0;
}

.seksi-icon.video    { background: #fef3c7; color: #d97706; }
.seksi-icon.gambar   { background: #dbeafe; color: #2563eb; }
.seksi-icon.artikel  { background: #dcfce7; color: #16a34a; }

.seksi-title {
    font-size: 13px;
    font-weight: 700;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: 0.6px;
}

.seksi-count {
    font-size: 11px;
    font-weight: 600;
    background: #f1f5f9;
    color: #6b7280;
    padding: 2px 8px;
    border-radius: 20px;
}

/* ── Grid ── */
.konten-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 14px;
}

/* ── Card ── */
.konten-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.2s, transform 0.2s;
}

.konten-card:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}

.card-media {
    height: 150px;
    background: #f1f5f9;
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
    transition: background 0.2s;
}

.card-media:hover .card-media-overlay { background: rgba(0,0,0,0.3); }

.overlay-icon {
    color: #fff;
    font-size: 2rem;
    opacity: 0;
    transform: scale(0.8);
    transition: opacity 0.2s, transform 0.2s;
}

.card-media:hover .overlay-icon { opacity: 1; transform: scale(1); }

.video-play-badge {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 2rem;
    background: rgba(0,0,0,0.18);
    pointer-events: none;
}

.card-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    padding: 2px 8px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    pointer-events: none;
    z-index: 2;
}

.badge-fisioterapi { background: rgba(37,99,235,0.88); color: #fff; }
.badge-gizi        { background: rgba(22,163,74,0.88); color: #fff; }
.badge-senam       { background: rgba(234,179,8,0.88); color: #fff; }
.badge-ptm         { background: rgba(220,38,38,0.88); color: #fff; }
.badge-jiwa        { background: rgba(147,51,234,0.88); color: #fff; }
.badge-lainnya     { background: rgba(14,165,233,0.88); color: #fff; }
.badge-default     { background: rgba(100,116,139,0.88); color: #fff; }

.artikel-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
    color: #2563eb;
    cursor: pointer;
}

.artikel-placeholder i { font-size: 2rem; opacity: 0.6; }
.artikel-placeholder span { font-size: 10px; font-weight: 700; margin-top: 6px; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.6; }

.card-content {
    padding: 12px 14px;
    flex: 1;
}

.card-meta {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 6px;
}

.card-tipe {
    padding: 2px 6px;
    background: #f1f5f9;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
}

.card-time {
    font-size: 11px;
    color: #9ca3af;
}

.card-judul {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 4px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
}

.card-desc {
    font-size: 12px;
    color: #6b7280;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.5;
}

.card-footer {
    padding: 8px 14px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fafafa;
}

.card-date { font-size: 11px; color: #9ca3af; }

.card-actions { display: flex; gap: 6px; }

.btn-icon {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.15s;
    text-decoration: none;
}

.btn-edit   { background: #eff6ff; color: #2563eb; }
.btn-edit:hover { background: #2563eb; color: #fff; }
.btn-delete { background: #fff1f2; color: #e11d48; }
.btn-delete:hover { background: #e11d48; color: #fff; }

/* ── Empty state ── */
.seksi-empty {
    padding: 24px;
    text-align: center;
    background: #f9fafb;
    border: 1px dashed #e5e7eb;
    border-radius: 10px;
    font-size: 12px;
    color: #9ca3af;
}

/* ── Modal ── */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.modal-overlay.active { display: flex; }

.modal-box {
    background: #fff;
    border-radius: 14px;
    width: 100%;
    max-width: 720px;
    max-height: 88vh;
    overflow-y: auto;
    box-shadow: 0 25px 50px rgba(0,0,0,0.3);
    animation: modalIn 0.22s ease;
}

@keyframes modalIn {
    from { opacity: 0; transform: scale(0.95) translateY(16px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}

.modal-header {
    padding: 14px 18px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    position: sticky;
    top: 0;
    background: #fff;
    z-index: 10;
    border-radius: 14px 14px 0 0;
}

.modal-header h2 { font-size: 14px; font-weight: 700; color: #0f172a; margin: 0; }

.modal-close {
    background: #f1f5f9;
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    font-size: 13px;
    transition: all 0.15s;
    flex-shrink: 0;
}

.modal-close:hover { background: #e11d48; color: #fff; }

.modal-body {
    padding: 16px 18px;
}

.modal-body img { width: 100%; border-radius: 8px; max-height: 420px; object-fit: contain; background: #f8fafc; }
.modal-body video { width: 100%; border-radius: 8px; max-height: 380px; background: #000; }

.modal-meta { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 10px; }
.modal-meta span { padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
.meta-tipe     { background: #f1f5f9; color: #475569; }
.meta-kategori { background: #eff6ff; color: #2563eb; }

.modal-deskripsi {
    margin-top: 10px;
    color: #334155;
    font-size: 13px;
    line-height: 1.7;
    border-top: 1px solid #f1f5f9;
    padding-top: 10px;
}

.ck-content-preview h1, .ck-content-preview h2, .ck-content-preview h3 { font-weight: 700; color: #0f172a; margin: 12px 0 6px; }
.ck-content-preview h1 { font-size: 18px; }
.ck-content-preview h2 { font-size: 15px; }
.ck-content-preview h3 { font-size: 13px; }
.ck-content-preview p  { margin-bottom: 8px; }
.ck-content-preview ul, .ck-content-preview ol { padding-left: 20px; margin-bottom: 8px; }
.ck-content-preview strong { font-weight: 700; }
.ck-content-preview em { font-style: italic; }
.ck-content-preview a { color: #2563eb; }
.ck-content-preview blockquote { border-left: 3px solid #2563eb; padding: 6px 12px; background: #f0f7ff; border-radius: 0 6px 6px 0; margin: 8px 0; }
</style>
@endpush

@section('content')
@php
    $categories = [1=>'Fisioterapi', 2=>'Gizi', 3=>'Senam', 4=>'Edukasi PTM', 5=>'Jiwa', 6=>'Lainnya'];
    $catClasses = [1=>'badge-fisioterapi', 2=>'badge-gizi', 3=>'badge-senam', 4=>'badge-ptm', 5=>'badge-jiwa', 6=>'badge-lainnya'];

    $videos   = $konten->filter(fn($k) => (int)$k->tipe_konten === 1);
    $gambar   = $konten->filter(fn($k) => (int)$k->tipe_konten === 2);
    $artikel  = $konten->filter(fn($k) => (int)$k->tipe_konten === 3);
@endphp

<div class="konten-page">

    {{-- Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Manajemen Konten</h1>
            <p class="page-subtitle">Kelola konten edukasi, video senam, dan informasi gizi untuk lansia.</p>
        </div>
        <a href="{{ route('konten.create') }}" class="btn-tambah">
            <i class="fa-solid fa-plus"></i> Tambah Konten
        </a>
    </div>

    @if(session('success'))
        <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:10px 14px; border-radius:8px; font-size:13px; display:flex; align-items:center; gap:8px;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    {{-- ── SEKSI VIDEO ── --}}
    <div class="konten-seksi">
        <div class="seksi-header">
            <div class="seksi-icon video"><i class="fa-solid fa-play"></i></div>
            <span class="seksi-title">Video</span>
            <span class="seksi-count">{{ $videos->count() }}</span>
        </div>
        @if($videos->isEmpty())
            <div class="seksi-empty">Belum ada konten video.</div>
        @else
            <div class="konten-grid">
                @foreach($videos as $item)
                    @include('admin.konten._card', ['item' => $item, 'categories' => $categories, 'catClasses' => $catClasses])
                @endforeach
            </div>
        @endif
    </div>

    {{-- ── SEKSI GAMBAR ── --}}
    <div class="konten-seksi">
        <div class="seksi-header">
            <div class="seksi-icon gambar"><i class="fa-solid fa-image"></i></div>
            <span class="seksi-title">Gambar</span>
            <span class="seksi-count">{{ $gambar->count() }}</span>
        </div>
        @if($gambar->isEmpty())
            <div class="seksi-empty">Belum ada konten gambar.</div>
        @else
            <div class="konten-grid">
                @foreach($gambar as $item)
                    @include('admin.konten._card', ['item' => $item, 'categories' => $categories, 'catClasses' => $catClasses])
                @endforeach
            </div>
        @endif
    </div>

    {{-- ── SEKSI ARTIKEL ── --}}
    <div class="konten-seksi">
        <div class="seksi-header">
            <div class="seksi-icon artikel"><i class="fa-solid fa-file-lines"></i></div>
            <span class="seksi-title">Artikel</span>
            <span class="seksi-count">{{ $artikel->count() }}</span>
        </div>
        @if($artikel->isEmpty())
            <div class="seksi-empty">Belum ada konten artikel.</div>
        @else
            <div class="konten-grid">
                @foreach($artikel as $item)
                    @include('admin.konten._card', ['item' => $item, 'categories' => $categories, 'catClasses' => $catClasses])
                @endforeach
            </div>
        @endif
    </div>

</div>

{{-- Modal Preview --}}
<div class="modal-overlay" id="previewModal" onclick="closeModalOutside(event)">
    <div class="modal-box" id="modalBox">
        <div class="modal-header">
            <h2 id="modalTitle">-</h2>
            <button class="modal-close" onclick="closeModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body" id="modalBody"></div>
    </div>
</div>

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
        mediaHtml = `<video src="${item.video_url}" controls autoplay muted></video>`;
    }
    let deskripsiHtml = item.deskripsi
        ? `<div class="modal-deskripsi ${item.tipe == 3 ? 'ck-content-preview' : ''}">${item.tipe == 3 ? item.deskripsi : item.deskripsi.replace(/</g,'&lt;')}</div>`
        : '';
    const metaHtml = `<div class="modal-meta"><span class="meta-tipe">${types[item.tipe] ?? 'Konten'}</span><span class="meta-kategori">${categories[item.kategori] ?? 'Lainnya'}</span></div>`;
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