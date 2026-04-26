<!-- Modal Edit Obat -->
<div id="modalEditObat" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 90%; max-width: 500px; max-height: 80vh; overflow-y: auto;">
        <div style="padding: 20px; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="margin: 0; font-size: 18px; font-weight: 600;">Edit Obat</h2>
            <button onclick="closeModalEditObat()" type="button" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #999;">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <div style="padding: 20px;">
            <!-- Tampilkan Error Validasi Jika Ada -->
            @if ($errors->any() && session('_method') == 'PUT')
                <div style="background: #ffebe9; border: 1px solid rgba(255,129,130,0.4); border-radius: 6px; padding: 15px; margin-bottom: 20px;">
                    <h4 style="color: #cf222e; margin-bottom: 5px; font-size: 14px;">Terjadi Kesalahan:</h4>
                    <ul style="color: #cf222e; padding-left: 20px; font-size: 12px; margin: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="" method="POST" id="formEditObat">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 15px;">
                    <label for="edit_nama_obat" style="display: block; margin-bottom: 6px; font-weight: 500; font-size: 14px;">Nama Obat *</label>
                    <input type="text" id="edit_nama_obat" name="nama_obat" 
                        placeholder="Masukkan nama obat" 
                        style="width: 100%; padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                        required>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="edit_tipe_obat" style="display: block; margin-bottom: 6px; font-weight: 500; font-size: 14px;">Tipe Obat *</label>
                    <select id="edit_tipe_obat" name="tipe_obat" 
                        style="width: 100%; padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                        required>
                        <option value="">-- Pilih Tipe Obat --</option>
                        @foreach ($tipeObat as $tipe)
                            <option value="{{ $tipe }}">{{ $tipe }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="edit_stock" style="display: block; margin-bottom: 6px; font-weight: 500; font-size: 14px;">Stok *</label>
                    <input type="number" id="edit_stock" name="stock" 
                        placeholder="Masukkan jumlah stok" 
                        min="0"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                        required>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="edit_keterangan" style="display: block; margin-bottom: 6px; font-weight: 500; font-size: 14px;">Keterangan</label>
                    <textarea id="edit_keterangan" name="keterangan" 
                        placeholder="Masukkan keterangan obat (opsional)" 
                        rows="3"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 14px; box-sizing: border-box;"></textarea>
                </div>

                <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                    <button type="button" onclick="closeModalEditObat()" class="btn-outline" style="padding: 8px 16px; border: 1px solid #D1D5DB; background: white; color: #374151; cursor: pointer; border-radius: 6px;">
                        Batal
                    </button>
                    <button type="submit" class="btn-primary" style="padding: 8px 16px; background: #0F766E; color: white; cursor: pointer; border-radius: 6px; border: none;">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModalEditObat(obatId, namaObat, tipeObat, stock, keterangan) {
    document.getElementById('modalEditObat').style.display = 'flex';
    document.getElementById('edit_nama_obat').value = namaObat;
    document.getElementById('edit_tipe_obat').value = tipeObat;
    document.getElementById('edit_stock').value = stock;
    document.getElementById('edit_keterangan').value = keterangan || '';
    
    // Set form action ke route update
    document.getElementById('formEditObat').action = '/obat/' + obatId;
}

function closeModalEditObat() {
    document.getElementById('modalEditObat').style.display = 'none';
}

// Close modal saat klik di luar
document.addEventListener('click', function(event) {
    const modal = document.getElementById('modalEditObat');
    if (event.target === modal) {
        closeModalEditObat();
    }
});

// Open modal jika ada error PUT
@if ($errors->any() && session('_method') == 'PUT')
    window.addEventListener('load', function() {
        // Ambil dari session atau dari input tersembunyi
        const obatId = document.querySelector('[name="obat_id_edit"]')?.value || '';
        if (obatId) openModalEditObat(obatId, '', '', '', '');
        document.getElementById('modalEditObat').style.display = 'flex';
    });
@endif
</script>
