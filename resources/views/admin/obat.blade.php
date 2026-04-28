@extends('layout.sidebar')

@push('styles')
    @vite('resources/css/app.css')
@endpush

@section('title', 'Data Obat')

@section('content')
    <main class="main-content">

        <!-- Tampilkan Error Validasi Jika Ada -->
        @if ($errors->any())
            <div style="background: #ffebe9; border: 1px solid rgba(255,129,130,0.4); border-radius: 6px; padding: 15px; margin-bottom: 20px;">
                <h4 style="color: #cf222e; margin-bottom: 5px;">Terjadi Kesalahan:</h4>
                <ul style="color: #cf222e; padding-left: 20px; font-size: 14px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="container">
            <header class="page-header">
                <div class="header-info">
                    <nav class="breadcrumb" aria-label="Breadcrumb">
                        <img class="icon" src="img/icon-2.svg" alt="Home" />
                        <img class="separator" src="img/icon-6.svg" alt="" />
                        <span class="text-muted">MANAJEMEN</span>
                    </nav>
                    <h1 class="page-title">Data Obat</h1>
                    <p class="page-subtitle">Kelola data obat untuk kebutuhan Posyandu Lansia.</p>
                </div>
                <button class="btn-primary" type="button" id="btn-tambah-obat">
                    <img src="img/icon-10.svg" alt="" />
                    <span>Tambah Obat</span>
                </button>
            </header>

            <section class="table-container card">
                <div class="table-header-actions">
                    <div class="search-wrapper">
                        <img src="img/icon-7.svg" alt="" />
                        <input type="search" placeholder="Cari nama obat atau kode..." id="main-search" />
                    </div>
                    <button class="btn-outline" id="btn-filter-obat">
                        <i class="fa-solid fa-filter"></i>
                        <span>Filter</span>
                    </button>
                </div>

                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>NAMA OBAT</th>
                            <th>KODE OBAT</th>
                            <th>STOK</th>
                            <th>SATUAN</th>
                            <th>KETERANGAN</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px; color: #999;">
                                Belum ada data obat
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </div>

    </main>
@endsection