<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <title>SIMPEL Register</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

@vite([
    'resources/css/register.css',
    'resources/js/register.js'
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

<form id="registerForm">

<div class="form-group">
<label>Nama Lengkap</label>
<div class="input-wrapper">
<i class="fa fa-user"></i>
<input type="text" id="nama">
</div>
</div>

<div class="form-group">
<label>Email</label>
<div class="input-wrapper">
<i class="fa fa-envelope"></i>
<input type="email" id="email">
</div>
<span class="error" id="emailError"></span>
</div>

<div class="form-group">
<label>NIK</label>
<div class="input-wrapper">
<i class="fa fa-id-card"></i>
<input type="text" id="nik" maxlength="16">
</div>
<span class="error" id="nikError"></span>
</div>

<div class="form-group">
<label>Nomor WhatsApp</label>
<div class="input-wrapper">
<i class="fa fa-phone"></i>
<input type="text">
</div>
</div>
                
                    <div class="form-group">
                    <label>Jabatan</label>
                        <div class="input-wrapper">
<i class="fa fa-user-tie"></i>
<select>
<option>Pilih Jabatan</option>
<option>Kader</option>
<option>Petugas</option>
<option>Admin</option>
</select>
</div>
</div>
                
                    <div class="form-group">
                    <label>Wilayah Kerja</label>
                        <div class="input-wrapper">
<i class="fa fa-map-marker-alt"></i>
<select id="wilayah">
<option value="">Pilih Wilayah Kerja</option>
<option>Posyandu Mawar</option>
<option>Posyandu Melati</option>
<option>Posyandu Anggrek</option>
<option>Posyandu Dahlia</option>
</select>
</div>
</div>

<div class="form-group">
<label>Password</label>
<div class="input-wrapper">
<i class="fa fa-lock icon-left"></i>
<input type="password" id="password">
<i class="fa fa-eye password-toggle" onclick="togglePassword('password', this)"></i>
</div>
<span class="error" id="passError"></span>
</div>

<div class="form-group">
<label>Konfirmasi Password</label>
<div class="input-wrapper">
<i class="fa fa-lock icon-left"></i>
<input type="password" id="confirmPassword">
<i class="fa fa-eye password-toggle" onclick="togglePassword('confirmPassword', this)"></i>
</div>
<span class="error" id="confirmError"></span>
</div>

<button class="btn-primary">Daftar Sekarang</button>

<div class="login-link">
Sudah punya akun?
<a href="{{ route('login') }}">Masuk</a>
</div>
            
</form>
        
    </div>
</div>