@extends('layout.sidebar')

@push('styles')
    @vite('resources/css/app.css')
@endpush


@push('styles')
    @vite('resources/css/cssAdmin/data_lansia.css')
@endpush

@section('content')
    <main class="main-content">
        <div class="container">
            <header class="page-header">
                <div class="header-info">
                    <nav class="breadcrumb" aria-label="Breadcrumb">
                        <img class="icon" src="img/icon-2.svg" alt="Home" />
                        <img class="separator" src="img/icon-6.svg" alt="" />
                        <span class="text-muted">MANAJEMEN</span>
                    </nav>
                    <h1 class="page-title">Data Lansia</h1>
                    <p class="page-subtitle">Kelola informasi kesehatan lansia untuk pelayanan Posyandu yang lebih baik.</p>
                </div>
                <button class="btn-primary" type="button">
                    <img src="img/icon-10.svg" alt="" />
                    <span>Tambah Lansia</span>
                </button>
            </header>

            <section class="stats-grid" aria-label="Statistik Lansia">
                <div class="stat-card">
                    <h2 class="stat-label">TOTAL TERDAFTAR</h2>
                    <div class="stat-content">
                        <span class="stat-number">128</span>
                        <img src="img/icon-4.svg" alt="" class="stat-icon" />
                    </div>
                </div>
                <div class="stat-card border-danger">
                    <h2 class="stat-label text-danger">RESIKO TINGGI</h2>
                    <div class="stat-content">
                        <span class="stat-number color-danger">14</span>
                        <img src="img/image.svg" alt="" class="stat-icon" />
                    </div>
                </div>
                <div class="stat-card border-success">
                    <h2 class="stat-label text-success">STATUS SEHAT</h2>
                    <div class="stat-content">
                        <span class="stat-number color-success">84</span>
                        <img src="img/icon-3.svg" alt="" class="stat-icon" />
                    </div>
                </div>
                <div class="stat-card">
                    <h2 class="stat-label">JADWAL PERIKSA</h2>
                    <div class="stat-content">
                        <span class="stat-number">5</span>
                        <img src="img/icon-11.svg" alt="" class="stat-icon" />
                    </div>
                </div>
            </section>

            <section class="table-container card">
                <div class="table-header-actions">
                    <div class="search-wrapper">
                        <img src="img/icon-7.svg" alt="" />
                        <input type="search" placeholder="Cari nama, NIK, atau alamat..." id="main-search" />
                    </div>
                    <button class="btn-outline">
                        <img src="img/icon-14.svg" alt="" />
                        <span>Filter</span>
                    </button>
                </div>

                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>NAMA LENGKAP</th>
                            <th>TANGGAL LAHIR</th>
                            <th>ALAMAT</th>
                            <th>PENYAKIT</th>
                            <th>STATUS RISIKO</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="table-row selectable-row active" data-id="1">
                            <td>
                                <div class="user-cell">
                                    <div class="avatar">SA</div>
                                    <div class="user-text">
                                        <span class="user-name">Siti Aminah</span>
                                        <span class="user-subtext">3275012345678901</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="main-text">78 Tahun</span>
                                <span class="sub-text">12/05/1945</span>
                            </td>
                            <td>
                                <address>Jl. Merdeka No. 10, RT 0…</address>
                            </td>
                            <td><span class="badge-pill">Hipertensi</span></td>
                            <td><span class="badge-status danger">RESIKO TINGGI</span></td>
                            <td>
                                <button class="btn-icon"><img src="img/data.svg" alt="View" /></button>
                            </td>
                        </tr>
                        <tr class="table-row selectable-row" data-id="2">
                            <td>
                                <div class="user-cell">
                                    <div class="avatar bg-gray">BS</div>
                                    <div class="user-text">
                                        <span class="user-name">Budi Santoso</span>
                                        <span class="user-subtext">3275087654321009</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="main-text">73 Tahun</span>
                                <span class="sub-text">23/08/1950</span>
                            </td>
                            <td>
                                <address>Jl. Mawar No. 5, RT 01/01</address>
                            </td>
                            <td><span class="badge-pill">Diabetes</span></td>
                            <td><span class="badge-status muted">NORMAL</span></td>
                            <td>
                                <button class="btn-icon"><img src="img/data-2.svg" alt="View" /></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <section class="detail-container card">
                <div class="detail-header">
                    <div class="header-content">
                        <div class="icon-box"><img src="img/icon.svg" alt=""></div>
                        <div>
                            <h3>Ringkasan Detail Lansia</h3>
                            <p>Informasi terkini untuk <strong id="dynamic-name">Siti Aminah</strong></p>
                        </div>
                    </div>
                    <a href="#" class="btn-outline-blue">Profil Lengkap ➔</a>
                </div>

                <div class="detail-content-grid">
                    <div class="profile-side">
                        <div class="profile-photo">
                            <img src="img/icon-9.svg" alt="User">
                        </div>
                        <span class="badge-risk high">RESIKO TINGGI</span>
                        <h2 id="name-display">Siti Aminah</h2>
                        <p class="age-text">78 Tahun</p>
                        <div class="status-pill live">● Hidup</div>
                    </div>

                    <div class="info-side-grid">
                        <div class="info-column">
                            <h4>👤 INFORMASI PRIBADI</h4>
                            <div class="data-item">
                                <label>NIK</label>
                                <p>3275012345678901</p>
                            </div>
                            <div class="data-item">
                                <label>NOMOR HANDPHONE</label>
                                <p>0812-3456-7890</p>
                            </div>
                            <div class="data-item">
                                <label>ALAMAT LENGKAP</label>
                                <p>Jl. Merdeka No. 10, RT 04/02, Kel. Melati...</p>
                            </div>
                        </div>

                        <div class="info-column">
                            <h4>📈 KESEHATAN TERAKHIR</h4>
                            <div class="health-cards">
                                <div class="h-card red">
                                    <span>TENSI</span>
                                    <strong>160/95</strong>
                                    <small>MMHG</small>
                                </div>
                                <div class="h-card blue">
                                    <span>GULA DARAH</span>
                                    <strong>145</strong>
                                    <small>MG/DL</small>
                                </div>
                            </div>
                            <div class="medical-note">
                                <label>CATATAN MEDIS</label>
                                <blockquote>"Pasien mengeluh pusing di pagi hari. Disarankan pembatasan konsumsi garam."
                                </blockquote>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection

@push('scripts')
    @vite('resources/js/jsAdmin/dataLansia.js')
@endpush