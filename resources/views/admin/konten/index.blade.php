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
    }

    .btn-add:hover {
        background: #1d4ed8;
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
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .card-media {
        height: 180px;
        background: #e2e8f0;
        position: relative;
        overflow: hidden;
    }

    .card-media img, .card-media video {
        width: 100%;
        height: 100%;
        object-fit: cover;
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
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .badge-fisioterapi { background: rgba(37, 99, 235, 0.9); color: white; }
    .badge-gizi { background: rgba(22, 163, 74, 0.9); color: white; }
    .badge-senam { background: rgba(234, 179, 8, 0.9); color: white; }
    .badge-ptm { background: rgba(220, 38, 38, 0.9); color: white; }
    .badge-jiwa { background: rgba(147, 51, 234, 0.9); color: white; }
    .badge-default { background: rgba(100, 116, 139, 0.9); color: white; }

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

    .card-actions {
        display: flex;
        gap: 0.5rem;
    }

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

    .btn-edit {
        background: #eff6ff;
        color: #2563eb;
        text-decoration: none;
    }

    .btn-edit:hover {
        background: #2563eb;
        color: white;
    }

    .btn-delete {
        background: #fff1f2;
        color: #e11d48;
    }

    .btn-delete:hover {
        background: #e11d48;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        background: white;
        border-radius: 1.5rem;
        grid-column: 1 / -1;
        border: 2px dashed #e2e8f0;
    }

    .empty-state i {
        font-size: 5rem;
        background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 1.5rem;
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


    <div class="konten-grid">
        @forelse($konten as $item)
            <div class="konten-card">
                <div class="card-media">
                    @if($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}">
                    @elseif($item->video)
                        <video src="{{ asset('storage/' . $item->video) }}" muted></video>
                        <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem; text-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                            <i class="fa-solid fa-circle-play"></i>
                        </div>
                    @else
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); color: #94a3b8;">
                            <i class="fa-solid fa-file-lines fa-4x" style="opacity: 0.5;"></i>
                        </div>
                    @endif
                    
                    @php
                        $catClass = match($item->kategori_konten) {
                            1 => 'badge-fisioterapi',
                            2 => 'badge-gizi',
                            3 => 'badge-senam',
                            4 => 'badge-ptm',
                            5 => 'badge-jiwa',
                            default => 'badge-default'
                        };
                        $categories = [1 => 'Fisioterapi', 2 => 'Gizi', 3 => 'Senam', 4 => 'Edukasi PTM', 5 => 'Jiwa'];
                        $types = [1 => 'Video', 2 => 'Gambar', 3 => 'Artikel', 4 => 'Audio'];
                    @endphp
                    <div class="card-badge {{ $catClass }}">
                        {{ $categories[$item->kategori_konten] ?? 'Lainnya' }}
                    </div>
                </div>
                <div class="card-content">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                        <span style="padding: 0.2rem 0.5rem; background: #f1f5f9; border-radius: 4px; font-size: 0.65rem; color: #64748b; font-weight: 800; text-transform: uppercase;">
                            {{ $types[$item->tipe_konten] ?? 'Konten' }}
                        </span>
                        <span style="font-size: 0.75rem; color: #94a3b8;">
                            <i class="fa-regular fa-clock"></i> 
                            @if($item->created_at)
                                {{ $item->created_at->diffForHumans() }}
                            @else
                                Tanggal tidak tersedia
                            @endif
                        </span>
                    </div>
                    <h3>{{ $item->judul }}</h3>
                    <p>{{ $item->deskripsi }}</p>
                </div>
                <div class="card-footer">
                    <span style="font-size: 0.75rem; color: #64748b; font-weight: 500;">
                        <i class="fa-regular fa-calendar-alt"></i> 
                        @if($item->created_at)
                            {{ $item->created_at->format('d M Y') }}
                        @else
                            -
                        @endif
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
                <i class="fa-solid fa-box-open"></i>
                <h2 style="font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Konten Masih Kosong</h2>
                <p style="color: #64748b; margin-bottom: 2rem;">Belum ada konten edukasi yang ditambahkan ke sistem.</p>
                <a href="{{ route('konten.create') }}" class="btn-add" style="display: inline-flex; width: auto;">
                    <i class="fa-solid fa-plus"></i> Tambah Sekarang
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection