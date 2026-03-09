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
                        <input type="text" id="edit_nama_lansia" name="nama_lansia" placeholder="Masukkan Nama Lengkap" required>
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
                <div class="form-group">
                    <label for="edit_alamat">Alamat</label>
                    <textarea id="edit_alamat" name="alamat" rows="2" placeholder="Masukkan Alamat Lengkap" required></textarea>
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