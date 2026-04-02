@extends('layout.sidebar')

@push('styles')
    @vite('resources/css/app.css')
    @vite('resources/css/cssAdmin/lansia_profile.css')
@endpush

@section('content')
<main class="main-content">
    <div class="profile-container">

        <!-- HEADER AKSI -->
        <div class="header-action">
            <a href="{{ route('data_lansia') }}" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Data Lansia
            </a>
            <button class="btn-primary" onclick="window.print()">
                <i class="fa-solid fa-print"></i> Cetak Profil
            </button>
        </div>

        <div class="profile-grid">
            
            <!-- KARTU IDENTITAS (KIRI) -->
            <div class="card identity-card">
                <div class="profile-avatar">
                    {{ strtoupper(substr($lansia->nama_lansia, 0, 2)) }}
                </div>
                <h1 class="profile-name">{{ $lansia->nama_lansia }}</h1>
                <p class="profile-nik">NIK: {{ $lansia->nik }}</p>
                
                @if($lansia->riwayat_penyakit)
                    <div class="status-badge warning">
                        <i class="fa-solid fa-heart-pulse"></i> Perlu Perhatian
                    </div>
                @else
                    <div class="status-badge">
                        <i class="fa-solid fa-check-circle"></i> Status Sehat
                    </div>
                @endif

                <div class="identity-details">
                    <div class="info-row">
                        <span class="info-label">Jenis Kelamin</span>
                        <span class="info-value">{{ $lansia->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tgl. Lahir</span>
                        <span class="info-value">{{ $lansia->tempat_lahir ?? 'Tidak Diketahui' }}, {{ \Carbon\Carbon::parse($lansia->tanggal_lahir)->format('d M Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Usia</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($lansia->tanggal_lahir)->age }} Tahun</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status Perkawinan</span>
                        <span class="info-value">{{ $lansia->status_perkawinan ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tgl. Terdaftar</span>
                        <span class="info-value">{{ $lansia->tanggal_daftar ? \Carbon\Carbon::parse($lansia->tanggal_daftar)->format('d M Y') : '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- DETAIL INFORMASI (KANAN) -->
            <div class="card-right-group">
                
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon"><i class="fa-solid fa-address-book"></i></div>
                        <h3>Informasi Kontak & Tempat Tinggal</h3>
                    </div>
                    
                    <div class="detail-grid">
                        <div class="data-box">
                            <label>Nomor Handphone (Telepon)</label>
                            <p>{{ $lansia->no_hp ?? 'Tidak ada nomor telepon' }}</p>
                        </div>
                        <div class="data-box">
                            <label>Alamat Email</label>
                            <p>{{ $lansia->email ?? 'Tidak ada email' }}</p>
                        </div>
                        <div class="data-box" style="grid-column: span 2;">
                            <label>Alamat Lengkap (Tempat Tinggal)</label>
                            <p>{{ $lansia->alamat ?? 'Alamat belum diatur' }}</p>
                        </div>
                    </div>
                </div>

                <div class="card card-medical">
                    <div class="card-header">
                        <div class="card-icon" style="background:#fef2f2; color:#ef4444;"><i class="fa-solid fa-file-medical"></i></div>
                        <h3>Riwayat Penyakit & Catatan Medis</h3>
                    </div>
                    
                    <div class="detail-grid">
                        <div class="data-box" style="grid-column: span 2; background: #fffbeb; border-color: #fef3c7;">
                            <label style="color: #b45309;"><i class="fa-solid fa-virus"></i> Riwayat Penyakit Tersimpan</label>
                            <p style="color: #92400e;">{{ $lansia->riwayat_penyakit ?? 'Belum ada riwayat penyakit yang tercatat.' }}</p>
                        </div>
                        <div class="data-box" style="grid-column: span 2;">
                            <label><i class="fa-solid fa-notes"></i> Keterangan / Catatan Tambahan Kader</label>
                            <p>{{ $lansia->keterangan ?? 'Tidak ada catatan tambahan untuk pasien ini.' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Contoh riwayat pemeriksaan jika tabelnya ada di masa depan -->
                <div class="card card-medical">
                    <div class="card-header">
                        <div class="card-icon" style="background:#ecfdf5; color:#10b981;"><i class="fa-solid fa-stethoscope"></i></div>
                        <h3>Riwayat Pemeriksaan Bulanan</h3>
                    </div>
                    <div class="empty-state">
                        <i class="fa-solid fa-folder-open"></i>
                        <p>Belum ada data pemeriksaan terbaru untuk lansia ini di bulan ini.</p>
                        <a href="{{ route('pemeriksaan.create') }}" class="btn-outline-blue" style="margin-top:15px; display:inline-block; border-radius: 8px; padding: 10px 20px; text-decoration:none; color: var(--primary); background: #eff6ff;">Buat Pemeriksaan Baru</a>
                    </div>
                </div>

            </div>
            <!-- Akhir Kanan -->

        </div>
    </div>
</main>
@endsection
