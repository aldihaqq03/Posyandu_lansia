@extends('layout.sidebar')

@section('title', 'Edit Petugas')

@push('styles')
    @vite('resources/css/app.css')
    @vite('resources/css/cssAdmin/tambah_data_petugas.css')
@endpush

@section('content')

    @php
        $currentRole = strtolower(Auth::user()->jabatan ?? '');
        $canChooseRole = $currentRole === 'super_admin';
        $isKepalaKader = $currentRole === 'kepala_kader';
    @endphp

    <div class="page-container">

        <div class="page-header">

            <div>
                <h1>Edit Petugas</h1>
                <p>Ubah data akun petugas sesuai kewenangan role Anda.</p>
            </div>

            <a href="/data_petugas" class="btn-back">
                <i class="fa fa-arrow-left"></i> Kembali ke Daftar
            </a>

        </div>

        <div class="form-card">
            <form action="{{ route('petugas.update', ['id' => $petugas->id_petugas]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-grid">

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" value="{{ old('nama', $petugas->nama) }}" required>
                    </div>

                    <div class="form-group">
                        <label>NIK</label>
                        <input type="text" name="nik" value="{{ old('nik', $petugas->nik) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Jabatan</label>
                        @if($canChooseRole)
                            <select name="jabatan" required>
                                <option value="kader" @selected(old('jabatan', $petugas->jabatan) === 'kader')>kader</option>
                                <option value="kepala_kader" @selected(old('jabatan', $petugas->jabatan) === 'kepala_kader')>kepala_kader</option>
                                <option value="super_admin" @selected(old('jabatan', $petugas->jabatan) === 'super_admin')>super_admin</option>
                            </select>
                        @elseif($isKepalaKader)
                            <select name="jabatan" required>
                                <option value="kader" selected>kader</option>
                            </select>
                        @else
                            <input type="hidden" name="jabatan" value="kader">
                            <input type="text" value="kader" readonly>
                            <small style="color:#6b7280; font-size:12px;">Role dikunci ke kader untuk kepala_kader.</small>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>Nomor WhatsApp</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $petugas->no_hp) }}" required>
                    </div>

                    <div class="form-group full">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email', $petugas->email) }}" required>
                    </div>

                </div>

                <div class="form-action">

                    <a href="{{ route('petugas.index') }}" class="btn-cancel">Batal</a>

                    <button type="submit" class="btn-save">
                        <i class="fa fa-save"></i> Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection