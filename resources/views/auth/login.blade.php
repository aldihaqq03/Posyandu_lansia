<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | SIMPEL</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: radial-gradient(circle at 10% 20%, #0a0f2e, #030514, #0b0f1c);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Canvas untuk bintang (sedikit) */
        #starCanvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        /* Card Login – besar, glassmorphic */
        .login-card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 520px;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(2px);
            border-radius: 48px;
            padding: 2.8rem 2.5rem;
            box-shadow: 0 30px 50px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
        }

        /* Header card */
        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo-icon {
            width: 500px;
            height: 500px;

            border-radius: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;

        }


        .logo-icon i {
            font-size: 34px;
            color: white;
        }

        .brand-header h1 {
            font-size: 2.1rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .brand-header p {
            color: #5b6e8c;
            font-size: 0.9rem;
            margin-top: 6px;
        }

        /* Alert */
        .alert-error,
        .alert-success {
            padding: 1rem 1.2rem;
            border-radius: 28px;
            font-size: 0.85rem;
            margin-bottom: 1.8rem;
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fff;
            border-left: 5px solid;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
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
            margin-bottom: 1.6rem;
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

        }

        .input-wrapper i:first-child {
            position: absolute;
            left: 18px;
            color: #94a3b8;
            font-size: 1.1rem;
        }

        .input-wrapper input {
            width: 100%;
            padding: 0.9rem 3rem 0.9rem 2.8rem;
            /* kiri icon + kanan space eye */
            border: 1.5px solid #e2e8f0;
            border-radius: 40px;
            font-size: 0.95rem;
        }


        .input-wrapper input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            font-size: 1.1rem;
            z-index: 2;
        }

        .password-wrapper {
            position: relative;
            width: 100%;
        }

        .password-wrapper input {
            width: 100%;
            padding: 12px 44px 12px 14px;
            /* kanan buat icon mata */
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            outline: none;
        }

        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-wrapper input {
            width: 100%;
            padding: 1rem 3rem 1rem 3rem;
            /* kiri icon lock + kanan eye */
            border: 1.5px solid #e2e8f0;
            border-radius: 40px;
            /* SAMAIN dengan email */
            font-size: 1rem;
            transition: all 0.25s;
        }

        .password-wrapper input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
        }

        .password-wrapper .toggle-password {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            font-size: 1.2rem;
            z-index: 5;
        }

        /* ICON MATA */
        .password-wrapper .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            cursor: pointer;
            color: #94a3b8;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .password-wrapper .toggle-password:hover {
            color: #3b82f6;
        }

        .form-group-modal .password-wrapper {
            display: block;
        }

        /* Checkbox tanpa border */
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 1.2rem 0 2rem;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: #3b82f6;
            margin: 0;
            border: none;
            box-shadow: none;
            cursor: pointer;
        }

        .checkbox-wrapper label {
            font-size: 0.9rem;
            font-weight: 500;
            color: #334155;
            cursor: pointer;
        }

        /* Actions: tombol login BESAR & lupa password */
        .form-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            /* atau cover kalau mau full kotak */
        }

        .logo-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #fff;
            border: 3px solid rgba(43, 127, 255, 0.6);
            box-shadow: 0 12px 20px rgba(43, 127, 255, 0.25);

            overflow: hidden;
        }

        .btn-login {
            background: linear-gradient(135deg, #2b7fff, #1d4ed8);
            border: none;
            padding: 1rem 1.5rem;
            border-radius: 60px;
            font-weight: 800;
            font-size: 1.1rem;
            color: white;
            cursor: pointer;
            transition: 0.25s;
            box-shadow: 0 10px 18px rgba(43, 127, 255, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 25px rgba(43, 127, 255, 0.5);
        }

        .forgot-link {
            text-align: center;
            font-size: 0.9rem;
            font-weight: 600;
            color: #3b82f6;
            text-decoration: none;
            margin-top: 0.5rem;
        }

        .forgot-link:hover {
            text-decoration: underline;
            color: #1e40af;
        }

        .register-link {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #5b6e8c;
            border-top: 1px solid #eef2f6;
            padding-top: 1.5rem;
        }

        .register-link a {
            font-weight: 700;
            color: #2b7fff;
            text-decoration: none;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .toggle-password {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            font-size: 1.2rem;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }

        /* Pastikan input memiliki padding kanan cukup agar teks tidak tertutup ikon */
        .input-wrapper input {
            padding-right: 3rem;
            /* memberi ruang untuk ikon mata */
        }

        /* Responsive */
        @media (max-width: 560px) {
            .login-card {
                padding: 2rem 1.5rem;
            }

            .btn-login {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>

    <canvas id="starCanvas"></canvas>

    <div class="login-card">
        <div class="brand-header">
            <div class="logo-icon">
                <img src="{{ asset('assets/img/logo_simpel.png') }}" alt="Logo SIMPEL">
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

                <div class="password-wrapper">
                    <i class="fas fa-lock" style="position:absolute; left:18px; color:#94a3b8;"></i>

                    <input type="password" name="password" id="password" placeholder="Minimal 8 karakter" required>

                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">
                        <i class="fa fa-eye-slash" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>

            <div class="checkbox-wrapper">
                <input type="checkbox" name="remember" id="remember" value="1"
                    {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Ingat saya</label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-login">
                    <i class="fas fa-arrow-right-to-bracket"></i> MASUK
                </button>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
                @endif
            </div>
        </form>


    </div>

    <script>
        // ==================== BINTANG (SEDIKIT, LAMBAT JATUH) ====================
        const canvas = document.getElementById('starCanvas');
        const ctx = canvas.getContext('2d');
        let width, height;
        let stars = [];

        function resizeCanvas() {
            width = window.innerWidth;
            height = window.innerHeight;
            canvas.width = width;
            canvas.height = height;
            initStars();
        }

        function initStars() {
            stars = [];
            // Jumlah bintang lebih sedikit (60-100 tergantung lebar)
            const starCount = Math.min(120, Math.floor(width * 0.08) + 40);
            for (let i = 0; i < starCount; i++) {
                stars.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    radius: Math.random() * 2.2 + 0.8,
                    alpha: Math.random() * 0.6 + 0.3,
                    speedY: Math.random() * 0.8 + 0.3, // jatuh pelan
                    speedX: (Math.random() - 0.5) * 0.2,
                    flicker: Math.random() * 0.03
                });
            }
        }

        function drawStars() {
            ctx.clearRect(0, 0, width, height);
            for (let star of stars) {
                ctx.beginPath();
                ctx.arc(star.x, star.y, star.radius, 0, Math.PI * 2);
                // Warna bintang putih kekuningan dengan kilap
                ctx.fillStyle = `rgba(255, 245, 210, ${star.alpha + Math.sin(Date.now() * star.flicker) * 0.1})`;
                ctx.fill();
                ctx.shadowBlur = star.radius * 1.5;
                ctx.shadowColor = 'rgba(255,240,180,0.6)';
                ctx.fill();
                ctx.shadowBlur = 0;
            }
        }

        function updateStars() {
            for (let star of stars) {
                star.y += star.speedY;
                star.x += star.speedX;
                if (star.y > height + 30) {
                    star.y = -20;
                    star.x = Math.random() * width;
                }
                if (star.x > width + 30) star.x = -30;
                if (star.x < -30) star.x = width + 30;
            }
            drawStars();
            requestAnimationFrame(updateStars);
        }

        window.addEventListener('resize', () => {
            resizeCanvas();
        });
        resizeCanvas();
        updateStars();

        // Toggle password visibility
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
