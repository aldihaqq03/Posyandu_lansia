<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi OTP | SIMPEL</title>
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

        /* Bintang (sedikit) */
        #starCanvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        /* Efek black hole (lingkaran glow oranye-kuning) */
        .blackhole {
            position: fixed;
            bottom: -150px;
            right: -150px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255, 140, 0, 0.5), rgba(255, 69, 0, 0.2), transparent 70%);
            border-radius: 50%;
            filter: blur(60px);
            z-index: 0;
            pointer-events: none;
            animation: pulseGlow 6s infinite alternate;
        }

        .blackhole::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, #ff8c00, #ff4500, #2c0e00);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            filter: blur(30px);
            opacity: 0.7;
        }

        @keyframes pulseGlow {
            0% {
                opacity: 0.5;
                transform: scale(1);
            }

            100% {
                opacity: 1;
                transform: scale(1.1);
            }
        }

        /* Card utama */
        .otp-card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 540px;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(2px);
            border-radius: 48px;
            padding: 2.5rem;
            box-shadow: 0 30px 50px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease;
        }

        .otp-card:hover {
            transform: translateY(-5px);
        }

        /* Header */
        .brand-header {
            text-align: center;
            margin-bottom: 1.5rem;
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
            font-size: 0.85rem;
        }

        .greeting {
            font-size: 1.6rem;
            font-weight: 800;
            color: #111827;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .greeting-sub {
            font-size: 0.85rem;
            line-height: 1.4;
            color: #64748b;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        /* Alert */
        .alert-error,
        .alert-success {
            padding: 0.9rem 1rem;
            border-radius: 28px;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
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

        /* Field */
        .input-group {
            margin-bottom: 1.5rem;
        }

        .input-group label {
            display: block;
            font-weight: 700;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            color: #1e293b;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            color: #94a3b8;
            font-size: 1rem;
        }

        .input-wrapper input {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 2.8rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 40px;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.25s;
            background: #ffffff;
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        input[readonly] {
            background: #f8fafc;
            color: #1e293b;
            cursor: default;
        }

        /* OTP Grid - perbaikan agar teks terlihat */
        .otp-grid {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin: 0.5rem 0 0.5rem;
        }

        .otp-box {
            width: 60px;
            height: 70px;
            text-align: center;
            font-size: 1.8rem;
            font-weight: 800;
            font-family: 'Inter', monospace;
            background: #ffffff;
            border: 2px solid #e2e8f0;
            border-radius: 20px;
            color: #0f172a;
            outline: none;
            transition: all 0.2s;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .otp-box:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
            transform: scale(1.02);
        }

        .otp-box.filled {
            border-color: #3b82f6;
            background: #eff6ff;
            color: #1d4ed8;
        }

        .otp-hint {
            font-size: 0.75rem;
            color: #64748b;
            text-align: center;
            margin-top: 0.5rem;
        }

        /* Tombol */
        .btn-submit {
            background: linear-gradient(135deg, #2b7fff, #1d4ed8);
            border: none;
            padding: 1rem;
            border-radius: 60px;
            font-weight: 800;
            font-size: 1rem;
            color: white;
            cursor: pointer;
            transition: 0.25s;
            box-shadow: 0 10px 18px rgba(43, 127, 255, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            margin-top: 0.5rem;
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
            font-size: 0.85rem;
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
            .otp-card {
                padding: 1.5rem;
            }

            .otp-box {
                width: 45px;
                height: 55px;
                font-size: 1.4rem;
            }

            .greeting {
                font-size: 1.3rem;
            }
        }
    </style>
</head>

<body>

    <canvas id="starCanvas"></canvas>
    <div class="blackhole"></div>

    <div class="otp-card">
        <div class="brand-header">
            <div class="logo-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h1>SIMPEL</h1>
            <p>Sistem Informasi Peduli Lansia</p>
        </div>

        <div class="greeting">Verifikasi OTP</div>
        <div class="greeting-sub">Kode verifikasi 6 digit telah dikirim ke email Anda.</div>

        @if (session('status'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i> {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <i class="fas fa-exclamation-triangle"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.otp.verify') }}" id="otpForm">
            @csrf

            <div class="input-group">
                <label for="email">Alamat Email</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" value="{{ old('email', $email ?? '') }}"
                        readonly required>
                </div>
            </div>

            <input type="hidden" name="otp" id="otpHidden">

            <div class="input-group">
                <label>Kode OTP 6 Digit</label>
                <div class="otp-grid" id="otpGrid">
                    <input type="text" class="otp-box" inputmode="numeric" maxlength="1" autocomplete="off"
                        aria-label="Digit 1">
                    <input type="text" class="otp-box" inputmode="numeric" maxlength="1" autocomplete="off"
                        aria-label="Digit 2">
                    <input type="text" class="otp-box" inputmode="numeric" maxlength="1" autocomplete="off"
                        aria-label="Digit 3">
                    <input type="text" class="otp-box" inputmode="numeric" maxlength="1" autocomplete="off"
                        aria-label="Digit 4">
                    <input type="text" class="otp-box" inputmode="numeric" maxlength="1" autocomplete="off"
                        aria-label="Digit 5">
                    <input type="text" class="otp-box" inputmode="numeric" maxlength="1" autocomplete="off"
                        aria-label="Digit 6">
                </div>
                <p class="otp-hint"><i class="fas fa-clock"></i> Kode berlaku 10 menit. Isi semua 6 digit.</p>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-arrow-right"></i> Lanjut Reset Password
            </button>
        </form>

        <a href="{{ route('password.request') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke input email
        </a>
    </div>

    <script>
        // Bintang jatuh (sedikit)
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
            const starCount = Math.min(100, Math.floor(width * 0.07) + 30);
            for (let i = 0; i < starCount; i++) {
                stars.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    radius: Math.random() * 2 + 0.6,
                    alpha: Math.random() * 0.6 + 0.3,
                    speedY: Math.random() * 0.7 + 0.2,
                    speedX: (Math.random() - 0.5) * 0.15,
                    flicker: Math.random() * 0.03
                });
            }
        }

        function drawStars() {
            ctx.clearRect(0, 0, width, height);
            for (let star of stars) {
                ctx.beginPath();
                ctx.arc(star.x, star.y, star.radius, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(255, 240, 190, ${star.alpha + Math.sin(Date.now() * star.flicker) * 0.1})`;
                ctx.fill();
                ctx.shadowBlur = star.radius * 1.5;
                ctx.shadowColor = 'rgba(255,220,150,0.5)';
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

        window.addEventListener('resize', () => resizeCanvas());
        resizeCanvas();
        updateStars();

        // ==================== OTP LOGIC (Perbaikan input visibility) ====================
        const boxes = document.querySelectorAll('.otp-box');
        const otpHidden = document.getElementById('otpHidden');
        const formOtp = document.getElementById('otpForm');

        function syncOtp() {
            const otpValue = Array.from(boxes).map(b => b.value).join('');
            otpHidden.value = otpValue;
        }

        boxes.forEach((box, idx) => {
            // Hanya angka
            box.addEventListener('input', (e) => {
                let val = e.target.value;
                val = val.replace(/\D/g, '');
                e.target.value = val;
                if (val) {
                    box.classList.add('filled');
                } else {
                    box.classList.remove('filled');
                }
                syncOtp();
                if (val && idx < boxes.length - 1) {
                    boxes[idx + 1].focus();
                }
            });

            // Backspace & panah
            box.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !box.value && idx > 0) {
                    boxes[idx - 1].focus();
                    boxes[idx - 1].value = '';
                    boxes[idx - 1].classList.remove('filled');
                    syncOtp();
                }
                if (e.key === 'ArrowLeft' && idx > 0) {
                    boxes[idx - 1].focus();
                }
                if (e.key === 'ArrowRight' && idx < boxes.length - 1) {
                    boxes[idx + 1].focus();
                }
            });

            // Paste handler
            box.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g,
                    '');
                const digits = pasteData.slice(0, 6).split('');
                digits.forEach((digit, i) => {
                    if (boxes[idx + i]) {
                        boxes[idx + i].value = digit;
                        boxes[idx + i].classList.add('filled');
                    }
                });
                syncOtp();
                const nextIndex = idx + digits.length;
                if (boxes[nextIndex]) boxes[nextIndex].focus();
            });
        });

        formOtp.addEventListener('submit', (e) => {
            syncOtp();
            if (otpHidden.value.length !== 6) {
                e.preventDefault();
                alert('Silakan isi semua 6 digit kode OTP.');
            }
        });

        // Fokus ke kotak pertama saat halaman dimuat
        if (boxes.length) boxes[0].focus();
    </script>

</body>

</html>
