<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | SIMPEL</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            background: #1a4fa0;
            /* fallback */
        }

        /* Background gradasi biru lembut seperti halaman verifikasi */
        .bg-layer {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 110% 45% at 50% 0%, #0d3278 0%, transparent 55%),
                radial-gradient(ellipse 110% 45% at 50% 100%, #0d3278 0%, transparent 55%),
                radial-gradient(ellipse 80% 60% at 50% 50%, #c8dcf8 0%, #a8c4f0 40%, #5a90d8 80%, #1a4fa0 100%);
            pointer-events: none;
        }

        /* Blur effect lembut di pojok */
        .bg-blob {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .bg-blob::before {
            content: '';
            position: absolute;
            top: 4%;
            left: -6%;
            width: 55%;
            height: 38%;
            background: radial-gradient(ellipse, rgba(255, 255, 255, 0.30) 0%, transparent 70%);
            filter: blur(20px);
            border-radius: 60% 40% 55% 45%;
        }

        .bg-blob::after {
            content: '';
            position: absolute;
            bottom: 4%;
            right: -8%;
            width: 55%;
            height: 36%;
            background: radial-gradient(ellipse, rgba(255, 255, 255, 0.24) 0%, transparent 70%);
            filter: blur(22px);
            border-radius: 40% 60% 45% 55%;
        }

        /* Card login – mirip dengan card verifikasi */
        .login-card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 480px;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            border-radius: 32px;
            padding: 2.5rem 2rem;
            box-shadow: 0 20px 60px rgba(13, 50, 120, 0.18), 0 0 0 1px rgba(255, 255, 255, 0.6) inset;
            transition: transform 0.3s ease;
            text-align: center;
        }

        .login-card:hover {
            transform: translateY(-5px);
        }

        /* Header dengan logo */
        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-ring {
            width: 70px;
            height: 70px;
            background: linear-gradient(145deg, #2278e0, #1255b0);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            box-shadow: 0 10px 20px rgba(18, 85, 176, 0.3);
        }

        .logo-ring svg {
            width: 34px;
            height: 34px;
            stroke: white;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .brand-header h1 {
            font-size: 2rem;
            font-weight: 800;
            color: #0f2a6e;
            letter-spacing: -0.5px;
        }

        .brand-header p {
            color: #6b84b0;
            font-size: 0.85rem;
            margin-top: 6px;
        }

        /* Alert styling */
        .alert-error,
        .alert-success {
            padding: 0.9rem 1.2rem;
            border-radius: 20px;
            font-size: 0.85rem;
            margin-bottom: 1.8rem;
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fff;
            border-left: 4px solid;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            text-align: left;
        }

        .alert-error {
            border-left-color: #ef4444;
            color: #b91c1c;
        }

        .alert-success {
            border-left-color: #10b981;
            color: #0a5c2e;
        }

        /* Form fields */
        .input-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .input-group label {
            display: block;
            font-weight: 700;
            font-size: 0.85rem;
            margin-bottom: 0.6rem;
            color: #1e293b;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i:first-child {
            position: absolute;
            left: 18px;
            color: #94a3b8;
            font-size: 1.1rem;
        }

        .input-wrapper input {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 3rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 40px;
            font-size: 0.95rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all 0.25s;
            background: #ffffff;
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        .toggle-password {
            position: absolute;
            right: 18px;
            background: none;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            font-size: 1.2rem;
        }

        /* Checkbox */
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 1rem 0 1.8rem;
            text-align: left;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #3b82f6;
            cursor: pointer;
        }

        .checkbox-wrapper label {
            font-size: 0.85rem;
            font-weight: 500;
            color: #334155;
            cursor: pointer;
        }

        /* Tombol login */
        .btn-login {
            background: linear-gradient(145deg, #2278e0, #1255b0);
            border: none;
            padding: 0.9rem 1.5rem;
            border-radius: 60px;
            font-weight: 700;
            font-size: 1rem;
            color: white;
            cursor: pointer;
            transition: 0.25s;
            box-shadow: 0 8px 20px rgba(18, 85, 176, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(18, 85, 176, 0.45);
        }

        .forgot-link {
            display: inline-block;
            text-align: center;
            font-size: 0.85rem;
            font-weight: 600;
            color: #3b82f6;
            text-decoration: none;
            margin-top: 1rem;
        }

        .forgot-link:hover {
            text-decoration: underline;
            color: #1e40af;
        }

        .register-link {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.85rem;
            color: #6b84b0;
            border-top: 1px solid #eef2f6;
            padding-top: 1.5rem;
        }

        .register-link a {
            font-weight: 700;
            color: #2278e0;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 560px) {
            .login-card {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>

<body>

    <div class="bg-layer"></div>
    <div class="bg-blob"></div>

    <div class="login-card">
        <div class="brand-header">
            <div class="logo-ring">
                <svg viewBox="0 0 24 24">
                    <path
                        d="M12 2C8.13 2 5 5.13 5 9c0 3.5 2.33 6.48 5.55 7.59L12 22l1.45-5.41C16.67 15.48 19 12.5 19 9c0-3.87-3.13-7-7-7z" />
                    <circle cx="12" cy="9" r="2.5" />
                </svg>
            </div>
            <h1>SIMPEL</h1>
            <p>Sistem Informasi Peduli Lansia</p>
        </div>

        @if (session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <i class="fas fa-exclamation-triangle"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('proses_login') }}">
            @csrf

            <div class="input-group">
                <label for="email">Alamat Email</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="nama@dinas.go.id" required autofocus>
                </div>
            </div>

            <div class="input-group">
                <label for="password">Kata Sandi</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i class="fas fa-eye-slash" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="checkbox-wrapper">
                <input type="checkbox" name="remember" id="remember" value="1"
                    {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-arrow-right-to-bracket"></i> Masuk ke Akun
            </button>

            @if (Route::has('password.request'))
                <div style="text-align: center;">
                    <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
                </div>
            @endif
        </form>

        <div class="register-link">
            Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
        </div>
    </div>

    <script>
        function togglePassword() {
            const pw = document.getElementById('password');
            const eye = document.getElementById('eyeIcon');
            if (pw.type === 'password') {
                pw.type = 'text';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
            } else {
                pw.type = 'password';
                eye.classList.remove('fa-eye');
                eye.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>

</html>
