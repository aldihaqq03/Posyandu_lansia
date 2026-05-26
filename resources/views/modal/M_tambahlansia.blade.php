<!-- Modal Tambah Lansia -->
<div id="modal-tambah-lansia" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Tambah Data Lansia</h2>
            <button class="btn-close-modal" id="btn-close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="form-tambah-lansia" action="{{ route('lansia.store') }}" method="POST">
                @csrf
                <div class="form-group" style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label for="nik">NIK</label>
                        <input type="text" id="nik" name="nik" placeholder="Masukkan 16 digit NIK" required>
                        <small id="error-nik" style="color: #e74c3c; font-size: 12px; display: none; margin-top: 4px;"></small>
                    </div>
                    <div style="flex: 1;">
                        <label for="nama_lansia">Nama Lengkap</label>
                        <input type="text" id="nama_lansia" name="nama_lansia" placeholder="Masukkan Nama Lengkap" required>
                        <small id="error-nama_lansia" style="color: #e74c3c; font-size: 12px; display: none; margin-top: 4px;"></small>
                    </div>
                </div>
                <div class="form-group" style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>
                        <small id="error-tanggal_lahir" style="color: #e74c3c; font-size: 12px; display: none; margin-top: 4px;"></small>
                    </div>
                    <div style="flex: 1;">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" placeholder="Contoh: Jakarta">
                    </div>
                    <div style="flex: 1;">
                        <label for="no_hp">No Handphone (Opsional)</label>
                        <input type="text" id="no_hp" name="no_hp" placeholder="Contoh: 081234567890">
                        <small id="error-no_hp" style="color: #e74c3c; font-size: 12px; display: none; margin-top: 4px;"></small>
                    </div>
                </div>
                <div class="form-group" style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label for="status_perkawinan">Status Perkawinan</label>
                        <select id="status_perkawinan" name="status_perkawinan">
                            <option value="">Pilih Status</option>
                            <option value="Belum Kawin">Belum Kawin</option>
                            <option value="Kawin">Kawin</option>
                            <option value="Cerai Hidup">Cerai Hidup</option>
                            <option value="Cerai Mati">Cerai Mati</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" style="display: flex; gap: 10px; margin-top: 10px;">
                    <div style="flex: 1;">
                        <label for="pekerjaan">Pekerjaan</label>
                        <select id="pekerjaan" name="pekerjaan">
                            <option value="">Pilih Pekerjaan</option>
                            <option value="TNI/POLRI">TNI/POLRI</option>
                            <option value="PNS">PNS</option>
                            <option value="Karyawan Swasta">Karyawan Swasta</option>
                            <option value="Buruh">Buruh</option>
                            <option value="Petani/Nelayan">Petani/Nelayan</option>
                            <option value="Tidak Bekerja / IRT">Tidak Bekerja / IRT</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <small id="error-pekerjaan" style="color: #e74c3c; font-size: 12px; display: none; margin-top: 4px;"></small>
                    </div>
                </div>

                <!-- Note: custom 'Lainnya' will be edited inline in the select via JS -->
                <div class="form-group">
                    <label for="riwayat_penyakit">Riwayat Penyakit</label>
                    <input type="text" id="riwayat_penyakit" name="riwayat_penyakit"
                        placeholder="Contoh: Hipertensi, Diabetes">
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="2" placeholder="Masukkan Alamat Lengkap" required></textarea>
                    <small id="error-alamat" style="color: #e74c3c; font-size: 12px; display: none; margin-top: 4px;"></small>
                </div>
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="2" placeholder="Informasi Tambahan"></textarea>
                </div>
                <div class="form-group" style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label for="email">Email (Opsional)</label>
                        <input type="email" id="email" name="email" placeholder="Contoh: kakek@email.com">
                        <small id="error-email" style="color: #e74c3c; font-size: 12px; display: none; margin-top: 4px;"></small>
                    </div>
                </div>

                <!-- INFORMASI KELUARGA SECTION -->
                <hr style="margin: 20px 0; border: none; border-top: 2px solid #e0e0e0;">
                <h3 style="margin-top: 20px; margin-bottom: 15px; color: #333;">Informasi Keluarga <span style="color: #e74c3c;">*</span></h3>
                <small id="error-keluarga" style="color: #e74c3c; font-size: 12px; display: none; margin-bottom: 10px; display: block;"></small>

                <div id="keluarga-container">
                    <div class="keluarga-item" data-first="true" style="padding: 15px; background-color: #f9f9f9; border-radius: 8px; margin-bottom: 15px; border: 1px solid #e0e0e0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <h4 style="margin: 0; color: #555;">Anggota Keluarga #1 <span style="font-size:11px; color:#e74c3c;">(Wajib)</span></h4>
                            {{-- Tombol hapus disembunyikan untuk keluarga pertama --}}
                        </div>
                        <div class="form-group">
                            <label>Nama Keluarga <span style="color:#e74c3c">*</span></label>
                            <input type="text" class="nama_keluarga_input" name="keluarga[0][nama_keluarga]" placeholder="Masukkan nama anggota keluarga" required>
                            <small id="error-keluarga-nama-0" style="color: #e74c3c; font-size: 12px; display: none; margin-top: 4px;"></small>
                        </div>
                        <div class="form-group" style="display: flex; gap: 10px;">
                            <div style="flex: 1;">
                                <label>No Telepon (Opsional)</label>
                                <input type="text" class="no_sama_input" name="keluarga[0][no_sama]" placeholder="Contoh: 081234567890">
                            </div>
                            <div style="flex: 1;">
                                <label>Alamat (Opsional)</label>
                                <input type="text" class="alamat_keluarga_input" name="keluarga[0][alamat]" placeholder="Alamat anggota keluarga">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" id="btn-tambah-keluarga" class="btn-secondary" style="margin-bottom: 15px;">+ Tambah Anggota Keluarga</button>

                {{-- ⚠️ PENTING: modal-footer dipindah ke DALAM form agar submitBtn bisa ditemukan --}}
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" id="btn-cancel-modal">Batal</button>
                    <button type="submit" id="btn-submit-tambah" class="btn-primary"
                        style="padding: 10px 18px; border-radius: 8px;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>