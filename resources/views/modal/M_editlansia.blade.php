<!-- Modal Edit Lansia -->
<div id="modal-edit-lansia" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Data Lansia</h2>
            <button class="btn-close-modal" id="btn-close-edit-modal">&times;</button>
        </div>
        <div class="modal-body">
            {{-- 
                ⚠️ PENTING: action dan method diisi oleh JS saat tombol edit diklik.
                @method('PUT') di sini harus ada agar Laravel tahu ini adalah PUT request.
                JS akan set form.action = /lansia/{id}
            --}}
            <form id="form-edit-lansia" method="POST" action="">
                @csrf
                @method('PUT')

                <div class="form-group" style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label for="edit_nik">NIK</label>
                        <input type="text" id="edit_nik" name="nik" placeholder="Masukkan 16 digit NIK" required>
                        <small id="error-edit_nik" style="color: #e74c3c; font-size: 12px; display: none; margin-top: 4px;"></small>
                    </div>
                    <div style="flex: 1;">
                        <label for="edit_nama_lansia">Nama Lengkap</label>
                        <input type="text" id="edit_nama_lansia" name="nama_lansia" placeholder="Masukkan Nama Lengkap" required>
                        <small id="error-edit_nama_lansia" style="color: #e74c3c; font-size: 12px; display: none; margin-top: 4px;"></small>
                    </div>
                </div>
                <div class="form-group" style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label for="edit_tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" id="edit_tanggal_lahir" name="tanggal_lahir" required>
                        <small id="error-edit_tanggal_lahir" style="color: #e74c3c; font-size: 12px; display: none; margin-top: 4px;"></small>
                    </div>
                    <div style="flex: 1;">
                        <label for="edit_jenis_kelamin">Jenis Kelamin</label>
                        <select id="edit_jenis_kelamin" name="jenis_kelamin" required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label for="edit_tempat_lahir">Tempat Lahir</label>
                        <input type="text" id="edit_tempat_lahir" name="tempat_lahir" placeholder="Contoh: Jakarta">
                    </div>
                    <div style="flex: 1;">
                        <label for="edit_no_hp">No Handphone (Opsional)</label>
                        <input type="text" id="edit_no_hp" name="no_hp" placeholder="Contoh: 081234567890">
                        <small id="error-edit_no_hp" style="color: #e74c3c; font-size: 12px; display: none; margin-top: 4px;"></small>
                    </div>
                </div>
                <div class="form-group" style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label for="edit_status_perkawinan">Status Perkawinan</label>
                        <select id="edit_status_perkawinan" name="status_perkawinan">
                            <option value="">Pilih Status</option>
                            <option value="Belum Kawin">Belum Kawin</option>
                            <option value="Kawin">Kawin</option>
                            <option value="Cerai Hidup">Cerai Hidup</option>
                            <option value="Cerai Mati">Cerai Mati</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="edit_riwayat_penyakit">Riwayat Penyakit</label>
                    <input type="text" id="edit_riwayat_penyakit" name="riwayat_penyakit"
                        placeholder="Contoh: Hipertensi, Diabetes">
                </div>
                <div class="form-group">
                    <label for="edit_alamat">Alamat</label>
                    <textarea id="edit_alamat" name="alamat" rows="2" placeholder="Masukkan Alamat Lengkap" required></textarea>
                    <small id="error-edit_alamat" style="color: #e74c3c; font-size: 12px; display: none; margin-top: 4px;"></small>
                </div>
                <div class="form-group">
                    <label for="edit_keterangan">Keterangan</label>
                    <textarea id="edit_keterangan" name="keterangan" rows="2" placeholder="Informasi Tambahan"></textarea>
                </div>
                <div class="form-group">
                    <label for="edit_email">Email (Opsional)</label>
                    <input type="email" id="edit_email" name="email" placeholder="Contoh: kakek@email.com">
                    <small id="error-edit_email" style="color: #e74c3c; font-size: 12px; display: none; margin-top: 4px;"></small>
                </div>

                <!-- INFORMASI KELUARGA SECTION -->
                <hr style="margin: 20px 0; border: none; border-top: 2px solid #e0e0e0;">
                <h3 style="margin-top: 20px; margin-bottom: 15px; color: #333;">
                    Informasi Keluarga <span style="color: #e74c3c;">*</span>
                </h3>

                <div id="edit-keluarga-container">
                    <!-- Diisi secara dinamis oleh JavaScript -->
                </div>

                <button type="button" id="btn-tambah-keluarga-edit" class="btn-secondary"
                    style="margin-bottom: 15px; background-color: #f5f5f5; color: #333; border: 1px dashed #999; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-size: 13px;">
                    + Tambah Anggota Keluarga
                </button>

                {{-- ⚠️ PENTING: modal-footer di dalam form agar tombol submit terikat dengan form --}}
                <div class="modal-footer">
                    <button type="button" id="btn-cancel-edit-modal" class="btn-secondary">Batal</button>
                    <button type="submit" id="btn-submit-edit" class="btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>