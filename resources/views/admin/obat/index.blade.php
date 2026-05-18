@extends('layout.sidebar')

@push('styles')
    @vite('resources/css/app.css')
@endpush

@section('title', 'Data Obat')

@section('content')
    <main class="main-content">


        <div class="container">
            <header class="page-header">
                <div class="header-info">
                    <nav class="breadcrumb" aria-label="Breadcrumb">
                        <img class="icon" src="/img/icon-2.svg" alt="Home" />
                        <img class="separator" src="/img/icon-6.svg" alt="" />
                        <span class="text-muted">MANAJEMEN</span>
                    </nav>
                    <h1 class="page-title">Data Obat</h1>
                    <p class="page-subtitle">Kelola data obat untuk kebutuhan Posyandu Lansia.</p>
                </div>
                <button onclick="openModalTambahObat()" class="btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border: none; cursor: pointer; background: #0F766E; color: white; padding: 10px 16px; border-radius: 6px;">
                    <img src="/img/icon-10.svg" alt="" />
                    <span>Tambah Obat</span>
                </button>
            </header>

            <section class="table-container card">
                <div class="table-header-actions">
                    <div class="search-wrapper">
                        <img src="/img/icon-7.svg" alt="" />
                        <input type="search" placeholder="Cari nama obat atau tipe..." id="main-search" />
                    </div>
                </div>

                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>NAMA OBAT</th>
                            <th>TIPE</th>
                            <th>STOK</th>
                            <th>KETERANGAN</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($obat as $item)
                            <tr>
                                <td>
                                    <div class="user-cell">
                                        <div style="flex: 1;">
                                            <span class="user-name">{{ $item->nama_obat }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->tipe_obat }}</td>
                                <td>
                                    <strong>{{ $item->stock }}</strong>
                                    <span style="color: #999; font-size: 12px;"> unit</span>
                                </td>
                                <td>{{ $item->keterangan ?? '-' }}</td>
                                <td>
                                    <div style="display: flex; gap: 8px; align-items: center;">
                                        <!-- Tombol Edit -->
                                        <button type="button" onclick="openModalEditObat('{{ $item->id_obat }}', '{{ $item->nama_obat }}', '{{ $item->tipe_obat }}', {{ $item->stock }}, '{{ addslashes($item->keterangan ?? '') }}')" 
                                            class="btn-outline" 
                                            style="padding: 6px 12px; font-size: 12px; background: white; color: #0F766E; border: 1px solid #0F766E; cursor: pointer; border-radius: 4px;">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </button>

                                        <!-- Tombol Restock -->
                                        <button type="button" 
                                            onclick="openModalRestock('{{ $item->id_obat }}', '{{ $item->nama_obat }}')" 
                                            title="Lihat histori restock"
                                            class="btn-outline tooltip" 
                                            style="padding: 6px 12px; font-size: 12px; background: white; color: #EAB308; border: 1px solid #EAB308; cursor: pointer; border-radius: 4px;">
                                            <i class="fa-solid fa-boxes-packing"></i> Restock
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('obat.destroy', $item->id_obat) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                style="padding: 6px 12px; font-size: 12px; background-color: #FEE2E2; color: #DC2626; border: 1px solid #FCA5A5; cursor: pointer; border-radius: 4px;"
                                                onclick="return confirm('Yakin ingin menghapus data obat ini? Data tidak akan dihapus secara permanen.')">
                                                <i class="fa-solid fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px 20px; color: #999;">
                                    <i class="fa-solid fa-inbox" style="font-size: 32px; margin-bottom: 10px; display: block;"></i>
                                    <p>Belum ada data obat. <button type="button" onclick="openModalTambahObat()" style="color: #0F766E; font-weight: 500; background: none; border: none; cursor: pointer; text-decoration: underline;">Tambah sekarang</button></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>
        </div>

    </main>

    <!-- Include Modals -->
    @include('admin.obat.modal_create')
    @include('admin.obat.modal_edit')
    @include('admin.obat.modal_restock')

    <script>
        // Search functionality
        const searchInput = document.getElementById('main-search');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('.custom-table tbody tr');
                
                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }
    </script>
@endsection
