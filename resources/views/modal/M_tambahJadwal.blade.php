{{-- resources/views/modal/M_tambahJadwal.blade.php --}}

<div class="modal-overlay" id="modalTambahJadwal">
    <div class="modal">

        <div class="modal-header">
            <div>
                <div class="modal-title">Tambah Jadwal Posyandu</div>
                <div class="modal-sub">Isi informasi jadwal dan tentukan jenis skrining</div>
            </div>
            <button class="btn-close" id="btn-close-modal">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="modal-body">

            {{-- INFORMASI JADWAL --}}
            <div class="modal-section-label">Informasi Jadwal</div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tanggal <span class="required">*</span></label>
                    <input type="date" class="form-control" id="input-tanggal" name="tanggal_pelaksanaan">
                </div>
                <div class="form-group">
                    <label class="form-label">Tema <span class="required">*</span></label>
                    <input type="text" class="form-control" id="input-tema" name="tema"
                        placeholder="Contoh: Pemeriksaan Rutin">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Lokasi <span class="required">*</span></label>
                <input type="text" class="form-control" id="input-lokasi" name="lokasi"
                    placeholder="Contoh: Balai RW 03, Jl. Mawar No.12">
            </div>

            {{-- JENIS SKRINING --}}
            <div class="modal-section-label">Jenis Skrining</div>

            <div class="skrining-section">
                <div class="skrining-section-header">
                    <i class="fa-solid fa-clipboard-check"></i>
                    Pilih skrining yang akan dilakukan di pertemuan ini
                </div>

                {{-- Kunjungan Rutin - selalu aktif --}}
                <div class="skrining-option disabled">
                    <input type="checkbox" checked disabled>
                    <div class="skrining-opt-info">
                        <div class="skrining-opt-title">Kunjungan Rutin</div>
                        <div class="skrining-opt-desc">
                            BB, TB, lingkar perut, tensi — selalu dilakukan setiap pertemuan
                        </div>
                    </div>
                    <span class="skrining-badge always">Selalu Ada</span>
                </div>

                {{-- Skrining Utama --}}
                <div class="skrining-option" id="opt-utama">
                    <input type="checkbox" id="chk-utama" name="ada_skrining_utama" value="1">
                    <div class="skrining-opt-info">
                        <div class="skrining-opt-title">Skrining Utama</div>
                        <div class="skrining-opt-desc">
                            SRQ-20, skrining penglihatan & pendengaran, riwayat penyakit
                        </div>
                    </div>
                    <span class="skrining-badge optional">Opsional</span>
                </div>

                {{-- Skrining PPOK --}}
                <div class="skrining-option" id="opt-ppok">
                    <input type="checkbox" id="chk-ppok" name="ada_skrining_ppok" value="1">
                    <div class="skrining-opt-info">
                        <div class="skrining-opt-title">Skrining PPOK</div>
                        <div class="skrining-opt-desc">
                            Wawancara PUMA, pemeriksaan spirometri deteksi dini PPOK
                        </div>
                    </div>
                    <span class="skrining-badge optional">Opsional</span>
                </div>
            </div>

            {{-- KEGIATAN --}}
            <div class="modal-section-label">Kegiatan</div>

            <div class="kegiatan-section">
                <div class="kegiatan-section-header">
                    <span>Daftar Kegiatan</span>
                    <span class="kegiatan-hint">
                        <i class="fa-regular fa-clock"></i> Jam bersifat opsional
                    </span>
                </div>
                <div id="kegiatanList">
                    <div class="kegiatan-item">
                        <div class="kegiatan-num">1</div>
                        <input class="kegiatan-input" type="text"
                            placeholder="Nama kegiatan, contoh: Senam pagi">
                        <span class="jam-label"><i class="fa-regular fa-clock"></i></span>
                        <input class="jam-input" type="time">
                        <button class="btn-remove" type="button">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
                <button class="btn-add-kegiatan" type="button" id="btn-add-kegiatan">
                    <i class="fa-solid fa-plus"></i> Tambah Kegiatan
                </button>
            </div>

            {{-- CATATAN --}}
            <div class="form-group" style="margin-top: 16px;">
                <label class="form-label">
                    Catatan <span class="form-hint">(opsional)</span>
                </label>
                <textarea class="form-control" id="input-catatan" name="keterangan" rows="3"
                    placeholder="Catatan tambahan untuk pertemuan ini..."></textarea>
            </div>

        </div>

        <div class="modal-footer">
            <button class="btn-ghost" type="button" id="btn-cancel-modal">Batal</button>
            <button class="btn-primary" type="button" id="btn-simpan-jadwal">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Jadwal
            </button>
        </div>

    </div>
</div>