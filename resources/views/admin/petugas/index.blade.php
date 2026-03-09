@extends('layout.sidebar')

@section('title','Data Petugas')

@push('styles')
    @vite('resources/css/app.css')
    @vite('resources/css/cssAdmin/data_petugas.css')
@endpush

@section('content')

<div class="page-header">

    <div>
        <h1>Data Petugas</h1>
        <p>Kelola informasi kader dan tenaga kesehatan Posyandu SIMPEL.</p>
    </div>

    <a href="/petugas/tambah" class="btn-primary">
        <i class="fa fa-user-plus"></i> Tambah Petugas
    </a>

</div>


<!-- CARD STATISTIK -->

<div class="stats-container">

    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fa fa-users"></i>
        </div>

        <div>
            <p class="stat-title">TOTAL</p>
            <h2>24</h2>
            <span>Petugas Terdaftar</span>
        </div>
    </div>


    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa fa-check"></i>
        </div>

        <div>
            <p class="stat-title">AKTIF</p>
            <h2>18</h2>
            <span>Status Aktif</span>
        </div>
    </div>


    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fa fa-clock"></i>
        </div>

        <div>
            <p class="stat-title">PENDING</p>
            <h2>3</h2>
            <span>Menunggu Persetujuan</span>
        </div>
    </div>

</div>



<!-- TABEL PETUGAS -->

<div class="table-container">

    <div class="table-header">
        <h3>Daftar Seluruh Petugas</h3>
    </div>

    <table class="table">

        <thead>
            <tr>
                <th>Nama Petugas</th>
                <th>Jabatan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

            <tr>

                <td class="user-cell">
                    <img src="https://i.pravatar.cc/40?img=1">
                    <div>
                        <strong>Siti Aminah</strong>
                        <small>siti.aminah@simpel.id</small>
                    </div>
                </td>

                <td>
                    <span class="badge blue">Ketua Kader</span>
                </td>

                <td>
                    <span class="status aktif">● Aktif</span>
                </td>

                <td class="aksi">
                    <button class="btn-icon"><i class="fa fa-edit"></i></button>
                    <button class="btn-icon"><i class="fa fa-trash"></i></button>
                </td>

            </tr>


            <tr>

                <td class="user-cell">
                    <img src="https://i.pravatar.cc/40?img=2">
                    <div>
                        <strong>Budi Santoso</strong>
                        <small>budi@simpel.id</small>
                    </div>
                </td>

                <td>
                    <span class="badge abu">Tenaga Kesehatan</span>
                </td>

                <td>
                    <span class="status aktif">● Aktif</span>
                </td>

                <td class="aksi">
                    <button class="btn-icon"><i class="fa fa-edit"></i></button>
                    <button class="btn-icon"><i class="fa fa-trash"></i></button>
                </td>

            </tr>

            <tr>

                <td class="user-cell">
                    <img src="https://i.pravatar.cc/40?img=3">
                    <div>
                        <strong>Ani Wijaya</strong>
                        <small>Baru Bergabung</small>
                    </div>
                </td>

                <td>
                    <span class="badge blue">Kader</span>
                </td>

                <td>
                    <span class="status pending">● Menunggu Persetujuan</span>
                </td>

                <td>
                    <button class="btn-setuju">SETUJUI</button>
                </td>

            </tr>

        </tbody>

    </table>

</div>

@endsection