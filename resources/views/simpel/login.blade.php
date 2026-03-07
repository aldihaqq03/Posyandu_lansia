<!DOCTYPE html>
<html lang="id">

<head>
<meta charset="UTF-8">
<title>Login SIMPEL</title>

@vite([
'resources/css/login.css',
'resources/js/login.js'
])

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>

<div class="login-wrapper">

<div class="login-card">

<div class="logo">
<img src="{{ asset('images/logo_posyandu.png') }}" alt="Logo Posyandu">
</div>

<h2 class="title-main">SIMPEL</h2>
<p class="subtitle">Sistem Informasi Peduli Lansia</p>

@if(session('error'))
<p style="color:red;text-align:center;">
{{ session('error') }}
</p>
@endif

<form method="POST" action="{{ route('login') }}">
@csrf

<!-- EMAIL -->
<div class="input-group">
<i class="fa fa-envelope"></i>
<input type="email" name="email" placeholder="Email" required>
</div>

<!-- PASSWORD -->
<div class="input-group">
<i class="fa fa-lock"></i>
<input type="password" id="password" name="password" placeholder="Password" required>

<i class="fa fa-eye toggle-password"
onclick="togglePassword('password',this)"></i>
</div>

<button type="submit" class="btn-login">
Masuk
</button>

<div class="auth-link">
Belum punya akun?
<a href="{{ route('register') }}">Daftar</a>
</div>

</form>

</div>

</div>

</body>
</html>