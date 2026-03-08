<div class="main-content">
    <header class="page-header">
        <div>
            <h1 class="page-title">Skrining Utama Lansia</h1>
            <p class="page-subtitle">Lengkapi formulir pemeriksaan berkala untuk deteksi dini risiko PTM.</p>
        </div>
        <button class="btn-primary" form="form-skrining">
            <img src="img/icon-save.svg" alt=""> Simpan Skrining
        </button>
    </header>

    <form id="form-skrining" action="#" method="POST">
        <div class="form-grid">

            <section class="form-card card">
                <div class="card-header">
                    <span class="step-number">01</span>
                    <h4>Faktor Risiko Perilaku</h4>
                </div>
                <div class="card-body">
                    <div class="input-group-row">
                        <div class="input-item">
                            <label>Apakah Merokok?</label>
                            <div class="radio-toggle">
                                <input type="radio" name="merokok" value="1" id="r-ya">
                                <label for="r-ya">Ya</label>
                                <input type="radio" name="merokok" value="0" id="r-tidak">
                                <label for="r-tidak">Tidak</label>
                            </div>
                        </div>
                        <div class="input-item hidden" id="kategori-rokok">
                            <label>Kategori Merokok</label>
                            <select name="merokok_kategori">
                                <option value="">Pilih Kategori...</option>
                                <option value="1">20-30 bungkus/tahun</option>
                                <option value="2">>30 bungkus/tahun</option>
                            </select>
                        </div>
                    </div>

                    <div class="input-grid-3">
                        <div class="input-item">
                            <label>Konsumsi Gula (>4 sdm/hari)</label>
                            <select name="konsumsi_gula">
                                <option value="1">Ya</option>
                                <option value="2">Tidak</option>
                                <option value="3">Tidak Setiap Hari</option>
                            </select>
                        </div>
                        <div class="input-item">
                            <label>Konsumsi Garam (>1 cth/hari)</label>
                            <select name="konsumsi_garam">
                                <option value="1">Ya</option>
                                <option value="2">Tidak</option>
                                <option value="3">Tidak Setiap Hari</option>
                            </select>
                        </div>
                        <div class="input-item">
                            <label>Aktivitas Fisik (≥150 mnt/mgg)</label>
                            <select name="aktivitas_fisik">
                                <option value="1">Ya (Cukup)</option>
                                <option value="2">Tidak</option>
                                <option value="3">Tidak Setiap Hari</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <section class="form-card card">
                <div class="card-header">
                    <span class="step-number">02</span>
                    <h4>Pengukuran Fisik</h4>
                </div>
                <div class="card-body">
                    <div class="input-grid-4">
                        <div class="input-item">
                            <label>Tinggi Badan (cm)</label>
                            <input type="number" step="0.1" name="tinggi_badan" placeholder="00.0">
                        </div>
                        <div class="input-item">
                            <label>Berat Badan (kg)</label>
                            <input type="number" step="0.1" name="berat_badan" placeholder="00.0">
                        </div>
                        <div class="input-item">
                            <label>IMT (Otomatis)</label>
                            <input type="text" name="imt" readonly class="bg-light" placeholder="0.00">
                        </div>
                        <div class="input-item">
                            <label>Lingkar Perut (cm)</label>
                            <input type="number" step="0.1" name="lingkar_perut" placeholder="00.0">
                        </div>
                    </div>
                    <div class="input-grid-2 mt-20">
                        <div class="input-item">
                            <label>Tekanan Darah Sistolik (mmHg)</label>
                            <input type="number" name="td_sistolik" placeholder="120">
                        </div>
                        <div class="input-item">
                            <label>Tekanan Darah Diastolik (mmHg)</label>
                            <input type="number" name="td_diastolik" placeholder="80">
                        </div>
                    </div>
                </div>
            </section>

            <section class="form-card card">
                <div class="card-header">
                    <span class="step-number">03</span>
                    <h4>Laboratorium & Khusus</h4>
                </div>
                <div class="card-body">
                    <div class="input-grid-2">
                        <div class="input-item">
                            <label>Gula Darah (mg/dL)</label>
                            <input type="number" name="gula_darah" placeholder="100">
                        </div>
                        <div class="input-item">
                            <label>Kolesterol (mg/dL)</label>
                            <input type="number" name="kolesterol" placeholder="150">
                        </div>
                    </div>
                    <div class="input-item mt-20">
                        <label>Hasil IVA / Sadanis (Khusus Perempuan)</label>
                        <select name="iva_sadanis">
                            <option value="">N/A (Laki-laki)</option>
                            <option value="1">Positif / Dilakukan</option>
                            <option value="0">Negatif / Tidak Dilakukan</option>
                        </select>
                    </div>
                </div>
            </section>

            <section class="form-card card full-width">
                <div class="card-header">
                    <span class="step-number">04</span>
                    <h4>Skrining Jiwa (SRQ-20)</h4>
                    <div class="skor-badge">Skor: <span id="total-srq">0</span>/20</div>
                </div>
                <div class="card-body">
                    <p class="instruction">Tanyakan pertanyaan berikut kepada lansia dalam 30 hari terakhir:</p>
                    <div class="srq-grid">
                        <div class="srq-item">
                            <p>1. Apakah Anda sering sakit kepala?</p>
                            <div class="srq-options">
                                <label><input type="radio" name="srq_1" value="1"> Ya</label>
                                <label><input type="radio" name="srq_1" value="0"> Tidak</label>
                            </div>
                        </div>
                        <div class="srq-item">
                            <p>2. Apakah Anda tidak nafsu makan?</p>
                            <div class="srq-options">
                                <label><input type="radio" name="srq_2" value="1"> Ya</label>
                                <label><input type="radio" name="srq_2" value="0"> Tidak</label>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </form>
</div>