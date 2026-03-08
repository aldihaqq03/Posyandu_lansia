<!-- Modal Edit Lansia -->
<div id="modal-edit-lansia" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Data Lansia</h2>
            <button class="btn-close-modal" id="btn-close-edit-modal">&times;</button>
        </div>
        <div class="modal-body">
            <form action="" method="POST" id="form-edit-lansia">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="edit_nik">NIK</label>
                    <input type="text" id="edit_nik" name="nik" placeholder="Masukkan 16 digit NIK" required>
                </div>
                <div class="form-group">
                    <label for="edit_jenis_kelamin">Jenis Kelamin</label>
                    <select id="edit_jenis_kelamin" name="jenis_kelamin" required>
                        <option value="laki-laki">Laki-laki</option>
                        <option value="perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_nama_lengkap">Nama Lengkap</label>
                    <input type="text" id="edit_nama_lengkap" name="nama_lengkap" placeholder="Masukkan Nama Lengkap"
                        required>
                </div>
                <div class="form-group">
                    <label for="edit_tanggal_lahir">Tanggal Lahir</label>
                    <input type="date" id="edit_tanggal_lahir" name="tanggal_lahir" required>
                </div>
                <div class="form-group">
                    <label for="edit_alamat">Alamat</label>
                    <textarea id="edit_alamat" name="alamat" rows="3" placeholder="Masukkan Alamat Lengkap"
                        required></textarea>
                </div>
                <div class="form-group">
                    <label for="edit_penyakit">Penyakit</label>
                    <input type="text" id="edit_penyakit" name="penyakit" placeholder="Contoh: Hipertensi, Asam Urat"
                        required>
                </div>
                <div class="form-group">
                    <label for="edit_status_risiko">Status Risiko</label>
                    <select id="edit_status_risiko" name="status_risiko">
                        <option value="NORMAL">NORMAL</option>
                        <option value="RESIKO TINGGI">RESIKO TINGGI</option>
                    </select>
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