<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>SIMPEL - Pendaftaran Berhasil</title>


    @vite('resources/css/berhasil.css')

<body>

    <div class="card">
        <div class="icon-container">
            <div class="checkmark">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
        </div>

        <h2>Pendaftaran Berhasil!</h2>

        <p>Akun Anda sedang dalam proses verifikasi oleh admin. Mohon tunggu informasi selanjutnya melalui email atau
            WhatsApp terdaftar.</p>

        <div class="info-box">
            <div class="info-icon">i</div>
            <span>Proses verifikasi biasanya memakan waktu 1x24 jam.</span>
        </div>

        <a href="{{ route('login') }}" class="btn-login">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                <polyline points="10 17 15 12 10 7"></polyline>
                <line x1="15" y1="12" x2="3" y2="12"></line>
            </svg>
            Kembali ke Login
        </a>
    </div>

</body>

</html>