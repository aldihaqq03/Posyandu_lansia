@extends('layout.sidebar')

@section('title', 'Tambah Konten')

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
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
    }

    .form-header {
        margin-bottom: 2.5rem;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 1.25rem;
    }

    .form-header h1 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }

    .form-group { margin-bottom: 1.75rem; }

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
        background: white;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37,99,235,0.1);
        background: #fcfdff;
    }

    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.75rem;
    }

    /* Upload zone dinamis */
    .upload-wrapper { display: none; }
    .upload-wrapper.visible { display: block; }

    .upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 1rem;
        padding: 2.5rem 2rem;
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
        display: block;
    }

    .upload-zone:hover i { color: #2563eb; }

    .upload-zone .upload-label {
        display: block;
        color: #64748b;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .upload-zone .upload-hint {
        display: block;
        color: #94a3b8;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    .upload-zone input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }

    .preview-container {
        margin-top: 1.25rem;
        border-radius: 0.75rem;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        background: #f1f5f9;
        display: none;
    }

    .preview-container img,
    .preview-container video {
        width: 100%;
        display: block;
        max-height: 280px;
        object-fit: contain;
    }

    .preview-name {
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        background: white;
        border-top: 1px solid #e2e8f0;
    }

    /* CKEditor wrapper */
    #ck-wrapper { display: none; }
    #ck-wrapper.visible { display: block; }
    #editor-textarea { display: none; }

    /* Textarea biasa untuk deskripsi non-artikel */
    #plain-deskripsi-wrapper { display: none; }
    #plain-deskripsi-wrapper.visible { display: block; }

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
        box-shadow: 0 4px 6px -1px rgba(37,99,235,0.2);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(37,99,235,0.3);
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

    .btn-back:hover { color: #0f172a; transform: translateX(-4px); }

    .optional-badge {
        font-size: 0.7rem;
        background: #f1f5f9;
        color: #64748b;
        padding: 0.15rem 0.5rem;
        border-radius: 4px;
        margin-left: 0.5rem;
        font-weight: 600;
    }

    /* Info tipe hint */
    .tipe-hint {
        margin-top: 0.5rem;
        font-size: 0.75rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        display: none;
    }
    .tipe-hint.visible { display: flex; }

    /* CKEditor override */
    .ck-editor__editable { min-height: 250px !important; border-radius: 0 0 0.75rem 0.75rem !important; }
    .ck.ck-editor__top .ck-sticky-panel .ck-toolbar { border-radius: 0.75rem 0.75rem 0 0 !important; }
    .ck.ck-editor { border-radius: 0.75rem !important; border: 1.5px solid #e2e8f0 !important; }
    .ck.ck-editor:focus-within { border-color: #2563eb !important; box-shadow: 0 0 0 4px rgba(37,99,235,0.1) !important; }
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
            <h1>Tambah Konten Baru</h1>
        </div>

        <form action="{{ route('konten.store') }}" method="POST" enctype="multipart/form-data" id="kontenForm">
            @csrf

            <div class="form-group">
                <label class="form-label">Judul Konten</label>
                <input type="text" name="judul" class="form-control" placeholder="Masukkan judul konten..." value="{{ old('judul') }}" required>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Tipe Konten</label>
                    <select name="tipe_konten" class="form-control" id="tipeSelect" required>
                        <option value="" disabled selected>Pilih Tipe...</option>
                        <option value="1" {{ old('tipe_konten') == 1 ? 'selected' : '' }}>Video</option>
                        <option value="2" {{ old('tipe_konten') == 2 ? 'selected' : '' }}>Gambar</option>
                        <option value="3" {{ old('tipe_konten') == 3 ? 'selected' : '' }}>Artikel</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori Konten</label>
                    <select name="kategori_konten" class="form-control" required>
                        <option value="" disabled selected>Pilih Kategori...</option>
                        <option value="1" {{ old('kategori_konten') == 1 ? 'selected' : '' }}>Fisioterapi</option>
                        <option value="2" {{ old('kategori_konten') == 2 ? 'selected' : '' }}>Gizi</option>
                        <option value="3" {{ old('kategori_konten') == 3 ? 'selected' : '' }}>Senam</option>
                        <option value="4" {{ old('kategori_konten') == 4 ? 'selected' : '' }}>Edukasi PTM</option>
                        <option value="5" {{ old('kategori_konten') == 5 ? 'selected' : '' }}>Jiwa</option>
                        <option value="6" {{ old('kategori_konten') == 6 ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
            </div>

            {{-- Upload zone: muncul sesuai tipe --}}
            <div class="form-group">
                {{-- Video --}}
                <div class="upload-wrapper" id="video-wrapper">
                    <label class="form-label">Unggah Video</label>
                    <div class="upload-zone" id="video-zone">
                        <i class="fa-solid fa-video"></i>
                        <span class="upload-label">Klik atau seret video ke sini</span>
                        <span class="upload-hint">MP4, MOV, AVI, WMV — maks. 50MB</span>
                        <input type="file" name="video" accept="video/*" id="video-input">
                    </div>
                    <div class="preview-container" id="video-preview"></div>
                </div>

                {{-- Gambar --}}
                <div class="upload-wrapper" id="gambar-wrapper">
                    <label class="form-label">Unggah Foto</label>
                    <div class="upload-zone" id="gambar-zone">
                        <i class="fa-solid fa-image"></i>
                        <span class="upload-label">Klik atau seret foto ke sini</span>
                        <span class="upload-hint">JPEG, PNG, GIF — maks. 5MB</span>
                        <input type="file" name="gambar" accept="image/*" id="gambar-input">
                    </div>
                    <div class="preview-container" id="gambar-preview"></div>
                </div>
            </div>

            {{-- Deskripsi: plain textarea untuk Video/Gambar --}}
            <div class="form-group" id="plain-deskripsi-wrapper">
                <label class="form-label">Deskripsi <span class="optional-badge">Opsional</span></label>
                <textarea name="deskripsi" id="plainDeskripsi" class="form-control" rows="5" placeholder="Tuliskan deskripsi singkat...">{{ old('deskripsi') }}</textarea>
            </div>

            {{-- Deskripsi CKEditor untuk Artikel --}}
            <div class="form-group" id="ck-wrapper">
                <label class="form-label">Isi Artikel</label>
                <div id="ckEditorContainer"></div>
                <textarea name="deskripsi" id="ckDeskripsi" style="display:none;">{{ old('deskripsi') }}</textarea>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-circle-plus"></i> Simpan Konten
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
{{-- CKEditor 5 CDN --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

<script>
    let ckEditorInstance = null;

    const tipeSelect = document.getElementById('tipeSelect');

    function handleTipeChange() {
        const val = tipeSelect.value;

        // Sembunyikan semua dulu
        document.getElementById('video-wrapper').classList.remove('visible');
        document.getElementById('gambar-wrapper').classList.remove('visible');
        document.getElementById('plain-deskripsi-wrapper').classList.remove('visible');
        document.getElementById('ck-wrapper').classList.remove('visible');

        if (val === '1') {
            document.getElementById('video-wrapper').classList.add('visible');
            document.getElementById('plain-deskripsi-wrapper').classList.add('visible');
        } else if (val === '2') {
            document.getElementById('gambar-wrapper').classList.add('visible');
            document.getElementById('plain-deskripsi-wrapper').classList.add('visible');
        } else if (val === '3') {
            document.getElementById('ck-wrapper').classList.add('visible');
            initCKEditor();
        }
    }

    function initCKEditor() {
        if (ckEditorInstance) return; // Sudah diinisialisasi

        ClassicEditor
            .create(document.getElementById('ckEditorContainer'), {
                placeholder: 'Tulis isi artikel di sini...',
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'bulletedList', 'numberedList', '|',
                    'blockQuote', 'link', '|',
                    'undo', 'redo'
                ]
            })
            .then(editor => {
                ckEditorInstance = editor;
                // Set nilai lama jika ada
                const existing = document.getElementById('ckDeskripsi').value;
                if (existing) editor.setData(existing);
            })
            .catch(err => console.error('CKEditor error:', err));
    }

    // Sync CKEditor ke textarea sebelum submit
    document.getElementById('kontenForm').addEventListener('submit', function() {
        if (ckEditorInstance) {
            document.getElementById('ckDeskripsi').value = ckEditorInstance.getData();
        }
        // Nonaktifkan textarea yang tidak dipakai agar tidak dikirim ganda
        if (tipeSelect.value === '3') {
            document.getElementById('plainDeskripsi').disabled = true;
        } else {
            document.getElementById('ckDeskripsi').disabled = true;
        }
    });

    tipeSelect.addEventListener('change', handleTipeChange);

    // Jalankan saat load jika ada nilai old()
    if (tipeSelect.value) handleTipeChange();

    // Preview Gambar
    document.getElementById('gambar-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const preview = document.getElementById('gambar-preview');
        const reader = new FileReader();
        reader.onload = function(ev) {
            preview.innerHTML = `
                <img src="${ev.target.result}" alt="Preview">
                <div class="preview-name"><i class="fa-solid fa-file-image"></i> ${file.name}</div>
            `;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    });

    // Preview Video
    document.getElementById('video-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const preview = document.getElementById('video-preview');
        preview.innerHTML = `
            <video src="${URL.createObjectURL(file)}" controls></video>
            <div class="preview-name"><i class="fa-solid fa-file-video"></i> ${file.name}</div>
        `;
        preview.style.display = 'block';
    });
</script>
@endpush