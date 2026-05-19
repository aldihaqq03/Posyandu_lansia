let allHistoriData = [];

function getConfigElement() {
    return document.getElementById("obat-page-config");
}

function getModalElement(id) {
    return document.getElementById(id);
}

function closeOnOverlayClick(modalId, closeHandler) {
    document.addEventListener("click", function (event) {
        const modal = getModalElement(modalId);
        if (modal && event.target === modal) {
            closeHandler();
        }
    });
}

window.openModalTambahObat = function (options = {}) {
    const modal = getModalElement("modalTambahObat");
    const form = document.getElementById("formTambahObat");

    if (!modal || !form) {
        return;
    }

    modal.style.display = "flex";

    if (options.resetForm !== false) {
        form.reset();
    }
};

window.closeModalTambahObat = function () {
    const modal = getModalElement("modalTambahObat");
    if (modal) {
        modal.style.display = "none";
    }
};

window.openModalEditObat = function (obatId, namaObat, tipeObat, keterangan) {
    const modal = getModalElement("modalEditObat");
    const form = document.getElementById("formEditObat");
    const hiddenId = document.getElementById("edit_obat_id");
    const nameInput = document.getElementById("edit_nama_obat");
    const typeInput = document.getElementById("edit_tipe_obat");
    const noteInput = document.getElementById("edit_keterangan");

    if (
        !modal ||
        !form ||
        !hiddenId ||
        !nameInput ||
        !typeInput ||
        !noteInput
    ) {
        return;
    }

    modal.style.display = "flex";
    form.action = `/obat/${obatId}`;
    hiddenId.value = obatId || "";

    if (namaObat !== undefined) {
        nameInput.value = namaObat;
    }

    if (tipeObat !== undefined) {
        typeInput.value = tipeObat;
    }

    if (keterangan !== undefined) {
        noteInput.value = keterangan || "";
    }
};

window.closeModalEditObat = function () {
    const modal = getModalElement("modalEditObat");
    if (modal) {
        modal.style.display = "none";
    }
};

window.openModalRestock = async function (id, nama) {
    const modal = getModalElement("modalRestock");
    const nameLabel = document.getElementById("restockNamaObat");
    const form = document.getElementById("formRestock");
    const loading = document.getElementById("restockHistoriLoading");
    const empty = document.getElementById("restockHistoriEmpty");
    const table = document.getElementById("tableRestockHistori");
    const tbody = document.getElementById("restockHistoriBody");
    const btnSemua = document.getElementById("btnLihatSemuaHistori");

    if (
        !modal ||
        !nameLabel ||
        !form ||
        !loading ||
        !empty ||
        !table ||
        !tbody ||
        !btnSemua
    ) {
        return;
    }

    modal.style.display = "flex";
    nameLabel.textContent = nama;
    form.action = `/obat/${id}/restock`;
    form.reset();

    loading.style.display = "block";
    empty.style.display = "none";
    table.style.display = "none";
    btnSemua.style.display = "none";
    tbody.innerHTML = "";
    allHistoriData = [];

    try {
        const response = await fetch(`/obat/${id}/mutasi-stok`);
        const rawData = await response.json();
        const data = rawData.filter((item) => item.tipe === "masuk");

        allHistoriData = data;
        loading.style.display = "none";

        if (data.length === 0) {
            empty.style.display = "block";
            return;
        }

        table.style.display = "table";

        data.slice(0, 5).forEach((item) => {
            tbody.appendChild(createHistoriRow(item));
        });

        if (data.length > 5) {
            btnSemua.style.display = "block";
        }
    } catch (error) {
        loading.style.display = "none";
        empty.style.display = "block";
        empty.innerHTML =
            '<span style="color:red;">Gagal memuat histori stok.</span>';
    }
};

window.openModalSemuaHistori = function () {
    const modal = getModalElement("modalSemuaHistori");
    const tbody = document.getElementById("semuaHistoriBody");

    if (!modal || !tbody) {
        return;
    }

    modal.style.display = "flex";
    tbody.innerHTML = "";

    allHistoriData.forEach((item) => {
        tbody.appendChild(createHistoriRow(item));
    });
};

window.closeModalSemuaHistori = function () {
    const modal = getModalElement("modalSemuaHistori");
    if (modal) {
        modal.style.display = "none";
    }
};

window.closeModalRestock = function () {
    const modal = getModalElement("modalRestock");
    if (modal) {
        modal.style.display = "none";
    }
};

function createHistoriRow(item) {
    const tr = document.createElement("tr");
    const d = new Date(item.created_at);
    const tgl = `${d.toLocaleDateString("id-ID", { day: "2-digit", month: "short", year: "numeric" })} ${d.toLocaleTimeString("id-ID", { hour: "2-digit", minute: "2-digit" })}`;
    const isMasuk = item.tipe === "masuk";
    const tipeHtml = `<span style="padding: 4px 8px; border-radius: 4px; font-weight: 600; font-size: 11px; background: ${isMasuk ? "#DCFCE7" : "#FEE2E2"}; color: ${isMasuk ? "#166534" : "#991B1B"};">${isMasuk ? "MASUK" : "KELUAR"}</span>`;
    const jmlHtml = `<strong style="color: ${isMasuk ? "#16A34A" : "#DC2626"}; font-size: 14px;">${isMasuk ? "+" : "-"}${item.jumlah}</strong>`;

    tr.innerHTML = `
        <td style="padding: 10px; border-bottom: 1px solid #E5E7EB; white-space: nowrap;">${tgl}</td>
        <td style="padding: 10px; border-bottom: 1px solid #E5E7EB; text-align: center;">${tipeHtml}</td>
        <td style="padding: 10px; border-bottom: 1px solid #E5E7EB; text-align: right;">${jmlHtml}</td>
        <td style="padding: 10px; border-bottom: 1px solid #E5E7EB;">${item.keterangan || "-"}</td>
    `;

    return tr;
}

function initObatPage() {
    const searchInput = document.getElementById("main-search");
    const config = getConfigElement();

    if (searchInput) {
        searchInput.addEventListener("keyup", function () {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll(
                ".custom-table tbody tr",
            );

            tableRows.forEach((row) => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? "" : "none";
            });
        });
    }

    closeOnOverlayClick("modalTambahObat", window.closeModalTambahObat);
    closeOnOverlayClick("modalEditObat", window.closeModalEditObat);
    closeOnOverlayClick("modalRestock", window.closeModalRestock);
    closeOnOverlayClick("modalSemuaHistori", window.closeModalSemuaHistori);

    if (!config) {
        return;
    }

    if (config.dataset.openCreate === "1") {
        window.openModalTambahObat({ resetForm: false });
    }

    if (config.dataset.openEdit === "1") {
        window.openModalEditObat(config.dataset.editId || "");
    }
}

document.addEventListener("DOMContentLoaded", initObatPage);
