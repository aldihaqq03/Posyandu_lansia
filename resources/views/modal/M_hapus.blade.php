 <!-- Modal Konfirmasi Hapus -->
        <div id="modal-hapus-lansia" class="modal-overlay">
            <div class="modal-content" style="max-width: 400px; text-align: center;">
                <form id="form-hapus-lansia" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body" style="padding: 20px 0;">
                        <div style="font-size: 50px; color: var(--danger); margin-bottom: 15px;">
                            <i class="fa-solid fa-circle-exclamation"></i>
                        </div>
                        <h2 style="font-size: 20px; color: var(--text-main); font-weight: 800; margin-bottom: 10px;">Hapus Data Lansia?</h2>
                        <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 25px;">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
                        <div style="display: flex; gap: 10px; justify-content: center;">
                            <button type="button" class="btn-secondary" id="btn-cancel-hapus" style="flex: 1;">Batal</button>
                            <button type="submit" class="btn-primary" style="flex: 1; background: var(--danger);">Ya, Hapus</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>