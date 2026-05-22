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
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
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

    /* Field readonly/disabled styling */
    .form-control[disabled],
    .form-control[readonly] {
        background: #f8fafc;
        color: #94a3b8;
        cursor: not-allowed;
        border-color: #e2e8f0;
    }

    .readonly-info {
        font-size: 0.72rem;
        color: #94a3b8;
        margin-top: 0.4rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.75rem;
    }

    /* Preview file yang sudah ada (read-only) */
    .file-preview-readonly {
        border: 1.5px solid #e2e8f0;
        border-radius: 0.75rem;
        overflow: hidden;
        background: #f8fafc;
    }

    .file-preview-readonly img,
    .file-preview-readonly video {
        width: 100%;
        display: block;
        max-height: 280px;
        object-fit: contain;
    }

    .file-preview-readonly .file-info {
        padding: 0.65rem 1rem;
        font-size: 0.75rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        background: white;
        border-top: 1px solid #e2e8f0;
    }

    /* Tidak ada file */
    .no-media-placeholder {
        border: 1.5px dashed #e2e8f0;
        border-radius: 0.75rem;
        padding: 2rem;
        text-align: center;
        background: #f8fafc;
        color: #94a3b8;
    }

    .no-media-placeholder i { font-size: 2rem; display: block; margin-bottom: 0.5rem; }
    .no-media-placeholder span { font-size: 0.8rem; font-weight: 500; }

    .readonly-badge {
        font-size: 0.65rem;
        background: #fef9c3;
        color: #854d0e;
        padding: 0.15rem 0.5rem;
        border-radius: 4px;
        margin-left: 0.5rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .editable-badge {
        font-size: 0.65rem;
        background: #dcfce7;
        color: #166534;
        padding: 0.15rem 0.5rem;
        border-radius: 4px;
        margin-left: 0.5rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.025em;
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

    /* Info box readonly */
    .info-box {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 0.75rem;
        padding: 0.875rem 1.25rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        font-size: 0.875rem;
        color: #92400e;
    }

    .info-box i { flex-shrink: 0; margin-top: 0.1rem; color: #d97706; }

    /* CKEditor */
    .ck-editor__editable { min-height: 280px !important; border-radius: 0 0 0.75rem 0.75rem !important; }
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
            <h1>Edit Konten</h1>
            <div style="font-size:0.75rem; color:#94a3b8; font-weight:500;">ID: #{{ $konten->id_konten }}</div>
        </div>

        <div class="info-box">
            <i class="fa-solid fa-circle-info"></i>
            <div>
                Hanya <strong>Judul</strong>, <strong>Kategori</strong>, dan <strong>{{ $konten->tipe_konten == 3 ? 'Isi Artikel' : 'Deskripsi' }}</strong> yang dapat diedit.
                Tipe konten dan file media tidak dapat diubah.
            </div>
        </div>

        <form action="{{ route('konten.update', $konten->id_konten) }}" method="POST" id="editForm">
            @csrf
            @method('PUT')

            {{-- Judul: bisa diedit --}}
            <div class="form-group">
                <label class="form-label">
                    Judul Konten
                    <span class="editable-badge">Dapat Diedit</span>
                </label>
                <input type="text" name="judul" class="form-control"
                    value="{{ old('judul', $konten->judul) }}"
                    placeholder="Masukkan judul konten..." required>
            </div>

            <div class="grid-2">
                {{-- Tipe: readonly --}}
                <div class="form-group">
                    <label class="form-label">
                        Tipe Konten
                        <span class="readonly-badge">Tidak Dapat Diedit</span>
                    </label>
                    @php
                        $types = [1=>'Video', 2=>'Gambar', 3=>'Artikel'];
                    @endphp
                    <input type="text" class="form-control"
                        value="{{ $types[$konten->tipe_konten] ?? 'Konten' }}"
                        disabled>
                    <p class="readonly-info"><i class="fa-solid fa-lock"></i> Tipe konten tidak dapat diubah setelah dibuat.</p>
                </div>

                {{-- Kategori: bisa diedit --}}
                <div class="form-group">
                    <label class="form-label">
                        Kategori Konten
                        <span class="editable-badge">Dapat Diedit</span>
                    </label>
                    <select name="kategori_konten" class="form-control" required>
                        <option value="1" {{ $konten->kategori_konten == 1 ? 'selected' : '' }}>Fisioterapi</option>
                        <option value="2" {{ $konten->kategori_konten == 2 ? 'selected' : '' }}>Gizi</option>
                        <option value="3" {{ $konten->kategori_konten == 3 ? 'selected' : '' }}>Senam</option>
                        <option value="4" {{ $konten->kategori_konten == 4 ? 'selected' : '' }}>Edukasi PTM</option>
                        <option value="5" {{ $konten->kategori_konten == 5 ? 'selected' : '' }}>Jiwa</option>
                        <option value="6" {{ $konten->kategori_konten == 6 ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
            </div>

            {{-- Preview file media (readonly) --}}
            @if($konten->gambar || $konten->video)
                <div class="form-group">
                    <label class="form-label">
                        File Media
                        <span class="readonly-badge">Tidak Dapat Diedit</span>
                    </label>
                    <div class="file-preview-readonly">
                        @if($konten->gambar)
                            <img src="{{ asset('storage/' . $konten->gambar) }}" alt="{{ $konten->judul }}">
                            <div class="file-info">
                                <i class="fa-solid fa-file-image"></i>
                                {{ basename($konten->gambar) }}
                            </div>
                        @elseif($konten->video)
                            <video src="{{ asset('storage/' . $konten->video) }}" controls></video>
                            <div class="file-info">
                                <i class="fa-solid fa-file-video"></i>
                                {{ basename($konten->video) }}
                            </div>
                        @endif
                    </div>
                    <p class="readonly-info"><i class="fa-solid fa-lock"></i> File tidak dapat diubah.</p>
                </div>
            @endif

            {{-- Deskripsi / Isi Artikel --}}
            <div class="form-group">
                <label class="form-label">
                    {{ $konten->tipe_konten == 3 ? 'Isi Artikel' : 'Deskripsi' }}
                    <span class="editable-badge">Dapat Diedit</span>
                </label>

                @if($konten->tipe_konten == 3)
                    {{-- Artikel: pakai CKEditor --}}
                    <div id="ckEditorContainer"></div>
                    <textarea name="deskripsi" id="ckDeskripsi" style="display:none;">{{ old('deskripsi', $konten->deskripsi) }}</textarea>
                @else
                    {{-- Video / Gambar: textarea biasa --}}
                    <textarea name="deskripsi" class="form-control" rows="5"
                        placeholder="Tuliskan deskripsi singkat...">{{ old('deskripsi', $konten->deskripsi) }}</textarea>
                @endif
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-save"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@if($konten->tipe_konten == 3)
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    let ckEditorInstance = null;

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
            const existing = document.getElementById('ckDeskripsi').value;
            if (existing) editor.setData(existing);
        })
        .catch(err => console.error('CKEditor error:', err));

    document.getElementById('editForm').addEventListener('submit', function() {
        if (ckEditorInstance) {
            document.getElementById('ckDeskripsi').value = ckEditorInstance.getData();
        }
    });
</script>
@endif
@endpush