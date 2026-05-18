<!-- Modal Restock -->
<div id="modalRestock"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div
        style="background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 90%; max-width: 650px; max-height: 90vh; overflow-y: auto; display: flex; flex-direction: column;">
        <div
            style="padding: 20px; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
            <h2 style="margin: 0; font-size: 18px; font-weight: 600;">Restock: <span id="restockNamaObat"
                    style="color: #0F766E;">-</span></h2>
            <button onclick="closeModalRestock()" type="button"
                style="background: none; border: none; font-size: 24px; cursor: pointer; color: #999;">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <div style="padding: 20px; flex: 1; overflow-y: auto;">
            <form id="formRestock" method="POST">
                @csrf
                <div style="margin-bottom: 15px;">
                    <label for="restock_jumlah"
                        style="display: block; margin-bottom: 6px; font-weight: 500; font-size: 14px;">Jumlah Restock
                        *</label>
                    <input type="number" id="restock_jumlah" name="jumlah" value="1" min="1" max="999999"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                        required>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="restock_keterangan"
                        style="display: block; margin-bottom: 6px; font-weight: 500; font-size: 14px;">Keterangan</label>
                    <textarea id="restock_keterangan" name="keterangan" placeholder="Contoh: Stok awal bulan" rows="2"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 14px; box-sizing: border-box;"></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-bottom: 20px;">
                    <button type="submit" class="btn-primary"
                        style="padding: 8px 16px; background: #0F766E; color: white; cursor: pointer; border-radius: 6px; border: none;">
                        <i class="fa-solid fa-plus"></i> Tambah Stok
                    </button>
                </div>
            </form>

            <hr style="border: 0; border-top: 1px solid #E5E7EB; margin-bottom: 20px;">

            <h3 style="font-size: 16px; margin-bottom: 10px;"><i class="fa-solid fa-clock-rotate-left"></i> 5 Data
                terbaru histori restock</h3>

            <div id="restockHistoriLoading" style="text-align: center; padding: 20px; color: #6B7280; display: none;">
                <i class="fa-solid fa-spinner fa-spin"></i> Memuat histori...
            </div>

            <div id="restockHistoriEmpty" style="text-align: center; padding: 20px; color: #6B7280; display: none;">
                Belum ada histori mutasi stok.
            </div>

            <div style="overflow-x: auto;">
                <table id="tableRestockHistori" class="custom-table"
                    style="width: 100%; display: none; font-size: 13px; text-align: left; border-collapse: collapse;">
                    <thead style="background: #F3F4F6;">
                        <tr>
                            <th style="padding: 10px; border-bottom: 2px solid #E5E7EB;">TANGGAL</th>
                            <th style="padding: 10px; border-bottom: 2px solid #E5E7EB; text-align: center;">TIPE</th>
                            <th style="padding: 10px; border-bottom: 2px solid #E5E7EB; text-align: right;">JUMLAH</th>
                            <th style="padding: 10px; border-bottom: 2px solid #E5E7EB;">KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody id="restockHistoriBody">
                        <!-- Dimasukkan via JS -->
                    </tbody>
                </table>
            </div>

            <div id="btnLihatSemuaHistori" style="display: none; text-align: center; margin-top: 15px;">
                <button type="button" onclick="openModalSemuaHistori()"
                    style="background: none; border: 1px solid #D1D5DB; padding: 6px 12px; border-radius: 4px; cursor: pointer; color: #4B5563; font-size: 13px;">
                    Lihat Semua Histori
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Semua Histori -->
<div id="modalSemuaHistori"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 1001; align-items: center; justify-content: center;">
    <div
        style="background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 90%; max-width: 800px; max-height: 90vh; display: flex; flex-direction: column;">
        <div
            style="padding: 20px; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
            <h2 style="margin: 0; font-size: 18px; font-weight: 600;">Semua Histori Mutasi Stok</h2>
            <button onclick="closeModalSemuaHistori()" type="button"
                style="background: none; border: none; font-size: 24px; cursor: pointer; color: #999;">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div style="padding: 20px; flex: 1; overflow-y: auto;">
            <div style="overflow-x: auto;">
                <table class="custom-table"
                    style="width: 100%; font-size: 13px; text-align: left; border-collapse: collapse;">
                    <thead style="background: #F3F4F6;">
                        <tr>
                            <th style="padding: 10px; border-bottom: 2px solid #E5E7EB;">TANGGAL</th>
                            <th style="padding: 10px; border-bottom: 2px solid #E5E7EB; text-align: center;">TIPE</th>
                            <th style="padding: 10px; border-bottom: 2px solid #E5E7EB; text-align: right;">JUMLAH</th>
                            <th style="padding: 10px; border-bottom: 2px solid #E5E7EB;">KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody id="semuaHistoriBody">
                        <!-- Dimasukkan via JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    let allHistoriData = [];

    async function openModalRestock(id, nama) {
        document.getElementById('modalRestock').style.display = 'flex';
        document.getElementById('restockNamaObat').textContent = nama;

        const form = document.getElementById('formRestock');
        form.action = `/obat/${id}/restock`;
        form.reset();

        const loading = document.getElementById('restockHistoriLoading');
        const empty = document.getElementById('restockHistoriEmpty');
        const table = document.getElementById('tableRestockHistori');
        const tbody = document.getElementById('restockHistoriBody');
        const btnSemua = document.getElementById('btnLihatSemuaHistori');

        loading.style.display = 'block';
        empty.style.display = 'none';
        table.style.display = 'none';
        btnSemua.style.display = 'none';
        tbody.innerHTML = '';
        allHistoriData = [];

        try {
            const response = await fetch(`/obat/${id}/mutasi-stok`);
            const rawData = await response.json();

            // Filter hanya yang tipe "masuk"
            const data = rawData.filter(item => item.tipe === 'masuk');

            allHistoriData = data;
            loading.style.display = 'none';

            if (data.length === 0) {
                empty.style.display = 'block';
            } else {
                table.style.display = 'table';
                const showData = data.slice(0, 5);

                showData.forEach(item => {
                    tbody.appendChild(createHistoriRow(item));
                });

                if (data.length > 5) {
                    btnSemua.style.display = 'block';
                }
            }
        } catch (err) {
            loading.style.display = 'none';
            empty.style.display = 'block';
            empty.innerHTML = '<span style="color:red;">Gagal memuat histori stok.</span>';
        }
    }

    function openModalSemuaHistori() {
        document.getElementById('modalSemuaHistori').style.display = 'flex';
        const tbody = document.getElementById('semuaHistoriBody');
        tbody.innerHTML = '';
        allHistoriData.forEach(item => {
            tbody.appendChild(createHistoriRow(item));
        });
    }

    function closeModalSemuaHistori() {
        document.getElementById('modalSemuaHistori').style.display = 'none';
    }

    function createHistoriRow(item) {
        const tr = document.createElement('tr');

        const d = new Date(item.created_at);
        const tgl = d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + ' ' +
            d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

        const isMasuk = item.tipe === 'masuk';
        const tipeHtml = `<span style="padding: 4px 8px; border-radius: 4px; font-weight: 600; font-size: 11px; background: ${isMasuk ? '#DCFCE7' : '#FEE2E2'}; color: ${isMasuk ? '#166534' : '#991B1B'};">${isMasuk ? 'MASUK' : 'KELUAR'}</span>`;
        const jmlHtml = `<strong style="color: ${isMasuk ? '#16A34A' : '#DC2626'}; font-size: 14px;">${isMasuk ? '+' : '-'}${item.jumlah}</strong>`;

        tr.innerHTML = `
        <td style="padding: 10px; border-bottom: 1px solid #E5E7EB; white-space: nowrap;">${tgl}</td>
        <td style="padding: 10px; border-bottom: 1px solid #E5E7EB; text-align: center;">${tipeHtml}</td>
        <td style="padding: 10px; border-bottom: 1px solid #E5E7EB; text-align: right;">${jmlHtml}</td>
        <td style="padding: 10px; border-bottom: 1px solid #E5E7EB;">${item.keterangan || '-'}</td>
    `;
        return tr;
    }

    function closeModalRestock() {
        document.getElementById('modalRestock').style.display = 'none';
    }

    // Close modal saat klik di luar
    document.addEventListener('click', function (event) {
        const modal = document.getElementById('modalRestock');
        if (event.target === modal) {
            closeModalRestock();
        }
    });
</script>