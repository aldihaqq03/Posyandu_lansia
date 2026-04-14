<!-- Modal Tambah Lansia -->
<div id="modal-tambah-lansia" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Tambah Data Lansia</h2>
            <button class="btn-close-modal" id="btn-close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <form action="{{ route('lansia.store') }}" method="POST">
                @csrf
                <div class="form-group" style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label for="nik">NIK</label>
                        <input type="text" id="nik" name="nik" placeholder="Masukkan 16 digit NIK" required>
                    </div>
                    <div style="flex: 1;">
                        <label for="nama_lansia">Nama Lengkap</label>
                        <input type="text" id="nama_lansia" name="nama_lansia" placeholder="Masukkan Nama Lengkap"
                            required>
                    </div>
                </div>
                <div class="form-group" style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>
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
                    <div style="flex: 1;">
                        <label for="tanggal_daftar">Tanggal Daftar</label>
                        <input type="date" id="tanggal_daftar" name="tanggal_daftar">
                    </div>
                </div>
                <div class="form-group">
                    <label for="riwayat_penyakit">Riwayat Penyakit</label>
                    <input type="text" id="riwayat_penyakit" name="riwayat_penyakit"
                        placeholder="Contoh: Hipertensi, Diabetes">
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="2" placeholder="Masukkan Alamat Lengkap"
                        required></textarea>
                </div>
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="2" placeholder="Informasi Tambahan"></textarea>
                </div>
                <div class="form-group" style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label for="email">Email (Opsional)</label>
                        <input type="email" id="email" name="email" placeholder="Contoh: kakek@email.com">
                    </div>
                </div>
                <!-- Password confirmatiion needed silently for validation -->
                <input type="hidden" id="password_confirmation" name="password_confirmation">

                <script>
                    document.getElementById('password').addEventListener('input', function () {
                        document.getElementById('password_confirmation').value = this.value;
                    });
                </script>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" id="btn-cancel-modal">Batal</button>
                    <button type="submit" class="btn-primary"
                        style="padding: 10px 18px; border-radius: 8px;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>