<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — SIMPEL</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 2rem;
            background: linear-gradient(135deg, #eef4fb 0%, #f6f9fc 100%);
            overflow: hidden;
        }

        .auth-container {
            width: 100%; max-width: 1180px; min-height: 680px;
            position: relative; display: flex;
            border-radius: 36px; overflow: hidden;
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255,255,255,0.7);
            box-shadow: 0 20px 60px rgba(15,42,110,0.08), 0 10px 25px rgba(15,42,110,0.05);
        }

        .form-section {
            width: 48%;
            background: rgba(255,255,255,0.94);
            padding: 4rem;
            z-index: 10;
            display: flex; flex-direction: column; justify-content: center;
        }

        .brand-header { display: flex; align-items: center; gap: 14px; margin-bottom: 3rem; }

        .logo-ring {
            width: 54px; height: 54px; border-radius: 18px;
            background: linear-gradient(145deg, #2b7fff, #1d63d8);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 10px 25px rgba(43,127,255,0.35);
        }

        .logo-ring svg { width: 26px; height: 26px; stroke: white; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

        .brand-text h1 { font-size: 1.4rem; font-weight: 800; color: #0f172a; }
        .brand-text p  { font-size: 0.75rem; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: #6b84b0; }

        .greeting     { font-size: 2.6rem; font-weight: 800; line-height: 1.1; color: #111827; margin-bottom: .7rem; }
        .greeting-sub { font-size: .98rem; line-height: 1.7; color: #64748b; margin-bottom: 2.4rem; }

        .alert-error, .alert-success {
            padding: 1rem 1.1rem; border-radius: 14px; font-size: .88rem; margin-bottom: 1.5rem;
        }
        .alert-error   { background: #fff1f1; border: 1px solid #ffd5d5; color: #d63031; }
        .alert-success { background: #effcf5; border: 1px solid #b8ecd1; color: #1f7a4d; }

        .field { margin-bottom: 1.4rem; }

        label { display: block; font-size: .88rem; font-weight: 700; color: #1e293b; margin-bottom: .7rem; }

        .input-wrap { position: relative; }

        .ico { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); }
        .ico svg { width: 19px; height: 19px; stroke: #94a3b8; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }

        input[type="email"] {
            width: 100%;
            border: 1.5px solid #e2e8f0;
            background: #ffffff;
            border-radius: 16px;
            padding: 1rem 1rem 1rem 3rem;
            font-size: .95rem;
            font-family: inherit;
            color: #1e293b;
            transition: .25s ease;
            outline: none;
        }

        input:focus { border-color: #3b82f6; box-shadow: 0 0 0 5px rgba(59,130,246,.10); }
        input::placeholder { color: #94a3b8; }

        .btn-login {
            border: none;
            background: linear-gradient(135deg, #2b7fff, #1d63d8);
            color: white;
            border-radius: 16px;
            padding: 1rem;
            font-size: .95rem; font-weight: 700; font-family: inherit;
            cursor: pointer;
            box-shadow: 0 12px 25px rgba(43,127,255,.25);
            transition: .25s ease;
            width: 100%;
        }

        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 18px 30px rgba(43,127,255,.35); }

        .back-link {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            font-size: .88rem; color: #64748b; text-decoration: none;
            margin-top: 1.5rem; transition: color .2s;
        }
        .back-link:hover { color: #2563eb; }
        .back-link svg { width: 15px; height: 15px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

        /* Visual */
        .visual-section {
            flex: 1; position: relative; overflow: hidden;
            background: linear-gradient(135deg, #f8fbff 0%, #edf4ff 100%);
        }
        .visual-section::before {
            content: ""; position: absolute;
            width: 550px; height: 550px; border-radius: 50%;
            background: rgba(59,130,246,.08); filter: blur(30px);
            top: 50%; right: -180px; transform: translateY(-50%);
        }
        .circle { position: absolute; border-radius: 50%; }
        .circle-1 { width: 950px; height: 950px; background: rgba(191,219,254,.35); top: 50%; right: -540px; transform: translateY(-50%); }
        .circle-2 { width: 760px; height: 760px; background: rgba(147,197,253,.30); top: 50%; right: -420px; transform: translateY(-50%); }
        .circle-3 { width: 560px; height: 560px; background: rgba(96,165,250,.24);  top: 50%; right: -290px; transform: translateY(-50%); }
        .circle-4 { width: 350px; height: 350px; background: linear-gradient(135deg, #60a5fa, #2563eb); top: 50%; right: -140px; transform: translateY(-50%); box-shadow: 0 0 80px rgba(37,99,235,.35); }

        @media (max-width: 900px) {
            .visual-section { display: none; }
            .form-section { width: 100%; padding: 2.5rem; }
            .auth-container { max-width: 500px; min-height: auto; }
            .greeting { font-size: 2rem; }
        }
    </style>
</head>
<body>

<div class="auth-container">

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

        <div class="greeting">Lupa Password?</div>
        <div class="greeting-sub">Masukkan email terdaftar, kami kirimkan kode OTP untuk membuka halaman reset password.</div>

        @if (session('status'))
            <div class="alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->has('email'))
            <div class="alert-error">{{ $errors->first('email') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="field">
                <label for="email">Email Akun Petugas</label>
                <div class="input-wrap">
                    <span class="ico">
                        <svg viewBox="0 0 24 24">
                            <rect x="2" y="4" width="20" height="16" rx="2"/>
                            <path d="m2 7 10 7 10-7"/>
                        </svg>
                    </span>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="email@dinas.go.id" required autofocus>
                </div>
            </div>

            <button type="submit" class="btn-login">Kirim Kode OTP</button>
        </form>

        <a href="{{ route('login') }}" class="back-link">
            <svg viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali ke halaman login
        </a>

    </div>

    <div class="visual-section">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
        <div class="circle circle-3"></div>
        <div class="circle circle-4"></div>
    </div>

</div>

</body>
</html>