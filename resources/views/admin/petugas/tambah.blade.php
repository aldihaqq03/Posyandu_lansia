@extends('layout.sidebar')

@section('title','Tambah Petugas')

@push('styles')
    @vite('resources/css/app.css')
    @vite('resources/css/cssAdmin/tambah_data_petugas.css')
@endpush

@section('content')

<div class="page-container">

<div class="page-header">

<div>
<h1>Form Tambah Petugas SIMPEL</h1>
<p>Daftarkan petugas atau kader baru untuk sistem informasi peduli lansia.</p>
</div>

<a href="/petugas" class="btn-back">
<i class="fa fa-arrow-left"></i> Kembali ke Daftar
</a>

</div>


<div class="form-card">

<form action="/petugas/store" method="POST" enctype="multipart/form-data">
@csrf

<div class="photo-upload">

<div class="upload-box">
<i class="fa fa-user"></i>
<input type="file" name="foto">
</div>

<div class="upload-info">
<h4>Foto Profil</h4>
<p>Unggah foto profil petugas. Gunakan format JPG atau PNG dengan ukuran maksimal 2MB.</p>
</div>

</div>


<div class="form-grid">

<div class="form-group">
<label>Nama Lengkap</label>
<input type="text" name="nama" placeholder="Masukkan nama lengkap">
</div>

<div class="form-group">
<label>NIK</label>
<input type="text" name="nik" placeholder="16 digit NIK">
</div>

<div class="form-group">
<label>Jabatan</label>
<select name="jabatan">
<option>Pilih Jabatan</option>
<option>Kader</option>
<option>Ketua Kader</option>
<option>Tenaga Kesehatan</option>
</select>
</div>

<div class="form-group">
<label>Wilayah Posyandu</label>
<input type="text" name="wilayah" placeholder="Contoh: Posyandu Mawar 01">
</div>

<div class="form-group">
<label>Nomor WhatsApp</label>
<input type="text" name="whatsapp" placeholder="+62">
</div>

<div class="form-group">
<label>Email</label>
<input type="email" name="email" placeholder="nama@gmail.com">
</div>

<div class="form-group full">
<label>Kata Sandi</label>
<input type="password" name="password" placeholder="Minimal 8 karakter">
</div>

</div>


<div class="form-action">

<a href="/petugas" class="btn-cancel">Batal</a>

<button class="btn-save">
<i class="fa fa-save"></i> Simpan Petugas
</button>

</div>

</form>

</div>

</div>

@endsection