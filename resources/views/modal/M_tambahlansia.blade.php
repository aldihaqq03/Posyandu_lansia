<!-- Modal Tambah Lansia -->

<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="route-store-lansia" content="{{ route('lansia.store') }}">

<div id="modal-tambah-lansia" class="modal-overlay">
    <div class="modal-content">

        <div class="modal-header">
            <h2>Tambah Data Lansia</h2>
            <button class="btn-close-modal" id="btn-close-modal">&times;</button>
        </div>

        <div class="modal-body">
            <form action="#" method="POST">

                <div class="form-group">
                    <label for="nik">NIK</label>
                    <input type="text" id="nik" name="nik" placeholder="Masukkan 16 digit NIK" required>
                </div>

                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan Nama Lengkap"
                        required>
                </div>

                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3" placeholder="Masukkan Alamat Lengkap"
                        required></textarea>
                </div>


                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan Email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan Password" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        placeholder="Konfiramsi Password" required>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" id="btn-cancel-modal">Batal</button>
                    <button type="submit" class="btn-primary" style="padding: 10px 18px; border-radius: 8px;">
                        Simpan
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>