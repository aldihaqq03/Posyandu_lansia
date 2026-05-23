<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — SIMPEL</title>
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

        .alert-error {
            padding: 1rem 1.1rem; border-radius: 14px; font-size: .88rem; margin-bottom: 1.5rem;
            background: #fff1f1; border: 1px solid #ffd5d5; color: #d63031;
        }

        .field { margin-bottom: 1.4rem; }

        label { display: block; font-size: .88rem; font-weight: 700; color: #1e293b; margin-bottom: .7rem; }

        .input-wrap { position: relative; }

        .ico { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); }
        .ico svg { width: 19px; height: 19px; stroke: #94a3b8; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }

        input[type="email"], input[type="password"], input[type="text"] {
            width: 100%;
            border: 1.5px solid #e2e8f0;
            background: #ffffff;
            border-radius: 16px;
            padding: 1rem 3rem;
            font-size: .95rem;
            font-family: inherit;
            color: #1e293b;
            transition: .25s ease;
            outline: none;
        }

        input:focus { border-color: #3b82f6; box-shadow: 0 0 0 5px rgba(59,130,246,.10); }
        input::placeholder { color: #94a3b8; }
        input[readonly] { background: #f8fafc; color: #94a3b8; cursor: default; }

        .toggle-pw {
            position: absolute; right: 16px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
        }
        .toggle-pw svg { width: 20px; height: 20px; stroke: #94a3b8; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
        .toggle-pw:hover svg { stroke: #3b82f6; }

        /* Strength bar */
        .strength-bar  { height: 4px; border-radius: 2px; margin-top: 8px; background: #e2e8f0; overflow: hidden; }
        .strength-fill { height: 100%; border-radius: 2px; width: 0%; transition: width .3s, background .3s; }
        .strength-label { font-size: 0.78rem; color: #94a3b8; margin-top: 5px; }

        .field-error { font-size: .82rem; color: #ef4444; margin-top: .5rem; }

        .btn-login {
            border: none;
            background: linear-gradient(135deg, #2b7fff, #1d63d8);
            color: white; border-radius: 16px;
            padding: 1rem; font-size: .95rem; font-weight: 700; font-family: inherit;
            cursor: pointer; box-shadow: 0 12px 25px rgba(43,127,255,.25);
            transition: .25s ease; width: 100%;
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
        .visual-section { flex: 1; position: relative; overflow: hidden; background: linear-gradient(135deg, #f8fbff 0%, #edf4ff 100%); }
        .visual-section::before { content: ""; position: absolute; width: 550px; height: 550px; border-radius: 50%; background: rgba(59,130,246,.08); filter: blur(30px); top: 50%; right: -180px; transform: translateY(-50%); }
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

        <div class="greeting">Buat Password Baru</div>
        <div class="greeting-sub">Buat password baru yang kuat untuk akun petugas Anda.</div>

        @if ($errors->any())
            <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="field">
                <label for="email">Email</label>
                <div class="input-wrap">
                    <span class="ico"><svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 7 10-7"/></svg></span>
                    <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required readonly>
                </div>
                @error('email') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label for="password">Password Baru</label>
                <div class="input-wrap">
                    <span class="ico"><svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
                    <input type="password" id="password" name="password" placeholder="Min. 6 karakter" required oninput="checkStrength(this.value)">
                    <button type="button" class="toggle-pw" onclick="togglePw('password','e1')">
                        <svg id="e1" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                <div class="strength-bar"><div class="strength-fill" id="sf"></div></div>
                <div class="strength-label" id="sl">Masukkan password baru</div>
                @error('password') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label for="password_confirmation">Konfirmasi Password</label>
                <div class="input-wrap">
                    <span class="ico"><svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru" required>
                    <button type="button" class="toggle-pw" onclick="togglePw('password_confirmation','e2')">
                        <svg id="e2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login">Simpan Password Baru</button>
        </form>

        <a href="{{ route('login') }}" class="back-link">
            <svg viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali ke login
        </a>

    </div>

    <div class="visual-section">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
        <div class="circle circle-3"></div>
        <div class="circle circle-4"></div>
    </div>

</div>

<script>
    function togglePw(id, ico) {
        const inp  = document.getElementById(id);
        const icon = document.getElementById(ico);
        if (inp.type === 'password') {
            inp.type = 'text';
            icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
        } else {
            inp.type = 'password';
            icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
        }
    }

    function checkStrength(v) {
        const fill = document.getElementById('sf');
        const lbl  = document.getElementById('sl');
        let s = 0;
        if (v.length >= 6)          s++;
        if (v.length >= 10)         s++;
        if (/[A-Z]/.test(v))        s++;
        if (/[0-9]/.test(v))        s++;
        if (/[^A-Za-z0-9]/.test(v)) s++;
        const lvl = [
            { p: 0,   c: 'transparent', t: 'Masukkan password baru' },
            { p: 20,  c: '#ef4444',     t: 'Sangat lemah' },
            { p: 40,  c: '#f97316',     t: 'Lemah' },
            { p: 60,  c: '#eab308',     t: 'Cukup' },
            { p: 80,  c: '#22c55e',     t: 'Kuat' },
            { p: 100, c: '#3b82f6',     t: 'Sangat kuat' },
        ][s];
        fill.style.width      = lvl.p + '%';
        fill.style.background = lvl.c;
        lbl.textContent       = lvl.t;
        lbl.style.color       = lvl.c === 'transparent' ? '#94a3b8' : lvl.c;
    }
</script>
</body>
</html>