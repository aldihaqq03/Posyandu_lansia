@extends('layout.sidebar')

@push('styles')
    @vite('resources/css/app.css')
@endpush

@section('title', 'Tambah Obat')

@section('content')
    <main class="main-content">
        <div class="container">
            <header class="page-header">
                <div class="header-info">
                    <nav class="breadcrumb" aria-label="Breadcrumb">
                        <img class="icon" src="/img/icon-2.svg" alt="Home" />
                        <img class="separator" src="/img/icon-6.svg" alt="" />
                        <a href="/obat" style="color: #0F766E; text-decoration: none;">Data Obat</a>
                        <img class="separator" src="/img/icon-6.svg" alt="" />
                        <span class="text-muted">Tambah</span>
                    </nav>
                    <h1 class="page-title">Tambah Obat</h1>
                    <p class="page-subtitle">Tambahkan data obat baru ke sistem.</p>
                </div>
            </header>

            <section class="card" style="padding: 30px;">
                <!-- Tampilkan Error Validasi Jika Ada -->
                @if ($errors->any())
                    <div style="background: #ffebe9; border: 1px solid rgba(255,129,130,0.4); border-radius: 6px; padding: 15px; margin-bottom: 20px;">
                        <h4 style="color: #cf222e; margin-bottom: 5px;">Terjadi Kesalahan:</h4>
                        <ul style="color: #cf222e; padding-left: 20px; font-size: 14px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('obat.store') }}" method="POST">
                    @csrf

                    <div style="margin-bottom: 20px;">
                        <label for="nama_obat" style="display: block; margin-bottom: 8px; font-weight: 500;">Nama Obat *</label>
                        <input type="text" id="nama_obat" name="nama_obat" value="{{ old('nama_obat') }}" 
                            placeholder="Masukkan nama obat" 
                            style="width: 100%; padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 14px;"
                            required>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="tipe_obat" style="display: block; margin-bottom: 8px; font-weight: 500;">Tipe Obat *</label>
                        <input type="text" id="tipe_obat" name="tipe_obat" value="{{ old('tipe_obat') }}" 
                            placeholder="Contoh: tablet, kapsul, sirup" 
                            style="width: 100%; padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 14px;"
                            required>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="stock" style="display: block; margin-bottom: 8px; font-weight: 500;">Stok *</label>
                        <input type="number" id="stock" name="stock" value="{{ old('stock') }}" 
                            placeholder="Masukkan jumlah stok" 
                            min="0"
                            style="width: 100%; padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 14px;"
                            required>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="keterangan" style="display: block; margin-bottom: 8px; font-weight: 500;">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" 
                            placeholder="Masukkan keterangan obat (opsional)" 
                            rows="4"
                            style="width: 100%; padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 14px;">{{ old('keterangan') }}</textarea>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn-primary">
                            <i class="fa-solid fa-check"></i>
                            <span>Simpan</span>
                        </button>
                        <a href="{{ route('obat.index') }}" class="btn-outline" style="display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-times"></i>
                            <span>Batal</span>
                        </a>
                    </div>
                </form>
            </section>
        </div>
    </main>
@endsection
