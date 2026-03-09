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
                                <input type="text" id="nama_lansia" name="nama_lansia" placeholder="Masukkan Nama Lengkap" required>
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
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="2" placeholder="Masukkan Alamat Lengkap" required></textarea>
                        </div>
                        <div class="form-group" style="display: flex; gap: 10px;">
                            <div style="flex: 1;">
                                <label for="email">Email Login (Opsional)</label>
                                <input type="email" id="email" name="email" placeholder="Contoh: kakek@email.com">
                            </div>
                            <div style="flex: 1;">
                                <label for="password">Password (Opsional)</label>
                                <input type="password" id="password" name="password" placeholder="Min. 6 Karakter">
                            </div>
                        </div>
                        <!-- Password confirmatiion needed silently for validation -->
                        <input type="hidden" id="password_confirmation" name="password_confirmation">

                        <script>
                            document.getElementById('password').addEventListener('input', function() {
                                document.getElementById('password_confirmation').value = this.value;
                            });
                        </script>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn-secondary" id="btn-cancel-modal">Batal</button>
                            <button type="submit" class="btn-primary" style="padding: 10px 18px; border-radius: 8px;">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
