@extends('layouts.app')

@section('content')

    <!-- HERO SECTION -->
    <div class="text-center py-5">
        <h1 class="display-4 fw-bold text-success">
            Sistem Informasi Posyandu Lansia
        </h1>
        <p class="lead mt-3">
            Membantu pencatatan data kesehatan lansia secara cepat,
            terstruktur, dan efisien.
        </p>

        <a href="/login" class="btn btn-success btn-lg mt-3 px-4 shadow">
            Login Petugas
        </a>
    </div>

    <!-- FITUR SECTION -->
    <div class="row mt-5 text-center">

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow h-100">
                <div class="card-body">
                    <h5 class="card-title text-success">
                        📋 Data Lansia
                    </h5>
                    <p class="card-text">
                        Mengelola data lansia dengan sistem yang rapi dan mudah diakses.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow h-100">
                <div class="card-body">
                    <h5 class="card-title text-success">
                        ❤️ Pemeriksaan Kesehatan
                    </h5>
                    <p class="card-text">
                        Mencatat hasil pemeriksaan seperti tekanan darah, berat badan, dan lainnya.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow h-100">
                <div class="card-body">
                    <h5 class="card-title text-success">
                        📊 Laporan
                    </h5>
                    <p class="card-text">
                        Membantu pembuatan laporan kegiatan posyandu secara otomatis.
                    </p>
                </div>
            </div>
        </div>

    </div>

@endsection