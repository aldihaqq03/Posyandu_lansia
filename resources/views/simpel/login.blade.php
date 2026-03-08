<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login SIMPEL</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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

            <!-- JUDUL DI DALAM CARD -->
            <h2 class="title-main">SIMPEL</h2>
                <h4 class="title">SISTEM INFORMASI PEDULI LANSIA</h3>


                    <form>

                    <label>Nama Lengkap</label>
                        <input type="text" placeholder="Masukkan nama lengkap">

                    <label>Kata Sandi</label>
                        <input type="password" placeholder="********">
                    
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