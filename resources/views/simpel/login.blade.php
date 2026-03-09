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

<form method="POST" action="{{ route('login') }}">
@csrf

@if(session('error'))
<div style="color: #e74c3c; background-color: #fde2e2; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-size: 14px;">
    {{ session('error') }}
</div>
@endif

<label>Email</label>
<div class="input-group">
<i class="fa fa-envelope"></i>
<input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" required>
</div>

<label>Kata Sandi</label>
<div class="input-group">
<i class="fa fa-lock"></i>
<input type="password" id="password" name="password" placeholder="********" required>
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