@extends('layout.sidebar')

@section('title', 'Skrining Utama')

@push('styles')
    @vite('resources/css/cssAdmin/skrining_utama.css')
@endpush

@section('content')
    <div class="skrining-wrapper">
        @php
            $jadwalHariIni = \Illuminate\Support\Facades\DB::table('jadwal_posyandu')
                ->whereDate('tanggal_pelaksanaan', \Carbon\Carbon::today())
                ->whereIn('status', [1, 2])
                ->where('ada_skrining_utama', 1)
                ->first();
        @endphp

        @if(!$jadwalHariIni)
            <div class="alert-warning" style="background: #fff7ed; border: 1px solid #fdba74; padding: 15px; border-radius: 8px; margin-bottom: 20px; color: #9a3412;">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <strong>Peringatan:</strong> Jadwal posyandu hari ini tidak mencakup <strong>Skrining Utama</strong>. Anda tidak dapat menyimpan data ini.
            </div>
        @endif


        <form action="{{ route('skrining_utama.store') }}" method="POST">
            @csrf

            <div class="search-lansia-wrapper">
                <h3><i class="fa-solid fa-user-magnifying-glass"></i>Pilih Lansia</h3>

                <div id="searchContainer">
                    <div class="search-input-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="inputCariLansia" placeholder="Ketik nama atau NIK Lansia..."
                            autocomplete="off">
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

            <div class="section-title"><i class="fa-solid fa-smoking"></i> Faktor Risiko Perilaku</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Merokok</label>
                    <select name="merokok" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Kategori Merokok</label>
                    <select name="merokok_kategori" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="1">20-30 bungkus/tahun</option>
                        <option value="2">>30 bungkus/tahun</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Paparan Asap Rokok</label>
                    <select name="paparan_asap_rokok" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Frekuensi Paparan</label>
                    <select name="paparan_asap_rokok_frekuensi" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="1">Setiap Hari</option>
                        <option value="2">Tidak Setiap Hari</option>
                    </select>
                </div>
            </div>

            <div class="section-title"><i class="fa-solid fa-utensils"></i> Konsumsi</div>
            <div class="form-grid">
                @php
                    $opsi = ['1' => 'Ya', '2' => 'Tidak', '3' => 'Tidak setiap hari'];
                @endphp
                @foreach (['konsumsi_gula' => 'Konsumsi Gula', 'konsumsi_garam' => 'Konsumsi Garam', 'konsumsi_minyak' => 'Konsumsi Minyak', 'konsumsi_sayur_buah' => 'Sayur/Buah', 'aktivitas_fisik' => 'Aktivitas Fisik', 'konsumsi_alkohol' => 'Konsumsi Alkohol'] as $name => $label)
                    <div class="form-group">
                        <label>{{ $label }}</label>
                        <select name="{{ $name }}" class="form-control">
                            <option value="">-- Pilih --</option>
                            @foreach ($opsi as $val => $text)
                                <option value="{{ $val }}">{{ $text }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
            </div>

            <div class="section-title"><i class="fa-solid fa-notes-medical"></i> Riwayat Penyakit</div>
            @php
                $penyakit = ['DM', 'Hipertensi', 'Jantung', 'Stroke', 'Asma', 'Kanker', 'Kolesterol', 'PPOK', 'Talasemia', 'Lupus', 'G. Penglihatan'];
                $penyakitValues = ['dm', 'hipertensi', 'jantung', 'stroke', 'asma', 'kanker', 'kolesterol', 'ppok', 'talasemia', 'lupus', 'g_penglihatan'];
            @endphp

            <div class="form-group" style="margin-bottom: 20px;">
                <label>Riwayat Penyakit Keluarga:</label>
                <div class="checkbox-grid" style="margin-top: 10px;">
                    @foreach ($penyakitValues as $index => $p)
                        <label class="checkbox-item">
                            <input type="checkbox" name="riwayat_penyakit_keluarga[]" value="{{ $p }}">
                            {{ $penyakit[$index] }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label>Riwayat Penyakit Sendiri:</label>
                <div class="checkbox-grid" style="margin-top: 10px;">
                    @foreach ($penyakitValues as $index => $p)
                        <label class="checkbox-item">
                            <input type="checkbox" name="riwayat_penyakit_sendiri[]" value="{{ $p }}">
                            {{ $penyakit[$index] }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="section-title"><i class="fa-solid fa-person-arrow-up-from-line"></i> Pengukuran Fisik & Lab</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Tinggi Badan (cm)</label>
                    <input type="number" step="0.1" name="tinggi_badan" class="form-control" placeholder="Contoh: 160.5">
                </div>
                <div class="form-group">
                    <label>Berat Badan (kg)</label>
                    <input type="number" step="0.1" name="berat_badan" class="form-control" placeholder="Contoh: 60.5">
                </div>
                <div class="form-group">
                    <label>Lingkar Perut (cm)</label>
                    <input type="number" step="0.1" name="lingkar_perut" class="form-control" placeholder="Contoh: 85">
                </div>
                <div class="form-group">
                    <label>TD Sistolik (mmHg)</label>
                    <input type="number" name="td_sistolik" class="form-control" placeholder="Contoh: 120">
                </div>
                <div class="form-group">
                    <label>TD Diastolik (mmHg)</label>
                    <input type="number" name="td_diastolik" class="form-control" placeholder="Contoh: 80">
                </div>
                <div class="form-group">
                    <label>Gula Darah (mg/dL)</label>
                    <input type="number" name="gula_darah" class="form-control" placeholder="Contoh: 110">
                </div>
                <div class="form-group">
                    <label>Kolesterol (mg/dL)</label>
                    <input type="number" name="kolesterol" class="form-control" placeholder="Contoh: 190">
                </div>
            </div>

            <div class="section-title"><i class="fa-solid fa-eye"></i> Panca Indera & SRQ-20</div>
            <div class="form-group" style="margin-bottom: 20px;">
                <label>Gangguan Penglihatan (Pilih yang sesuai):</label>
                <div class="checkbox-grid" style="margin-top: 10px;">
                    @foreach (['Katarak' => 'katarak', 'Pteregium' => 'pteregium', 'Kel. Refraksi' => 'kelainan_refraksi', 'Ulkus' => 'ulkus', 'Conjungtivitis' => 'conjungtivitis', 'Glaukoma' => 'glaukoma', 'Retinopati' => 'retinopati', 'Normal' => 'normal'] as $label => $val)
                        <label class="checkbox-item">
                            <input type="checkbox" name="skrining_penglihatan[]" value="{{ $val }}">
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 30px;">
                <label>Gangguan Pendengaran (Pilih yang sesuai):</label>
                <div class="checkbox-grid" style="margin-top: 10px;">
                    @foreach (['Serumen Prop' => 'serumen_prop', 'OMP' => 'omp', 'OMK' => 'omk', 'Tajam Pendengaran' => 'tajam_pendengaran', 'Presbikusis' => 'presbikusis', 'Congek' => 'congek', 'Normal' => 'normal'] as $label => $val)
                        <label class="checkbox-item">
                            <input type="checkbox" name="skrining_pendengaran[]" value="{{ $val }}">
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label>Kuisioner SRQ-20 (Centang jika "Ya"):</label>
                @php
                    $srq_questions = [
                        'Apakah Anda sering merasa sakit kepala?',
                        'Apakah Anda kehilangan nafsu makan?',
                        'Apakah Anda sulit tidur?',
                        'Apakah Anda mudah merasa takut?',
                        'Apakah Anda merasa cemas, tegang, atau khawatir?',
                        'Apakah tangan Anda gemetar?',
                        'Apakah pencernaan Anda terganggu/buruk?',
                        'Apakah Anda merasa sulit untuk berpikir jernih?',
                        'Apakah Anda merasa tidak bahagia?',
                        'Apakah Anda menangis lebih sering dari biasanya?',
                        'Apakah Anda merasa sulit untuk menikmati kegiatan sehari-hari?',
                        'Apakah Anda merasa kesulitan untuk mengambil keputusan?',
                        'Apakah pekerjaan sehari-hari Anda terganggu?',
                        'Apakah Anda merasa tidak mampu berperan hal yang bermanfaat dalam hidup?',
                        'Apakah Anda kehilangan minat pada berbagai hal?',
                        'Apakah Anda merasa tidak berharga?',
                        'Apakah Anda mempunyai pikiran untuk mengakhiri hidup Anda?',
                        'Apakah Anda merasa lelah sepanjang waktu?',
                        'Apakah Anda merasakan tidak enak di perut?',
                        'Apakah Anda mudah lelah?'
                    ];
                @endphp
                <div class="srq-list">
                    @foreach ($srq_questions as $index => $q)
                        <label class="srq-item">
                            <input type="checkbox" name="srq_{{ $index + 1 }}" value="1">
                            <span><strong>{{ $index + 1 }}.</strong> {{ $q }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="section-title"><i class="fa-solid fa-comment-medical"></i> Catatan Keluhan</div>
            <div class="form-group" style="margin-bottom: 20px;">
                <label>Keluhan Lansia</label>
                <textarea name="keluhan" class="form-control" placeholder="Tuliskan keluhan yang dirasakan lansia saat ini..." rows="3" style="resize: vertical;"></textarea>
            </div>

            <button type="submit" class="btn-submit" {{ !$jadwalHariIni ? 'disabled' : '' }}>
                <i class="fa-solid fa-floppy-disk"></i> Simpan Data Skrining
            </button>

        </form>
    </div>

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
                        l.nama_lansia.toLowerCase().includes(val) ||
                        l.nik.includes(val)
                    );
                    renderDropdown(filtered);
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
@endsection