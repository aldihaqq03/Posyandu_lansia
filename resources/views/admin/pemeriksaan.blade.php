@extends('layout.sidebar')

@section('title', 'Pemeriksaan Mingguan')

@push('styles')
    @vite('resources/css/cssAdmin/skrining_utama.css')
@endpush

@section('content')
    <div class="skrining-wrapper">
        <div class="skrining-header">
            <h1>Pemeriksaan Mingguan</h1>
            <p>Sistem Informasi Peduli Lansia (SIMPEL)</p>
        </div>

        <form action="#" method="POST">
            @csrf

            <div class="search-lansia-wrapper">
                <h3><i class="fa-solid fa-user-magnifying-glass"></i>Pilih Lansia</h3>
                <div id="searchContainer">
                    <div class="search-input-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="inputCariLansia" placeholder="Ketik nama atau NIK Lansia..."
                            autocomplete="off">
                    </div>
                    <div class="dropdown-results" id="dropdownLansia"></div>
                </div>

                <div class="selected-lansia-card" id="selectedLansiaCard">
                    <div class="selected-info">
                        <span class="lbl">Lansia Terpilih:</span>
                        <span class="val" id="selLansiaName">-</span>
                        <span class="lansia-nik" id="selLansiaNik">-</span>
                    </div>
                    <button type="button" class="btn-change-lansia" id="btnGantiLansia">Ganti Lansia</button>
                </div>
                <input type="hidden" name="id_lansia" id="idLansiaInput" required>
            </div>

            <div class="section-title"><i class="fa-solid fa-heart-pulse"></i> Hasil Pemeriksaan</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Tinggi Badan (cm)</label>
                    <input type="number" step="0.1" name="tinggi_badan" class="form-control" placeholder="Contoh: 160.5" required>
                </div>
                <div class="form-group">
                    <label>Berat Badan (kg)</label>
                    <input type="number" step="0.1" name="berat_badan" class="form-control" placeholder="Contoh: 65.5" required>
                </div>
                <div class="form-group">
                    <label>Lingkar Perut (cm)</label>
                    <input type="number" step="0.1" name="lingkar_perut" class="form-control" placeholder="Contoh: 80.5" required>
                </div>
                <div class="form-group">
                    <label>TD Sistolik (mmHg)</label>
                    <input type="number" name="td_sistolik" class="form-control" placeholder="Contoh: 120" required>
                </div>
                <div class="form-group">
                    <label>TD Diastolik (mmHg)</label>
                    <input type="number" name="td_diastolik" class="form-control" placeholder="Contoh: 80" required>
                </div>
            </div>

            <div class="section-title"><i class="fa-solid fa-notes-medical"></i> Tindakan & Edukasi</div>
            <div class="form-group" style="margin-bottom: 20px;">
                <label>Edukasi Penyakit</label>
                <textarea name="edukasi_penyakit" class="form-control" placeholder="Catat edukasi yang diberikan pada lansia..." rows="4" required style="resize: vertical;"></textarea>
            </div>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label>Resep Obat (Opsional)</label>
                <textarea name="resep_obat" class="form-control" placeholder="Tuliskan resep obat jika ada..." rows="4" style="resize: vertical;"></textarea>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Pemeriksaan
            </button>

        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lansiaData = @json($lansias);

        const inputCari = document.getElementById('inputCariLansia');
        const dropdown = document.getElementById('dropdownLansia');
        const searchContainer = document.getElementById('searchContainer');
        const selectedCard = document.getElementById('selectedLansiaCard');
        const idInput = document.getElementById('idLansiaInput');
        const selName = document.getElementById('selLansiaName');
        const selNik = document.getElementById('selLansiaNik');
        const btnGanti = document.getElementById('btnGantiLansia');

        function renderDropdown(data) {
            dropdown.innerHTML = '';
            if (data.length === 0) {
                dropdown.innerHTML = '<div class="lansia-result-item"><span class="lansia-name text-gray-500">Tidak ditemukan</span></div>';
            } else {
                data.slice(0, 10).forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'lansia-result-item';
                    div.innerHTML = `
                        <span class="lansia-name">${item.nama_lansia}</span>
                        <span class="lansia-nik">NIK: ${item.nik}</span>
                    `;
                    div.addEventListener('click', () => {
                        selectLansia(item);
                    });
                    dropdown.appendChild(div);
                });
            }
            dropdown.classList.add('active');
        }

        function selectLansia(item) {
            idInput.value = item.id_lansia;
            selName.textContent = item.nama_lansia;
            selNik.textContent = 'NIK: ' + item.nik;

            searchContainer.style.display = 'none';
            selectedCard.classList.add('active');
            dropdown.classList.remove('active');
            inputCari.value = '';
        }

        inputCari.addEventListener('input', function () {
            const val = this.value.toLowerCase().trim();
            if (!val) {
                dropdown.classList.remove('active');
                return;
            }
            const filtered = lansiaData.filter(l =>
                (l.nama_lansia && l.nama_lansia.toLowerCase().includes(val)) ||
                (l.nik && l.nik.includes(val))
            );
            renderDropdown(filtered);
        });

        // Handle enter key
        inputCari.addEventListener('keydown', function(e) {
            if(e.key === 'Enter') {
                e.preventDefault(); // Prevent form submission
                const val = this.value.toLowerCase().trim();
                if(val.length > 0) {
                    const filtered = lansiaData.filter(l => 
                        (l.nama_lansia && l.nama_lansia.toLowerCase().includes(val)) || 
                        (l.nik && l.nik.includes(val))
                    );
                    if(filtered.length > 0) {
                        selectLansia(filtered[0]);
                    }
                }
            }
        });

        document.addEventListener('click', function (e) {
            if (!searchContainer.contains(e.target)) {
                dropdown.classList.remove('active');
            }
        });

        inputCari.addEventListener('focus', function () {
            if (this.value.trim() !== '') {
                const event = new Event('input');
                this.dispatchEvent(event);
            }
        });

        btnGanti.addEventListener('click', function () {
            idInput.value = '';
            selectedCard.classList.remove('active');
            searchContainer.style.display = 'block';
            inputCari.focus();
        });
    });
</script>
@endpush