{{-- resources/views/admin/skrining/index.blade.php --}}
@extends('layout.sidebar')

@push('styles')
    @vite('resources/css/cssAdmin/skrining_index.css')
@endpush

@section('title', 'Input Skrining')

@section('content')
<div class="skrining-wrapper">

    {{-- ══════════════════════════════════════════
         HEADER
    ══════════════════════════════════════════ --}}
    <header class="page-header">
        <div class="header-left">
            <nav class="breadcrumb">
                <a href="/dashboard" class="text-muted">Dashboard</a>
                <i class="fa-solid fa-chevron-right"></i>
                <span class="text-muted">Input Skrining</span>
            </nav>
            <h1 class="page-title">Input Skrining Posyandu</h1>
        </div>
    </header>

    {{-- ══════════════════════════════════════════
         STATUS JADWAL
    ══════════════════════════════════════════ --}}
    @if($jadwal)
        <div class="jadwal-info-bar aktif">
            <i class="fa-solid fa-calendar-check"></i>
            <div>
                <strong>Jadwal Hari Ini:</strong> {{ $jadwal->tema }}
                &nbsp;·&nbsp; {{ $jadwal->lokasi }}
                &nbsp;·&nbsp; {{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->translatedFormat('d F Y') }}
            </div>
            <div class="jadwal-tags-bar">
                @foreach($jadwal->detailSkrining as $ds)
                    <span class="skrining-chip">
                        {{ \App\Models\DetailSkrining::labelMap()[$ds->jenis_skrining] ?? '-' }}
                    </span>
                @endforeach
            </div>
        </div>
    @else
        <div class="jadwal-info-bar nonaktif">
            <i class="fa-solid fa-calendar-xmark"></i>
            <span>Tidak ada jadwal posyandu aktif hari ini. Input skrining dikunci.</span>
        </div>
    @endif

    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="alert-error"><i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert-success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
    @endif

    {{-- ══════════════════════════════════════════
         FORM UTAMA
    ══════════════════════════════════════════ --}}
    <form action="{{ route('skrining.store') }}" method="POST" id="formSkrining"
        class="{{ !$jadwal ? 'form-locked' : '' }}">
        @csrf

        {{-- ─── PILIH LANSIA ─────────────────────────────────────── --}}
        <div class="form-section">
            <div class="section-header">
                <i class="fa-solid fa-person-cane"></i>
                <span>Data Lansia</span>
            </div>
            <div class="form-group">
                <label class="form-label">Pilih Lansia <span class="required">*</span></label>
                <select name="id_lansia" id="select-lansia" class="form-control" {{ !$jadwal ? 'disabled' : '' }} required>
                    <option value="">-- Pilih Lansia --</option>
                    @foreach($lansia as $l)
                        <option value="{{ $l->id_lansia }}" {{ old('id_lansia') == $l->id_lansia ? 'selected' : '' }}>
                            {{ $l->nama_lansia }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Keluhan</label>
                <textarea name="keluhan" class="form-control" rows="2"
                    placeholder="Keluhan yang disampaikan lansia (opsional)"
                    {{ !$jadwal ? 'disabled' : '' }}>{{ old('keluhan') }}</textarea>
            </div>
        </div>

        {{-- ══════════════════════════════════════════
             KUNJUNGAN RUTIN
        ══════════════════════════════════════════ --}}
        @if($jadwal && in_array(\App\Models\DetailSkrining::KUNJUNGAN_RUTIN, $aktifSkrining))
        <div class="form-section" id="section-kunjungan">
            <div class="section-header kunjungan">
                <i class="fa-solid fa-stethoscope"></i>
                <span>Kunjungan Rutin</span>
                <span class="badge-always">Selalu Ada</span>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Berat Badan (kg) <span class="required">*</span></label>
                    <input type="number" name="berat_badan" step="0.1" class="form-control"
                        placeholder="60.5" value="{{ old('berat_badan') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tinggi Badan (cm) <span class="required">*</span></label>
                    <input type="number" name="tinggi_badan" step="0.1" class="form-control"
                        placeholder="165.0" value="{{ old('tinggi_badan') }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Lingkar Perut (cm) <span class="required">*</span></label>
                    <input type="number" name="lingkar_perut" step="0.1" class="form-control"
                        placeholder="80.0" value="{{ old('lingkar_perut') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">IMT (otomatis)</label>
                    <input type="text" id="preview-imt" class="form-control" readonly placeholder="—" tabindex="-1">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">TD Sistolik (mmHg) <span class="required">*</span></label>
                    <input type="number" name="td_sistolik" class="form-control"
                        placeholder="120" value="{{ old('td_sistolik') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">TD Diastolik (mmHg) <span class="required">*</span></label>
                    <input type="number" name="td_diastolik" class="form-control"
                        placeholder="80" value="{{ old('td_diastolik') }}" required>
                </div>
            </div>

            {{-- ─── RESEP OBAT ──────────────────────────────────── --}}
            <div class="resep-toggle">
                <label class="toggle-label">
                    <input type="checkbox" name="ada_resep" id="chk-ada-resep" value="1"
                        {{ old('ada_resep') ? 'checked' : '' }}>
                    <span>Tambahkan Resep Obat</span>
                </label>
            </div>

            <div id="resep-section" class="{{ old('ada_resep') ? '' : 'd-none' }}">
                <div class="resep-header">
                    <span>Daftar Resep</span>
                    <button type="button" class="btn-add-kecil" id="btn-add-resep">
                        <i class="fa-solid fa-plus"></i> Tambah Obat
                    </button>
                </div>
                <div id="resep-list">
                    @forelse(old('resep', []) as $i => $r)
                        @include('admin.skrining._resep_row', ['i' => $i, 'r' => $r, 'obat' => $obat])
                    @empty
                        @include('admin.skrining._resep_row', ['i' => 0, 'r' => [], 'obat' => $obat])
                    @endforelse
                </div>
                <div class="form-group" style="margin-top:12px">
                    <label class="form-label">Catatan Resep</label>
                    <textarea name="catatan_resep" class="form-control" rows="2"
                        placeholder="Instruksi tambahan resep...">{{ old('catatan_resep') }}</textarea>
                </div>
            </div>
        </div>
        @endif

        {{-- ══════════════════════════════════════════
             SKRINING UTAMA
        ══════════════════════════════════════════ --}}
        @if($jadwal && in_array(\App\Models\DetailSkrining::SKRINING_UTAMA, $aktifSkrining))
        <div class="form-section" id="section-utama">
            <div class="section-header utama">
                <i class="fa-solid fa-clipboard-list"></i>
                <span>Skrining Utama</span>
            </div>

            {{-- Pengukuran Lab --}}
            <div class="subsection-label">Pengukuran Lab</div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Gula Darah (mg/dL)</label>
                    <input type="number" name="gula_darah" class="form-control"
                        placeholder="100" value="{{ old('gula_darah') }}">
                    <small class="form-hint">Baik: &lt;145 · Sedang: 145–199 · Tidak Baik: ≥200</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Kolesterol (mg/dL)</label>
                    <input type="number" name="kolesterol" class="form-control"
                        placeholder="180" value="{{ old('kolesterol') }}">
                    <small class="form-hint">Baik: &lt;150 · Sedang: 150–189 · Tidak Baik: ≥190</small>
                </div>
            </div>

            {{-- IVA/Sadanis (khusus perempuan, opsional) --}}
            <div class="form-group">
                <label class="form-label">IVA / Sadanis <span class="subsection-hint">(opsional, perempuan)</span></label>
                <div class="radio-group">
                    <label><input type="radio" name="iva_sadanis" value="1" {{ old('iva_sadanis') == '1' ? 'checked' : '' }}> Positif / Dilakukan</label>
                    <label><input type="radio" name="iva_sadanis" value="0" {{ old('iva_sadanis') == '0' ? 'checked' : '' }}> Negatif / Tidak Dilakukan</label>
                </div>
            </div>

            {{-- Gaya Hidup --}}
            <div class="subsection-label">Gaya Hidup</div>

            {{-- Merokok --}}
            <div class="gaya-hidup-item">
                <label class="form-label">Merokok</label>
                <div class="radio-group">
                    <label><input type="radio" name="merokok" value="1" {{ old('merokok') == '1' ? 'checked' : '' }}> Ya</label>
                    <label><input type="radio" name="merokok" value="0" {{ old('merokok') == '0' ? 'checked' : '' }}> Tidak</label>
                </div>
            </div>
            <div class="form-group" id="merokok-kategori-group" style="{{ old('merokok') == '1' ? '' : 'display:none' }}">
                <label class="form-label">Kategori Merokok</label>
                <div class="radio-group">
                    <label><input type="radio" name="merokok_kategori" value="1" {{ old('merokok_kategori') == '1' ? 'checked' : '' }}> 20–30 bungkus/tahun</label>
                    <label><input type="radio" name="merokok_kategori" value="2" {{ old('merokok_kategori') == '2' ? 'checked' : '' }}> &gt;30 bungkus/tahun</label>
                </div>
            </div>

            {{-- Paparan Asap Rokok --}}
            <div class="gaya-hidup-item">
                <label class="form-label">Paparan Asap Rokok (anggota keluarga serumah merokok)</label>
                <div class="radio-group">
                    <label><input type="radio" name="paparan_asap_rokok" value="1" {{ old('paparan_asap_rokok') == '1' ? 'checked' : '' }}> Ya</label>
                    <label><input type="radio" name="paparan_asap_rokok" value="0" {{ old('paparan_asap_rokok') == '0' ? 'checked' : '' }}> Tidak</label>
                </div>
            </div>
            <div class="form-group" id="paparan-frekuensi-group" style="{{ old('paparan_asap_rokok') == '1' ? '' : 'display:none' }}">
                <label class="form-label">Frekuensi Paparan</label>
                <div class="radio-group">
                    <label><input type="radio" name="paparan_asap_rokok_frekuensi" value="1" {{ old('paparan_asap_rokok_frekuensi') == '1' ? 'checked' : '' }}> Setiap Hari</label>
                    <label><input type="radio" name="paparan_asap_rokok_frekuensi" value="2" {{ old('paparan_asap_rokok_frekuensi') == '2' ? 'checked' : '' }}> Tidak Setiap Hari</label>
                </div>
            </div>

            @php
                $gayaHidupItems = [
                    ['name' => 'konsumsi_alkohol',   'label' => 'Konsumsi Alkohol'],
                    ['name' => 'konsumsi_gula',      'label' => 'Konsumsi Gula Berlebih'],
                    ['name' => 'konsumsi_garam',     'label' => 'Konsumsi Garam Berlebih'],
                    ['name' => 'konsumsi_minyak',    'label' => 'Konsumsi Minyak Berlebih'],
                    ['name' => 'konsumsi_sayur_buah','label' => 'Konsumsi Sayur/Buah Cukup'],
                    ['name' => 'aktivitas_fisik',    'label' => 'Aktivitas Fisik ≥150 mnt/minggu'],
                ];
            @endphp
            <div class="gaya-hidup-grid">
                @foreach($gayaHidupItems as $item)
                <div class="gaya-hidup-item">
                    <label class="form-label">{{ $item['label'] }}</label>
                    <div class="radio-group">
                        <label><input type="radio" name="{{ $item['name'] }}" value="1" {{ old($item['name']) == '1' ? 'checked' : '' }}> Ya</label>
                        <label><input type="radio" name="{{ $item['name'] }}" value="2" {{ old($item['name']) == '2' ? 'checked' : '' }}> Tidak</label>
                        <label><input type="radio" name="{{ $item['name'] }}" value="3" {{ old($item['name']) == '3' ? 'checked' : '' }}> Kadang</label>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Riwayat Penyakit --}}
            <div class="subsection-label">Riwayat Penyakit Keluarga</div>
            @php
                $penyakitKeluarga = ['dm' => 'Diabetes', 'hipertensi' => 'Hipertensi', 'jantung' => 'Jantung',
                    'stroke' => 'Stroke', 'asma' => 'Asma', 'kanker' => 'Kanker',
                    'kolesterol' => 'Kolesterol', 'ppok' => 'PPOK', 'talasemia' => 'Talasemia',
                    'lupus' => 'Lupus', 'g_penglihatan' => 'Gangguan Penglihatan'];
            @endphp
            <div class="checkbox-grid">
                @foreach($penyakitKeluarga as $val => $label)
                <label class="checkbox-item">
                    <input type="checkbox" name="riwayat_penyakit_keluarga[]" value="{{ $val }}"
                        {{ in_array($val, old('riwayat_penyakit_keluarga', [])) ? 'checked' : '' }}>
                    {{ $label }}
                </label>
                @endforeach
            </div>

            <div class="subsection-label">Riwayat Penyakit Sendiri</div>
            @php
                $penyakitSendiri = ['dm' => 'Diabetes', 'hipertensi' => 'Hipertensi', 'jantung' => 'Jantung',
                    'stroke' => 'Stroke', 'asma' => 'Asma', 'kanker' => 'Kanker',
                    'kolesterol' => 'Kolesterol', 'ppok' => 'PPOK', 'talasemia' => 'Talasemia',
                    'lupus' => 'Lupus', 'g_penglihatan' => 'Gangguan Penglihatan'];
            @endphp
            <div class="checkbox-grid">
                @foreach($penyakitSendiri as $val => $label)
                <label class="checkbox-item">
                    <input type="checkbox" name="riwayat_penyakit_sendiri[]" value="{{ $val }}"
                        {{ in_array($val, old('riwayat_penyakit_sendiri', [])) ? 'checked' : '' }}>
                    {{ $label }}
                </label>
                @endforeach
            </div>

            {{-- SRQ-20 --}}
            <div class="subsection-label">SRQ-20 <span class="subsection-hint">(centang jika YA)</span></div>
            @php
                $srqItems = [
                    1  => 'Sering sakit kepala?',
                    2  => 'Tidak nafsu makan?',
                    3  => 'Sulit tidur?',
                    4  => 'Mudah takut?',
                    5  => 'Merasa tegang/cemas/kuatir?',
                    6  => 'Tangan gemetar?',
                    7  => 'Pencernaan terganggu?',
                    8  => 'Sulit berpikir jernih?',
                    9  => 'Merasa tidak bahagia?',
                    10 => 'Menangis lebih sering?',
                    11 => 'Sulit menikmati kegiatan sehari-hari?',
                    12 => 'Sulit mengambil keputusan?',
                    13 => 'Pekerjaan sehari-hari terganggu?',
                    14 => 'Tidak mampu melakukan hal bermanfaat?',
                    15 => 'Kehilangan minat pada berbagai hal?',
                    16 => 'Merasa tidak berharga?',
                    17 => 'Pikiran untuk mengakhiri hidup?',
                    18 => 'Merasa lelah sepanjang waktu?',
                    19 => 'Rasa tidak enak di perut?',
                    20 => 'Mudah lelah?',
                ];
            @endphp
            <div class="srq-grid">
                @foreach($srqItems as $n => $pertanyaan)
                <label class="srq-item">
                    <input type="checkbox" name="srq_{{ $n }}" value="1"
                        {{ old("srq_{$n}") ? 'checked' : '' }}>
                    <span class="srq-num">{{ $n }}.</span>
                    <span>{{ $pertanyaan }}</span>
                </label>
                @endforeach
            </div>
            <div class="srq-total-preview">
                Skor SRQ: <strong id="srq-total">0</strong> / 20
                <span class="srq-hint">(≥6 = indikasi gangguan jiwa)</span>
            </div>

            {{-- Skrining Penglihatan --}}
            <div class="subsection-label">Skrining Penglihatan</div>
            @php
                $kondisiPenglihatan = ['katarak' => 'Katarak', 'pteregium' => 'Pteregium',
                    'kelainan_refraksi' => 'Kelainan Refraksi', 'ulkus' => 'Ulkus',
                    'conjungtivitis' => 'Conjungtivitis', 'glaukoma' => 'Glaukoma',
                    'retinopati' => 'Retinopati', 'normal' => 'Normal'];
            @endphp
            <div class="checkbox-grid">
                @foreach($kondisiPenglihatan as $val => $label)
                <label class="checkbox-item">
                    <input type="checkbox" name="skrining_penglihatan[]" value="{{ $val }}"
                        {{ in_array($val, old('skrining_penglihatan', [])) ? 'checked' : '' }}>
                    {{ $label }}
                </label>
                @endforeach
            </div>

            {{-- Skrining Pendengaran --}}
            <div class="subsection-label">Skrining Pendengaran</div>
            @php
                $kondisiPendengaran = ['serumen_prop' => 'Serumen Prop', 'omp' => 'OMP',
                    'omk' => 'OMK', 'tajam_pendengaran' => 'Tajam Pendengaran',
                    'presbikusis' => 'Presbikusis', 'congek' => 'Congek', 'normal' => 'Normal'];
            @endphp
            <div class="checkbox-grid">
                @foreach($kondisiPendengaran as $val => $label)
                <label class="checkbox-item">
                    <input type="checkbox" name="skrining_pendengaran[]" value="{{ $val }}"
                        {{ in_array($val, old('skrining_pendengaran', [])) ? 'checked' : '' }}>
                    {{ $label }}
                </label>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ══════════════════════════════════════════
             SKRINING PPOK
        ══════════════════════════════════════════ --}}
        @if($jadwal && in_array(\App\Models\DetailSkrining::SKRINING_PPOK, $aktifSkrining))
        <div class="form-section" id="section-ppok">
            <div class="section-header ppok">
                <i class="fa-solid fa-lungs"></i>
                <span>Skrining PPOK</span>
            </div>

            {{-- Profil --}}
            <div class="subsection-label">Profil</div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Pekerjaan</label>
                    <select name="pekerjaan" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="1" {{ old('pekerjaan') == '1' ? 'selected' : '' }}>TNI/POLRI</option>
                        <option value="2" {{ old('pekerjaan') == '2' ? 'selected' : '' }}>PNS</option>
                        <option value="3" {{ old('pekerjaan') == '3' ? 'selected' : '' }}>Karyawan Swasta</option>
                        <option value="4" {{ old('pekerjaan') == '4' ? 'selected' : '' }}>Buruh</option>
                        <option value="5" {{ old('pekerjaan') == '5' ? 'selected' : '' }}>Petani/Nelayan</option>
                        <option value="6" {{ old('pekerjaan') == '6' ? 'selected' : '' }}>Tidak Bekerja/IRT</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status Vaksinasi COVID-19</label>
                    <select name="status_vaksinasi_covid" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="1" {{ old('status_vaksinasi_covid') == '1' ? 'selected' : '' }}>Vaksinasi 1</option>
                        <option value="2" {{ old('status_vaksinasi_covid') == '2' ? 'selected' : '' }}>Vaksinasi 2</option>
                        <option value="3" {{ old('status_vaksinasi_covid') == '3' ? 'selected' : '' }}>Booster 1</option>
                    </select>
                </div>
            </div>

            {{-- Gaya Hidup PPOK --}}
            <div class="subsection-label">Gaya Hidup</div>
            <div class="gaya-hidup-grid">
                <div class="gaya-hidup-item">
                    <label class="form-label">Kurang Aktivitas Fisik</label>
                    <div class="radio-group">
                        <label><input type="radio" name="kurang_aktivitas_fisik" value="1" {{ old('kurang_aktivitas_fisik') == '1' ? 'checked' : '' }}> Ya</label>
                        <label><input type="radio" name="kurang_aktivitas_fisik" value="0" {{ old('kurang_aktivitas_fisik') == '0' ? 'checked' : '' }}> Tidak</label>
                    </div>
                </div>
                <div class="gaya-hidup-item">
                    <label class="form-label">Kurang Sayur/Buah</label>
                    <div class="radio-group">
                        <label><input type="radio" name="kurang_sayur_buah" value="1" {{ old('kurang_sayur_buah') == '1' ? 'checked' : '' }}> Ya</label>
                        <label><input type="radio" name="kurang_sayur_buah" value="0" {{ old('kurang_sayur_buah') == '0' ? 'checked' : '' }}> Tidak</label>
                    </div>
                </div>
                <div class="gaya-hidup-item">
                    <label class="form-label">Merokok</label>
                    <div class="radio-group">
                        <label><input type="radio" name="merokok" value="1" {{ old('merokok') == '1' ? 'checked' : '' }}> Ya</label>
                        <label><input type="radio" name="merokok" value="0" {{ old('merokok') == '0' ? 'checked' : '' }}> Tidak</label>
                    </div>
                </div>
                <div class="gaya-hidup-item">
                    <label class="form-label">Jenis Rokok</label>
                    <select name="jenis_rokok" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="1" {{ old('jenis_rokok') == '1' ? 'selected' : '' }}>Rokok Konvensional</option>
                        <option value="2" {{ old('jenis_rokok') == '2' ? 'selected' : '' }}>Rokok Elektrik</option>
                        <option value="3" {{ old('jenis_rokok') == '3' ? 'selected' : '' }}>Keduanya</option>
                        <option value="4" {{ old('jenis_rokok') == '4' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div class="gaya-hidup-item">
                    <label class="form-label">Konsumsi Alkohol</label>
                    <div class="radio-group">
                        <label><input type="radio" name="konsumsi_alkohol" value="1" {{ old('konsumsi_alkohol') == '1' ? 'checked' : '' }}> Ya</label>
                        <label><input type="radio" name="konsumsi_alkohol" value="0" {{ old('konsumsi_alkohol') == '0' ? 'checked' : '' }}> Tidak</label>
                    </div>
                </div>
            </div>

            {{-- Riwayat Penyakit PPOK --}}
            <div class="subsection-label">Riwayat Penyakit Keluarga</div>
            @php
                $penyakitPpok = ['diabetes' => 'Diabetes', 'hipertensi' => 'Hipertensi',
                    'jantung' => 'Jantung', 'stroke' => 'Stroke', 'kanker' => 'Kanker',
                    'thalasemia' => 'Thalasemia'];
            @endphp
            <div class="checkbox-grid">
                @foreach($penyakitPpok as $val => $label)
                <label class="checkbox-item">
                    <input type="checkbox" name="riwayat_penyakit_keluarga[]" value="{{ $val }}"
                        {{ in_array($val, old('riwayat_penyakit_keluarga', [])) ? 'checked' : '' }}>
                    {{ $label }}
                </label>
                @endforeach
            </div>

            <div class="subsection-label">Riwayat Penyakit Sendiri</div>
            @php
                $penyakitSendiriPpok = ['diabetes' => 'Diabetes', 'hipertensi' => 'Hipertensi',
                    'jantung' => 'Jantung', 'stroke' => 'Stroke', 'kanker' => 'Kanker',
                    'asma' => 'Asma', 'kolesterol_tinggi' => 'Kolesterol Tinggi',
                    'ppok' => 'PPOK', 'thalasemia' => 'Thalasemia', 'lupus' => 'Lupus',
                    'gangguan_penglihatan' => 'Gangguan Penglihatan',
                    'gangguan_pendengaran' => 'Gangguan Pendengaran', 'disabilitas' => 'Disabilitas'];
            @endphp
            <div class="checkbox-grid">
                @foreach($penyakitSendiriPpok as $val => $label)
                <label class="checkbox-item">
                    <input type="checkbox" name="riwayat_penyakit_sendiri[]" value="{{ $val }}"
                        {{ in_array($val, old('riwayat_penyakit_sendiri', [])) ? 'checked' : '' }}>
                    {{ $label }}
                </label>
                @endforeach
            </div>

            {{-- Pemeriksaan Tambahan --}}
            <div class="subsection-label">Pemeriksaan Tambahan</div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Rapid Antigen COVID</label>
                    <div class="radio-group">
                        <label><input type="radio" name="rapid_antigen" value="1" {{ old('rapid_antigen') == '1' ? 'checked' : '' }}> Positif</label>
                        <label><input type="radio" name="rapid_antigen" value="0" {{ old('rapid_antigen') == '0' ? 'checked' : '' }}> Negatif</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Kadar CO Pernapasan (ppm)</label>
                    <input type="number" name="kadar_co_ppm" class="form-control"
                        placeholder="0" min="0" value="{{ old('kadar_co_ppm') }}">
                </div>
            </div>

            {{-- Kuesioner PUMA --}}
            <div class="subsection-label">Kuesioner PUMA</div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Jenis Kelamin</label>
                    <div class="radio-group">
                        <label><input type="radio" name="puma_jenis_kelamin" value="0" {{ old('puma_jenis_kelamin') === '0' ? 'checked' : '' }}> Perempuan (skor 0)</label>
                        <label><input type="radio" name="puma_jenis_kelamin" value="1" {{ old('puma_jenis_kelamin') == '1' ? 'checked' : '' }}> Laki-laki (skor 1)</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori Usia</label>
                    <select name="puma_kategori_usia" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="0" {{ old('puma_kategori_usia') === '0' ? 'selected' : '' }}>40–49 tahun (skor 0)</option>
                        <option value="1" {{ old('puma_kategori_usia') == '1' ? 'selected' : '' }}>50–59 tahun (skor 1)</option>
                        <option value="2" {{ old('puma_kategori_usia') == '2' ? 'selected' : '' }}>≥ 60 tahun (skor 2)</option>
                    </select>
                </div>
            </div>

            @php
                $pumaItems = [
                    ['name' => 'puma_napas_pendek',    'label' => 'Pernah merasa napas pendek saat jalan cepat/menanjak?'],
                    ['name' => 'puma_sulit_dahak',     'label' => 'Biasanya sulit mengeluarkan dahak saat tidak flu?'],
                    ['name' => 'puma_batuk_tanpa_flu', 'label' => 'Biasanya batuk saat tidak menderita flu?'],
                    ['name' => 'puma_pernah_spirometri','label' => 'Pernah diminta dokter/nakes periksa fungsi paru?'],
                ];
            @endphp
            @foreach($pumaItems as $p)
            <div class="form-group">
                <label class="form-label">{{ $p['label'] }}</label>
                <div class="radio-group">
                    <label><input type="radio" name="{{ $p['name'] }}" value="1" {{ old($p['name']) == '1' ? 'checked' : '' }}> Ya (skor 1)</label>
                    <label><input type="radio" name="{{ $p['name'] }}" value="0" {{ old($p['name']) === '0' ? 'checked' : '' }}> Tidak (skor 0)</label>
                </div>
            </div>
            @endforeach

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Rokok per hari</label>
                    <input type="number" name="puma_rokok_per_hari" class="form-control"
                        placeholder="0" min="0" value="{{ old('puma_rokok_per_hari', 0) }}" id="puma-rokok-per-hari">
                </div>
                <div class="form-group">
                    <label class="form-label">Lama merokok (tahun)</label>
                    <input type="number" name="puma_lama_merokok_tahun" class="form-control"
                        placeholder="0" min="0" value="{{ old('puma_lama_merokok_tahun', 0) }}" id="puma-lama-merokok">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Pack Years (otomatis)</label>
                <input type="text" id="preview-pack-years" class="form-control" readonly placeholder="—" tabindex="-1">
            </div>

            {{-- Spirometri Pre --}}
            <div class="subsection-label">Hasil Spirometri Pre-Bronkodilator <span class="subsection-hint">(jika dilakukan)</span></div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">VEP1 Pre (liter)</label>
                    <input type="number" name="vep1_pre" step="0.01" class="form-control"
                        placeholder="2.50" value="{{ old('vep1_pre') }}" id="vep1-pre">
                </div>
                <div class="form-group">
                    <label class="form-label">KVP Pre (liter)</label>
                    <input type="number" name="kvp_pre" step="0.01" class="form-control"
                        placeholder="3.00" value="{{ old('kvp_pre') }}" id="kvp-pre">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Rasio VEP1/KVP Pre (%) (otomatis)</label>
                <input type="text" id="preview-rasio-pre" class="form-control" readonly placeholder="—" tabindex="-1">
            </div>

            {{-- Spirometri Post --}}
            <div class="subsection-label">Hasil Spirometri Post-Bronkodilator <span class="subsection-hint">(jika diberikan bronkodilator)</span></div>
            <div class="form-group">
                <label class="form-label">Pemberian Bronkodilator</label>
                <div class="radio-group">
                    <label><input type="radio" name="pemberian_bronkodilator" value="1" {{ old('pemberian_bronkodilator') == '1' ? 'checked' : '' }}> Ya</label>
                    <label><input type="radio" name="pemberian_bronkodilator" value="0" {{ old('pemberian_bronkodilator') === '0' ? 'checked' : '' }}> Tidak</label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">VEP1 Post (liter)</label>
                    <input type="number" name="vep1_post" step="0.01" class="form-control"
                        placeholder="2.50" value="{{ old('vep1_post') }}" id="vep1-post">
                </div>
                <div class="form-group">
                    <label class="form-label">KVP Post (liter)</label>
                    <input type="number" name="kvp_post" step="0.01" class="form-control"
                        placeholder="3.00" value="{{ old('kvp_post') }}" id="kvp-post">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Rasio VEP1/KVP Post (%) (otomatis)</label>
                <input type="text" id="preview-rasio-post" class="form-control" readonly placeholder="—" tabindex="-1">
            </div>

            <div class="form-group">
                <label class="form-label">Catatan Hasil Spirometri</label>
                <textarea name="hasil_spirometri" class="form-control" rows="2"
                    placeholder="Kesimpulan hasil pemeriksaan...">{{ old('hasil_spirometri') }}</textarea>
            </div>
        </div>
        @endif

        {{-- ══════════════════════════════════════════
             SUBMIT
        ══════════════════════════════════════════ --}}
        @if($jadwal)
        <div class="form-footer">
            <a href="/dashboard" class="btn-ghost">Batal</a>
            <button type="submit" class="btn-primary" id="btn-submit-skrining">
                <i class="fa-solid fa-floppy-disk"></i>
                Simpan Semua Skrining
            </button>
        </div>
        @else
        <div class="form-footer">
            <div class="locked-notice">
                <i class="fa-solid fa-lock"></i>
                Input tidak tersedia — tidak ada jadwal posyandu aktif hari ini.
            </div>
        </div>
        @endif

    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ── IMT Preview ──────────────────────────────────────
    const bbInput = document.querySelector('[name="berat_badan"]');
    const tbInput = document.querySelector('[name="tinggi_badan"]');
    const imtEl   = document.getElementById('preview-imt');

    function hitungIMT() {
        const bb = parseFloat(bbInput?.value);
        const tb = parseFloat(tbInput?.value);
        imtEl.value = (bb > 0 && tb > 0)
            ? (bb / Math.pow(tb / 100, 2)).toFixed(2)
            : '';
    }
    bbInput?.addEventListener('input', hitungIMT);
    tbInput?.addEventListener('input', hitungIMT);

    // ── SRQ Counter ──────────────────────────────────────
    const srqTotal = document.getElementById('srq-total');
    document.querySelectorAll('[name^="srq_"]').forEach(cb => {
        cb.addEventListener('change', () => {
            const total = document.querySelectorAll('[name^="srq_"]:checked').length;
            if (srqTotal) {
                srqTotal.textContent = total;
                srqTotal.style.color = total >= 6 ? '#dc2626' : 'inherit';
            }
        });
    });

    // ── Toggle Resep ─────────────────────────────────────
    const chkResep     = document.getElementById('chk-ada-resep');
    const resepSection = document.getElementById('resep-section');
    chkResep?.addEventListener('change', () => {
        resepSection.classList.toggle('d-none', !chkResep.checked);
        
        // Toggle required attribute pada field resep
        document.querySelectorAll('[name^="resep["]').forEach(input => {
            input.required = chkResep.checked;
        });
    });

    // ── Tambah Baris Resep ───────────────────────────────
    let resepIdx = document.querySelectorAll('.resep-row').length;
    document.getElementById('btn-add-resep')?.addEventListener('click', () => {
        const list = document.getElementById('resep-list');
        const row  = document.createElement('div');
        row.className = 'resep-row';
        row.innerHTML = resepRowHTML(resepIdx);
        list.appendChild(row);
        resepIdx++;
        bindRemoveResep(row);
    });

    document.querySelectorAll('.resep-row').forEach(bindRemoveResep);

    function bindRemoveResep(row) {
        row.querySelector('.btn-remove-resep')?.addEventListener('click', () => row.remove());
    }

    function resepRowHTML(i) {
        const options = @json($obat->map(fn($o) => ['id' => $o->id_obat, 'nama' => $o->nama_obat]));
        const opts = options.map(o => `<option value="${o.id}">${o.nama}</option>`).join('');
        return `
            <div class="resep-row-inner">
                <select name="resep[${i}][id_obat]" class="form-control" required>
                    <option value="">-- Pilih Obat --</option>
                    ${opts}
                </select>
                <input type="text" name="resep[${i}][dosis]" class="form-control" placeholder="Dosis (cth: 500mg)" required>
                <input type="text" name="resep[${i}][frekuensi]" class="form-control" placeholder="Frekuensi (cth: 3x1)" required>
                <input type="text" name="resep[${i}][keterangan]" class="form-control" placeholder="Keterangan (opsional)">
                <button type="button" class="btn-remove-resep"><i class="fa-solid fa-xmark"></i></button>
            </div>`;
    }

    // ── Merokok toggle kategori ───────────────────────────
    document.querySelectorAll('[name="merokok"]').forEach(r => {
        r.addEventListener('change', () => {
            const group = document.getElementById('merokok-kategori-group');
            if (group) group.style.display = r.value === '1' ? '' : 'none';
        });
    });

    // ── Paparan asap rokok toggle frekuensi ──────────────
    document.querySelectorAll('[name="paparan_asap_rokok"]').forEach(r => {
        r.addEventListener('change', () => {
            const group = document.getElementById('paparan-frekuensi-group');
            if (group) group.style.display = r.value === '1' ? '' : 'none';
        });
    });

    // ── PUMA Pack Years Preview ───────────────────────────
    const rphInput  = document.getElementById('puma-rokok-per-hari');
    const lmtInput  = document.getElementById('puma-lama-merokok');
    const pyEl      = document.getElementById('preview-pack-years');

    function hitungPackYears() {
        const rph = parseFloat(rphInput?.value || 0);
        const lmt = parseFloat(lmtInput?.value || 0);
        if (pyEl) {
            pyEl.value = (rph > 0 && lmt > 0)
                ? ((lmt * rph) / 20).toFixed(2) + ' pack-years'
                : '—';
        }
    }
    rphInput?.addEventListener('input', hitungPackYears);
    lmtInput?.addEventListener('input', hitungPackYears);

    // ── Spirometri Rasio Pre ──────────────────────────────
    const vep1Pre = document.getElementById('vep1-pre');
    const kvpPre  = document.getElementById('kvp-pre');
    const rasioPreEl = document.getElementById('preview-rasio-pre');

    function hitungRasioPre() {
        const v = parseFloat(vep1Pre?.value);
        const k = parseFloat(kvpPre?.value);
        if (rasioPreEl) {
            rasioPreEl.value = (v > 0 && k > 0)
                ? ((v / k) * 100).toFixed(2) + ' %'
                : '—';
        }
    }
    vep1Pre?.addEventListener('input', hitungRasioPre);
    kvpPre?.addEventListener('input', hitungRasioPre);

    // ── Spirometri Rasio Post ─────────────────────────────
    const vep1Post = document.getElementById('vep1-post');
    const kvpPost  = document.getElementById('kvp-post');
    const rasioPostEl = document.getElementById('preview-rasio-post');

    function hitungRasioPost() {
        const v = parseFloat(vep1Post?.value);
        const k = parseFloat(kvpPost?.value);
        if (rasioPostEl) {
            rasioPostEl.value = (v > 0 && k > 0)
                ? ((v / k) * 100).toFixed(2) + ' %'
                : '—';
        }
    }
    vep1Post?.addEventListener('input', hitungRasioPost);
    kvpPost?.addEventListener('input', hitungRasioPost);

    // ── Prevent Double Submit ────────────────────────────
    document.getElementById('formSkrining')?.addEventListener('submit', function () {
        const btn = document.getElementById('btn-submit-skrining');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
        }
    });

});
</script>
@endpush