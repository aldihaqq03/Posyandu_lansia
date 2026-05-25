@extends('layout.sidebar')

@section('title', 'Tambah Petugas')

@push('styles')
    @vite('resources/css/app.css')
    @vite('resources/css/cssAdmin/tambah_data_petugas.css')
@endpush

@section('content')

@php
    $currentRole = strtolower(Auth::user()->jabatan ?? '');
@endphp

    <div class="page-container">

        <div class="page-header">

            <div>
                <h1>Form Tambah Petugas SIMPEL</h1>
                <p>Daftarkan petugas atau kader baru untuk sistem informasi peduli lansia.</p>
            </div>

            <a href="/data_petugas" class="btn-back">
                <i class="fa fa-arrow-left"></i> Kembali ke Daftar
            </a>

        </div>


        <div class="form-card">

            <form action="{{ route('petugas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="photo-upload">

                    <div class="upload-box">
                        <i class="fa fa-user"></i>
                        <input type="file" name="foto" accept="image/*">
                    </div>

                </div>

                <div class="form-grid">

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>
                    </div>

                    <div class="form-group">
                        <label>NIK</label>
                        <input type="text" name="nik" placeholder="16 digit NIK" required>
                    </div>

                    <div class="form-group">
                        <label>Jabatan</label>
                        @if($currentRole === 'super_admin')
                            <select name="jabatan" id="jabatan" required>
                                <option value="">Pilih Jabatan</option>
                                <option value="kader" {{ old('jabatan') === 'kader' ? 'selected' : '' }}>kader</option>
                                <option value="kepala_kader" {{ old('jabatan') === 'kepala_kader' ? 'selected' : '' }}>kepala_kader</option>
                            </select>
                        @elseif($currentRole === 'kepala_kader')
                            <select name="jabatan" id="jabatan" required>
                                <option value="kader" selected>kader</option>
                            </select>
                        @else
                            <input type="hidden" name="jabatan" id="jabatan" value="kader">
                            <input type="text" value="kader" readonly>
                            <small style="color:#6b7280; font-size:12px;">Role dikunci ke kader untuk kepala_kader.</small>
                        @endif
                    </div>



                    <div class="form-group">
                        <label>Nomor WhatsApp</label>
                        <input type="text" name="no_hp" placeholder="+62" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="nama@gmail.com" required>
                    </div>

                    <div class="form-group full">
                        <label>Kata Sandi</label>
                        <input type="password" name="password" placeholder="Minimal 8 karakter" required>
                    </div>

                </div>

                <div class="form-action">

                    <a href="{{ route('petugas.index') }}" class="btn-cancel">Batal</a>

                    <button type="submit" class="btn-save">
                        <i class="fa fa-save"></i> Simpan Petugas
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection

@push('scripts')
    @vite('resources/js/jsADMIN/data_petugas.js')
@endpush