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




        {{-- ══════════════════════════════════════════
        WIZARD STEPS INDICATOR
        Step 1 selalu ada: Data Lansia + Kunjungan Rutin (digabung)
        Step 2 (opsional): Skrining Utama
        Step 3 (opsional): Skrining PPOK
        Step Terakhir: Konfirmasi
        ══════════════════════════════════════════ --}}
        @if($jadwal)
            @php
                // Step 1 selalu ada (lansia + kunjungan digabung)
                $steps = [['id' => 'step-lansia', 'label' => 'Data & Kunjungan', 'icon' => 'fa-person-cane']];

                if (in_array(\App\Models\DetailSkrining::SKRINING_UTAMA, $aktifSkrining))
                    $steps[] = ['id' => 'step-utama', 'label' => 'Skrining Utama', 'icon' => 'fa-clipboard-list'];

                if (in_array(\App\Models\DetailSkrining::SKRINING_PPOK, $aktifSkrining))
                    $steps[] = ['id' => 'step-ppok', 'label' => 'Skrining PPOK', 'icon' => 'fa-lungs'];

                $steps[] = ['id' => 'step-review', 'label' => 'Konfirmasi', 'icon' => 'fa-check-double'];

                // Hitung next/prev untuk setiap step berdasarkan array $steps
                $stepIds = array_column($steps, 'id');
            @endphp

            <div class="wizard-track">
                @foreach($steps as $i => $step)
                    <div class="wizard-step {{ $i === 0 ? 'active' : '' }}" id="wiz-{{ $step['id'] }}">
                        <div class="wizard-step-circle">
                            <i class="fa-solid {{ $step['icon'] }}"></i>
                            <span class="wizard-step-num">{{ $i + 1 }}</span>
                        </div>
                        <span class="wizard-step-label">{{ $step['label'] }}</span>
                    </div>
                    @if(!$loop->last)
                        <div class="wizard-connector" id="conn-{{ $i }}"></div>
                    @endif
                @endforeach
            </div>
        @endif

        {{-- ══════════════════════════════════════════
        FORM UTAMA
        ══════════════════════════════════════════ --}}
        <form action="{{ route('skrining.store') }}" method="POST" id="formSkrining"
            class="{{ !$jadwal ? 'form-locked' : '' }}">
            @csrf

            {{-- ══════════════════════════════════════
            STEP 1 — DATA LANSIA + KUNJUNGAN RUTIN (selalu ada)
            Kunjungan rutin selalu ada di setiap skrining,
            jadi digabung dengan pilih lansia, keluhan, dan saran.
            ══════════════════════════════════════ --}}
            <div class="wizard-panel active" id="step-lansia">

                {{-- ── Bagian: Pilih Lansia ── --}}
                <div class="form-section">
                    <div class="section-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <i class="fa-solid fa-person-cane"></i>
                            <span>Data Lansia</span>
                        </div>
                        @if($sudahSkrining->isNotEmpty())
                            <button type="button" onclick="openModalSudahSkrining()"
                                style="font-size: 12px; background: #eef2ff; color: #4f46e5; border: 1px solid #c7d2fe; padding: 4px 10px; border-radius: 4px; cursor: pointer; display: flex; align-items: center; gap: 4px;">
                                <i class="fa-solid fa-list-check"></i> Sudah Skrining ({{ $sudahSkrining->count() }})
                            </button>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pilih Lansia <span class="required">*</span></label>
                        @if($lansia->isEmpty())
                            <div class="empty-lansia-notice">
                                <i class="fa-solid fa-circle-check" style="color:var(--green-500)"></i>
                                Semua lansia terdaftar sudah menyelesaikan skrining hari ini.
                            </div>

                    <div class="form-group">
                        <label class="form-label">Diagnosis</label>
                        <textarea name="diagnosa_masuk" class="form-control" rows="2"
                            placeholder="Contoh: hipertensi, DM, atau diagnosis awal lainnya">{{ old('diagnosis') }}</textarea>
                    </div>
                        @else
                            <select name="id_lansia" id="select-lansia" class="form-control" {{ !$jadwal ? 'disabled' : '' }}
                                required>
                                <option value="">-- Pilih Lansia --</option>
                                @foreach($lansia as $l)
                                    <option value="{{ $l->id_lansia }}"
                                            data-pekerjaan="{{ $l->pekerjaan ?? '' }}"
                                            data-tanggal-lahir="{{ $l->tanggal_lahir ?? '' }}"
                                            data-umur="{{ $l->tanggal_lahir ? \Carbon\Carbon::parse($l->tanggal_lahir)->age : ($l->umur ?? '') }}"
                                            data-nik="{{ $l->nik ?? '' }}"
                                            {{ old('id_lansia') == $l->id_lansia ? 'selected' : '' }}>
                                            {{ $l->nama_lansia }} - {{ $l->nik ?? '-' }}
                                        </option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label">Keluhan</label>
                        <textarea name="keluhan" class="form-control" rows="2"
                            placeholder="Keluhan yang disampaikan lansia (opsional)" {{ !$jadwal ? 'disabled' : '' }}>{{ old('keluhan') }}</textarea>
                    </div>

                    {{-- SARAN — muncul setelah lansia dipilih --}}
                    <div class="resep-toggle" id="saran-section" style="display:none;">
                        <div id="saran-sebelumnya-wrapper" style="display:none; margin-bottom:12px;">
                            <label class="form-label" style="margin-bottom:6px;">
                                <i class="fa-solid fa-clock-rotate-left"></i> Saran Sebelumnya
                            </label>
                            <div id="saran-sebelumnya-list"></div>
                        </div>

                        <div class="resep-header">
                            <span>Saran untuk Lansia</span>
                            <button type="button" class="btn-add-kecil" id="btn-add-saran" {{ !$jadwal ? 'disabled' : '' }}>
                                <i class="fa-solid fa-plus"></i> Tambah Saran
                            </button>
                        </div>
                        <div id="saran-baru-list"></div>
                    </div>
                </div>

                {{-- ── Bagian: Kunjungan Rutin (selalu ada) ── --}}
                <div class="form-section">
                    <div class="section-header kunjungan">
                        <i class="fa-solid fa-stethoscope"></i>
                        <span>Kunjungan Rutin</span>
                        <span class="badge-always">Selalu Ada</span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Berat Badan (kg) <span class="required">*</span></label>
                            <input type="number" name="berat_badan" step="0.1" class="form-control" placeholder="60.5"
                                value="{{ old('berat_badan') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tinggi Badan (cm) <span class="required">*</span></label>
                            <input type="number" name="tinggi_badan" step="0.1" class="form-control" placeholder="165.0"
                                value="{{ old('tinggi_badan') }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Lingkar Perut (cm) <span class="required">*</span></label>
                            <input type="number" name="lingkar_perut" step="0.1" class="form-control" placeholder="80.0"
                                value="{{ old('lingkar_perut') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">IMT (otomatis)</label>
                            <input type="text" id="preview-imt" class="form-control" readonly placeholder="—" tabindex="-1">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">TD Sistolik (mmHg) <span class="required">*</span></label>
                            <input type="number" name="td_sistolik" class="form-control" placeholder="120"
                                value="{{ old('td_sistolik') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">TD Diastolik (mmHg) <span class="required">*</span></label>
                            <input type="number" name="td_diastolik" class="form-control" placeholder="80"
                                value="{{ old('td_diastolik') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Diagnosis</label>
                        <textarea name="diagnosis" class="form-control" rows="2"
                            placeholder="Contoh: hipertensi, DM, atau diagnosis awal lainnya">{{ old('diagnosis') }}</textarea>
                    </div>

                    {{-- RESEP OBAT --}}
                    <div class="resep-toggle">
                        <label class="toggle-label">
                            <input type="checkbox" name="ada_resep" id="chk-ada-resep" value="1" {{ old('ada_resep') ? 'checked' : '' }}>
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

                {{-- Nav --}}
                <div class="wizard-nav">
                    <a href="/dashboard" class="btn-ghost">Batal</a>
                    @php
                        // Next dari step-lansia: utama → ppok → review (ambil step ke-2 dari $steps)
                        $nextLansia = isset($stepIds[1]) ? $stepIds[1] : 'step-review';
                    @endphp
                    <button type="button" class="btn-primary btn-next" data-next="{{ $nextLansia }}" {{ $lansia->isEmpty() ? 'disabled' : '' }}>
                        Lanjut <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- ══════════════════════════════════════
            STEP 2 — SKRINING UTAMA (opsional)
            ══════════════════════════════════════ --}}
            @if($jadwal && in_array(\App\Models\DetailSkrining::SKRINING_UTAMA, $aktifSkrining))
                @php
                    $idxUtama = array_search('step-utama', $stepIds);
                    $prevUtama = $stepIds[$idxUtama - 1] ?? 'step-lansia';
                    $nextUtama = $stepIds[$idxUtama + 1] ?? 'step-review';
                @endphp
                <div class="wizard-panel" id="step-utama">
                    <div class="form-section">
                        <div class="section-header utama">
                            <i class="fa-solid fa-clipboard-list"></i>
                            <span>Skrining Utama</span>
                        </div>

                        <div class="subsection-label">Pengukuran Lab</div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Gula Darah (mg/dL) <span class="required">*</span></label>
                                <input type="number" name="gula_darah" class="form-control" placeholder="100"
                                    value="{{ old('gula_darah') }}" required>
                                <small class="form-hint">Baik: &lt;145 · Sedang: 145–199 · Tidak Baik: ≥200</small>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kolesterol (mg/dL) <span class="required">*</span></label>
                                <input type="number" name="kolesterol" class="form-control" placeholder="180"
                                    value="{{ old('kolesterol') }}" required>
                                <small class="form-hint">Baik: &lt;150 · Sedang: 150–189 · Tidak Baik: ≥190</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">IVA / Sadanis <span class="subsection-hint">(opsional,
                                    perempuan)</span></label>
                            <div class="radio-group">
                                <label><input type="radio" name="iva_sadanis" value="1" {{ old('iva_sadanis') == '1' ? 'checked' : '' }}> Positif / Dilakukan</label>
                                <label><input type="radio" name="iva_sadanis" value="0" {{ old('iva_sadanis') == '0' ? 'checked' : '' }}> Negatif / Tidak Dilakukan</label>
                            </div>
                        </div>

                        <div class="subsection-label">Gaya Hidup</div>
                        <div class="gaya-hidup-item" style="margin-bottom:14px;">
                            <label class="form-label">Merokok</label>
                            <div class="radio-group">
                                <label><input type="radio" name="merokok" value="1" {{ old('merokok') == '1' ? 'checked' : '' }}>
                                    Ya</label>
                                <label><input type="radio" name="merokok" value="0" {{ old('merokok') == '0' ? 'checked' : '' }}>
                                    Tidak</label>
                            </div>
                        </div>
                        <div class="form-group" id="merokok-kategori-group"
                            style="{{ old('merokok') == '1' ? '' : 'display:none' }}">
                            <label class="form-label">Kategori Merokok</label>
                            <div class="radio-group">
                                <label><input type="radio" name="merokok_kategori" value="1" {{ old('merokok_kategori') == '1' ? 'checked' : '' }}> 20–30 bungkus/tahun</label>
                                <label><input type="radio" name="merokok_kategori" value="2" {{ old('merokok_kategori') == '2' ? 'checked' : '' }}> &gt;30 bungkus/tahun</label>
                            </div>
                        </div>

                        <div class="gaya-hidup-item" style="margin-bottom:14px;">
                            <label class="form-label">Paparan Asap Rokok (anggota keluarga serumah merokok)</label>
                            <div class="radio-group">
                                <label><input type="radio" name="paparan_asap_rokok" value="1" {{ old('paparan_asap_rokok') == '1' ? 'checked' : '' }}> Ya</label>
                                <label><input type="radio" name="paparan_asap_rokok" value="0" {{ old('paparan_asap_rokok') == '0' ? 'checked' : '' }}> Tidak</label>
                            </div>
                        </div>
                        <div class="form-group" id="paparan-frekuensi-group"
                            style="{{ old('paparan_asap_rokok') == '1' ? '' : 'display:none' }}">
                            <label class="form-label">Frekuensi Paparan</label>
                            <div class="radio-group">
                                <label><input type="radio" name="paparan_asap_rokok_frekuensi" value="1" {{ old('paparan_asap_rokok_frekuensi') == '1' ? 'checked' : '' }}> Setiap Hari</label>
                                <label><input type="radio" name="paparan_asap_rokok_frekuensi" value="2" {{ old('paparan_asap_rokok_frekuensi') == '2' ? 'checked' : '' }}> Tidak Setiap Hari</label>
                            </div>
                        </div>

                        @php
                            $gayaHidupItems = [
                                ['name' => 'konsumsi_alkohol', 'label' => 'Konsumsi Alkohol'],
                                ['name' => 'konsumsi_gula', 'label' => 'Konsumsi Gula Berlebih'],
                                ['name' => 'konsumsi_garam', 'label' => 'Konsumsi Garam Berlebih'],
                                ['name' => 'konsumsi_minyak', 'label' => 'Konsumsi Minyak Berlebih'],
                                ['name' => 'konsumsi_sayur_buah', 'label' => 'Konsumsi Sayur/Buah Cukup'],
                                ['name' => 'aktivitas_fisik', 'label' => 'Aktivitas Fisik ≥150 mnt/minggu'],
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

                        <div class="subsection-label">Riwayat Penyakit Keluarga</div>
                        @php
                            $penyakitKeluarga = [
                                'diabetes' => 'Diabetes',
                                'hipertensi' => 'Hipertensi',
                                'jantung' => 'Jantung',
                                'stroke' => 'Stroke',
                                'asma' => 'Asma',
                                'kanker' => 'Kanker',
                                'kolesterol' => 'Kolesterol',
                                'ppok' => 'PPOK',
                                'talasemia' => 'Talasemia',
                                'lupus' => 'Lupus',
                                'gangguan_penglihatan' => 'Gangguan Penglihatan'
                            ];
                        @endphp
                        <div class="checkbox-grid">
                            @foreach($penyakitKeluarga as $val => $label)
                                <label class="checkbox-item">
                                    <input type="checkbox" name="riwayat_penyakit_keluarga[]" value="{{ $val }}" {{ in_array($val, old('riwayat_penyakit_keluarga', [])) ? 'checked' : '' }}>
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>

                        <div class="subsection-label">Riwayat Penyakit Sendiri</div>
                        @php
                            $penyakitSendiri = [
                                'diabetes' => 'Diabetes',
                                'hipertensi' => 'Hipertensi',
                                'jantung' => 'Jantung',
                                'stroke' => 'Stroke',
                                'asma' => 'Asma',
                                'kanker' => 'Kanker',
                                'kolesterol' => 'Kolesterol',
                                'ppok' => 'PPOK',
                                'talasemia' => 'Talasemia',
                                'lupus' => 'Lupus',
                                'gangguan_penglihatan' => 'Gangguan Penglihatan'
                            ];
                        @endphp
                        <div class="checkbox-grid">
                            @foreach($penyakitSendiri as $val => $label)
                                <label class="checkbox-item">
                                    <input type="checkbox" name="riwayat_penyakit_sendiri[]" value="{{ $val }}" {{ in_array($val, old('riwayat_penyakit_sendiri', [])) ? 'checked' : '' }}>
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>

                        <div class="subsection-label">SRQ-20 <span class="subsection-hint">(centang jika YA)</span></div>
                        @php
                            $srqItems = [
                                1 => 'Sering sakit kepala?',
                                2 => 'Tidak nafsu makan?',
                                3 => 'Sulit tidur?',
                                4 => 'Mudah takut?',
                                5 => 'Merasa tegang/cemas/kuatir?',
                                6 => 'Tangan gemetar?',
                                7 => 'Pencernaan terganggu?',
                                8 => 'Sulit berpikir jernih?',
                                9 => 'Merasa tidak bahagia?',
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
                                    <input type="checkbox" name="srq_{{ $n }}" value="1" {{ old("srq_{$n}") ? 'checked' : '' }}>
                                    <span class="srq-num">{{ $n }}.</span>
                                    <span>{{ $pertanyaan }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="srq-total-preview">
                            Skor SRQ: <strong id="srq-total">0</strong> / 20
                            <span class="srq-hint">(≥6 = indikasi gangguan jiwa)</span>
                        </div>

                        <div class="subsection-label">Skrining Penglihatan</div>
                        @php
                            $kondisiPenglihatan = [
                                'katarak' => 'Katarak',
                                'pteregium' => 'Pteregium',
                                'kelainan_refraksi' => 'Kelainan Refraksi',
                                'ulkus' => 'Ulkus',
                                'conjungtivitis' => 'Conjungtivitis',
                                'glaukoma' => 'Glaukoma',
                                'retinopati' => 'Retinopati',
                                'normal' => 'Normal'
                            ];
                        @endphp
                        <div class="checkbox-grid">
                            @foreach($kondisiPenglihatan as $val => $label)
                                <label class="checkbox-item">
                                    <input type="checkbox" name="skrining_penglihatan[]" value="{{ $val }}" {{ in_array($val, old('skrining_penglihatan', [])) ? 'checked' : '' }}>
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>

                        <div class="subsection-label">Skrining Pendengaran</div>
                        @php
                            $kondisiPendengaran = [
                                'serumen_prop' => 'Serumen Prop',
                                'omp' => 'OMP',
                                'omk' => 'OMK',
                                'tajam_pendengaran' => 'Tajam Pendengaran',
                                'presbikusis' => 'Presbikusis',
                                'congek' => 'Congek',
                                'normal' => 'Normal'
                            ];
                        @endphp
                        <div class="checkbox-grid">
                            @foreach($kondisiPendengaran as $val => $label)
                                <label class="checkbox-item">
                                    <input type="checkbox" name="skrining_pendengaran[]" value="{{ $val }}" {{ in_array($val, old('skrining_pendengaran', [])) ? 'checked' : '' }}>
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Nav --}}
                    <div class="wizard-nav">
                        <button type="button" class="btn-ghost btn-prev" data-prev="{{ $prevUtama }}">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </button>
                        <button type="button" class="btn-primary btn-next" data-next="{{ $nextUtama }}">
                            Lanjut <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            @endif

            {{-- ══════════════════════════════════════
            STEP 3 — SKRINING PPOK (opsional)
            ══════════════════════════════════════ --}}
            @if($jadwal && in_array(\App\Models\DetailSkrining::SKRINING_PPOK, $aktifSkrining))
                @php
                    $idxPpok = array_search('step-ppok', $stepIds);
                    $prevPpok = $stepIds[$idxPpok - 1] ?? 'step-lansia';
                    $nextPpok = $stepIds[$idxPpok + 1] ?? 'step-review';
                @endphp
                <div class="wizard-panel" id="step-ppok">
                    <div class="form-section">
                        <div class="section-header ppok">
                            <i class="fa-solid fa-lungs"></i>
                            <span>Skrining PPOK</span>
                        </div>

                        <div class="subsection-label">Profil</div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Pekerjaan Lansia</label>
                                <input type="text" id="ppok-pekerjaan-display" class="form-control" readonly
                                    placeholder="Pilih lansia terlebih dahulu" style="background:#e5e7eb;">
                                <input type="hidden" name="pekerjaan" id="ppok-pekerjaan-hidden" value="{{ old('pekerjaan') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status Vaksinasi COVID-19</label>
                                <select name="status_vaksinasi_covid" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="1" {{ old('status_vaksinasi_covid') == '1' ? 'selected' : '' }}>Vaksinasi 1
                                    </option>
                                    <option value="2" {{ old('status_vaksinasi_covid') == '2' ? 'selected' : '' }}>Vaksinasi 2
                                    </option>
                                    <option value="3" {{ old('status_vaksinasi_covid') == '3' ? 'selected' : '' }}>Booster 1
                                    </option>
                                </select>
                            </div>
                        </div>

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
                                    <label><input type="radio" name="merokok_ppok" value="1" {{ old('merokok_ppok') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="merokok_ppok" value="0" {{ old('merokok_ppok') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                            <div class="gaya-hidup-item">
                                <label class="form-label">Jenis Rokok</label>
                                <select name="jenis_rokok" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="1" {{ old('jenis_rokok') == '1' ? 'selected' : '' }}>Rokok Konvensional
                                    </option>
                                    <option value="2" {{ old('jenis_rokok') == '2' ? 'selected' : '' }}>Rokok Elektrik</option>
                                    <option value="3" {{ old('jenis_rokok') == '3' ? 'selected' : '' }}>Keduanya</option>
                                    <option value="4" {{ old('jenis_rokok') == '4' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                            <div class="gaya-hidup-item">
                                <label class="form-label">Konsumsi Alkohol</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="konsumsi_alkohol_ppok" value="1" {{ old('konsumsi_alkohol_ppok') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="konsumsi_alkohol_ppok" value="0" {{ old('konsumsi_alkohol_ppok') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <div class="subsection-label">Riwayat Penyakit Keluarga</div>
                        @php
                            $penyakitPpok = [
                                'diabetes' => 'Diabetes',
                                'hipertensi' => 'Hipertensi',
                                'jantung' => 'Jantung',
                                'stroke' => 'Stroke',
                                'kanker' => 'Kanker',
                                'thalasemia' => 'Thalasemia'
                            ];
                        @endphp
                        <div class="checkbox-grid">
                            @foreach($penyakitPpok as $val => $label)
                                <label class="checkbox-item">
                                    <input type="checkbox" name="riwayat_penyakit_keluarga_ppok[]" value="{{ $val }}" {{ in_array($val, old('riwayat_penyakit_keluarga_ppok', [])) ? 'checked' : '' }}>
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>

                        <div class="subsection-label">Riwayat Penyakit Sendiri</div>
                        @php
                            $penyakitSendiriPpok = [
                                'diabetes' => 'Diabetes',
                                'hipertensi' => 'Hipertensi',
                                'jantung' => 'Jantung',
                                'stroke' => 'Stroke',
                                'kanker' => 'Kanker',
                                'asma' => 'Asma',
                                'kolesterol_tinggi' => 'Kolesterol Tinggi',
                                'ppok' => 'PPOK',
                                'thalasemia' => 'Thalasemia',
                                'lupus' => 'Lupus',
                                'gangguan_penglihatan' => 'Gangguan Penglihatan',
                                'gangguan_pendengaran' => 'Gangguan Pendengaran',
                                'disabilitas' => 'Disabilitas'
                            ];
                        @endphp
                        <div class="checkbox-grid">
                            @foreach($penyakitSendiriPpok as $val => $label)
                                <label class="checkbox-item">
                                    <input type="checkbox" name="riwayat_penyakit_sendiri_ppok[]" value="{{ $val }}" {{ in_array($val, old('riwayat_penyakit_sendiri_ppok', [])) ? 'checked' : '' }}>
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>

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
                                <input type="number" name="kadar_co_ppm" class="form-control" placeholder="0" min="0"
                                    value="{{ old('kadar_co_ppm') }}">
                            </div>
                        </div>

                        <div class="subsection-label">Kuesioner PUMA</div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Jenis Kelamin</label>
                                    <div class="radio-group">
                                        <label><input type="radio" name="puma_jenis_kelamin" value="0" {{ old('puma_jenis_kelamin') === '0' ? 'checked' : '' }} required> Perempuan (skor 0)</label>
                                        <label><input type="radio" name="puma_jenis_kelamin" value="1" {{ old('puma_jenis_kelamin') == '1' ? 'checked' : '' }}> Laki-laki (skor 1)</label>
                                    </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kategori Usia</label>
                                <input type="text" id="puma-kategori-display" class="form-control" readonly placeholder="Pilih lansia terlebih dahulu" style="background:#e5e7eb;">
                                <input type="hidden" name="puma_kategori_usia" id="puma-kategori-hidden" value="{{ old('puma_kategori_usia') }}">
                            </div>
                        </div>

                        @php
                            $pumaItems = [
                                ['name' => 'puma_napas_pendek', 'label' => 'Pernah merasa napas pendek saat jalan cepat/menanjak?'],
                                ['name' => 'puma_sulit_dahak', 'label' => 'Biasanya sulit mengeluarkan dahak saat tidak flu?'],
                                ['name' => 'puma_batuk_tanpa_flu', 'label' => 'Biasanya batuk saat tidak menderita flu?'],
                                ['name' => 'puma_pernah_spirometri', 'label' => 'Pernah diminta dokter/nakes periksa fungsi paru?'],
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
                                <input type="number" name="puma_rokok_per_hari" class="form-control" placeholder="0" min="0"
                                    value="{{ old('puma_rokok_per_hari', 0) }}" id="puma-rokok-per-hari" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Lama merokok (tahun)</label>
                                <input type="number" name="puma_lama_merokok_tahun" class="form-control" placeholder="0" min="0"
                                    value="{{ old('puma_lama_merokok_tahun', 0) }}" id="puma-lama-merokok" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pack Years (otomatis)</label>
                            <input type="text" id="preview-pack-years" class="form-control" readonly placeholder="—"
                                tabindex="-1">
                        </div>

                        <div class="subsection-label">Hasil Spirometri Pre-Bronkodilator <span class="subsection-hint">(jika
                                dilakukan)</span></div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">VEP1 Pre (liter)</label>
                                <input type="number" name="vep1_pre" step="0.01" class="form-control" placeholder="2.50"
                                    value="{{ old('vep1_pre') }}" id="vep1-pre">
                            </div>
                            <div class="form-group">
                                <label class="form-label">KVP Pre (liter)</label>
                                <input type="number" name="kvp_pre" step="0.01" class="form-control" placeholder="3.00"
                                    value="{{ old('kvp_pre') }}" id="kvp-pre">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Rasio VEP1/KVP Pre (%) (otomatis)</label>
                            <input type="text" id="preview-rasio-pre" class="form-control" readonly placeholder="—"
                                tabindex="-1">
                        </div>

                        <div class="subsection-label">Hasil Spirometri Post-Bronkodilator <span class="subsection-hint">(jika
                                diberikan bronkodilator)</span></div>
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
                                <input type="number" name="vep1_post" step="0.01" class="form-control" placeholder="2.50"
                                    value="{{ old('vep1_post') }}" id="vep1-post">
                            </div>
                            <div class="form-group">
                                <label class="form-label">KVP Post (liter)</label>
                                <input type="number" name="kvp_post" step="0.01" class="form-control" placeholder="3.00"
                                    value="{{ old('kvp_post') }}" id="kvp-post">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Rasio VEP1/KVP Post (%) (otomatis)</label>
                            <input type="text" id="preview-rasio-post" class="form-control" readonly placeholder="—"
                                tabindex="-1">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Catatan Hasil Spirometri</label>
                            <textarea name="hasil_spirometri" class="form-control" rows="2"
                                placeholder="Kesimpulan hasil pemeriksaan...">{{ old('hasil_spirometri') }}</textarea>
                        </div>
                    </div>

                    {{-- Nav --}}
                    <div class="wizard-nav">
                        <button type="button" class="btn-ghost btn-prev" data-prev="{{ $prevPpok }}">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </button>
                        <button type="button" class="btn-primary btn-next" data-next="{{ $nextPpok }}">
                            Lanjut <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            @endif

            {{-- ══════════════════════════════════════
            STEP TERAKHIR — KONFIRMASI & SUBMIT
            ══════════════════════════════════════ --}}
            <div class="wizard-panel" id="step-review">
                <div class="form-section review-section">
                    <div class="section-header">
                        <i class="fa-solid fa-check-double"></i>
                        <span>Konfirmasi & Simpan</span>
                    </div>

                    <div class="review-summary" id="review-summary">
                        {{-- Diisi oleh JS --}}
                    </div>

                    <div class="review-notice">
                        <i class="fa-solid fa-circle-info"></i>
                        Pastikan semua data sudah benar sebelum menyimpan. Data yang sudah disimpan tidak dapat diubah
                        melalui halaman ini.
                    </div>
                </div>

                {{-- Nav --}}
                @php
                    $idxReview = array_search('step-review', $stepIds);
                    $prevReview = $stepIds[$idxReview - 1] ?? 'step-lansia';
                @endphp
                <div class="wizard-nav">
                    <button type="button" class="btn-ghost btn-prev" data-prev="{{ $prevReview }}">
                        <i class="fa-solid fa-arrow-left"></i> Kembali
                    </button>
                    <button type="submit" class="btn-submit-final" id="btn-submit-skrining">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Semua Skrining
                    </button>
                </div>
            </div>

            {{-- Locked state --}}
            @unless($jadwal)
                <div class="form-footer">
                    <div class="locked-notice">
                        <i class="fa-solid fa-lock"></i>
                        Input tidak tersedia — tidak ada jadwal posyandu aktif hari ini.
                    </div>
                </div>
            @endunless

        </form>
    </div>

    <!-- Modal Sudah Skrining -->
    @if($sudahSkrining->isNotEmpty())
        <div id="modalSudahSkrining"
            style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center;">
            <div
                style="background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 90%; max-width: 600px; max-height: 90vh; display: flex; flex-direction: column;">
                <div
                    style="padding: 16px 20px; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center;">
                    <h2 style="margin: 0; font-size: 16px; font-weight: 600;">Lansia yang Sudah Skrining Hari Ini</h2>
                    <button onclick="closeModalSudahSkrining()" type="button"
                        style="background: none; border: none; font-size: 20px; cursor: pointer; color: #999;">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <div style="padding: 0; flex: 1; overflow-y: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                        <thead style="background: #F9FAFB; position: sticky; top: 0;">
                            <tr>
                                <th style="padding: 12px 20px; text-align: left; border-bottom: 1px solid #E5E7EB;">NAMA / NIK
                                </th>
                                <th
                                    style="padding: 12px 20px; text-align: left; border-bottom: 1px solid #E5E7EB; width: 150px;">
                                    JENIS KELAMIN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sudahSkrining as $l)
                                <tr>
                                    <td style="padding: 12px 20px; border-bottom: 1px solid #E5E7EB;">
                                        <div style="font-weight: 600; color: #111827;">{{ $l->nama_lansia }}</div>
                                        <div style="font-size: 11px; color: #6B7280; margin-top: 2px;">NIK: {{ $l->nik ?? '-' }}
                                        </div>
                                    </td>
                                    <td style="padding: 12px 20px; border-bottom: 1px solid #E5E7EB;">
                                        @if($l->jenis_kelamin == 'L')
                                            <span
                                                style="color: #2563EB; background: #DBEAFE; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 500;">Laki-laki</span>
                                        @else
                                            <span
                                                style="color: #DB2777; background: #FCE7F3; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 500;">Perempuan</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        function openModalSudahSkrining() {
            document.getElementById('modalSudahSkrining').style.display = 'flex';
        }
        function closeModalSudahSkrining() {
            document.getElementById('modalSudahSkrining').style.display = 'none';
        }
        document.addEventListener('click', function (e) {
            const modal = document.getElementById('modalSudahSkrining');
            if (e.target === modal) {
                closeModalSudahSkrining();
            }
        });
        document.addEventListener('DOMContentLoaded', () => {

            // ══════════════════════════════════════════════════════════════
            //  WIZARD ENGINE
            //  Sumber kebenaran: panel yang ada di DOM, diurutkan sesuai
            //  kemunculannya — tidak perlu hard-code urutan di JS.
            // ══════════════════════════════════════════════════════════════
            const panels = [...document.querySelectorAll('.wizard-panel')];
            const wizSteps = [...document.querySelectorAll('.wizard-step')];
            const connectors = [...document.querySelectorAll('.wizard-connector')];

            // Map: panel id → index dalam DOM
            const stepOrder = panels.map(p => p.id);

            function currentPanelIndex() {
                return panels.findIndex(p => p.classList.contains('active'));
            }

            function goToStep(targetId) {
                const fromIdx = currentPanelIndex();
                const toIdx = stepOrder.indexOf(targetId);
                if (toIdx === -1 || toIdx === fromIdx) return;

                const dir = toIdx > fromIdx ? 'slide-left' : 'slide-right';

                // Sembunyikan panel aktif
                const fromPanel = panels[fromIdx];
                fromPanel.classList.add(dir === 'slide-left' ? 'exit-left' : 'exit-right');
                setTimeout(() => {
                    fromPanel.classList.remove('active', 'exit-left', 'exit-right');
                }, 260);

                // Tampilkan panel tujuan
                const toPanel = panels[toIdx];
                toPanel.classList.add(dir === 'slide-left' ? 'enter-right' : 'enter-left');
                toPanel.classList.add('active');
                setTimeout(() => {
                    toPanel.classList.remove('enter-right', 'enter-left');
                }, 20);

                // Update wizard track
                wizSteps.forEach((ws, i) => {
                    ws.classList.toggle('active', i === toIdx);
                    ws.classList.toggle('completed', i < toIdx);
                });
                connectors.forEach((c, i) => {
                    c.classList.toggle('filled', i < toIdx);
                });

                // Scroll ke atas
                document.querySelector('.skrining-wrapper')
                    ?.scrollIntoView({ behavior: 'smooth', block: 'start' });

                if (targetId === 'step-review') buildReview();
            }
            // ══════════════════════════════════════════════════════════════
            //  LOCK LANSIA — setelah pindah dari step pertama, 
            //  select lansia tidak bisa diubah lagi
            // ══════════════════════════════════════════════════════════════
            function lockLansia() {
                const sel = document.getElementById('select-lansia');
                if (sel) {
                    sel.disabled = true;
                    // Tambah hidden input agar value tetap terkirim saat form submit
                    // (disabled field tidak ikut submit)
                    const existing = document.getElementById('hidden-id-lansia');
                    if (!existing) {
                        const hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = 'id_lansia';
                        hidden.id = 'hidden-id-lansia';
                        hidden.value = sel.value;
                        sel.closest('form')?.appendChild(hidden);
                    }
                }
            }

            // Bind tombol Next
            document.querySelectorAll('.btn-next').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (!validateCurrentStep()) return;
                    const target = btn.dataset.next;
                    if (target) {
                        goToStep(target);
                        // Lock lansia setelah pindah dari step pertama
                        lockLansia();
                    }
                });
            });

            // Bind tombol Prev
            document.querySelectorAll('.btn-prev').forEach(btn => {
                btn.addEventListener('click', () => {
                    const target = btn.dataset.prev;
                    if (target) goToStep(target);
                });
            });

            // ── Validasi required fields pada step aktif ─────────────────
            // ── Validasi required fields pada step aktif ─────────────────
            function validateCurrentStep() {
                const panel = panels[currentPanelIndex()];
                const requiredFields = panel.querySelectorAll('[required]');
                let ok = true;

                // 1. Bersihkan tanda error sebelumnya
                panel.querySelectorAll('.field-error').forEach(el => el.classList.remove('field-error'));

                requiredFields.forEach(el => {
                    // 2. KUNCI PERBAIKAN OBAT: Lewati validasi jika elemen sedang disembunyikan (d-none)
                    if (el.offsetParent === null) return;

                    let isEmpty = false;

                    // 3. KUNCI PERBAIKAN PPOK/UTAMA: Logika khusus untuk Radio Button / Checkbox
                    if (el.type === 'radio' || el.type === 'checkbox') {
                        // Cek apakah di dalam grup nama yang sama ada yang sudah dicentang
                        const isChecked = panel.querySelector(`input[name="${el.name}"]:checked`);
                        if (!isChecked) {
                            isEmpty = true;
                            // Berikan efek error pada div pembungkusnya agar kelihatan
                            el.closest('.radio-group, .checkbox-grid')?.classList.add('field-error');
                        }
                    }
                    // Logika untuk Dropdown Select
                    else if (el.tagName === 'SELECT') {
                        isEmpty = !el.value;
                    }
                    // Logika untuk Text/Number Input biasa
                    else {
                        isEmpty = !el.value.trim();
                    }

                    // Jika kosong, tandai error
                    if (isEmpty) {
                        if (el.type !== 'radio' && el.type !== 'checkbox') {
                            el.classList.add('field-error');
                        }
                        ok = false;
                    }
                });

                // Jika ada error, scroll layar ke letak error pertama
                if (!ok) {
                    const first = panel.querySelector('.field-error');
                    first?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    if (first && typeof first.focus === 'function') first.focus();
                }
                return ok;
            } // ══════════════════════════════════════════════════════════════
            //  REVIEW SUMMARY
            // ══════════════════════════════════════════════════════════════
            function buildReview() {
                const box = document.getElementById('review-summary');
                if (!box) return;

                const lansiaEl = document.getElementById('select-lansia');
                const lansiaName = lansiaEl
                    ? lansiaEl.options[lansiaEl.selectedIndex]?.text
                    : '—';
                const pekerjaanDisplay = document.getElementById('ppok-pekerjaan-display')?.value || '—';

                const bb = document.querySelector('[name="berat_badan"]')?.value;
                const tb = document.querySelector('[name="tinggi_badan"]')?.value;
                const lp = document.querySelector('[name="lingkar_perut"]')?.value;
                const tds = document.querySelector('[name="td_sistolik"]')?.value;
                const tdd = document.querySelector('[name="td_diastolik"]')?.value;
                const imt = document.getElementById('preview-imt')?.value;
                const keluhan = document.querySelector('[name="keluhan"]')?.value;

                const srqTotal = document.getElementById('srq-total')?.textContent || '0';
                const gula = document.querySelector('[name="gula_darah"]')?.value;
                const kol = document.querySelector('[name="kolesterol"]')?.value;

                const adaResep = document.getElementById('chk-ada-resep')?.checked;
                const jumlahResep = adaResep
                    ? document.querySelectorAll('#resep-list .resep-row').length
                    : 0;

                let html = `<div class="review-grid">`;

                // Lansia
                html += reviewCard('fa-person-cane', 'Data Lansia', [
                    ['Nama', lansiaName],
                    ['Keluhan', keluhan || '—'],
                    ['Pekerjaan', pekerjaanDisplay],
                ]);

                // Kunjungan Rutin (selalu ada)
                html += reviewCard('fa-stethoscope', 'Kunjungan Rutin', [
                    ['Berat Badan', bb ? bb + ' kg' : '—'],
                    ['Tinggi Badan', tb ? tb + ' cm' : '—'],
                    ['Lingkar Perut', lp ? lp + ' cm' : '—'],
                    ['IMT', imt || '—'],
                    ['Tekanan Darah', (tds && tdd) ? tds + '/' + tdd + ' mmHg' : '—'],
                    ['Resep Obat', adaResep ? jumlahResep + ' item' : 'Tidak ada'],
                ]);

                // Skrining Utama (opsional)
                if (gula || kol || parseInt(srqTotal) > 0) {
                    html += reviewCard('fa-clipboard-list', 'Skrining Utama', [
                        ['Gula Darah', gula ? gula + ' mg/dL' : '—'],
                        ['Kolesterol', kol ? kol + ' mg/dL' : '—'],
                        ['Skor SRQ-20', srqTotal + ' / 20'],
                    ]);
                }

                // PPOK (opsional)
                const packYears = document.getElementById('preview-pack-years')?.value;
                const pumaSkor = document.querySelector('[name="puma_total_skor"]')?.value;
                if (packYears && packYears !== '—') {
                    html += reviewCard('fa-lungs', 'Skrining PPOK', [
                        ['Pack Years', packYears],
                    ]);
                }

                html += `</div>`;
                box.innerHTML = html;
            }

            function reviewCard(icon, title, rows) {
                const rowsHtml = rows.map(([k, v]) => `
                                        <div class="review-row">
                                            <span class="review-key">${k}</span>
                                            <span class="review-val">${v}</span>
                                        </div>`).join('');
                return `
                                        <div class="review-card">
                                            <div class="review-card-title">
                                                <i class="fa-solid ${icon}"></i> ${title}
                                            </div>
                                            ${rowsHtml}
                                        </div>`;
            }

            // ══════════════════════════════════════════════════════════════
            //  IMT Preview
            // ══════════════════════════════════════════════════════════════
            const bbInput = document.querySelector('[name="berat_badan"]');
            const tbInput = document.querySelector('[name="tinggi_badan"]');
            const imtEl = document.getElementById('preview-imt');

            function hitungIMT() {
                const bb = parseFloat(bbInput?.value);
                const tb = parseFloat(tbInput?.value);
                if (imtEl) {
                    imtEl.value = (bb > 0 && tb > 0)
                        ? (bb / Math.pow(tb / 100, 2)).toFixed(2)
                        : '';
                }
            }
            bbInput?.addEventListener('input', hitungIMT);
            tbInput?.addEventListener('input', hitungIMT);

            // ══════════════════════════════════════════════════════════════
            //  SARAN
            // ══════════════════════════════════════════════════════════════
            const selectLansia = document.getElementById('select-lansia');
            const saranSection = document.getElementById('saran-section');
            const saranSebelumnyaWrapper = document.getElementById('saran-sebelumnya-wrapper');
            const saranSebelumnyaList = document.getElementById('saran-sebelumnya-list');
            const saranBaruList = document.getElementById('saran-baru-list');
            let saranIdx = 0;

            const tbTerakhirMap = @json($tbTerakhir ?? []);

            function decodePekerjaan(value) {
                const map = {
                    '1': 'TNI/POLRI',
                    '2': 'PNS',
                    '3': 'Karyawan Swasta',
                    '4': 'Buruh',
                    '5': 'Petani/Nelayan',
                    '6': 'Tidak Bekerja/IRT',
                };
                if (!value) return '';
                if (map[String(value)]) return map[String(value)];
                return String(value);
            }

            function computePumaCategoryFromLansiaData(dobStr, ageVal) {
                // Prefer explicit age if provided by dataset
                let age = null;
                if (ageVal !== undefined && ageVal !== null && String(ageVal).trim() !== '') {
                    const parsed = parseInt(ageVal, 10);
                    if (!isNaN(parsed)) age = parsed;
                }

                // If no explicit age, try DOB parse
                if (age === null) {
                    if (!dobStr) return { cat: null, label: 'Umur tidak tersedia' };
                    const d = new Date(dobStr);
                    if (isNaN(d)) return { cat: null, label: 'Tanggal lahir tidak valid' };
                    const today = new Date();
                    age = today.getFullYear() - d.getFullYear();
                    const m = today.getMonth() - d.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < d.getDate())) age--;
                }

                if (age >= 60) return { cat: 2, label: '≥ 60 tahun (skor 2) — umur ' + age + ' th' };
                if (age >= 50) return { cat: 1, label: '50–59 tahun (skor 1) — umur ' + age + ' th' };
                if (age >= 40) return { cat: 0, label: '40–49 tahun (skor 0) — umur ' + age + ' th' };
                return { cat: null, label: age + ' tahun (di bawah 40)' };
            }

            function syncPekerjaanFromLansia(id) {
                const selectEl = document.getElementById('select-lansia');
                const pekerjaanDisplay = document.getElementById('ppok-pekerjaan-display');
                const pekerjaanHidden = document.getElementById('ppok-pekerjaan-hidden');

                const opt = selectEl?.selectedOptions?.[0];
                const raw = opt?.dataset.pekerjaan || '';
                const label = decodePekerjaan(raw);

                if (pekerjaanDisplay) {
                    pekerjaanDisplay.value = label;
                }
                if (pekerjaanHidden) {
                    pekerjaanHidden.value = raw;
                }
            }

            selectLansia?.addEventListener('change', async function () {
                const id = this.value;

                syncPekerjaanFromLansia(id);

                // Auto-calc PUMA kategori usia from lansia tanggal lahir
                try {
                    const opt = this.selectedOptions?.[0];
                    const dob = opt?.dataset?.tanggalLahir || opt?.dataset?.tanggal_lahir || opt?.dataset?.tanggal || '';
                    const age = opt?.dataset?.umur || opt?.dataset?.age || '';
                    const res = computePumaCategoryFromLansiaData(dob, age);
                    const displayEl = document.getElementById('puma-kategori-display');
                    const hiddenEl = document.getElementById('puma-kategori-hidden');
                    if (displayEl) displayEl.value = res.label || '';
                    if (hiddenEl) hiddenEl.value = (res.cat !== null && res.cat !== undefined) ? res.cat : '';
                } catch (err) {
                    // ignore
                }

                // Set Tinggi Badan otomatis
                if (id && tbTerakhirMap[id]) {
                    const tbInputEl = document.querySelector('[name="tinggi_badan"]');
                    if (tbInputEl && !tbInputEl.value) {
                        tbInputEl.value = tbTerakhirMap[id];
                        hitungIMT(); // recalculate IMT if needed
                    }
                }

                saranSebelumnyaWrapper.style.display = 'none';
                saranSebelumnyaList.innerHTML = '';
                saranBaruList.innerHTML = '';
                saranIdx = 0;
                saranSection.style.display = id ? 'block' : 'none';
                if (!id) return;

                // Loading state
                saranSebelumnyaList.innerHTML = '<span style="font-size:13px;color:#9ca3af;font-style:italic"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</span>';
                saranSebelumnyaWrapper.style.display = 'block';

                try {
                    const res = await fetch(`/lansia/${id}/saran`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const json = await res.json();
                    const data = json.data || [];

                    if (data.length === 0) {
                        saranSebelumnyaWrapper.style.display = 'none';
                    } else {
                        saranSebelumnyaList.innerHTML = data.map(s => `
                                                <div class="saran-edit-form" data-id="${s.id_saran}" style="margin-bottom:16px;padding:12px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;">
                                                    <div class="form-group" style="margin-bottom:12px;">
                                                        <input type="text" class="form-control saran-edit-jenis"
                                                            value="${escSaran(s.jenis_saran)}" placeholder="Judul saran...">
                                                    </div>
                                                    <div class="form-group" style="margin-bottom:12px;">
                                                        <textarea class="form-control saran-edit-isi"
                                                            placeholder="Isi saran..." rows="3">${escSaran(s.isi_saran)}</textarea>
                                                    </div>
                                                    <div style="display:flex;gap:8px;justify-content:flex-end;">
                                                        <button type="button" class="btn-delete-saran-lama" data-id="${s.id_saran}"
                                                            style="padding:6px 12px;background:#ef4444;color:white;border:none;border-radius:4px;font-size:12px;cursor:pointer;font-weight:500;">
                                                            <i class="fa-solid fa-trash"></i> Hapus
                                                        </button>
                                                    </div>
                                                </div>
                                            `).join('');
                        saranSebelumnyaList.querySelectorAll('.btn-delete-saran-lama').forEach(btn => {
                            btn.addEventListener('click', handleDeleteSaranLama);
                        });
                    }
                } catch {
                    saranSebelumnyaWrapper.style.display = 'none';
                }
            });

            document.getElementById('btn-add-saran')?.addEventListener('click', () => {
                const row = document.createElement('div');
                row.className = 'resep-row';
                row.innerHTML = saranRowHTML(saranIdx);
                saranBaruList.appendChild(row);
                row.querySelector('.btn-remove-saran')?.addEventListener('click', () => row.remove());
                saranIdx++;
            });

            function saranRowHTML(i) {
                return `
                                        <div class="saran-row-inner" style="margin-bottom:10px;">
                                            <div class="saran-row-fields">
                                                <input type="text" name="saran[${i}][jenis_saran]" class="form-control"
                                                    placeholder="Judul saran (cth: Pola Makan, Aktivitas Fisik...)" required>
                                                <textarea name="saran[${i}][isi_saran]" class="form-control"
                                                    placeholder="Tulis isi saran untuk lansia..." rows="3" required></textarea>
                                            </div>
                                            <button type="button" class="btn-remove-resep btn-remove-saran" title="Hapus saran"
                                                style="margin-left:8px;flex-shrink:0;margin-top:2px;">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>`;
            }

            function escSaran(str) {
                return String(str || '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;');
            }

            // ══════════════════════════════════════════════════════════════
            //  SRQ Counter
            // ══════════════════════════════════════════════════════════════
            const srqTotalEl = document.getElementById('srq-total');
            document.querySelectorAll('[name^="srq_"]').forEach(cb => {
                cb.addEventListener('change', () => {
                    const total = document.querySelectorAll('[name^="srq_"]:checked').length;
                    if (srqTotalEl) {
                        srqTotalEl.textContent = total;
                        srqTotalEl.style.color = total >= 6 ? '#dc2626' : 'inherit';
                    }
                });
            });

            // ══════════════════════════════════════════════════════════════
            //  Toggle Resep Obat
            // ══════════════════════════════════════════════════════════════
            const chkResep = document.getElementById('chk-ada-resep');
            const resepSection = document.getElementById('resep-section');
            chkResep?.addEventListener('change', () => {
                const show = chkResep.checked;
                resepSection.classList.toggle('d-none', !show);

                // Tambah required saat dicentang, hapus saat tidak
                resepSection.querySelectorAll('select.form-control, input.form-control').forEach(el => {
                    const name = el.name || '';
                    // Hanya field obat, dosis, frekuensi yang required — bukan keterangan
                    if (name.includes('[id_obat]') || name.includes('[dosis]') || name.includes('[frekuensi]')) {
                        el.required = show;
                    }
                });
            });

            // ── Tambah Baris Resep ───────────────────────────────────────
            let resepIdx = document.querySelectorAll('#resep-list .resep-row').length;
            document.getElementById('btn-add-resep')?.addEventListener('click', () => {
                const list = document.getElementById('resep-list');
                const row = document.createElement('div');
                row.className = 'resep-row';
                row.style.marginBottom = '12px';
                row.style.borderBottom = '1px dashed #ccc';
                row.style.paddingBottom = '12px';
                row.innerHTML = resepRowHTML(resepIdx);
                list.appendChild(row);
                resepIdx++;
                bindRemoveResep(row);
                bindJenisJadwal(row);

                if (chkResep?.checked) {
                    row.querySelectorAll('select.form-control, input.form-control').forEach(el => {
                        const name = el.name || '';
                        if (name.includes('[id_obat]') || name.includes('[dosis]') || name.includes('[frekuensi]') || name.includes('[jumlah_obat]')) {
                            el.required = true;
                        }
                    });
                }
            });

            document.querySelectorAll('#resep-list .resep-row').forEach(row => {
                bindRemoveResep(row);
                bindJenisJadwal(row);
            });

            function bindRemoveResep(row) {
                row.querySelector('.btn-remove-resep')?.addEventListener('click', () => row.remove());
            }

            function bindJenisJadwal(row) {
                const select = row.querySelector('.select-jenis-jadwal');
                const group = row.querySelector('.hari-konsumsi-group');
                const checkboxes = group?.querySelectorAll('input[type="checkbox"]');
                const frekuensiInput = row.querySelector('.input-frekuensi');
                if (select && group) {
                    select.addEventListener('change', () => {
                        if (select.value === 'hari_tertentu') {
                            group.style.display = 'flex';
                            if (frekuensiInput) {
                                frekuensiInput.placeholder = 'Frq/mgg';
                            }
                        } else {
                            group.style.display = 'none';
                            if (checkboxes) checkboxes.forEach(cb => cb.checked = false);
                            if (frekuensiInput) {
                                frekuensiInput.placeholder = 'Frq/hari';
                            }
                        }
                    });

                    // Initialize pekerjaan preview if lansia is already selected
                    syncPekerjaanFromLansia(selectLansia?.value);
                    // Trigger initial change
                    select.dispatchEvent(new Event('change'));
                }
            }

            function resepRowHTML(i) {
                const options = @json($obat->map(fn($o) => ['id' => $o->id_obat, 'nama' => $o->nama_obat]));
                const opts = options.map(o => `<option value="${o.id}">${o.nama}</option>`).join('');
        return `
            <div class="resep-row-inner" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 10px; align-items: flex-end;">
                <div style="grid-column: span 2;">
                    <label style="display: block; font-size: 11px; margin-bottom: 4px; color: #666; font-weight: 600;">Pilih Obat</label>
                    <select name="resep[${i}][id_obat]" class="form-control" style="width: 100%;">
                        <option value="">-- Pilih Obat --</option>
                        ${opts}
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 11px; margin-bottom: 4px; color: #666; font-weight: 600;">Dosis</label>
                    <input type="text" name="resep[${i}][dosis]" class="form-control" placeholder="cth: 500mg" style="width: 100%;">
                </div>
                
                <div>
                    <label style="display: block; font-size: 11px; margin-bottom: 4px; color: #666; font-weight: 600;">Frekuensi</label>
                    <input type="number" min="1" name="resep[${i}][frekuensi]" class="form-control input-frekuensi" placeholder="Jml" style="width: 100%;">
                </div>
                
                <div>
                    <label style="display: block; font-size: 11px; margin-bottom: 4px; color: #666; font-weight: 600;">Durasi Hari</label>
                    <input type="number" min="1" name="resep[${i}][durasi_hari]" class="form-control" placeholder="Hari" style="width: 100%;">
                </div>

                <div>
                    <label style="display: block; font-size: 11px; margin-bottom: 4px; color: #666; font-weight: 600;">Jenis Jadwal</label>
                    <select name="resep[${i}][jenis_jadwal]" class="form-control select-jenis-jadwal" style="width: 100%;">
                        <option value="harian">Harian</option>
                        <option value="hari_tertentu">Hari Tertentu</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 11px; margin-bottom: 4px; color: #666; font-weight: 600;">Jml Obat</label>
                    <input type="number" min="1" name="resep[${i}][jumlah_obat]" class="form-control" placeholder="Jml" value="1" style="width: 100%;">
                </div>
                
                <div style="grid-column: span 2;">
                    <label style="display: block; font-size: 11px; margin-bottom: 4px; color: #666; font-weight: 600;">Keterangan</label>
                    <input type="text" name="resep[${i}][keterangan]" class="form-control" placeholder="Opsional" style="width: 100%;">
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="button" class="btn-remove-resep" style="padding: 8px 10px; background: #fee2e2; border: 1px solid #fca5a5; color: #dc2626; border-radius: 6px; cursor: pointer; width: 100%;">
                        <i class="fa-solid fa-trash"></i> Hapus Baris
                    </button>
                </div>
            </div>

            <div class="hari-konsumsi-group" style="display: none; gap: 10px; margin-top: 10px; flex-wrap: wrap; background: #f8fafc; padding: 10px; border-radius: 6px; border: 1px solid #e2e8f0;">
                <div style="width: 100%; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 4px;">Pilih Hari Konsumsi:</div>
                ${['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'].map(h => `
                    <label class="checkbox-item" style="font-size: 0.85rem; display: flex; align-items: center; gap: 4px; margin-bottom: 0;">
                        <input type="checkbox" name="resep[${i}][hari_konsumsi][]" value="${h}" class="chk-hari">
                        ${h.charAt(0).toUpperCase() + h.slice(1)}
                    </label>
                `).join('')}
            </div>`;
            }
            // ══════════════════════════════════════════════════════════════
            //  Toggle kondisional Merokok & Paparan
            // ══════════════════════════════════════════════════════════════
            document.querySelectorAll('[name="merokok"]').forEach(r => {
                r.addEventListener('change', () => {
                    const group = document.getElementById('merokok-kategori-group');
                    if (group) group.style.display = r.value === '1' ? '' : 'none';
                });
            });

            document.querySelectorAll('[name="paparan_asap_rokok"]').forEach(r => {
                r.addEventListener('change', () => {
                    const group = document.getElementById('paparan-frekuensi-group');
                    if (group) group.style.display = r.value === '1' ? '' : 'none';
                });
            });

            // ══════════════════════════════════════════════════════════════
            //  PPOK — Kalkulasi Pack Years & Rasio Spirometri
            // ══════════════════════════════════════════════════════════════
            const rphInput = document.getElementById('puma-rokok-per-hari');
            const lmtInput = document.getElementById('puma-lama-merokok');
            const pyEl = document.getElementById('preview-pack-years');

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

            const vep1Pre = document.getElementById('vep1-pre');
            const kvpPre = document.getElementById('kvp-pre');
            const rasioPreEl = document.getElementById('preview-rasio-pre');
            function hitungRasioPre() {
                const v = parseFloat(vep1Pre?.value);
                const k = parseFloat(kvpPre?.value);
                if (rasioPreEl) rasioPreEl.value = (v > 0 && k > 0)
                    ? ((v / k) * 100).toFixed(2) + ' %' : '—';
            }
            vep1Pre?.addEventListener('input', hitungRasioPre);
            kvpPre?.addEventListener('input', hitungRasioPre);

            const vep1Post = document.getElementById('vep1-post');
            const kvpPost = document.getElementById('kvp-post');
            const rasioPostEl = document.getElementById('preview-rasio-post');
            function hitungRasioPost() {
                const v = parseFloat(vep1Post?.value);
                const k = parseFloat(kvpPost?.value);
                if (rasioPostEl) rasioPostEl.value = (v > 0 && k > 0)
                    ? ((v / k) * 100).toFixed(2) + ' %' : '—';
            }
            vep1Post?.addEventListener('input', hitungRasioPost);
            kvpPost?.addEventListener('input', hitungRasioPost);

            // ══════════════════════════════════════════════════════════════
            //  DELETE Saran Lama
            // ══════════════════════════════════════════════════════════════
            async function handleDeleteSaranLama(e) {
                const btn = e.currentTarget;
                const id = btn.dataset.id;
                const idLansia = selectLansia?.value;
                if (!confirm('Yakin mau hapus saran ini?')) return;
                try {
                    const res = await fetch(`/lansia/${idLansia}/saran/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
                        }
                    });
                    const json = await res.json();
                    if (res.ok) {
                        // Reload daftar saran
                        selectLansia.dispatchEvent(new Event('change'));
                    } else {
                        alert('Error: ' + (json.message || 'Gagal hapus'));
                    }
                } catch (err) {
                    alert('Error: ' + err.message);
                }
            }

            // ══════════════════════════════════════════════════════════════
            //  Toggle Sudah Skrining List
            // ══════════════════════════════════════════════════════════════
            document.getElementById('btn-toggle-sudah')?.addEventListener('click', function () {
                const list = document.getElementById('sudah-skrining-list');
                const icon = document.getElementById('icon-toggle-sudah');
                const shown = list.style.display !== 'none';
                list.style.display = shown ? 'none' : 'flex';
                icon.style.transform = shown ? 'rotate(0deg)' : 'rotate(180deg)';
            });

            // ══════════════════════════════════════════════════════════════
            //  SUBMIT — kumpulkan saran_edit & prevent double submit
            // ══════════════════════════════════════════════════════════════
            document.getElementById('formSkrining')?.addEventListener('submit', function (e) {
                const btn = document.getElementById('btn-submit-skrining');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
                }

                // Kumpulkan saran yang diedit sebagai hidden input
                document.querySelectorAll('.saran-edit-form').forEach(form => {
                    const id = form.dataset.id;
                    const jenis = form.querySelector('.saran-edit-jenis')?.value;
                    const isi = form.querySelector('.saran-edit-isi')?.value;

                    const mkInput = (name, val) => {
                        const inp = document.createElement('input');
                        inp.type = 'hidden';
                        inp.name = name;
                        inp.value = val;
                        this.appendChild(inp);
                    };
                    mkInput(`saran_edit[${id}][jenis_saran]`, jenis);
                    mkInput(`saran_edit[${id}][isi_saran]`, isi);
                });
            });

            // Trigger initial change so UI (pekerjaan, PUMA kategori) is populated if lansia already selected
            selectLansia?.dispatchEvent(new Event('change'));

        });
    </script>
@endpush