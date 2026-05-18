@extends('layout.sidebar')

@section('title', 'Edit Konten')

@push('styles')
<style>
    .form-container {
        padding: 2rem;
        background: #f8fafc;
        min-height: 100vh;
    }

    .form-card {
        max-width: 850px;
        margin: 0 auto;
        background: white;
        border-radius: 1.5rem;
        padding: 2.5rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    }

    .form-header {
        margin-bottom: 2.5rem;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .form-header h1 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }

    .form-group {
        margin-bottom: 1.75rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 0.75rem;
    }

    .form-control {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 0.75rem;
        font-size: 1rem;
        transition: all 0.2s;
        color: #1e293b;
    }

    .form-control:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        background: #fcfdff;
    }

    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.75rem;
    }

    .upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        background: #f8fafc;
    }

    .upload-zone:hover {
        border-color: #2563eb;
        background: #f0f7ff;
        transform: translateY(-2px);
    }

    .upload-zone i {
        font-size: 2.5rem;
        color: #94a3b8;
        margin-bottom: 1rem;
        transition: color 0.3s;
    }

    .upload-zone:hover i {
        color: #2563eb;
    }

    .upload-zone span {
        display: block;
        color: #64748b;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .upload-zone input {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }

    .preview-container {
        margin-top: 1.25rem;
        border-radius: 0.75rem;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        background: #f1f5f9;
        display: {{ $konten->gambar || $konten->video ? 'block' : 'none' }};
    }

    .preview-container img, .preview-container video {
        width: 100%;
        display: block;
        max-height: 250px;
        object-fit: contain;
    }

    .current-file-info {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .btn-submit {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: white;
        padding: 1rem 2rem;
        border-radius: 0.75rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        width: 100%;
        font-size: 1.1rem;
        margin-top: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        filter: brightness(1.1);
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.65rem;
        color: #64748b;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 2rem;
        transition: all 0.2s;
    }

    .btn-back:hover {
        color: #0f172a;
        transform: translateX(-4px);
    }

    .optional-badge {
        font-size: 0.7rem;
        background: #f1f5f9;
        color: #64748b;
        padding: 0.15rem 0.5rem;
        border-radius: 4px;
        margin-left: 0.5rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <a href="{{ route('konten.index') }}" class="btn-back">
        <i class="fa-solid fa-arrow-left-long"></i>
        Kembali ke Daftar Konten
    </a>

    <div class="form-card">
        <div class="form-header">
            <h1>Edit Konten</h1>
            <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;">ID Konten: #{{ $konten->id_konten }}</div>
        </div>

        <form action="{{ route('konten.update', $konten->id_konten) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Judul Konten</label>
                <input type="text" name="judul" class="form-control" value="{{ old('judul', $konten->judul) }}" placeholder="Masukkan judul konten..." required>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Tipe Konten</label>
                    <select name="tipe_konten" class="form-control" required>
                        <option value="1" {{ $konten->tipe_konten == 1 ? 'selected' : '' }}>Video</option>
                        <option value="2" {{ $konten->tipe_konten == 2 ? 'selected' : '' }}>Gambar</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori Konten</label>
                    <select name="kategori_konten" class="form-control" required>
                        <option value="1" {{ $konten->kategori_konten == 1 ? 'selected' : '' }}>Fisioterapi</option>
                        <option value="2" {{ $konten->kategori_konten == 2 ? 'selected' : '' }}>Gizi</option>
                        <option value="3" {{ $konten->kategori_konten == 3 ? 'selected' : '' }}>Senam</option>
                        <option value="4" {{ $konten->kategori_konten == 4 ? 'selected' : '' }}>Edukasi PTM</option>
                        <option value="5" {{ $konten->kategori_konten == 5 ? 'selected' : '' }}>Jiwa</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi / Isi Konten</label>
                <textarea name="deskripsi" class="form-control" rows="6" placeholder="Tuliskan deskripsi atau isi artikel di sini...">{{ old('deskripsi', $konten->deskripsi) }}</textarea>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Update Foto <span class="optional-badge">Opsional</span></label>
                    <div class="upload-zone" id="image-upload-zone">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span>Klik atau seret foto ke sini</span>
                        <input type="file" name="gambar" accept="image/*" id="image-input">
                        <div class="preview-container" id="image-preview" style="{{ $konten->gambar ? 'display:block' : '' }}">
                            @if($konten->gambar)
                                <img src="{{ asset('storage/' . $konten->gambar) }}" alt="Preview">
                            @endif
                        </div>
                    </div>
                    @if($konten->gambar)
                        <div class="current-file-info">
                            <i class="fa-solid fa-file-image"></i> File saat ini: {{ basename($konten->gambar) }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label class="form-label">Update Video <span class="optional-badge">Opsional</span></label>
                    <div class="upload-zone" id="video-upload-zone">
                        <i class="fa-solid fa-clapperboard"></i>
                        <span>Klik atau seret video ke sini</span>
                        <input type="file" name="video" accept="video/*" id="video-input">
                        <div class="preview-container" id="video-preview" style="{{ $konten->video ? 'display:block' : '' }}">
                            @if($konten->video)
                                <video src="{{ asset('storage/' . $konten->video) }}" controls></video>
                            @endif
                        </div>
                    </div>
                    @if($konten->video)
                        <div class="current-file-info">
                            <i class="fa-solid fa-file-video"></i> File saat ini: {{ basename($konten->video) }}
                        </div>
                    @endif
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-save"></i> Perbarui Konten
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview Image
    document.getElementById('image-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('image-preview');
        const zone = document.getElementById('image-upload-zone');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}">`;
                preview.style.display = 'block';
                zone.querySelector('i').style.display = 'none';
                zone.querySelector('span').innerText = file.name;
            }
            reader.readAsDataURL(file);
        }
    });

    // Preview Video
    document.getElementById('video-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('video-preview');
        const zone = document.getElementById('video-upload-zone');
        
        if (file) {
            preview.innerHTML = `<video src="${URL.createObjectURL(file)}" controls></video>`;
            preview.style.display = 'block';
            zone.querySelector('i').style.display = 'none';
            zone.querySelector('span').innerText = file.name;
        }
    });
</script>
@endpush
