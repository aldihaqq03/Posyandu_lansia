@extends('layout.sidebar')

@push('styles')
    @vite('resources/css/app.css')
@endpush

@push('styles')
    @vite('resources/css/cssAdmin/pemeriksaan.css')
@endpush

@push('scripts')
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
                <span class="badge-info">{{ count($lansias) }} Lansia Terdaftar</span>
            </div>
            <table class="pemeriksaan-table">
                <thead>
                    <tr>
                        <th>NAMA</th>
                        <th>NIK</th>
                        <th>GENDER</th>
                        <th>USIA</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody id="lansia-table-body">
                    @forelse($lansias as $l)
                        <tr>
                            <td><strong>{{ htmlspecialchars($l->nama_lansia) }}</strong></td>
                            <td>{{ $l->nik }}</td>
                            <td>
                                @if(strtolower($l->jenis_kelamin) == 'perempuan' || strtolower($l->jenis_kelamin) == 'p')
                                    <span class="gender female">Perempuan</span>
                                @else
                                    <span class="gender male">Laki-laki</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($l->tanggal_lahir)->age ?? '-' }} Thn</td>
                            <td><button type="button" class="btn-pilih" onclick="pilihLansia({{ $l->id_lansia }}, '{{ addslashes($l->nama_lansia) }}', this)">Pilih</button></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px;">Belum ada data lansia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <section class="form-section">
            <div class="form-title">
                <h2>Hasil Pemeriksaan</h2>
                <p>Input data kesehatan untuk: <strong id="selected-lansia-name">Belum dipilih (Pilih pada tabel di atas)</strong></p>
            </div>

            <form action="#" method="POST">
                <input type="hidden" name="id_lansia" id="selected-id-lansia" required>
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

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const searchInput = document.getElementById('search-lansia');
        const clearSearchBtn = document.getElementById('btn-clear-search');
        const tableBody = document.getElementById('lansia-table-body');
        
        if(searchInput && tableBody) {
            const rows = tableBody.getElementsByTagName('tr');
            
            searchInput.addEventListener('keyup', function() {
                const term = this.value.toLowerCase();
                
                if (term.length > 0) {
                    clearSearchBtn.style.display = 'block';
                } else {
                    clearSearchBtn.style.display = 'none';
                }

                Array.from(rows).forEach(row => {
                    const nameCell = row.cells[0];
                    const nikCell = row.cells[1];
                    if (nameCell && nikCell) {
                        const name = nameCell.textContent.toLowerCase();
                        const nik = nikCell.textContent.toLowerCase();
                        if (name.includes(term) || nik.includes(term)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            });

            clearSearchBtn.addEventListener('click', () => {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('keyup'));
            });
        }
    });

    function pilihLansia(id, nama, btnElement) {
        document.getElementById('selected-id-lansia').value = id;
        document.getElementById('selected-lansia-name').textContent = nama;
        
        // Kembalikan semua tombol menjadi Pilih
        document.querySelectorAll('.btn-terpilih').forEach(btn => {
            btn.className = 'btn-pilih';
            btn.innerHTML = 'Pilih';
            const row = btn.closest('tr');
            if(row) row.classList.remove('row-active');
        });

        // Set tombol yang diklik menjadi Terpilih
        btnElement.className = 'btn-terpilih';
        btnElement.innerHTML = '<i class="fa-solid fa-check"></i> Terpilih';
        const currentRow = btnElement.closest('tr');
        if(currentRow) currentRow.classList.add('row-active');
        
        // Scroll form agar nyaman
        document.querySelector('.form-section').scrollIntoView({ behavior: 'smooth' });
    }
</script>
@endpush