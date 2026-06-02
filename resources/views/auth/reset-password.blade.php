<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password | SIMPEL</title>
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
            background: #0a0f1a;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow-x: hidden;
        }

        /* ========= AURORA EFFECT (BACKGROUND SAJA) ========= */
        .aurora {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }

        .aurora-layer {
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 40%, rgba(0, 255, 180, 0.3), transparent 50%),
                radial-gradient(circle at 70% 60%, rgba(100, 80, 255, 0.35), transparent 60%),
                radial-gradient(circle at 40% 80%, rgba(0, 200, 255, 0.25), transparent 70%);
            filter: blur(80px);
            animation: auroraMove 18s infinite alternate ease-in-out;
            opacity: 0.8;
        }

        .aurora-layer2 {
            position: absolute;
            width: 180%;
            height: 180%;
            background: radial-gradient(circle at 80% 20%, rgba(80, 220, 255, 0.35), transparent 60%),
                radial-gradient(circle at 20% 70%, rgba(150, 100, 255, 0.3), transparent 70%);
            filter: blur(100px);
            animation: auroraMove2 22s infinite alternate ease-in-out;
            opacity: 0.7;
        }

        @keyframes auroraMove {
            0% {
                transform: translate(-5%, -5%) rotate(0deg);
            }

            100% {
                transform: translate(5%, 5%) rotate(2deg);
            }
        }

        @keyframes auroraMove2 {
            0% {
                transform: translate(5%, 3%) rotate(0deg);
            }

            100% {
                transform: translate(-3%, -4%) rotate(-3deg);
            }
        }

        /* Bintang (sedikit) */
        .stars {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .star {
            position: absolute;
            background: white;
            border-radius: 50%;
            opacity: 0;
            animation: twinkle 3s infinite alternate;
        }

        @keyframes twinkle {
            0% {
                opacity: 0.2;
                transform: scale(1);
            }

            100% {
                opacity: 0.9;
                transform: scale(1.2);
            }
        }

        /* ========= CARD PUTIH (SOLID) ========= */
        .reset-card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 520px;
            background: #ffffff;
            border-radius: 48px;
            padding: 2.5rem;
            box-shadow: 0 30px 50px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .reset-card:hover {
            transform: translateY(-5px);
        }

        /* Header */
        .brand-header {
            text-align: center;
            margin-bottom: 1.8rem;
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
            font-size: 1.7rem;
            font-weight: 800;
            color: #111827;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .greeting-sub {
            font-size: 0.85rem;
            line-height: 1.4;
            color: #64748b;
            margin-bottom: 1.8rem;
            text-align: center;
        }

        /* Alert */
        .alert-error {
            padding: 0.9rem 1rem;
            border-radius: 28px;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fff1f1;
            border-left: 4px solid #ef4444;
            color: #b91c1c;
        }

        .alert-error i {
            color: #ef4444;
        }

        /* Form */
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
            z-index: 2;
        }

        .input-wrapper input {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 2.8rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 40px;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            color: #1e293b;
            transition: all 0.25s;
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        .input-wrapper input::placeholder {
            color: #94a3b8;
        }

        .input-wrapper input[readonly] {
            background: #f8fafc;
            color: #64748b;
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            background: none;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            font-size: 1.1rem;
            z-index: 2;
        }

        /* Strength meter */
        .strength-bar {
            height: 6px;
            border-radius: 3px;
            background: #e2e8f0;
            margin-top: 12px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            transition: width 0.3s, background 0.3s;
            border-radius: 3px;
        }

        .strength-label {
            font-size: 0.7rem;
            margin-top: 6px;
            color: #64748b;
        }

        .field-error {
            font-size: 0.75rem;
            color: #ef4444;
            margin-top: 6px;
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
            box-shadow: 0 10px 18px rgba(43, 127, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            margin-top: 0.8rem;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 28px rgba(43, 127, 255, 0.4);
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
            .reset-card {
                padding: 1.5rem;
            }

            .greeting {
                font-size: 1.4rem;
            }
        }
    </style>
</head>

<body>

    <div class="aurora">
        <div class="aurora-layer"></div>
        <div class="aurora-layer2"></div>
    </div>

    <div class="stars" id="starsContainer"></div>

    <div class="reset-card">
        <div class="brand-header">
            <div class="logo-icon">
                <i class="fas fa-lock-open"></i>
            </div>
            <h1>SIMPEL</h1>
            <p>Sistem Informasi Peduli Lansia</p>
        </div>

        <div class="greeting">Buat Password Baru</div>
        <div class="greeting-sub">Password baru minimal 6 karakter & kuat untuk akun Anda</div>

        @if ($errors->any())
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="input-group">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" value="{{ old('email', $email) }}" readonly
                        required>
                </div>
                @error('email')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-group">
                <label for="password">Password Baru</label>
                <div class="input-wrapper">
                    <i class="fas fa-key"></i>
                    <input type="password" id="password" name="password" placeholder="Minimal 6 karakter" required
                        oninput="checkStrength(this.value)">
                    <button type="button" class="toggle-password" onclick="togglePassword('password')">
                        <i class="fas fa-eye-slash" id="passwordEye"></i>
                    </button>
                </div>
                <div class="strength-bar">
                    <div class="strength-fill" id="strengthFill"></div>
                </div>
                <div class="strength-label" id="strengthLabel">Masukkan password baru</div>
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-check-circle"></i>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        placeholder="Ulangi password baru" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">
                        <i class="fas fa-eye-slash" id="confirmEye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Simpan Password Baru
            </button>
        </form>

        <a href="{{ route('login') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke login
        </a>
    </div>

    <script>
        // ========= BINTANG SEDIKIT =========
        function generateStars() {
            const container = document.getElementById('starsContainer');
            container.innerHTML = '';
            const starCount = 80;
            for (let i = 0; i < starCount; i++) {
                const star = document.createElement('div');
                star.classList.add('star');
                const size = Math.random() * 2 + 1;
                star.style.width = size + 'px';
                star.style.height = size + 'px';
                star.style.left = Math.random() * 100 + '%';
                star.style.top = Math.random() * 100 + '%';
                star.style.animationDelay = Math.random() * 5 + 's';
                star.style.animationDuration = Math.random() * 3 + 2 + 's';
                container.appendChild(star);
            }
        }
        generateStars();

        // ========= TOGGLE PASSWORD =========
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const eyeIcon = fieldId === 'password' ? document.getElementById('passwordEye') : document.getElementById(
                'confirmEye');
            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            }
        }

        // ========= STRENGTH METER =========
        function checkStrength(val) {
            const fill = document.getElementById('strengthFill');
            const label = document.getElementById('strengthLabel');
            let score = 0;
            if (val.length >= 6) score++;
            if (val.length >= 10) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const levels = [{
                    width: 0,
                    color: 'transparent',
                    text: 'Masukkan password baru'
                },
                {
                    width: 20,
                    color: '#ef4444',
                    text: 'Sangat lemah'
                },
                {
                    width: 40,
                    color: '#f97316',
                    text: 'Lemah'
                },
                {
                    width: 60,
                    color: '#eab308',
                    text: 'Cukup'
                },
                {
                    width: 80,
                    color: '#22c55e',
                    text: 'Kuat'
                },
                {
                    width: 100,
                    color: '#3b82f6',
                    text: 'Sangat kuat'
                }
            ];
            const level = levels[score];
            fill.style.width = level.width + '%';
            fill.style.backgroundColor = level.color;
            label.textContent = level.text;
            label.style.color = level.color === 'transparent' ? '#64748b' : level.color;
        }
    </script>
</body>

</html>
