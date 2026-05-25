@extends('layout.sidebar')

@push('styles')
    @vite('resources/css/cssAdmin/jadwal_posyandu.css')
@endpush

@section('title', 'Jadwal Posyandu')

@section('content')
    <div class="jadwal-wrapper">
        <header class="page-header">
            <div class="header-left">
                <nav class="breadcrumb">
                    <a href="/dashboard" class="text-muted">Dashboard</a>
                    <i class="fa-solid fa-chevron-right"></i>
                    <span class="text-muted">Jadwal Posyandu</span>
                </nav>
                <h1 class="page-title">Jadwal Posyandu</h1>
                <p class="page-subtitle">Kelola jadwal pertemuan dan jenis skrining posyandu lansia.</p>
            </div>
            <button class="btn-primary" type="button" id="btn-tambah-jadwal">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Jadwal</span>
            </button>
        </header>



        {{-- SUMMARY CARD --}}
        <section class="summary-card" aria-label="Ringkasan Jadwal">
            <div class="summary-left">
                <div class="summary-greeting">Ringkasan Jadwal — {{ date('Y') }}</div>
                <div class="summary-title">Posyandu Puskesmas Jambesari</div>
                <div class="summary-stats">
                    <div class="sum-stat">
                        <div class="sum-num">{{ $stats['total'] }}</div>
                        <div class="sum-label">Total</div>
                    </div>
                    <div class="sum-divider"></div>
                    <div class="sum-stat">
                        <div class="sum-num">{{ $stats['terjadwal'] }}</div>
                        <div class="sum-label">Terjadwal</div>
                    </div>
                    <div class="sum-divider"></div>
                    <div class="sum-stat">
                        <div class="sum-num">{{ $stats['berlangsung'] }}</div>
                        <div class="sum-label">Berlangsung</div>
                    </div>
                    <div class="sum-divider"></div>
                    <div class="sum-stat">
                        <div class="sum-num">{{ $stats['selesai'] }}</div>
                        <div class="sum-label">Selesai</div>
                    </div>
                </div>
            </div>
            <div class="summary-right">📅</div>
        </section>

        {{-- FILTER --}}
        <section class="filter-bar">
            <div class="search-wrapper">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Cari lokasi posyandu" id="search-jadwal">
            </div>
            <select class="filter-select" id="filter-status">
                <option value="">Semua Status</option>
                <option value="1">Terjadwal</option>
                <option value="2">Berlangsung</option>
                <option value="3">Selesai</option>
                <option value="4">Dibatalkan</option>
            </select>
            <select class="filter-select" id="filter-bulan">
                <option value="">Semua Bulan</option>
                <option value="1">Januari</option>
                <option value="2">Februari</option>
                <option value="3">Maret</option>
                <option value="4">April</option>
                <option value="5">Mei</option>
                <option value="6">Juni</option>
                <option value="7">Juli</option>
                <option value="8">Agustus</option>
                <option value="9">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>
        </section>

        {{-- LIST JADWAL --}}
        <section class="jadwal-list" aria-label="Daftar Jadwal">

            @php
                $bulanSekarang = '';
                $colorMap = [
                    0 => 'blue',
                    1 => 'yellow',
                    2 => 'gray',
                    3 => 'gray',
                ];

                $badgeMap = [
                    0 => 'badge-terjadwal',
                    1 => 'badge-berlangsung',
                    2 => 'badge-selesai',
                    3 => 'badge-batal',
                ];

                $labelMap = [
                    0 => 'Terjadwal',
                    1 => 'Berlangsung',
                    2 => 'Selesai',
                    3 => 'Dibatalkan',
                ];
            @endphp

            @forelse($jadwalPosyandu as $item)
                @php
                    $tgl = \Carbon\Carbon::parse($item->tanggal_pelaksanaan);
                    $bulanIni = $tgl->translatedFormat('F Y');
                    $color = $colorMap[$item->status] ?? 'gray';
                    $kegiatan = $item->kegiatan ? json_decode($item->kegiatan) : [];
                @endphp

                {{-- DIVIDER BULAN --}}
                @if($bulanIni !== $bulanSekarang)
                    <div class="month-divider">
                        <span class="month-divider-text">{{ $bulanIni }}</span>
                        <span class="month-divider-line"></span>
                    </div>
                    @php $bulanSekarang = $bulanIni; @endphp
                @endif

                {{-- CARD --}}
                <div class="jadwal-card {{ $item->status == 3 || $item->status == 4 ? 'card-done' : '' }}"
                    data-id="{{ $item->id_jadwal_posyandu }}"
                    tabindex="0"
                    role="button"
                    title="Klik untuk melihat ringkasan detail"
                    aria-label="Lihat detail jadwal">

                    {{-- TANGGAL --}}
                    <div class="jadwal-date {{ $color }}">
                        <div class="day">{{ $tgl->format('d') }}</div>
                        <div class="month">{{ $tgl->format('M') }}</div>
                    </div>

                    {{-- ACCENT BAR --}}
                    <div class="jadwal-accent {{ $color }}"></div>

                    {{-- INFO --}}
                    <div class="jadwal-info">
                        <div class="jadwal-tema">{{ $item->tema }}</div>
                        <div class="jadwal-meta">
                            <span>
                                <i class="fa-solid fa-location-dot"></i>
                                {{ $item->lokasi }}
                            </span>
                        </div>
                        <div class="jadwal-tags">
                            @foreach($kegiatan as $k)
                                <span class="tag">
                                    @if(is_object($k))
                                        {{ $k->nama }}{{ !empty($k->jam) ? ' ' . $k->jam : '' }}
                                    @else
                                        {{ $k }}
                                    @endif
                                </span>
                            @endforeach
                            @if(empty($kegiatan))
                                <span class="tag">Belum ada kegiatan</span>
                            @endif
                        </div>
                    </div>

                    {{-- BADGE + AKSI --}}
                    <div class="jadwal-right">
                        <span class="badge {{ $badgeMap[$item->status] ?? '' }}">
                            {{ $labelMap[$item->status] ?? '-' }}
                        </span>
                        <div class="jadwal-actions">
                            @if($item->status == 0)
                                <button class="btn-outline btn-sm btn-edit" data-id="{{ $item->id_jadwal_posyandu }}">
                                    Edit
                                </button>
                            @endif

                            @if($item->status == 1)
                                <form method="POST" action="{{ route('jadwal_posyandu.selesai', $item->id_jadwal_posyandu) }}"
                                    onsubmit="return confirm('Tandai jadwal ini sebagai selesai?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-outline btn-sm btn-selesai">
                                        Selesai
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                </div>

            @empty
                <div class="empty-state">
                    <i class="fa-solid fa-calendar-xmark"></i>
                    <p>Belum ada jadwal posyandu.</p>
                    <span>Klik "Tambah Jadwal" untuk membuat jadwal baru.</span>
                </div>
            @endforelse

        </section>

    </div>

    {{-- MODAL --}}
    @include('modal.M_tambahJadwal')
    @include('modal.M_editJadwal')
    @include('modal.M_detailJadwal')

@endsection

@push('scripts')
    @vite('resources/js/jsADMIN/jadwal_posyandu.js')
@endpush