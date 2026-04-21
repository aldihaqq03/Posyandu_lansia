<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di SIMPEL</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/welcome.css'])
</head>

<body>

    <nav>
        <a href="/" class="logo">
            <x-logo style="height: 50px; width: 50px; margin-right: 10px; vertical-align: middle; color: currentColor;" />
            SIM<span>PEL</span>
        </a>
        <div class="nav-links">
            <a href="{{ route('login') }}" class="nav-link">Masuk</a>
           
        </div>
    </nav>

    <div class="shape-1"></div>
    <div class="shape-2"></div>

    <section class="hero">
        <div class="hero-content">
            <div class="hero-badge"><i class="fa-solid fa-stethoscope"></i> Aplikasi Posyandu Lansia</div>
            <h1 class="hero-title">Perawatan Maksimal untuk <span>Masa Tua Bahagia</span></h1>
            <p class="hero-description">
                Sistem Informasi Peduli Lansia (SIMPEL) hadir untuk mempermudah pemantauan kesehatan, penjadwalan
                pemeriksaan, dan pengelolaan data lansia secara modern, cepat, dan efisien.
            </p>
            <div class="hero-buttons">
                <a href="{{ route('login') }}" class="btn-primary">Masuk Sekarang</a>
                <a href="#fitur" class="btn-secondary">Pelajari Fitur</a>
            </div>
        </div>
    </section>

    <section id="fitur" class="features">
        <div class="feature-card">
            <div class="icon-wrapper"><i class="fa-solid fa-heart-pulse"></i></div>
            <h3 class="feature-title">Pemeriksaan Rutin</h3>
            <p class="feature-text">Pencatatan rekam medis terintegrasi yang memudahkan kader posyandu memantau kondisi
                kesehatan lansia setiap bulannya dengan lebih teliti.</p>
        </div>
        <div class="feature-card">
            <div class="icon-wrapper"><i class="fa-solid fa-calendar-check"></i></div>
            <h3 class="feature-title">Jadwal Teratur</h3>
            <p class="feature-text">Sistem informasi yang memudahkan pemantauan jadwal posyandu secara akurat sehingga
                tidak ada pelayanan yang terlewatkan.</p>
        </div>
        <div class="feature-card">
            <div class="icon-wrapper"><i class="fa-solid fa-chart-pie"></i></div>
            <h3 class="feature-title">Laporan Analitik</h3>
            <p class="feature-text">Fitur penganalisaan data dan laporan komprehensif bagi pengelola kesehatan daerah
                untuk menunjang keputusan yang jauh lebih baik.</p>
        </div>
    </section>

    <footer>
        &copy; {{ date('Y') }} SIMPEL (Sistem Informasi Peduli Lansia). All rights reserved.
    </footer>

    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>