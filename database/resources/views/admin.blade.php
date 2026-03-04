<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Admin - Sistem Informasi Posyandu Lansia</title>

    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic"
        rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body style="font-family: 'Lato', sans-serif;">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Posyandu Lansia - Admin</a>
            <div>
                <span class="me-3">Halo, Admin 👋</span>
                <a href="#" class="btn btn-outline-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Header Dashboard -->
    <header class="bg-primary text-white text-center py-5">
        <div class="container">
            <h1 class="fw-bold">Dashboard Admin</h1>
            <p class="lead">Kelola data posyandu lansia dengan mudah dan cepat</p>
        </div>
    </header>

    <!-- Statistik -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center">

                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-people fs-1 text-primary"></i>
                            <h5 class="mt-3">Total Lansia</h5>
                            <h3 class="fw-bold">120</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-heart-pulse fs-1 text-success"></i>
                            <h5 class="mt-3">Pemeriksaan Hari Ini</h5>
                            <h3 class="fw-bold">25</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-file-earmark-text fs-1 text-warning"></i>
                            <h5 class="mt-3">Total Laporan</h5>
                            <h3 class="fw-bold">45</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-person-badge fs-1 text-danger"></i>
                            <h5 class="mt-3">Total Admin</h5>
                            <h3 class="fw-bold">5</h3>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Tabel Data -->
    <section class="bg-light py-5">
        <div class="container">
            <h3 class="mb-4">Data Lansia Terbaru</h3>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Umur</th>
                            <th>Jenis Kelamin</th>
                            <th>Status Kesehatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Siti Aminah</td>
                            <td>67</td>
                            <td>Perempuan</td>
                            <td><span class="badge bg-success">Sehat</span></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Budi Santoso</td>
                            <td>70</td>
                            <td>Laki-laki</td>
                            <td><span class="badge bg-warning text-dark">Kontrol</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center py-4">
        <div class="container">
            <p class="text-muted mb-0">&copy; 2026 Sistem Informasi Posyandu Lansia</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>