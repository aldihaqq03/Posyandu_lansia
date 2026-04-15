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

    <a href="{{ route('petugas.tambah') }}" class="btn-primary">
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
            <h2>{{ $total }}</h2>
            <span>Petugas Terdaftar</span>
        </div>
    </div>


    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa fa-check"></i>
        </div>

        <div>
            <p class="stat-title">AKTIF</p>
            <h2>{{ $aktif }}</h2>
            <span>Status Aktif</span>
        </div>
    </div>


    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fa fa-clock"></i>
        </div>

        <div>
            <p class="stat-title">PENDING</p>
            <h2>{{ $pending }}</h2>
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

        @forelse($petugas as $p)

            <tr>

                <td class="user-cell">

                    <img src="{{ $p->foto ? asset('storage/'.$p->foto) : 'https://i.pravatar.cc/40' }}">

                    <div>
                        <strong>{{ $p->nama }}</strong>
                        <small>{{ $p->email }}</small>
                    </div>

                </td>

                <td>
                    <span class="badge blue">
                        {{ $p->jabatan }}
                    </span>
                </td>

                <td>

                    @if($p->status == 'aktif')
                        <span class="status aktif">● Aktif</span>
                    @else
                        <span class="status pending">● Pending</span>
                    @endif

                </td>

                <td class="aksi">

                    <!-- EDIT -->
                   <a href="{{ route('petugas.edit', ['id' => $p->id_petugas]) }}" class="btn-icon">
                        <i class="fa fa-edit"></i>
                    </a>

                    <!-- DELETE -->
                    <form action="{{ route('petugas.destroy',$p->id_petugas) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')

                        <button class="btn-icon" onclick="return confirm('Hapus data petugas?')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>

                </td>

            </tr>

        @empty

            <tr>
                <td colspan="4" style="text-align:center; padding:20px;">
                    Belum ada data petugas
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection