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
                <!--  <button class="btn-primary" onclick="window.print()">
                    <i class="fa-solid fa-print"></i> Cetak Profil
                </button> -->
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
                            <span class="info-value">{{ $lansia->tempat_lahir ?? 'Tidak Diketahui' }},
                                {{ \Carbon\Carbon::parse($lansia->tanggal_lahir)->format('d M Y') }}</span>
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
                            <span
                                class="info-value">{{ $lansia->tanggal_daftar ? \Carbon\Carbon::parse($lansia->tanggal_daftar)->format('d M Y') : '-' }}</span>
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
                            <div class="card-icon" style="background:#fef2f2; color:#ef4444;"><i
                                    class="fa-solid fa-file-medical"></i></div>
                            <h3>Riwayat Penyakit & Catatan Medis</h3>
                        </div>

                        <div class="detail-grid">
                            <div class="data-box" style="grid-column: span 2; background: #fffbeb; border-color: #fef3c7;">
                                <label style="color: #b45309;"><i class="fa-solid fa-virus"></i> Riwayat Penyakit
                                    Tersimpan</label>
                                <p style="color: #92400e;">
                                    {{ $lansia->riwayat_penyakit ?? 'Belum ada riwayat penyakit yang tercatat.' }}</p>
                            </div>
                            <div class="data-box" style="grid-column: span 2;">
                                <label><i class="fa-solid fa-notes"></i> Keterangan / Catatan Tambahan Kader</label>
                                <p>{{ $lansia->keterangan ?? 'Tidak ada catatan tambahan untuk pasien ini.' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- RIWAYAT SKRINING UTAMA -->
                    <div class="card card-medical">
                        <div class="card-header">
                            <div class="card-icon" style="background:#ecfdf5; color:#10b981;"><i
                                    class="fa-solid fa-notes-medical"></i></div>
                            <h3>Riwayat Skrining Utama</h3>
                        </div>
                        @if($skriningUtama->isEmpty())
                            <div class="empty-state">
                                <i class="fa-solid fa-folder-open"></i>
                                <p>Belum ada data skrining utama untuk lansia ini.</p>
                            </div>
                        @else
                            <div style="overflow-x:auto;">
                                <table class="custom-table"
                                    style="width: 100%; border-collapse: collapse; margin-top: 15px; text-align: left;">
                                    <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                        <tr>
                                            <th style="padding: 10px; color: #475569; font-size: 0.85rem;">Tanggal</th>
                                            <th style="padding: 10px; color: #475569; font-size: 0.85rem;">Tensi</th>
                                            <th style="padding: 10px; color: #475569; font-size: 0.85rem;">BB/TB (IMT)</th>
                                            <th style="padding: 10px; color: #475569; font-size: 0.85rem;">Gula Darah</th>
                                            <th style="padding: 10px; color: #475569; font-size: 0.85rem;">Kolesterol</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($skriningUtama as $utama)
                                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                                <td style="padding: 10px; font-size: 0.85rem;">
                                                    {{ \Carbon\Carbon::parse($utama->tanggal_skrining)->format('d M Y') }}</td>
                                                <td style="padding: 10px; font-size: 0.85rem;">
                                                    {{ $utama->td_sistolik ?? '-' }}/{{ $utama->td_diastolik ?? '-' }} mmHg</td>
                                                <td style="padding: 10px; font-size: 0.85rem;">{{ $utama->berat_badan ?? '-' }} kg /
                                                    {{ $utama->tinggi_badan ?? '-' }} cm ({{ $utama->imt ?? '-' }})</td>
                                                <td style="padding: 10px; font-size: 0.85rem;">{{ $utama->gula_darah ?? '-' }} mg/dL
                                                </td>
                                                <td style="padding: 10px; font-size: 0.85rem;">{{ $utama->kolesterol ?? '-' }} mg/dL
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- RIWAYAT SKRINING PPOK -->
                    <div class="card card-medical" style="margin-top: 20px;">
                        <div class="card-header">
                            <div class="card-icon" style="background:#eff6ff; color:#3b82f6;"><i
                                    class="fa-solid fa-lungs"></i></div>
                            <h3>Riwayat Skrining PPOK / Paru</h3>
                        </div>
                        @if($skriningPPOK->isEmpty())
                            <div class="empty-state">
                                <i class="fa-solid fa-folder-open"></i>
                                <p>Belum ada data skrining PPOK untuk lansia ini.</p>
                            </div>
                        @else
                            <div style="overflow-x:auto;">
                                <table class="custom-table"
                                    style="width: 100%; border-collapse: collapse; margin-top: 15px; text-align: left;">
                                    <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                        <tr>
                                            <th style="padding: 10px; color: #475569; font-size: 0.85rem;">Tanggal</th>
                                            <th style="padding: 10px; color: #475569; font-size: 0.85rem;">Skor PUMA</th>
                                            <th style="padding: 10px; color: #475569; font-size: 0.85rem;">Rapid Antigen</th>
                                            <th style="padding: 10px; color: #475569; font-size: 0.85rem;">Spirometri (VEP1/KVP
                                                Pre)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($skriningPPOK as $ppok)
                                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                                <td style="padding: 10px; font-size: 0.85rem;">
                                                    {{ \Carbon\Carbon::parse($ppok->tanggal_skrining)->format('d M Y') }}</td>
                                                <td style="padding: 10px; font-size: 0.85rem;">{{ $ppok->puma_total_skor ?? '-' }}
                                                    {{ $ppok->puma_kategori_hasil == 1 ? '(Sedang/Berat)' : '(Normal)' }}</td>
                                                <td style="padding: 10px; font-size: 0.85rem;">
                                                    {{ $ppok->rapid_antigen === null ? '-' : ($ppok->rapid_antigen ? 'Positif' : 'Negatif') }}
                                                </td>
                                                <td style="padding: 10px; font-size: 0.85rem;">{{ $ppok->vep1_pre ?? '-' }} /
                                                    {{ $ppok->kvp_pre ?? '-' }} ({{ $ppok->rasio_vep1_kvp_pre ?? '-' }}%)</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- JADWAL MINGGUAN -->
                    <div class="card card-medical" style="margin-top: 20px; margin-bottom: 30px;">
                        <div class="card-header">
                            <div class="card-icon" style="background:#fefce8; color:#eab308;"><i
                                    class="fa-solid fa-calendar-week"></i></div>
                            <h3>Catatan Jadwal Mingguan (Intervensi)</h3>
                        </div>
                        @if($jadwalMingguan->isEmpty())
                            <div class="empty-state">
                                <i class="fa-solid fa-folder-open"></i>
                                <p>Belum ada jadwal mingguan yang disusun.</p>
                            </div>
                        @else
                            <div style="overflow-x:auto;">
                                <table class="custom-table"
                                    style="width: 100%; border-collapse: collapse; margin-top: 15px; text-align: left;">
                                    <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                        <tr>
                                            <th style="padding: 10px; color: #475569; font-size: 0.85rem;">Hari</th>
                                            <th style="padding: 10px; color: #475569; font-size: 0.85rem;">Waktu</th>
                                            <th style="padding: 10px; color: #475569; font-size: 0.85rem;">Aktivitas</th>
                                            <th style="padding: 10px; color: #475569; font-size: 0.85rem;">Deskripsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $hariMap = [0 => 'Senin', 1 => 'Selasa', 2 => 'Rabu', 3 => 'Kamis', 4 => 'Jumat', 5 => 'Sabtu', 6 => 'Minggu'];
                                        @endphp
                                        @foreach($jadwalMingguan as $jadwal)
                                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                                <td style="padding: 10px; font-size: 0.85rem; font-weight: 600;">
                                                    {{ $hariMap[$jadwal->hari] ?? '-' }}</td>
                                                <td style="padding: 10px; font-size: 0.85rem;">
                                                    {{ \Carbon\Carbon::parse($jadwal->waktu_aktivitas)->format('H:i') }}
                                                    ({{ $jadwal->durasi_menit }} mnt)</td>
                                                <td style="padding: 10px; font-size: 0.85rem;"><span
                                                        style="padding: 3px 8px; background:#eff6ff; color:#3b82f6; border-radius:12px; font-size: 0.75rem;">{{ $jadwal->nama_aktivitas }}</span>
                                                </td>
                                                <td style="padding: 10px; font-size: 0.85rem;">{{ $jadwal->deskripsi ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                </div>
                <!-- Akhir Kanan -->

            </div>
        </div>
    </main>
@endsection