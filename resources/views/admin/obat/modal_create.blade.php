<!-- Modal Tambah Obat -->
<div id="modalTambahObat" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 90%; max-width: 500px; max-height: 80vh; overflow-y: auto;">
        <div style="padding: 20px; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="margin: 0; font-size: 18px; font-weight: 600;">Tambah Obat</h2>
            <button onclick="closeModalTambahObat()" type="button" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #999;">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <div style="padding: 20px;">
            <!-- Tampilkan Error Validasi Jika Ada -->
            @if ($errors->any() && (session('_method') == 'POST' || is_null(session('_method'))))
                <div style="background: #ffebe9; border: 1px solid rgba(255,129,130,0.4); border-radius: 6px; padding: 15px; margin-bottom: 20px;">
                    <h4 style="color: #cf222e; margin-bottom: 5px; font-size: 14px;">Terjadi Kesalahan:</h4>
                    <ul style="color: #cf222e; padding-left: 20px; font-size: 12px; margin: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('obat.store') }}" method="POST" id="formTambahObat">
                @csrf

                <div style="margin-bottom: 15px;">
                    <label for="nama_obat" style="display: block; margin-bottom: 6px; font-weight: 500; font-size: 14px;">Nama Obat *</label>
                    <input type="text" id="nama_obat" name="nama_obat" value="{{ old('nama_obat') }}" 
                        placeholder="Masukkan nama obat" 
                        style="width: 100%; padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                        required>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="tipe_obat" style="display: block; margin-bottom: 6px; font-weight: 500; font-size: 14px;">Tipe Obat *</label>
                    <select id="tipe_obat" name="tipe_obat" 
                        style="width: 100%; padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                        required>
                        <option value="">-- Pilih Tipe Obat --</option>
                        @foreach ($tipeObat as $tipe)
                            <option value="{{ $tipe }}" {{ old('tipe_obat') == $tipe ? 'selected' : '' }}>{{ $tipe }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="stock" style="display: block; margin-bottom: 6px; font-weight: 500; font-size: 14px;">Stok *</label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock') }}" 
                        placeholder="Masukkan jumlah stok" 
                        min="0"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                        required>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="keterangan" style="display: block; margin-bottom: 6px; font-weight: 500; font-size: 14px;">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" 
                        placeholder="Masukkan keterangan obat (opsional)" 
                        rows="3"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 14px; box-sizing: border-box;">{{ old('keterangan') }}</textarea>
                </div>

                <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                    <button type="button" onclick="closeModalTambahObat()" class="btn-outline" style="padding: 8px 16px; border: 1px solid #D1D5DB; background: white; color: #374151; cursor: pointer; border-radius: 6px;">
                        Batal
                    </button>
                    <button type="submit" class="btn-primary" style="padding: 8px 16px; background: #0F766E; color: white; cursor: pointer; border-radius: 6px; border: none;">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModalTambahObat() {
    document.getElementById('modalTambahObat').style.display = 'flex';
    // Reset form
    document.getElementById('formTambahObat').reset();
}

function closeModalTambahObat() {
    document.getElementById('modalTambahObat').style.display = 'none';
}

// Close modal saat klik di luar
document.addEventListener('click', function(event) {
    const modal = document.getElementById('modalTambahObat');
    if (event.target === modal) {
        closeModalTambahObat();
    }
});

// Open modal jika ada error
@if ($errors->any() && (session('_method') == 'POST' || is_null(session('_method'))))
    window.addEventListener('load', function() {
        openModalTambahObat();
    });
@endif
</script>
