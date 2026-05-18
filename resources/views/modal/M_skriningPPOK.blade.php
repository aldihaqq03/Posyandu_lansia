@extends('layout.sidebar')

@section('title', 'Skrining PPOK')

@push('styles')
    @vite('resources/css/cssAdmin/skrining_utama.css')
@endpush

@section('content')
<div class="skrining-wrapper">
    @php
        $jadwalHariIni = \Illuminate\Support\Facades\DB::table('jadwal_posyandu')
            ->whereDate('tanggal_pelaksanaan', \Carbon\Carbon::today())
            ->whereIn('status', [1, 2])
            ->where('ada_skrining_ppok', 1)
            ->first();
    @endphp

    @if(!$jadwalHariIni)
        <div class="alert-warning" style="background: #fff7ed; border: 1px solid #fdba74; padding: 15px; border-radius: 8px; margin-bottom: 20px; color: #9a3412;">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <strong>Peringatan:</strong> Jadwal posyandu hari ini tidak mencakup <strong>Skrining PPOK</strong>. Anda tidak dapat menyimpan data ini.
        </div>
    @endif


    <form action="{{ route('skrining_ppok.store') }}" method="POST">
        @csrf

        <!-- PENCARIAN LANSIA -->
        <div class="search-lansia-wrapper">
            <h3><i class="fa-solid fa-user-magnifying-glass"></i> Cari Data Lansia</h3>
            
            <div id="searchContainer">
                <div class="search-input-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="inputCariLansia" placeholder="Ketik nama atau NIK Lansia..." autocomplete="off">
                </div>
                <!-- Dropdown hasil -->
                <div class="dropdown-results" id="dropdownLansia"></div>
            </div>

            <!-- Card terpilih -->
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


        <div class="section-title"><i class="fa-solid fa-address-card"></i> Identitas Tambahan</div>
        <div class="form-grid">
            <div class="form-group">
                <label>Pekerjaan</label>
                <select name="pekerjaan" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="1">TNI/POLRI</option>
                    <option value="2">PNS</option>
                    <option value="3">Karyawan Swasta</option>
                    <option value="4">Buruh</option>
                    <option value="5">Petani/Nelayan</option>
                    <option value="6">Tidak Bekerja/IRT</option>
                </select>
            </div>
            <div class="form-group">
                <label>Status Vaksinasi Covid-19</label>
                <select name="status_vaksinasi_covid" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="1">Vaksinasi 1</option>
                    <option value="2">Vaksinasi 2</option>
                    <option value="3">Booster 1</option>
                </select>
            </div>
        </div>

        <div class="section-title"><i class="fa-solid fa-clipboard-list"></i> Wawancara Faktor Risiko PTM</div>
        <div class="form-grid">
            <div class="form-group">
                <label>Kurang aktivitas fisik?</label>
                <select name="kurang_aktivitas_fisik" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>
            <div class="form-group">
                <label>Kurang sayur/buah?</label>
                <select name="kurang_sayur_buah" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>
            <div class="form-group">
                <label>Merokok?</label>
                <select name="merokok" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>
            <div class="form-group">
                <label>Jenis Rokok</label>
                <select name="jenis_rokok" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="1">Rokok Konvensional</option>
                    <option value="2">Rokok Elektrik</option>
                    <option value="3">Keduanya</option>
                    <option value="4">Lainnya</option>
                </select>
            </div>
            <div class="form-group">
                <label>Konsumsi Alkohol?</label>
                <select name="konsumsi_alkohol" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>
        </div>

        @php
            $penyakitKeluarga = ['Diabetes' => 'diabetes', 'Hipertensi' => 'hipertensi', 'Jantung' => 'jantung', 'Stroke' => 'stroke', 'Kanker' => 'kanker', 'Thalasemia' => 'thalasemia'];
            $penyakitSendiri = array_merge($penyakitKeluarga, ['Asma' => 'asma', 'Kolesterol Tinggi' => 'kolesterol_tinggi', 'PPOK' => 'ppok', 'Lupus' => 'lupus', 'G. Penglihatan' => 'gangguan_penglihatan', 'G. Pendengaran' => 'gangguan_pendengaran', 'Disabilitas' => 'disabilitas']);
        @endphp
        
        <div class="form-group" style="margin-top: 15px;">
            <label>Riwayat Penyakit Keluarga:</label>
            <div class="checkbox-grid">
                @foreach ($penyakitKeluarga as $label => $val)
                <label class="checkbox-item">
                    <input type="checkbox" name="riwayat_penyakit_keluarga[]" value="{{ $val }}">
                    {{ $label }}
                </label>
                @endforeach
            </div>
        </div>

        <div class="form-group" style="margin-top: 15px;">
            <label>Riwayat Penyakit Sendiri:</label>
            <div class="checkbox-grid">
                @foreach ($penyakitSendiri as $label => $val)
                <label class="checkbox-item">
                    <input type="checkbox" name="riwayat_penyakit_sendiri[]" value="{{ $val }}">
                    {{ $label }}
                </label>
                @endforeach
            </div>
        </div>

        <div class="section-title"><i class="fa-solid fa-weight-scale"></i> Pengukuran Faktor Risiko PTM</div>
        <div class="form-grid">
            <div class="form-group">
                <label>Berat Badan (kg)</label>
                <input type="number" step="0.1" name="berat_badan" id="bbInput" class="form-control" placeholder="Contoh: 65.5">
            </div>
            <div class="form-group">
                <label>Tinggi Badan (cm)</label>
                <input type="number" step="0.1" name="tinggi_badan" id="tbInput" class="form-control" placeholder="Contoh: 165.0">
            </div>
            <div class="form-group">
                <label>IMT (kg/m²)</label>
                <input type="number" step="0.01" name="imt" id="imtInput" class="form-control" readonly placeholder="Otomatis" style="background:#e5e7eb;">
            </div>
            <div class="form-group">
                <label>Lingkar Perut (cm)</label>
                <input type="number" step="0.1" name="lingkar_perut" class="form-control">
            </div>
            <div class="form-group">
                <label>TD Sistolik (mmHg)</label>
                <input type="number" name="td_sistolik" class="form-control" placeholder="120">
            </div>
            <div class="form-group">
                <label>TD Diastolik (mmHg)</label>
                <input type="number" name="td_diastolik" class="form-control" placeholder="80">
            </div>
        </div>

        <div class="section-title"><i class="fa-solid fa-lungs"></i> Wawancara PUMA (Deteksi Dini PPOK)</div>
        <div class="form-grid">
            <div class="form-group">
                <label>Jenis Kelamin (PUMA)</label>
                <select name="puma_jenis_kelamin" id="puma_jk" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="1">Laki-laki (Skor: 1)</option>
                    <option value="0">Perempuan (Skor: 0)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Kategori Usia</label>
                <select name="puma_kategori_usia" id="puma_usia" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="0">40-49 th (Skor: 0)</option>
                    <option value="1">50-59 th (Skor: 1)</option>
                    <option value="2">>=60 th (Skor: 2)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Tidak Merokok?</label>
                <select name="puma_tidak_merokok" id="puma_tdk_rokok" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="1">Ya (Skor: 0)</option>
                    <option value="0">Tidak (Hitung Pack Years)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Rata2 Rokok/Hari</label>
                <input type="number" name="puma_rokok_per_hari" id="puma_jml_rokok" class="form-control" placeholder="Batang/hari">
            </div>
            <div class="form-group">
                <label>Lama Merokok (Tahun)</label>
                <input type="number" name="puma_lama_merokok_tahun" id="puma_lama_rokok" class="form-control" placeholder="Tahun">
            </div>
            <div class="form-group">
                <label>Pack Years</label>
                <input type="number" step="0.01" name="puma_pack_years" id="puma_pack_years" class="form-control" readonly placeholder="Otomatis" style="background:#e5e7eb;">
            </div>
            <div class="form-group">
                <label>Skor Merokok</label>
                <input type="number" name="puma_skor_merokok" id="puma_skor_rokok" class="form-control" readonly placeholder="Otomatis" style="background:#e5e7eb;">
            </div>
            <div class="form-group">
                <label>Napas Pendek</label>
                <select name="puma_napas_pendek" id="puma_napas" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="1">Ya (Skor: 1)</option>
                    <option value="0">Tidak (Skor: 0)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Sulit Keluar Dahak?</label>
                <select name="puma_sulit_dahak" id="puma_dahak" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="1">Ya (Skor: 1)</option>
                    <option value="0">Tidak (Skor: 0)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Batuk Tanpa Flu?</label>
                <select name="puma_batuk_tanpa_flu" id="puma_batuk" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="1">Ya (Skor: 1)</option>
                    <option value="0">Tidak (Skor: 0)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Pernah Spirometri?</label>
                <select name="puma_pernah_spirometri" id="puma_spiro" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="1">Ya (Skor: 1)</option>
                    <option value="0">Tidak (Skor: 0)</option>
                </select>
            </div>
            
            <div class="form-group" style="grid-column: 1 / -1;">
                <div style="background: #eff6ff; padding: 15px; border-radius: 8px; border: 1px solid #bfdbfe; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 13px; color: #1d4ed8; font-weight: 600;">Total Skor PUMA: <span id="lbl_puma_total" style="font-size: 18px;">0</span></div>
                        <div style="font-size: 12px; color: #3b82f6; margin-top: 5px;">Hasil PUMA: <strong id="lbl_puma_kategori">Edukasi</strong></div>
                    </div>
                    <input type="hidden" name="puma_total_skor" id="puma_total_skor">
                    <input type="hidden" name="puma_kategori_hasil" id="puma_kategori_hasil">
                </div>
            </div>
        </div>

        <div class="section-title"><i class="fa-solid fa-stethoscope"></i> Pemeriksaan Spirometri</div>
        <div class="form-grid">
            <div class="form-group">
                <label>Rapid Antigen</label>
                <select name="rapid_antigen" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="1">Positif</option>
                    <option value="0">Negatif</option>
                </select>
            </div>
            <div class="form-group">
                <label>Kadar CO (ppm)</label>
                <input type="number" name="kadar_co_ppm" class="form-control">
            </div>
            
            <!-- Pre Bronkodilator -->
            <div class="form-group" style="grid-column: 1 / -1; margin-top: 10px;">
                <label style="color: #3b82f6;"><strong>Sebelum Bronkodilator</strong></label>
            </div>
            <div class="form-group">
                <label>VEP1 Pre (liter)</label>
                <input type="number" step="0.01" name="vep1_pre" id="vep1_pre" class="form-control">
            </div>
            <div class="form-group">
                <label>KVP Pre (liter)</label>
                <input type="number" step="0.01" name="kvp_pre" id="kvp_pre" class="form-control">
            </div>
            <div class="form-group">
                <label>Rasio VEP1/KVP Pre (%)</label>
                <input type="number" step="0.01" name="rasio_vep1_kvp_pre" id="rasio_pre" class="form-control" readonly style="background:#e5e7eb;">
            </div>

            <!-- Bronko -->
            <div class="form-group">
                <label>Pemberian Bronkodilator?</label>
                <select name="pemberian_bronkodilator" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>
            
            <!-- Post Bronkodilator -->
            <div class="form-group" style="grid-column: 1 / -1; margin-top: 10px;">
                <label style="color: #3b82f6;"><strong>Setelah Bronkodilator</strong></label>
            </div>
            <div class="form-group">
                <label>VEP1 Post (liter)</label>
                <input type="number" step="0.01" name="vep1_post" id="vep1_post" class="form-control">
            </div>
            <div class="form-group">
                <label>KVP Post (liter)</label>
                <input type="number" step="0.01" name="kvp_post" id="kvp_post" class="form-control">
            </div>
            <div class="form-group">
                <label>Rasio VEP1/KVP Post (%)</label>
                <input type="number" step="0.01" name="rasio_vep1_kvp_post" id="rasio_post" class="form-control" readonly style="background:#e5e7eb;">
            </div>

            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Kesimpulan Pemeriksaan Spirometri</label>
                <textarea name="hasil_spirometri" class="form-control" rows="3" placeholder="Isi hasil kesimpulan..."></textarea>
            </div>

            <div class="section-title"><i class="fa-solid fa-comment-medical"></i> Catatan Keluhan</div>
            <div class="form-group" style="margin-bottom: 20px;">
                <label>Keluhan Lansia</label>
                <textarea name="keluhan" class="form-control" placeholder="Tuliskan keluhan yang dirasakan lansia saat ini..." rows="3" style="resize: vertical;"></textarea>
            </div>

        </div>

        <button type="submit" class="btn-submit" {{ !$jadwalHariIni ? 'disabled' : '' }}>
            <i class="fa-solid fa-floppy-disk"></i> Simpan Skrining PPOK
        </button>

    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const lansiaData = @json($lansias);
        
        // PENCARIAN LANSIA //
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
        
        inputCari.addEventListener('input', function() {
            const val = this.value.toLowerCase().trim();
            if(!val) {
                dropdown.classList.remove('active');
                return;
            }
            const filtered = lansiaData.filter(l => 
                l.nama_lansia.toLowerCase().includes(val) || 
                l.nik.includes(val)
            );
            renderDropdown(filtered);
        });
        
        document.addEventListener('click', function(e) {
            if (!searchContainer.contains(e.target)) {
                dropdown.classList.remove('active');
            }
        });
        
        inputCari.addEventListener('focus', function() {
            if(this.value.trim() !== '') {
                const event = new Event('input');
                this.dispatchEvent(event);
            }
        });
        
        btnGanti.addEventListener('click', function() {
            idInput.value = '';
            selectedCard.classList.remove('active');
            searchContainer.style.display = 'block';
            inputCari.focus();
        });


        // OTOMATISASI HITUNGAN //
        
        // 1. IMT
        const bb = document.getElementById('bbInput');
        const tb = document.getElementById('tbInput');
        const imt = document.getElementById('imtInput');
        
        function calcIMT() {
            if(bb.value && tb.value) {
                const tbm = tb.value / 100;
                const hitung = bb.value / (tbm * tbm);
                imt.value = hitung.toFixed(2);
            } else {
                imt.value = '';
            }
        }
        bb.addEventListener('input', calcIMT);
        tb.addEventListener('input', calcIMT);

        // 2. SPIROMETRI (Rasio VEP1/KVP)
        function calcRasio(vepid, kvpid, targetid) {
            const vep = document.getElementById(vepid);
            const kvp = document.getElementById(kvpid);
            const target = document.getElementById(targetid);
            
            function calc() {
                if(vep.value && kvp.value && parseFloat(kvp.value) > 0) {
                    const r = (parseFloat(vep.value) / parseFloat(kvp.value)) * 100;
                    target.value = r.toFixed(2);
                } else {
                    target.value = '';
                }
            }
            vep.addEventListener('input', calc);
            kvp.addEventListener('input', calc);
        }
        calcRasio('vep1_pre', 'kvp_pre', 'rasio_pre');
        calcRasio('vep1_post', 'kvp_post', 'rasio_post');


        // 3. SKOR PUMA
        const p_jk = document.getElementById('puma_jk');
        const p_usia = document.getElementById('puma_usia');
        const p_tdk_rokok = document.getElementById('puma_tdk_rokok');
        const p_jml_rokok = document.getElementById('puma_jml_rokok');
        const p_lama_rokok = document.getElementById('puma_lama_rokok');
        const puma_pack_years = document.getElementById('puma_pack_years');
        const p_skor_rokok = document.getElementById('puma_skor_rokok');
        const p_napas = document.getElementById('puma_napas');
        const p_dahak = document.getElementById('puma_dahak');
        const p_batuk = document.getElementById('puma_batuk');
        const p_spiro = document.getElementById('puma_spiro');

        const lbl_total = document.getElementById('lbl_puma_total');
        const lbl_kategori = document.getElementById('lbl_puma_kategori');
        const in_total = document.getElementById('puma_total_skor');
        const in_kategori = document.getElementById('puma_kategori_hasil');

        function calcPUMA() {
            let total = 0;
            
            // JK
            if(p_jk.value !== '') total += parseInt(p_jk.value);
            // Usia
            if(p_usia.value !== '') total += parseInt(p_usia.value);
            
            // Rokok logic
            if(p_tdk_rokok.value == '1') {
                puma_pack_years.value = '';
                p_skor_rokok.value = 0;
            } else if (p_tdk_rokok.value == '0') {
                if(p_jml_rokok.value && p_lama_rokok.value) {
                    const py = (parseFloat(p_jml_rokok.value) * parseFloat(p_lama_rokok.value)) / 20;
                    puma_pack_years.value = py.toFixed(2);
                    let sr = 0;
                    if(py >= 20 && py <= 30) sr = 1;
                    if(py > 30) sr = 2;
                    p_skor_rokok.value = sr;
                    total += sr;
                }
            }

            // Napas dll
            if(p_napas.value !== '') total += parseInt(p_napas.value);
            if(p_dahak.value !== '') total += parseInt(p_dahak.value);
            if(p_batuk.value !== '') total += parseInt(p_batuk.value);
            if(p_spiro.value !== '') total += parseInt(p_spiro.value);

            lbl_total.textContent = total;
            in_total.value = total;

            if(total < 6) {
                lbl_kategori.textContent = 'Edukasi Gaya Hidup';
                lbl_kategori.style.color = '#3b82f6'; // blue
                in_kategori.value = 0;
            } else {
                lbl_kategori.textContent = 'Risiko PPOK (Lakukan Spirometri)';
                lbl_kategori.style.color = '#ef4444'; // red
                in_kategori.value = 1;
            }
        }

        const pumaInputs = [p_jk, p_usia, p_tdk_rokok, p_jml_rokok, p_lama_rokok, p_napas, p_dahak, p_batuk, p_spiro];
        pumaInputs.forEach(el => el.addEventListener('input', calcPUMA));
        calcPUMA();
    });
</script>
@endpush
@endsection
