<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lupa Password | SIMPEL</title>
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

        /* Card utama – glassmorphic, besar */
        .forgot-card {
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

        .forgot-card:hover {
            transform: translateY(-5px);
        }

        /* Header */
        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(145deg, #2b7fff, #1d4ed8);
            border-radius: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            box-shadow: 0 12px 20px rgba(43, 127, 255, 0.3);
        }

        .logo-icon i {
            font-size: 34px;
            color: white;
        }

        .brand-header h1 {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .brand-header p {
            color: #5b6e8c;
            font-size: 0.9rem;
            margin-top: 6px;
        }

        .greeting {
            font-size: 1.8rem;
            font-weight: 800;
            color: #111827;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .greeting-sub {
            font-size: 0.9rem;
            line-height: 1.5;
            color: #64748b;
            margin-bottom: 2rem;
            text-align: center;
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

        /* Form group */
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
            padding: 1rem 1rem 1rem 3rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 40px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.25s;
            background: #ffffff;
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
        }

        /* Tombol besar */
        .btn-submit {
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

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 25px rgba(43, 127, 255, 0.5);
        }

        /* Back link */
        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            color: #3b82f6;
            text-decoration: none;
            margin-top: 1.8rem;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: #1e40af;
            text-decoration: underline;
        }

        @media (max-width: 560px) {
            .forgot-card {
                padding: 2rem 1.5rem;
            }

            .greeting {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <canvas id="starCanvas"></canvas>

    <div class="forgot-card">
        <div class="brand-header">
            <div class="logo-icon">
                <i class="fas fa-key"></i>
            </div>
            <h1>SIMPEL</h1>
            <p>Sistem Informasi Peduli Lansia</p>
        </div>

        <div class="greeting">Lupa Password?</div>
        <div class="greeting-sub">Masukkan email terdaftar, kami akan mengirimkan kode OTP untuk reset password.</div>

        @if (session('status'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i> {{ session('status') }}
            </div>
        @endif

        @if ($errors->has('email'))
            <div class="alert-error">
                <i class="fas fa-exclamation-triangle"></i> {{ $errors->first('email') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="input-group">
                <label for="email">Email Akun Petugas</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="email@dinas.go.id" required autofocus>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-paper-plane"></i> Kirim Kode OTP
            </button>
        </form>

        <a href="{{ route('login') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke halaman login
        </a>
    </div>

    <script>
        // ==================== BINTANG SEDIKIT (SAMA SEPERTI LOGIN) ====================
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
            const starCount = Math.min(120, Math.floor(width * 0.08) + 40);
            for (let i = 0; i < starCount; i++) {
                stars.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    radius: Math.random() * 2.2 + 0.8,
                    alpha: Math.random() * 0.6 + 0.3,
                    speedY: Math.random() * 0.8 + 0.3,
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
    </script>

</body>

</html>
