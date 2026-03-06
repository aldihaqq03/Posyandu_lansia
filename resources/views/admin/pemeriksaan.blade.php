@extends('layout.sidebar')

@push('styles')
    @vite('resources/css/app.css')
@endpush

@push('styles')
    @vite('resources/css/cssAdmin/pemeriksaan.css')
@endpush

@push('styles')
    @vite('resources/js/jsAdmin/pemeriksaan.js')
@endpush
@section('content')
    <div class="pemeriksaan-wrapper">
        <header class="page-header">
            <div class="header-left">
                <h1>Pemeriksaan Kesehatan</h1>
                <p>Sistem Informasi Peduli Lansia (SIMPEL)</p>
            </div>
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" placeholder="Cari lansia..." id="search-lansia">
                <button class="btn-clear-search" id="btn-clear-search" style="display: none;" title="Hapus pencarian">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </header>

        <section class="card table-section">
            <div class="card-header-flex">
                <h2>Pilih Lansia</h2>
                <span class="badge-info">48 Lansia Terdaftar</span>
            </div>
            <table class="pemeriksaan-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NAMA</th>
                        <th>USIA</th>
                        <th>GENDER</th>
                        <th>KUNJUNGAN TERAKHIR</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#001</td>
                        <td><strong>Siti Aminah</strong></td>
                        <td>72 Thn</td>
                        <td><span class="gender female">Perempuan</span></td>
                        <td>15 Okt 2023</td>
                        <td><button class="btn-pilih">Pilih</button></td>
                    </tr>
                    <tr class="row-active">
                        <td>#002</td>
                        <td><strong>Budi Santoso</strong></td>
                        <td>68 Thn</td>
                        <td><span class="gender male">Laki-laki</span></td>
                        <td>12 Okt 2023</td>
                        <td><button class="btn-terpilih">Terpilih</button></td>
                    </tr>
                    <tr>
                        <td>#003</td>
                        <td><strong>Ratna Sari</strong></td>
                        <td>75 Thn</td>
                        <td><span class="gender female">Perempuan</span></td>
                        <td>30 Sep 2023</td>
                        <td><button class="btn-pilih">Pilih</button></td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="form-section">
            <div class="form-title">
                <h2>Hasil Pemeriksaan</h2>
                <p>Input data kesehatan untuk: <strong>Budi Santoso</strong></p>
            </div>

            <form action="#" method="POST">
                <div class="form-grid">
                    <div class="form-left">
                        <div class="card inner-card">
                            <h3><i class="icon-vital">📈</i> Tanda Vital & Fisik</h3>
                            <div class="input-grid">
                                <div class="input-group">
                                    <label>Berat Badan (kg)</label>
                                    <input type="text" placeholder="contoh: 65.5">
                                </div>
                                <div class="input-group">
                                    <label>Tinggi Badan (cm)</label>
                                    <input type="text" placeholder="contoh: 170">
                                </div>
                                <div class="input-group">
                                    <label>Tekanan Darah (mmHg)</label>
                                    <input type="text" placeholder="contoh: 120/80">
                                </div>
                                <div class="input-group">
                                    <label>Gula Darah (mg/dL)</label>
                                    <input type="text" placeholder="contoh: 110">
                                </div>
                            </div>
                        </div>

                        <div class="card inner-card">
                            <h3><i class="icon-obs">📄</i> Catatan Observasi</h3>
                            <div class="input-group">
                                <label>Keluhan Fisik</label>
                                <textarea placeholder="Deskripsikan rasa sakit atau gejala yang dilaporkan lansia..."
                                    rows="4"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-right">
                        <div class="card inner-card sticky-card">
                            <h3><i class="icon-saran">💡</i> Saran/Catatan</h3>
                            <div class="input-group">
                                <label>Saran Nutrisi</label>
                                <select>
                                    <option>Pilih rekomendasi standar</option>
                                </select>
                            </div>
                            <div class="input-group">
                                <label>Rekomendasi Spesifik</label>
                                <textarea placeholder="Instruksi tambahan untuk lansia..." rows="6"></textarea>
                            </div>
                            <div class="info-box">
                                <p>ℹ️ Saran akan dicetak pada kartu kesehatan bulanan lansia.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="reset" class="btn-clear">Bersihkan Form</button>
                    <button type="submit" class="btn-save">💾 Simpan Pemeriksaan</button>
                </div>
            </form>
        </section>
    </div>
@endsection