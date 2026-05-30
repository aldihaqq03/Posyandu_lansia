<div class="konten-card">
    <div class="card-media" onclick="openModal({{ $item->id_konten }})">
        @if($item->gambar)
            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}">
            <div class="card-media-overlay">
                <i class="fa-solid fa-magnifying-glass-plus overlay-icon"></i>
            </div>
        @elseif($item->video)
            <video src="{{ asset('storage/' . $item->video) }}" muted preload="metadata"></video>
            <div class="video-play-badge"><i class="fa-solid fa-circle-play"></i></div>
            <div class="card-media-overlay">
                <i class="fa-solid fa-play overlay-icon"></i>
            </div>
        @else
            <div class="artikel-placeholder">
                <i class="fa-solid fa-file-lines"></i>
                <span>Artikel</span>
            </div>
            <div class="card-media-overlay">
                <i class="fa-solid fa-book-open overlay-icon"></i>
            </div>
        @endif
        <div class="card-badge {{ $catClasses[$item->kategori_konten] ?? 'badge-default' }}">
            {{ $categories[$item->kategori_konten] ?? 'Lainnya' }}
        </div>
    </div>

    <div class="card-content">
        <div class="card-meta">
            <span class="card-tipe">{{ ['','Video','Gambar','Artikel'][$item->tipe_konten] ?? 'Konten' }}</span>
            <span class="card-time"><i class="fa-regular fa-clock"></i> {{ $item->created_at?->diffForHumans() ?? '-' }}</span>
        </div>
        <h3 class="card-judul">{{ $item->judul }}</h3>
        <p class="card-desc">{{ $item->deskripsi ? strip_tags($item->deskripsi) : '-' }}</p>
    </div>

    <div class="card-footer">
        <span class="card-date"><i class="fa-regular fa-calendar-alt"></i> {{ $item->created_at?->format('d M Y') ?? '-' }}</span>
        <div class="card-actions">
            <a href="{{ route('konten.edit', $item->id_konten) }}" class="btn-icon btn-edit" title="Edit">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <form action="{{ route('konten.destroy', $item->id_konten) }}" method="POST" onsubmit="return confirm('Yakin hapus konten ini?')" style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-icon btn-delete" title="Hapus">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </form>
        </div>
    </div>
</div>