@extends('layout.sidebar')

@push('styles')
    @vite('resources/css/app.css')
    @vite('resources/css/cssAdmin/obat.css')
@endpush

@section('title', 'Data Obat')

@section('content')
    <div class="obat-page" style="width: 100%; box-sizing: border-box; display: flex; flex-direction: column; gap: 16px;">

        {{-- ── HEADER ─────────────────────────────────────────────── --}}
                    <div class="page-header">

                <div class="header-info">
                    <h1 class="page-title">Data Obat</h1>
                    <p class="page-subtitle">
                        Kelola inventaris obat-obatan posyandu dan pantau stok secara real-time.
                    </p>
                </div>

                <button onclick="openModalTambahObat()" class="btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Obat</span>
                </button>

            </div>

            {{-- ── TABEL ───────────────────────────────────────────────── --}}
            <section class="card obat-table-wrap">
                <div class="table-header-actions">
                    <div class="search-wrapper">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="search" placeholder="Cari nama obat atau tipe..." id="main-search" />
                    </div>
                </div>

                {{-- Wrapper scroll: sticky thead bekerja karena overflow ada di sini --}}
                <div class="obat-scroll-body">
                    <table class="custom-table obat-table"
                        style="width: 100%; border-collapse: collapse; table-layout: auto; min-width: 680px;">
                        <colgroup>
                            <col class="obat-col-nama">
                            <col class="obat-col-tipe">
                            <col class="obat-col-stok">
                            <col class="obat-col-ket">
                            <col class="obat-col-aksi">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="obat-col-nama"  style="text-align: left;">NAMA OBAT</th>
                                <th class="obat-col-tipe"  style="text-align: center;">TIPE</th>
                                <th class="obat-col-stok"  style="text-align: center;">STOK</th>
                                <th class="obat-col-ket"   style="text-align: left;">KETERANGAN</th>
                                <th class="obat-col-aksi"  style="text-align: center;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($obat as $item)
                                <tr>
                                    {{-- Nama Obat --}}
                                    <td class="obat-col-nama">{{ $item->nama_obat }}</td>

                                    {{-- Tipe --}}
                                    <td class="obat-col-tipe">{{ $item->tipe_obat }}</td>

                                    {{-- Stok --}}
                                    <td class="obat-col-stok">
                                        <span class="obat-stok-num {{ $item->stock < 10 ? 'color-danger' : '' }}">{{ $item->stock }}</span>
                                    </td>

                                    {{-- Keterangan --}}
                                    <td class="obat-col-ket" title="{{ $item->keterangan ?? '-' }}">{{ $item->keterangan ?? '-' }}</td>

                                    {{-- Aksi --}}
                                    <td class="obat-col-aksi">
                                        <div class="obat-aksi-wrap">
                                            <button type="button" class="obat-btn obat-btn-edit"
                                                onclick="openModalEditObat('{{ $item->id_obat }}', '{{ $item->nama_obat }}', '{{ $item->tipe_obat }}', '{{ addslashes($item->keterangan ?? '') }}')">
                                                <i class="fa-solid fa-pen-to-square"></i> Edit
                                            </button>

                                            <button type="button" class="obat-btn obat-btn-restock"
                                                onclick="openModalRestock('{{ $item->id_obat }}', '{{ $item->nama_obat }}')">
                                                <i class="fa-solid fa-boxes-packing"></i> Restock
                                            </button>

                                            <form action="{{ route('obat.destroy', $item->id_obat) }}" method="POST" style="display:inline; margin:0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="obat-btn obat-btn-hapus"
                                                    onclick="return confirm('Yakin ingin menghapus data obat ini?')">
                                                    <i class="fa-solid fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 60px 20px; color: #94a3b8;">
                                        <i class="fa-solid fa-inbox" style="font-size: 40px; margin-bottom: 12px; display: block; color: #cbd5e1;"></i>
                                        <p style="font-size: 14px; font-weight: 600; margin-bottom: 4px; color: #64748b;">Belum ada data obat</p>
                                        <p style="font-size: 13px;">
                                            <button type="button" onclick="openModalTambahObat()"
                                                style="color: #0F766E; font-weight: 600; background: none; border: none; cursor: pointer; text-decoration: underline;">
                                                Tambah sekarang
                                            </button>
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
    </div>

    <div id="obat-page-config"
        data-open-create="{{ $errors->any() && (session('_method') == 'POST' || is_null(session('_method'))) ? '1' : '0' }}"
        data-open-edit="{{ $errors->any() && session('_method') == 'PUT' ? '1' : '0' }}"
        data-edit-id="{{ old('obat_id_edit') }}"
        style="display: none;"></div>

    @vite('resources/js/jsADMIN/obat.js')

    <!-- Include Modals -->
    @include('admin.obat.modal_create')
    @include('admin.obat.modal_edit')
    @include('admin.obat.modal_restock')
@endsection
