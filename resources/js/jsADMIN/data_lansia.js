/* resources/js/jsAdmin/data_lansia.js */
document.addEventListener("DOMContentLoaded", function () {
    // ─────────────────────────────────────────────────────────────
    // 1. Animasi Angka Statistik
    // ─────────────────────────────────────────────────────────────
    document.querySelectorAll(".stat-number").forEach((el) => {
        const target = parseInt(el.innerText.replace(/\D/g, ""));
        if (isNaN(target) || target === 0) return;
        let count = 0;
        const step = target / (1000 / 16);
        const tick = () => {
            count = Math.min(count + step, target);
            el.innerText = Math.ceil(count);
            if (count < target) requestAnimationFrame(tick);
        };
        tick();
    });

    // ─────────────────────────────────────────────────────────────
    // 2. Pencarian Real-time
    // ─────────────────────────────────────────────────────────────
    const searchInput = document.getElementById("main-search");
    const tableRows = document.querySelectorAll(".custom-table tbody tr");

    searchInput?.addEventListener("input", function () {
        const q = this.value.toLowerCase();
        tableRows.forEach((row) => {
            row.style.display = row.innerText.toLowerCase().includes(q)
                ? ""
                : "none";
        });
    });

    // ─────────────────────────────────────────────────────────────
    // 3. Klik Baris → Tampilkan Detail Panel
    // ─────────────────────────────────────────────────────────────
    const detailPanel = document.getElementById("detail-panel");

    document.querySelectorAll(".selectable-row").forEach((row) => {
        row.addEventListener("click", function () {
            document
                .querySelectorAll(".selectable-row")
                .forEach((r) => r.classList.remove("row-selected"));
            this.classList.add("row-selected");

            const id = this.dataset.id;
            const nama = this.dataset.nama || "-";
            const nik = this.dataset.nik || "-";
            const umur = this.dataset.umur || "-";
            const hp = this.dataset.noHp || "-";
            const alamat = this.dataset.alamat || "-";
            const jk =
                this.dataset.jenisKelamin === "L" ? "Laki-laki" : "Perempuan";
            const riwayat = this.dataset.riwayatPenyakit || "-";

            // Tampilkan panel
            detailPanel.style.opacity = "0";
            detailPanel.style.display = "block";

            // Avatar initials
            const initials = nama
                .split(" ")
                .map((w) => w[0])
                .slice(0, 2)
                .join("")
                .toUpperCase();
            const avatarEl = document.getElementById("detail-avatar");
            if (avatarEl) avatarEl.textContent = initials;

            setText("dynamic-name", nama);
            setText("name-display", nama);
            setText("detail-umur", umur + " Tahun");
            setText("detail-jk", jk);
            setText("d-nik", nik);
            setText("d-hp", hp);
            setText("d-alamat", alamat);
            setText("d-riwayat", riwayat);

            // Link Histori Skrining
            const btnHistori = document.getElementById("btn-histori-skrining");
            if (btnHistori && id) {
                btnHistori.href = `/lansia/${id}/histori-skrining`;
            }

            // Ambil data kesehatan via AJAX
            fetchHealthSummary(id);

            requestAnimationFrame(() => {
                detailPanel.style.transition = "opacity 0.3s ease";
                detailPanel.style.opacity = "1";
            });

            detailPanel.scrollIntoView({ behavior: "smooth", block: "start" });
        });
    });

    function setText(id, val) {
        const el = document.getElementById(id);
        if (el) el.innerText = val;
    }

    /**
     * Ambil data kesehatan terakhir dari endpoint:
     *   GET /lansia/{id}/health-summary
     *
     * Field yang dikembalikan controller:
     *   sistolik, diastolik  → dari skrining_kunjungan.td_sistolik / td_diastolik
     *   gula_darah           → dari skrining_utama.gula_darah
     *   kolesterol           → dari skrining_utama.kolesterol
     */
    async function fetchHealthSummary(id) {
        ["d-sistolik", "d-diastolik", "d-gula", "d-kolesterol"].forEach((k) =>
            setText(k, "…"),
        );
        try {
            const res = await fetch(`/lansia/${id}/health-summary`, {
                headers: { "X-Requested-With": "XMLHttpRequest" },
            });
            if (!res.ok) throw new Error("HTTP " + res.status);
            const data = await res.json();
            setText("d-sistolik", data.sistolik ?? "-");
            setText("d-diastolik", data.diastolik ?? "-");
            setText("d-gula", data.gula_darah ?? "-");
            setText("d-kolesterol", data.kolesterol ?? "-");
        } catch {
            ["d-sistolik", "d-diastolik", "d-gula", "d-kolesterol"].forEach(
                (k) => setText(k, "-"),
            );
        }
    }

    // Cegah klik Histori Skrining jika belum ada lansia dipilih
    document
        .getElementById("btn-histori-skrining")
        ?.addEventListener("click", function (e) {
            if (this.getAttribute("href") === "#") {
                e.preventDefault();
                alert("Silakan pilih lansia dari tabel terlebih dahulu.");
            }
        });

    // ─────────────────────────────────────────────────────────────
    // 4. Modal Tambah Lansia
    // ─────────────────────────────────────────────────────────────
    const modalTambah = document.getElementById("modal-tambah-lansia");
    const formTambah = modalTambah?.querySelector("form");

    document
        .getElementById("btn-tambah-lansia")
        ?.addEventListener("click", () => {
            formTambah?.reset();
            modalTambah?.classList.add("active");
        });

    setupModalClose(modalTambah, [
        document.getElementById("btn-close-modal"),
        document.getElementById("btn-cancel-modal"),
    ]);

    // ─────────────────────────────────────────────────────────────
    // 5. Modal Edit Lansia
    // ─────────────────────────────────────────────────────────────
    const modalEdit = document.getElementById("modal-edit-lansia");
    const formEdit = document.getElementById("form-edit-lansia");

    document.querySelectorAll(".edit-btn").forEach((btn) => {
        btn.addEventListener("click", () => {
            const row = btn.closest("tr");
            if (!row) return;

            if (formEdit) formEdit.action = `/lansia/${row.dataset.id}`;

            setVal("edit_nama_lansia", row.dataset.nama);
            setVal("edit_nik", row.dataset.nik);
            setVal("edit_alamat", row.dataset.alamat);
            setVal("edit_tanggal_lahir", row.dataset.tanggalLahir);
            setVal("edit_jenis_kelamin", row.dataset.jenisKelamin || "L");
            setVal("edit_no_hp", row.dataset.noHp);
            setVal("edit_tempat_lahir", row.dataset.tempatLahir);
            setVal("edit_status_perkawinan", row.dataset.statusPerkawinan);
            setVal("edit_riwayat_penyakit", row.dataset.riwayatPenyakit);
            setVal("edit_tanggal_daftar", row.dataset.tanggalDaftar);
            setVal("edit_keterangan", row.dataset.keterangan);
            setVal("edit_email", row.dataset.email);

            modalEdit?.classList.add("active");
        });
    });

    setupModalClose(modalEdit, [
        document.getElementById("btn-close-edit-modal"),
        document.getElementById("btn-cancel-edit-modal"),
    ]);

    // ─────────────────────────────────────────────────────────────
    // 6. Modal Hapus Lansia
    // ─────────────────────────────────────────────────────────────
    const modalHapus = document.getElementById("modal-hapus-lansia");
    const formHapus = modalHapus?.querySelector("form");
    let rowToDelete = null;

    document.querySelectorAll(".delete-btn").forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            rowToDelete = btn.closest("tr");
            if (rowToDelete && formHapus) {
                formHapus.action = `/lansia/${rowToDelete.dataset.id}`;
            }
            modalHapus?.classList.add("active");
        });
    });

    document
        .getElementById("btn-confirm-hapus")
        ?.addEventListener("click", () => {
            if (rowToDelete) {
                rowToDelete.style.transition = "opacity 0.3s ease";
                rowToDelete.style.opacity = "0";
                setTimeout(() => rowToDelete?.remove(), 300);
            }
            modalHapus?.classList.remove("active");
            rowToDelete = null;
        });

    setupModalClose(modalHapus, [document.getElementById("btn-cancel-hapus")]);

    // ─────────────────────────────────────────────────────────────
    // 7. Modal Filter
    // ─────────────────────────────────────────────────────────────
    const modalFilter = document.getElementById("modal-filter-lansia");
    const formFilter = modalFilter?.querySelector("form");

    document
        .getElementById("btn-filter-lansia")
        ?.addEventListener("click", () => {
            modalFilter?.classList.add("active");
        });

    setupModalClose(modalFilter, [
        document.getElementById("btn-close-filter-modal"),
    ]);

    formFilter?.addEventListener("submit", (e) => {
        e.preventDefault();
        const status = document.getElementById("filter_status")?.value;
        const umur = document.getElementById("filter_umur")?.value;
        alert(`Filter: Status=${status || "Semua"}, Umur=${umur || "Semua"}`);
        modalFilter.classList.remove("active");
    });

    // ─────────────────────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────────────────────
    function setVal(id, val) {
        const el = document.getElementById(id);
        if (el) el.value = val ?? "";
    }

    function setupModalClose(modal, triggers = []) {
        if (!modal) return;
        const close = () => modal.classList.remove("active");
        triggers.forEach((btn) => btn?.addEventListener("click", close));
        modal.addEventListener("click", (e) => {
            if (e.target === modal) close();
        });
    }
});
