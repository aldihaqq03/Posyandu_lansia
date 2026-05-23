<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Terverifikasi — SIMPEL</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 1.5rem; position: relative; overflow: hidden;
            background: #1a4fa0;
        }

        .bg-layer {
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 110% 45% at 50% 0%,   #0d3278 0%, transparent 55%),
                radial-gradient(ellipse 110% 45% at 50% 100%, #0d3278 0%, transparent 55%),
                radial-gradient(ellipse 80% 60% at 50% 50%,   #c8dcf8 0%, #a8c4f0 40%, #5a90d8 80%, #1a4fa0 100%);
            pointer-events: none;
        }

        .bg-blob { position: absolute; inset: 0; pointer-events: none; overflow: hidden; }
        .bg-blob::before {
            content: ''; position: absolute; top: 4%; left: -6%; width: 55%; height: 38%;
            background: radial-gradient(ellipse, rgba(255,255,255,0.30) 0%, transparent 70%);
            filter: blur(20px); border-radius: 60% 40% 55% 45%;
        }
        .bg-blob::after {
            content: ''; position: absolute; bottom: 4%; right: -8%; width: 55%; height: 36%;
            background: radial-gradient(ellipse, rgba(255,255,255,0.24) 0%, transparent 70%);
            filter: blur(22px); border-radius: 40% 60% 45% 55%;
        }

        .card {
            position: relative; z-index: 1;
            background: rgba(255,255,255,0.93);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.9);
            border-radius: 24px;
            width: 100%; max-width: 400px;
            padding: 2.8rem 2rem;
            box-shadow: 0 20px 60px rgba(13,50,120,0.18), 0 0 0 1px rgba(255,255,255,0.6) inset;
            text-align: center;
        }

        /* Logo */
        .logo-wrap { display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 1.75rem; }

        .logo-ring {
            width: 44px; height: 44px; border-radius: 13px;
            background: linear-gradient(145deg, #2278e0, #1255b0);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 6px 16px rgba(18,85,176,0.32);
        }

        .logo-ring svg {
            width: 22px; height: 22px; stroke: #fff; fill: none;
            stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
        }

        .logo-text .app-name { font-size: 1rem; font-weight: 800; color: #0f2a6e; text-align: left; }
        .logo-text .app-tag { font-size: 0.62rem; color: #6b84b0; text-transform: uppercase; letter-spacing: 0.06em; }

        /* Check ring */
        .check-ring {
            width: 76px; height: 76px; border-radius: 50%;
            background: #eef4ff; border: 2.5px solid #c5d8f7;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem;
            animation: pop 0.55s cubic-bezier(0.34,1.56,0.64,1) both;
        }

        @keyframes pop { from { transform: scale(0.4); opacity: 0; } to { transform: scale(1); opacity: 1; } }

        .check-ring svg {
            width: 38px; height: 38px; stroke: #1a6fd4; fill: none;
            stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round;
            stroke-dasharray: 50; stroke-dashoffset: 50;
            animation: draw 0.6s 0.35s ease forwards;
        }

        @keyframes draw { to { stroke-dashoffset: 0; } }

        h1 { font-size: 1.75rem; font-weight: 800; color: #0f2a6e; margin-bottom: 0.5rem; }

        .desc { font-size: 0.83rem; color: #7a93bb; line-height: 1.65; margin-bottom: 1.75rem; }
        .desc strong { color: #1a6fd4; font-weight: 700; }

        /* Checklist */
        .checklist {
            background: #f4f8ff; border: 1.5px solid #d0dff5;
            border-radius: 16px; padding: 1rem 1.1rem;
            text-align: left; margin-bottom: 1.75rem;
        }

        .check-row {
            display: flex; align-items: center; gap: 10px;
            padding: 0.38rem 0; font-size: 0.81rem; color: #4a5e85; font-weight: 500;
        }

        .check-row:not(:last-child) { border-bottom: 1px solid #e2eaf5; }

        .check-row svg {
            width: 17px; height: 17px; flex-shrink: 0;
            background: #1a6fd4; border-radius: 50%;
            stroke: #fff; fill: none; padding: 3px;
            stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round;
        }

        /* Button */
        .btn-login {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; padding: 0.85rem;
            background: linear-gradient(160deg, #2278e0 0%, #1255b0 100%);
            border: none; border-radius: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.9375rem; font-weight: 700; color: #fff;
            text-decoration: none; cursor: pointer;
            transition: transform 0.15s, box-shadow 0.2s, opacity 0.2s;
            box-shadow: 0 6px 20px rgba(18,85,176,0.35);
        }

        .btn-login:hover { box-shadow: 0 8px 28px rgba(18,85,176,0.45); opacity: 0.95; }
        .btn-login:active { transform: scale(0.975); }
        .btn-login svg { width: 17px; height: 17px; stroke: #fff; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    </style>
</head>
<body>
    <div class="bg-layer"></div>
    <div class="bg-blob"></div>

    <div class="card">
        <div class="logo-wrap">
            <div class="logo-ring">
                <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 3.5 2.33 6.48 5.55 7.59L12 22l1.45-5.41C16.67 15.48 19 12.5 19 9c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5" stroke-width="2"/></svg>
            </div>
            <div class="logo-text">
                <div class="app-name">SIMPEL</div>
                <div class="app-tag">Peduli Lansia</div>
            </div>
        </div>

        <div class="check-ring">
            <svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
        </div>

        <h1>Email Terverifikasi!</h1>
        <p class="desc">
            Akun petugas Anda kini <strong>aktif</strong> dan siap digunakan. Silakan masuk dengan email dan password yang sudah didaftarkan.
        </p>

        <div class="checklist">
            <div class="check-row">
                <svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                Email berhasil diverifikasi
            </div>
            <div class="check-row">
                <svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                Status akun diubah menjadi aktif
            </div>
            <div class="check-row">
                <svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                Akses dashboard petugas tersedia
            </div>
        </div>

        <a href="{{ route('login') }}" class="btn-login">
            <svg viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
            Masuk ke Akun
        </a>
    </div>
</body>
</html>