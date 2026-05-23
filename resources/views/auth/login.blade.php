<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — SIMPEL</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *,
        *::before,
        *::after {
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

            padding: 2rem;

            background:
                linear-gradient(
                    135deg,
                    #eef4fb 0%,
                    #f6f9fc 100%
                );

            overflow: hidden;
        }

        /* =========================
            CARD UTAMA
        ========================== */

        .auth-container {
            width: 100%;
            max-width: 1180px;
            min-height: 680px;

            position: relative;
            display: flex;

            border-radius: 36px;

            overflow: hidden;

            background: rgba(255,255,255,0.88);

            backdrop-filter: blur(14px);

            border: 1px solid rgba(255,255,255,0.7);

            box-shadow:
                0 20px 60px rgba(15, 42, 110, 0.08),
                0 10px 25px rgba(15, 42, 110, 0.05);
        }

        /* =========================
            KIRI FORM
        ========================== */

        .form-section {
            width: 48%;

            background: rgba(255,255,255,0.94);

            padding: 4rem;

            z-index: 10;

            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* =========================
            HEADER
        ========================== */

        .brand-header {
            display: flex;
            align-items: center;
            gap: 14px;

            margin-bottom: 3rem;
        }

        .logo-ring {
            width: 54px;
            height: 54px;

            border-radius: 18px;

            background:
                linear-gradient(
                    145deg,
                    #2b7fff,
                    #1d63d8
                );

            display: flex;
            align-items: center;
            justify-content: center;

            box-shadow:
                0 10px 25px rgba(43,127,255,0.35);
        }

        .logo-ring svg {
            width: 26px;
            height: 26px;

            stroke: white;
            fill: none;

            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .brand-text h1 {
            font-size: 1.4rem;
            font-weight: 800;

            color: #0f172a;
        }

        .brand-text p {
            font-size: 0.75rem;
            font-weight: 600;

            letter-spacing: .08em;
            text-transform: uppercase;

            color: #6b84b0;
        }

        /* =========================
            TEKS
        ========================== */

        .greeting {
            font-size: 2.6rem;
            font-weight: 800;

            line-height: 1.1;

            color: #111827;

            margin-bottom: .7rem;
        }

        .greeting-sub {
            font-size: .98rem;
            line-height: 1.7;

            color: #64748b;

            margin-bottom: 2.4rem;
        }

        /* =========================
            ALERT
        ========================== */

        .alert-error,
        .alert-success {
            padding: 1rem 1.1rem;

            border-radius: 14px;

            font-size: .88rem;

            margin-bottom: 1.5rem;
        }

        .alert-error {
            background: #fff1f1;
            border: 1px solid #ffd5d5;
            color: #d63031;
        }

        .alert-success {
            background: #effcf5;
            border: 1px solid #b8ecd1;
            color: #1f7a4d;
        }

        /* =========================
            FORM
        ========================== */

        .field {
            margin-bottom: 1.4rem;
        }

        label {
            display: block;

            font-size: .88rem;
            font-weight: 700;

            color: #1e293b;

            margin-bottom: .7rem;
        }

        .input-wrap {
            position: relative;
        }

        .ico {
            position: absolute;

            left: 16px;
            top: 50%;

            transform: translateY(-50%);
        }

        .ico svg {
            width: 19px;
            height: 19px;

            stroke: #94a3b8;
            fill: none;

            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;

            border: 1.5px solid #e2e8f0;

            background: #ffffff;

            border-radius: 16px;

            padding:
                1rem
                3rem
                1rem
                3rem;

            font-size: .95rem;
            font-family: inherit;

            color: #1e293b;

            transition: .25s ease;

            outline: none;
        }

        input:focus {
            border-color: #3b82f6;

            box-shadow:
                0 0 0 5px rgba(59,130,246,.10);
        }

        input::placeholder {
            color: #94a3b8;
        }

        /* =========================
            TOGGLE PASSWORD
        ========================== */

        .toggle-pw {
            position: absolute;

            right: 16px;
            top: 50%;

            transform: translateY(-50%);

            background: none;
            border: none;

            cursor: pointer;
        }

        .toggle-pw svg {
            width: 19px;
            height: 19px;

            stroke: #94a3b8;
            fill: none;

            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .toggle-pw:hover svg {
            stroke: #3b82f6;
        }

        .field-error {
            margin-top: .5rem;

            font-size: .82rem;

            color: #ef4444;
        }

        /* =========================
            ACTION
        ========================== */

        .form-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 1rem;

            margin-top: 2rem;
        }

        .btn-forgot {
            font-size: .88rem;
            font-weight: 700;

            color: #64748b;

            text-decoration: none;

            transition: .2s;
        }

        .btn-forgot:hover {
            color: #2563eb;
        }

        .btn-login {
            border: none;

            background:
                linear-gradient(
                    135deg,
                    #2b7fff,
                    #1d63d8
                );

            color: white;

            border-radius: 16px;

            padding: 1rem 2.4rem;

            font-size: .95rem;
            font-weight: 700;
            font-family: inherit;

            cursor: pointer;

            box-shadow:
                0 12px 25px rgba(43,127,255,.25);

            transition: .25s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);

            box-shadow:
                0 18px 30px rgba(43,127,255,.35);
        }

        /* =========================
            REGISTER
        ========================== */

        .register-link {
            margin-top: 2.5rem;

            font-size: .92rem;

            color: #64748b;
        }

        .register-link a {
            color: #2563eb;

            font-weight: 700;

            text-decoration: none;
        }

        /* =========================
            KANAN VISUAL
        ========================== */

        .visual-section {
            flex: 1;

            position: relative;

            overflow: hidden;

            background:
                linear-gradient(
                    135deg,
                    #f8fbff 0%,
                    #edf4ff 100%
                );
        }

        .visual-section::before {
            content: "";

            position: absolute;

            width: 550px;
            height: 550px;

            border-radius: 50%;

            background:
                rgba(59,130,246,.08);

            filter: blur(30px);

            top: 50%;
            right: -180px;

            transform: translateY(-50%);
        }

        /* =========================
            LINGKARAN
        ========================== */

        .circle {
            position: absolute;

            border-radius: 50%;
        }

        .circle-1 {
            width: 950px;
            height: 950px;

            background:
                rgba(191,219,254,.35);

            top: 50%;
            right: -540px;

            transform: translateY(-50%);
        }

        .circle-2 {
            width: 760px;
            height: 760px;

            background:
                rgba(147,197,253,.30);

            top: 50%;
            right: -420px;

            transform: translateY(-50%);
        }

        .circle-3 {
            width: 560px;
            height: 560px;

            background:
                rgba(96,165,250,.24);

            top: 50%;
            right: -290px;

            transform: translateY(-50%);
        }

        .circle-4 {
            width: 350px;
            height: 350px;

            background:
                linear-gradient(
                    135deg,
                    #60a5fa,
                    #2563eb
                );

            top: 50%;
            right: -140px;

            transform: translateY(-50%);

            box-shadow:
                0 0 80px rgba(37,99,235,.35);
        }
        .toggle-pw svg {
    width: 20px;
    height: 20px;

    stroke: #94a3b8;
    fill: none;

    stroke-width: 2;

    stroke-linecap: round;
    stroke-linejoin: round;

    flex-shrink: 0;
}


        /* =========================
            MOBILE
        ========================== */

        @media (max-width: 900px) {

            .visual-section {
                display: none;
            }

            .form-section {
                width: 100%;
                padding: 2.5rem;
            }

            .auth-container {
                max-width: 500px;
                min-height: auto;
            }

            .greeting {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>

<div class="auth-container">

    <!-- =====================
        FORM
    ====================== -->

    <div class="form-section">

        <div class="brand-header">

            <div class="logo-ring">
                <svg viewBox="0 0 24 24">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 3.5 2.33 6.48 5.55 7.59L12 22l1.45-5.41C16.67 15.48 19 12.5 19 9c0-3.87-3.13-7-7-7z"/>
                    <circle cx="12" cy="9" r="2.5"/>
                </svg>
            </div>

            <div class="brand-text">
                <h1>SIMPEL</h1>
                <p>Sistem Informasi Peduli Lansia</p>
            </div>

        </div>

        <div class="greeting">
            Welcome Back!
        </div>

        <div class="greeting-sub">
            Masuk ke akun petugas Anda untuk melanjutkan monitoring kesehatan lansia.
        </div>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('proses_login') }}">

            @csrf

            <!-- EMAIL -->

            <div class="field">

                <label for="email">
                    Username
                </label>

                <div class="input-wrap">

                    <span class="ico">
                        <svg viewBox="0 0 24 24">
                            <rect x="2" y="4" width="20" height="16" rx="2"/>
                            <path d="m2 7 10 7 10-7"/>
                        </svg>
                    </span>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="nama@dinas.go.id"
                        required
                        autofocus
                    >

                </div>

                @error('email')
                    <div class="field-error">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <!-- PASSWORD -->

            <div class="field">

                <label for="password">
                    Password
                </label>

                <div class="input-wrap">

                    <span class="ico">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </span>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        required
                    >

                    <button
                        type="button"
                        class="toggle-pw"
                        onclick="togglePw()"
                    >
                        <svg id="eye-ico" viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>

                </div>

            </div>

            <div style="margin-top: 1rem; padding: 1rem 1.05rem; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; display: flex; align-items: center; gap: 0.8rem; font-family: 'Plus Jakarta Sans', sans-serif;">
                <input
                    type="checkbox"
                    id="remember"
                    name="remember"
                    value="1"
                    {{ old('remember') ? 'checked' : '' }}
                    style="width: 18px; height: 18px; accent-color: #2b7fff; cursor: pointer; flex-shrink: 0;"
                >
                <label for="remember" style="margin: 0; cursor: pointer; font-size: 0.92rem; font-weight: 600; color: #334155; font-family: 'Plus Jakarta Sans', sans-serif; line-height: 1.4;">
                    Ingat saya
                </label>
            </div>

            <!-- ACTION -->

            <div class="form-actions">

                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="btn-forgot">
                        Forgot password?
                    </a>
                @endif

                <button type="submit" class="btn-login">
                    Login
                </button>

            </div>

        </form>

        <div class="register-link">
            Don't have an account?
            <a href="{{ route('register') }}">
                Daftar di sini
            </a>
        </div>

    </div>

    <!-- =====================
        VISUAL
    ====================== -->

    <div class="visual-section">

        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
        <div class="circle circle-3"></div>
        <div class="circle circle-4"></div>

       

    </div>

</div>

<script>

    function togglePw() {

    const inp = document.getElementById('password');
    const ico = document.getElementById('eye-ico');

    if (inp.type === 'password') {

        inp.type = 'text';

        ico.innerHTML = `
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
            <path d="M1 1L23 23"/>
        `;

    } else {

        inp.type = 'password';

        ico.innerHTML = `
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
        `;
    }
}

</script>

</body>
</html>