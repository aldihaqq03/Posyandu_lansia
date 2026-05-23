<div class="modal-overlay" id="modalDetailJadwal">
    <div class="modal modal-detail-jadwal">
        <div class="modal-header">
            <div>
                <div class="modal-title">Detail Jadwal Posyandu</div>
                <div class="modal-sub">Ringkasan lengkap jadwal kegiatan dan skrining</div>
            </div>
            <button class="btn-close" id="btn-close-modal-detail" type="button" aria-label="Tutup detail">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="modal-body">
            <div class="detail-grid">
                <div class="detail-field full-width">
                    <span class="detail-label">Tema</span>
                    <span class="detail-value" id="detail-tema">-</span>
                </div>

                <div class="detail-field">
                    <span class="detail-label">Tanggal Pelaksanaan</span>
                    <span class="detail-value" id="detail-tanggal">-</span>
                </div>

                <div class="detail-field">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        <span class="badge" id="detail-status-badge">-</span>
                    </span>
                </div>

                <div class="detail-field full-width">
                    <span class="detail-label">Lokasi</span>
                    <span class="detail-value" id="detail-lokasi">-</span>
                </div>
            </div>

            <div class="modal-section-label">Jenis Skrining</div>
            <div class="detail-tags" id="detail-skrining-tags"></div>

            <div class="modal-section-label">Daftar Kegiatan</div>
            <div class="detail-kegiatan" id="detail-kegiatan-list"></div>

            <div class="modal-section-label">Catatan</div>
            <p class="detail-catatan" id="detail-catatan">-</p>
        </div>

        <div class="modal-footer">
            <button class="btn-primary" type="button" id="btn-close-modal-detail-footer">
                Tutup
            </button>
        </div>
    </div>
</div>
