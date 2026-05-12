<!-- Modal Filter -->
        <div id="modal-filter-lansia" class="modal-overlay">
            <div class="modal-content" style="max-width: 400px;">
                <div class="modal-header">
                    <h2>Filter Data</h2>
                    <button class="btn-close-modal" id="btn-close-filter-modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="#" method="GET">
                        <div class="form-group">
                            <label for="filter_status">Status Risiko</label>
                            <select id="filter_status" name="status">
                                <option value="">Semua Status</option>
                                <option value="NORMAL">Normal</option>
                                <option value="RESIKO TINGGI">Resiko Tinggi</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="filter_umur">Kategori Umur</label>
                            <select id="filter_umur" name="umur">
                                <option value="">Semua Umur</option>
                                <option value="60-69">60 - 69 Tahun</option>
                                <option value="70-79">70 - 79 Tahun</option>
                                <option value="80+">80+ Tahun</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn-secondary" style="background: transparent;">Reset</button>
                            <button type="submit" class="btn-primary" style="padding: 10px 18px; border-radius: 8px;">Terapkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>