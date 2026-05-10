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
                        <small id="error-nik" style="color: #e74c3c; font-size: 12px; display: none; margin-top: 4px;"></small>
                    </div>
                    <div style="flex: 1;">
                        <label for="nama_lansia">Nama Lengkap</label>
                        <input type="text" id="nama_lansia" name="nama_lansia" placeholder="Masukkan Nama Lengkap"
                            required>
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
                <div class="form-group">
                    <label for="riwayat_penyakit">Riwayat Penyakit</label>
                    <input type="text" id="riwayat_penyakit" name="riwayat_penyakit"
                        placeholder="Contoh: Hipertensi, Diabetes">
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="2" placeholder="Masukkan Alamat Lengkap"
                        required></textarea>
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
                <h3 style="margin-top: 20px; margin-bottom: 15px; color: #333;">Informasi Keluarga (Opsional)</h3>

                <div id="keluarga-container">
                    <div class="keluarga-item" style="padding: 15px; background-color: #f9f9f9; border-radius: 8px; margin-bottom: 15px; border: 1px solid #e0e0e0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <h4 style="margin: 0; color: #555;">Anggota Keluarga #1</h4>
                            <button type="button" class="btn-remove-keluarga" style="background: none; border: none; color: #e74c3c; cursor: pointer; font-size: 18px; padding: 0;">✕</button>
                        </div>
                        <div class="form-group">
                            <label for="nama_keluarga_1">Nama Keluarga</label>
                            <input type="text" class="nama_keluarga_input" name="keluarga[0][nama_keluarga]" placeholder="Masukkan nama anggota keluarga">
                        </div>
                        <div class="form-group" style="display: flex; gap: 10px;">
                            <div style="flex: 1;">
                                <label for="no_sama_1">No Telepon (Opsional)</label>
                                <input type="text" class="no_sama_input" name="keluarga[0][no_sama]" placeholder="Contoh: 081234567890">
                            </div>
                            <div style="flex: 1;">
                                <label for="alamat_kluarga_1">Alamat (Opsional)</label>
                                <input type="text" class="alamat_keluarga_input" name="keluarga[0][alamat]" placeholder="Alamat anggota keluarga">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" id="btn-tambah-keluarga" class="btn-secondary" style="margin-bottom: 15px;">+ Tambah Anggota Keluarga</button>

                <!-- Password confirmation needed silently for validation -->
                <input type="hidden" id="password_confirmation" name="password_confirmation">

                <script>
                    let keluargaCount = 1;

                    document.getElementById('btn-tambah-keluarga').addEventListener('click', function(e) {
                        e.preventDefault();
                        const container = document.getElementById('keluarga-container');
                        keluargaCount++;
                        
                        const newItem = document.createElement('div');
                        newItem.className = 'keluarga-item';
                        newItem.style.cssText = 'padding: 15px; background-color: #f9f9f9; border-radius: 8px; margin-bottom: 15px; border: 1px solid #e0e0e0;';
                        newItem.innerHTML = `
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <h4 style="margin: 0; color: #555;">Anggota Keluarga #${keluargaCount}</h4>
                                <button type="button" class="btn-remove-keluarga" style="background: none; border: none; color: #e74c3c; cursor: pointer; font-size: 18px; padding: 0;">✕</button>
                            </div>
                            <div class="form-group">
                                <label for="nama_keluarga_${keluargaCount}">Nama Keluarga</label>
                                <input type="text" class="nama_keluarga_input" name="keluarga[${keluargaCount - 1}][nama_keluarga]" placeholder="Masukkan nama anggota keluarga">
                            </div>
                            <div class="form-group" style="display: flex; gap: 10px;">
                                <div style="flex: 1;">
                                    <label for="no_sama_${keluargaCount}">No Telepon (Opsional)</label>
                                    <input type="text" class="no_sama_input" name="keluarga[${keluargaCount - 1}][no_sama]" placeholder="Contoh: 081234567890">
                                </div>
                                <div style="flex: 1;">
                                    <label for="alamat_keluarga_${keluargaCount}">Alamat (Opsional)</label>
                                    <input type="text" class="alamat_keluarga_input" name="keluarga[${keluargaCount - 1}][alamat]" placeholder="Alamat anggota keluarga">
                                </div>
                            </div>
                        `;
                        
                        container.appendChild(newItem);
                        attachRemoveButtonListener(newItem.querySelector('.btn-remove-keluarga'));
                    });

                    function attachRemoveButtonListener(button) {
                        button.addEventListener('click', function(e) {
                            e.preventDefault();
                            this.closest('.keluarga-item').remove();
                        });
                    }

                    document.querySelectorAll('.btn-remove-keluarga').forEach(btn => {
                        attachRemoveButtonListener(btn);
                    });

                    // VALIDASI FORM - Real-time dengan Inline Error Messages
                    const form = document.querySelector('form');
                    const submitBtn = form.querySelector('button[type="submit"]');
                    
                    // Validasi function untuk setiap field
                    function validateNIK(value) {
                        if (!value) return 'NIK tidak boleh kosong';
                        if (!/^\d{16}$/.test(value.trim())) return 'NIK harus 16 digit angka';
                        return '';
                    }
                    
                    function validateNamaLansia(value) {
                        if (!value) return 'Nama Lansia tidak boleh kosong';
                        if (value.trim().length < 3) return 'Nama Lansia minimal 3 karakter';
                        return '';
                    }
                    
                    function validateTanggalLahir(value) {
                        if (!value) return 'Tanggal Lahir tidak boleh kosong';
                        const birthDate = new Date(value);
                        const today = new Date('2026-05-04');
                        let age = today.getFullYear() - birthDate.getFullYear();
                        const monthDiff = today.getMonth() - birthDate.getMonth();
                        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                            age--;
                        }
                        if (age < 40) {
                            return `Umur harus minimal 40 tahun (Saat ini umur Anda ${age} tahun)`;
                        }
                        return '';
                    }
                    
                    function validateNoHP(value) {
                        if (!value) return '';
                        if (!/^(\+62|0)[0-9]{9,12}$/.test(value.replace(/\D/g, '0'))) {
                            return 'Format No HP tidak valid (Contoh: 081234567890)';
                        }
                        return '';
                    }
                    
                    function validateEmail(value) {
                        if (!value) return '';
                        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                            return 'Format Email tidak valid';
                        }
                        return '';
                    }
                    
                    function validateAlamat(value) {
                        if (!value) return 'Alamat Lansia tidak boleh kosong';
                        return '';
                    }
                    
                    // Function untuk show/hide error
                    function showError(fieldId, message) {
                        const errorEl = document.getElementById(`error-${fieldId}`);
                        const inputEl = document.getElementById(fieldId);
                        if (errorEl) {
                            if (message) {
                                errorEl.textContent = message;
                                errorEl.style.display = 'block';
                                if (inputEl) {
                                    inputEl.style.borderColor = '#e74c3c';
                                    inputEl.style.borderWidth = '2px';
                                }
                            } else {
                                errorEl.style.display = 'none';
                                if (inputEl) {
                                    inputEl.style.borderColor = '';
                                    inputEl.style.borderWidth = '';
                                }
                            }
                        }
                    }
                    
                    // Function untuk check apakah form valid
                    function isFormValid() {
                        const nikError = validateNIK(document.getElementById('nik').value);
                        const namaError = validateNamaLansia(document.getElementById('nama_lansia').value);
                        const tanggalError = validateTanggalLahir(document.getElementById('tanggal_lahir').value);
                        const hpError = validateNoHP(document.getElementById('no_hp').value);
                        const emailError = validateEmail(document.getElementById('email').value);
                        const alamatError = validateAlamat(document.getElementById('alamat').value);
                        
                        return !nikError && !namaError && !tanggalError && !hpError && !emailError && !alamatError;
                    }
                    
                    // Real-time validation on input
                    document.getElementById('nik').addEventListener('blur', function() {
                        const error = validateNIK(this.value);
                        showError('nik', error);
                        submitBtn.disabled = !isFormValid();
                    });
                    
                    document.getElementById('nama_lansia').addEventListener('blur', function() {
                        const error = validateNamaLansia(this.value);
                        showError('nama_lansia', error);
                        submitBtn.disabled = !isFormValid();
                    });
                    
                    document.getElementById('tanggal_lahir').addEventListener('change', function() {
                        const error = validateTanggalLahir(this.value);
                        showError('tanggal_lahir', error);
                        submitBtn.disabled = !isFormValid();
                    });
                    
                    document.getElementById('no_hp').addEventListener('blur', function() {
                        const error = validateNoHP(this.value);
                        showError('no_hp', error);
                        submitBtn.disabled = !isFormValid();
                    });
                    
                    document.getElementById('email').addEventListener('blur', function() {
                        const error = validateEmail(this.value);
                        showError('email', error);
                        submitBtn.disabled = !isFormValid();
                    });
                    
                    document.getElementById('alamat').addEventListener('blur', function() {
                        const error = validateAlamat(this.value);
                        showError('alamat', error);
                        submitBtn.disabled = !isFormValid();
                    });
                    
                    // Submit handler - final check
                    form.addEventListener('submit', function(e) {
                        if (!isFormValid()) {
                            e.preventDefault();
                            // Re-validate all fields to show errors
                            showError('nik', validateNIK(document.getElementById('nik').value));
                            showError('nama_lansia', validateNamaLansia(document.getElementById('nama_lansia').value));
                            showError('tanggal_lahir', validateTanggalLahir(document.getElementById('tanggal_lahir').value));
                            showError('no_hp', validateNoHP(document.getElementById('no_hp').value));
                            showError('email', validateEmail(document.getElementById('email').value));
                            showError('alamat', validateAlamat(document.getElementById('alamat').value));
                        }
                    });
                    
                    // Initial: disable submit button
                    submitBtn.disabled = true;
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