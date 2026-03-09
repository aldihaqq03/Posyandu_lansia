<!DOCTYPE html>
<html lang="id">

<head>
<meta charset="UTF-8">
<title>Login SIMPEL</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

@vite([
'resources/css/login.css',
'resources/js/login.js'
])

</head>

<body>

<div class="login-wrapper">

<div class="login-card">

<div class="logo">
<img src="{{ asset('images/logo_posyandu.png') }}" alt="logo">
</div>

<h2 class="title-main">SIMPEL</h2>
<p class="subtitle">SISTEM INFORMASI PEDULI LANSIA</p>

<form>

<label>Nama Lengkap</label>
<div class="input-group">
<i class="fa fa-user"></i>
<input type="text" placeholder="Masukkan nama lengkap">
</div>

<label>Kata Sandi</label>
<div class="input-group">
<i class="fa fa-lock"></i>
<input type="password" id="password" placeholder="********">
<i class="fa fa-eye toggle-password"
onclick="togglePassword('password',this)"></i>
</div>

<button type="submit" class="btn-login">
Masuk
</button>

</form>

<div class="auth-link">
Belum punya akun?
<a href="{{ route('register') }}">Daftar</a>
</div>

</div>

</div>

</body>
</html>