<!DOCTYPE html>
<htm
l lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login SIMPEL</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite([
        'resources/css/login.css',
        'resources/js/login.js'
    ])

</head>

<body>
    
<div class="login-wrapper">
        
<div class="login-card">

<div class="logo">
<x-logo style="width: 80px; height: 80px; color: #0ea5e9; margin: 0 auto; display: block;" />
</div>

<h2 class="title-main">SIMPEL</h2>
<p class="subtitle">SISTEM INFORMASI PEDULI LANSIA</p>

<form method="POST" action="{{ route('login') }}">
@csrf



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


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ session('error') }}',
            confirmButtonColor: '#0ea5e9'
        });
    @endif

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#0ea5e9'
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: '{{ $errors->first() }}',
            confirmButtonColor: '#0ea5e9'
        });
    @endif
</script>
</body>
</html>