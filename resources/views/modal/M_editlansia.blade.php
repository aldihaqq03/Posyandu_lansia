<!-- Modal Edit Lansia -->
<div id="modal-edit-lansia" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Data Lansia</h2>
            <button class="btn-close-modal" id="btn-close-edit-modal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="form-edit-lansia" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group" style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label for="edit_nik">NIK</label>
                        <input type="text" id="edit_nik" name="nik" placeholder="Masukkan 16 digit NIK" required>
                    </div>
                    <div style="flex: 1;">
                        <label for="edit_nama_lansia">Nama Lengkap</label>
                        <input type="text" id="edit_nama_lansia" name="nama_lansia" placeholder="Masukkan Nama Lengkap"
                            required>
                    </div>
                </div>
                <div class="form-group" style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label for="edit_tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" id="edit_tanggal_lahir" name="tanggal_lahir" required>
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
                        <label for="edit_no_hp">No Handphone</label>
                        <input type="text" id="edit_no_hp" name="no_hp" placeholder="Contoh: 081234567890">
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
                    <textarea id="edit_alamat" name="alamat" rows="2" placeholder="Masukkan Alamat Lengkap"
                        required></textarea>
                </div>
                <div class="form-group">
                    <label for="edit_keterangan">Keterangan</label>
                    <textarea id="edit_keterangan" name="keterangan" rows="2"
                        placeholder="Informasi Tambahan"></textarea>
                </div>
                <div class="form-group">
                    <label for="edit_email">Email (Opsional)</label>
                    <input type="email" id="edit_email" name="email" placeholder="Contoh: kakek@email.com">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" id="btn-cancel-edit-modal">Batal</button>
                    <button type="submit" class="btn-primary"
                        style="padding: 10px 18px; border-radius: 8px;">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>