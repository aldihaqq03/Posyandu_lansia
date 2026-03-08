<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<title>SIMPEL Register</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

@vite([
    'resources/css/style.css',
    'resources/js/register.js'
=======
'resources/css/register.css',
'resources/js/register.js'
>>>>>>> Stashed changes
])

</head>

<body>

<div class="container">
<div class="card">

<div class="logo">
<img src="{{ asset('images/logo_posyandu.png') }}">
</div>

<h2 class="title-main">Daftar Akun SIMPEL</h2>
<h4 class="title">SISTEM INFORMASI PEDULI LANSIA</h4>

<form id="registerForm" action="{{ route('register') }}" method="POST">
@csrf

<div class="form-group">
<label>Nama Lengkap</label>
<div class="input-wrapper">
<i class="fa fa-user"></i>
<input type="text" id="nama" name="nama" required>
</div>
</div>

<div class="form-group">
<label>Email</label>
<div class="input-wrapper">
<i class="fa fa-envelope"></i>
<input type="email" id="email" name="email" required>
</div>
<span class="error" id="emailError"></span>
</div>

<div class="form-group">
<label>NIK</label>
<div class="input-wrapper">
<i class="fa fa-id-card"></i>
<input type="text" id="nik" name="nik" maxlength="16" required>
</div>
<span class="error" id="nikError"></span>
</div>

<div class="form-group">
<label>Nomor WhatsApp</label>
<div class="input-wrapper">
<i class="fa fa-phone"></i>
<input type="text" name="whatsapp" required>
</div>
</div>

<div class="form-group">
<label>Jabatan</label>
<div class="input-wrapper">
<i class="fa fa-user-tie"></i>

<select name="jabatan" required>
<option value="">Pilih Jabatan</option>
<option value="Kader">Kader</option>
<option value="Petugas">Petugas</option>
<option value="Admin">Admin</option>
</select>

</div>
</div>

<div class="form-group">
<label>Wilayah Kerja</label>
<div class="input-wrapper">
<i class="fa fa-map-marker-alt"></i>

<select id="wilayah" name="wilayah_kerja" required>
<option value="">Pilih Wilayah Kerja</option>
<option value="Posyandu Mawar">Posyandu Mawar</option>
<option value="Posyandu Melati">Posyandu Melati</option>
<option value="Posyandu Anggrek">Posyandu Anggrek</option>
<option value="Posyandu Dahlia">Posyandu Dahlia</option>
</select>

</div>
</div>

<div class="form-group">
<label>Password</label>
<div class="input-wrapper">
<i class="fa fa-lock icon-left"></i>
<input type="password" id="password" name="password" required>
<i class="fa fa-eye password-toggle" onclick="togglePassword('password', this)"></i>
</div>
<span class="error" id="passError"></span>
</div>

<div class="form-group">
<label>Konfirmasi Password</label>
<div class="input-wrapper">
<i class="fa fa-lock icon-left"></i>
<input type="password" id="confirmPassword" name="password_confirmation" required>
<i class="fa fa-eye password-toggle" onclick="togglePassword('confirmPassword', this)"></i>
</div>
<span class="error" id="confirmError"></span>
</div>

<button type="submit" class="btn-primary">
Daftar Sekarang
</button>

<div class="login-link">
Sudah punya akun?
<a href="{{ route('login') }}">Masuk</a>
</div>

</form>

</div>
</div>

</body>
</html>