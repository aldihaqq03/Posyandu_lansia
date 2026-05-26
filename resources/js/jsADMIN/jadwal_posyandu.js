function formatDateIndo(dateStr) {
    if (!dateStr) return "";
    const date = new Date(dateStr);
    const months = [
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember",
    ];
    return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
}

/**
 * Kumpulkan jenis_skrining[] dari checkbox.
 * Kunjungan Rutin = 3 (sesuai DB), selalu disertakan.
 * prefix '' = modal tambah, 'edit-' = modal edit
 */
function getJenisSkrining(prefix = "") {
    const jenis = [3]; // Kunjungan Rutin selalu ada (nilai 3 di DB)

    if (prefix) {
        // Edit mode: baca via ID
        if (document.getElementById(`${prefix}chk-utama`)?.checked)
            jenis.push(1);
        if (document.getElementById(`${prefix}chk-ppok`)?.checked)
            jenis.push(2);
    } else {
        // Tambah mode: baca semua checkbox name="jenis_skrining" yang dicentang
        document
            .querySelectorAll('input[name="jenis_skrining"]:checked')
            .forEach((cb) => {
                const val = parseInt(cb.value);
                if (!jenis.includes(val)) jenis.push(val);
            });
    }

    return jenis;
}

document.addEventListener("DOMContentLoaded", function () {
    const csrfToken = document.querySelector(
        'meta[name="csrf-token"]',
    )?.content;

    function setText(id, value) {
        const el = document.getElementById(id);
        if (el) el.textContent = value ?? "-";
    }

    // ===================== MODAL TAMBAH =====================
    const modalTambah = document.getElementById("modalTambahJadwal");
    const btnTambah = document.getElementById("btn-tambah-jadwal");
    const btnClose = document.getElementById("btn-close-modal");
    const btnCancel = document.getElementById("btn-cancel-modal");

    function openModalTambah() {
        modalTambah.classList.add("open");
        document.body.style.overflow = "hidden";
    }
    function closeModalTambah() {
        modalTambah.classList.remove("open");
        document.body.style.overflow = "";
    }

    btnTambah?.addEventListener("click", openModalTambah);
    btnClose?.addEventListener("click", closeModalTambah);
    btnCancel?.addEventListener("click", closeModalTambah);
    modalTambah?.addEventListener("click", (e) => {
        if (e.target === modalTambah) closeModalTambah();
    });

    // ===================== MODAL EDIT =====================
    const modalEdit = document.getElementById("modalEditJadwal");
    const btnCloseEdit = document.getElementById("btn-close-modal-edit");
    const btnCancelEdit = document.getElementById("btn-cancel-modal-edit");
    const editKegiatanList = document.getElementById("editKegiatanList");

    function openModalEdit() {
        modalEdit.classList.add("open");
        document.body.style.overflow = "hidden";
    }
    function closeModalEdit() {
        modalEdit.classList.remove("open");
        document.body.style.overflow = "";
    }

    btnCloseEdit?.addEventListener("click", closeModalEdit);
    btnCancelEdit?.addEventListener("click", closeModalEdit);
    modalEdit?.addEventListener("click", (e) => {
        if (e.target === modalEdit) closeModalEdit();
    });

    // ===================== MODAL DETAIL =====================
    const modalDetail = document.getElementById("modalDetailJadwal");
    const btnCloseDetail = document.getElementById("btn-close-modal-detail");
    const btnCloseDetailFooter = document.getElementById(
        "btn-close-modal-detail-footer",
    );

    function openModalDetail() {
        modalDetail?.classList.add("open");
        document.body.style.overflow = "hidden";
    }

    function closeModalDetail() {
        modalDetail?.classList.remove("open");
        document.body.style.overflow = "";
    }

    btnCloseDetail?.addEventListener("click", closeModalDetail);
    btnCloseDetailFooter?.addEventListener("click", closeModalDetail);
    modalDetail?.addEventListener("click", (e) => {
        if (e.target === modalDetail) closeModalDetail();
    });

    function renderStatusBadge(status) {
        const badge = document.getElementById("detail-status-badge");
        if (!badge) return;

        const map = {
            0: { label: "Terjadwal", cls: "badge-terjadwal" },
            1: { label: "Berlangsung", cls: "badge-berlangsung" },
            2: { label: "Selesai", cls: "badge-selesai" },
            3: { label: "Dibatalkan", cls: "badge-batal" },
        };
        const conf = map[status] || { label: "-", cls: "" };

        badge.className = "badge";
        if (conf.cls) badge.classList.add(conf.cls);
        badge.textContent = conf.label;
    }

    function renderDetailSkrining(detailSkrining = []) {
        const container = document.getElementById("detail-skrining-tags");
        if (!container) return;

        container.innerHTML = "";
        const map = {
            1: "Skrining Utama",
            2: "Skrining PPOK",
            3: "Kunjungan Rutin",
        };

        if (!Array.isArray(detailSkrining) || detailSkrining.length === 0) {
            container.innerHTML =
                '<span class="detail-tag">Belum ada data skrining</span>';
            return;
        }

        detailSkrining.forEach((item) => {
            const code = parseInt(item.jenis_skrining);
            const label = map[code] || `Skrining #${code}`;
            container.innerHTML += `<span class="detail-tag">${label}</span>`;
        });
    }

    function renderDetailKegiatan(kegiatan = []) {
        const container = document.getElementById("detail-kegiatan-list");
        if (!container) return;

        container.innerHTML = "";
        if (!Array.isArray(kegiatan) || kegiatan.length === 0) {
            container.innerHTML =
                '<div class="detail-empty">Belum ada kegiatan.</div>';
            return;
        }

        kegiatan.forEach((item, idx) => {
            const nama = typeof item === "object" ? item.nama : item;
            const jam = typeof item === "object" ? item.jam : "";
            container.innerHTML += `
                <div class="detail-kegiatan-item">
                    <span class="detail-kegiatan-no">${idx + 1}</span>
                    <span class="detail-kegiatan-nama">${nama || "-"}</span>
                    <span class="detail-kegiatan-jam">${jam || "-"}</span>
                </div>
            `;
        });
    }

    function openJadwalDetail(id) {
        if (!id) return;

        fetch(`/jadwal_posyandu/${id}`, {
            headers: { Accept: "application/json" },
        })
            .then((res) => res.json())
            .then((data) => {
                setText("detail-tema", data.tema || "-");
                setText(
                    "detail-tanggal",
                    formatDateIndo(data.tanggal_pelaksanaan),
                );
                setText("detail-lokasi", data.lokasi || "-");
                setText("detail-catatan", data.keterangan || "-");
                renderStatusBadge(parseInt(data.status));
                renderDetailSkrining(data.detail_skrining || []);
                renderDetailKegiatan(data.kegiatan || []);
                openModalDetail();
            })
            .catch(() => {
                alert("Gagal mengambil detail jadwal!");
            });
    }

    document.querySelectorAll(".jadwal-card[data-id]").forEach((card) => {
        card.addEventListener("click", function (e) {
            if (e.target.closest(".jadwal-actions")) return;
            openJadwalDetail(this.dataset.id);
        });

        card.addEventListener("keydown", function (e) {
            if (e.key === "Enter" || e.key === " ") {
                e.preventDefault();
                openJadwalDetail(this.dataset.id);
            }
        });
    });

    // ===================== FETCH DATA MODAL EDIT =====================
    document.querySelectorAll(".btn-edit").forEach((btn) => {
        btn.addEventListener("click", function () {
            const id = this.dataset.id;

            fetch(`/jadwal_posyandu/${id}`, {
                headers: { Accept: "application/json" },
            })
                .then((res) => res.json())
                .then((data) => {
                    document.getElementById("edit-id").value =
                        data.id_jadwal_posyandu;

                    document.getElementById("edit-tema").value = data.tema;
                    document.getElementById("edit-lokasi").value = data.lokasi;
                    document.getElementById("edit-catatan").value =
                        data.keterangan ?? "";

                    // detail_skrining: 1=Utama, 2=PPOK, 3=Kunjungan
                    const aktifJenis = (data.detail_skrining ?? []).map((d) =>
                        parseInt(d.jenis_skrining),
                    );
                    document.getElementById("edit-chk-utama").checked =
                        aktifJenis.includes(1);
                    document.getElementById("edit-chk-ppok").checked =
                        aktifJenis.includes(2);

                    // Minimal tanggal = H+3 dari hari ini
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    today.setDate(today.getDate() + 3); // Menghitung H+3 dari hari ini

                    const year = today.getFullYear();
                    const month = String(today.getMonth() + 1).padStart(2, "0");
                    const day = String(today.getDate()).padStart(2, "0");

                    const minDateStr = `${year}-${month}-${day}`;
                    const inputTanggal =
                        document.getElementById("edit-tanggal");

                    // 1. KUNCI UTAMA: Set atribut 'min' agar tanggal H+3 ke belakang otomatis abu-abu dan tidak bisa dipilih
                    inputTanggal.setAttribute("min", minDateStr);
                    inputTanggal.dataset.minDate = minDateStr;

                    // 2. VALIDASI NILAI: Jika tanggal dari database sudah kedaluwarsa (kurang dari H+3 hari ini), paksa set ke batas minimal
                    if (data.tanggal_pelaksanaan < minDateStr) {
                        inputTanggal.value = minDateStr;
                    } else {
                        inputTanggal.value = data.tanggal_pelaksanaan;
                    }

                    // 3. RENDER HINT TEXT
                    const hintEl = document.getElementById("edit-tanggal-hint");
                    if (hintEl) {
                        hintEl.textContent = `Minimal tanggal: ${formatDateIndo(minDateStr)} (H+3 dari hari ini)`;
                        hintEl.style.display = "block";
                        hintEl.style.color = "var(--primary-mid)";
                        hintEl.style.fontSize = "12px";
                        hintEl.style.marginTop = "4px";
                    }

                    // Isi kegiatan
                    editKegiatanList.innerHTML = "";
                    const kegiatan = data.kegiatan ?? [];
                    if (kegiatan.length > 0) {
                        kegiatan.forEach((k, i) =>
                            editKegiatanList.appendChild(
                                createKegiatanItemEdit(i + 1, k.nama, k.jam),
                            ),
                        );
                    } else {
                        editKegiatanList.appendChild(
                            createKegiatanItemEdit(1, "", ""),
                        );
                    }

                    openModalEdit();
                })
                .catch((err) => {
                    console.error(err);
                    alert("Gagal mengambil data jadwal!");
                });
        });
    });

    // ===================== SIMPAN JADWAL BARU =====================
    document
        .getElementById("btn-simpan-jadwal")
        ?.addEventListener("click", function () {
            const tanggal = document.getElementById("input-tanggal").value;
            const tema = document.getElementById("input-tema").value.trim();
            const lokasi = document.getElementById("input-lokasi").value.trim();

            if (!tanggal || !tema || !lokasi) {
                alert("Tanggal, Tema, dan Lokasi wajib diisi!");
                return;
            }

            const kegiatan = [];
            document
                .getElementById("kegiatanList")
                ?.querySelectorAll(".kegiatan-item")
                .forEach((item) => {
                    const nama = item
                        .querySelector(".kegiatan-input")
                        .value.trim();
                    const jam = item.querySelector(".jam-input").value;
                    if (nama) kegiatan.push({ nama, jam: jam || null });
                });

            const payload = {
                tanggal_pelaksanaan: tanggal,
                tema: tema,
                lokasi: lokasi,
                jenis_skrining: getJenisSkrining(""), // [3] atau [3,1] atau [3,2] atau [3,1,2]
                kegiatan: kegiatan,
                keterangan:
                    document.getElementById("input-catatan").value.trim() ||
                    null,
            };

            fetch("/jadwal_posyandu", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    Accept: "application/json",
                },
                body: JSON.stringify(payload),
            })
                .then(async (res) => {
                    const data = await res.json();
                    if (!res.ok)
                        throw new Error(
                            data.error || data.message || "Gagal menyimpan",
                        );
                    return data;
                })
                .then(() => window.location.reload())
                .catch((err) =>
                    alert("Gagal menyimpan jadwal: " + err.message),
                );
        });

    // ===================== UPDATE JADWAL =====================
    document
        .getElementById("btn-update-jadwal")
        ?.addEventListener("click", function () {
            const id = document.getElementById("edit-id").value;
            const tanggal = document.getElementById("edit-tanggal").value;
            const tema = document.getElementById("edit-tema").value.trim();
            const lokasi = document.getElementById("edit-lokasi").value.trim();
            const minDate =
                document.getElementById("edit-tanggal").dataset.minDate;

            if (!tanggal || tanggal < minDate) {
                alert(
                    `Tanggal minimal ${formatDateIndo(minDate)} (H+3 dari hari ini)`,
                );
                return;
            }
            if (!tema || !lokasi) {
                alert("Tema dan Lokasi wajib diisi!");
                return;
            }

            const kegiatan = [];
            editKegiatanList
                ?.querySelectorAll(".kegiatan-item")
                .forEach((item) => {
                    const nama = item
                        .querySelector(".kegiatan-input")
                        .value.trim();
                    const jam = item.querySelector(".jam-input").value;
                    if (nama) kegiatan.push({ nama, jam: jam || null });
                });

            const payload = {
                _method: "PUT",
                tanggal_pelaksanaan: tanggal,
                tema: tema,
                lokasi: lokasi,
                jenis_skrining: getJenisSkrining("edit-"),
                kegiatan: kegiatan,
                keterangan:
                    document.getElementById("edit-catatan").value.trim() ||
                    null,
            };

            fetch(`/jadwal_posyandu/${id}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    Accept: "application/json",
                },
                body: JSON.stringify(payload),
            })
                .then(async (res) => {
                    const data = await res.json();
                    if (!res.ok)
                        throw new Error(
                            data.error || data.message || "Gagal update",
                        );
                    return data;
                })
                .then(() => {
                    closeModalEdit();
                    window.location.reload();
                })
                .catch((err) =>
                    alert("Gagal menyimpan perubahan: " + err.message),
                );
        });

    // ===================== ESC TUTUP MODAL =====================
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            closeModalTambah();
            closeModalEdit();
            closeModalDetail();
        }
    });

    // ===================== TOGGLE SKRINING =====================
    document
        .querySelectorAll(".skrining-option:not(.disabled)")
        .forEach((opt) => {
            opt.addEventListener("click", function (e) {
                if (e.target.type !== "checkbox") {
                    const cb = this.querySelector('input[type="checkbox"]');
                    cb.checked = !cb.checked;
                }
            });
        });

    // ===================== KEGIATAN — MODAL TAMBAH =====================
    const kegiatanList = document.getElementById("kegiatanList");
    const btnAddKegiatan = document.getElementById("btn-add-kegiatan");

    function createKegiatanItem(num, nama = "", jam = "") {
        const item = document.createElement("div");
        item.className = "kegiatan-item";
        item.innerHTML = `
            <div class="kegiatan-num">${num}</div>
            <input class="kegiatan-input" type="text" placeholder="Nama kegiatan" value="${nama}">
            <span class="jam-label"><i class="fa-regular fa-clock"></i></span>
            <input class="jam-input" type="time" value="${jam ?? ""}">
            <button class="btn-remove" type="button"><i class="fa-solid fa-xmark"></i></button>
        `;
        item.querySelector(".btn-remove").addEventListener("click", () => {
            if (kegiatanList.querySelectorAll(".kegiatan-item").length <= 1)
                return;
            item.remove();
            updateNumbers(kegiatanList);
        });
        return item;
    }

    kegiatanList?.querySelectorAll(".btn-remove").forEach((btn) => {
        btn.addEventListener("click", () => {
            if (kegiatanList.querySelectorAll(".kegiatan-item").length <= 1)
                return;
            btn.closest(".kegiatan-item").remove();
            updateNumbers(kegiatanList);
        });
    });

    btnAddKegiatan?.addEventListener("click", () => {
        const num = kegiatanList.querySelectorAll(".kegiatan-item").length + 1;
        const newItem = createKegiatanItem(num);
        kegiatanList.appendChild(newItem);
        newItem.querySelector(".kegiatan-input").focus();
    });

    // ===================== KEGIATAN — MODAL EDIT =====================
    function createKegiatanItemEdit(num, nama = "", jam = "") {
        const item = document.createElement("div");
        item.className = "kegiatan-item";
        item.innerHTML = `
            <div class="kegiatan-num">${num}</div>
            <input class="kegiatan-input" type="text" placeholder="Nama kegiatan" value="${nama}">
            <span class="jam-label"><i class="fa-regular fa-clock"></i></span>
            <input class="jam-input" type="time" value="${jam ?? ""}">
            <button class="btn-remove" type="button"><i class="fa-solid fa-xmark"></i></button>
        `;
        item.querySelector(".btn-remove").addEventListener("click", () => {
            if (editKegiatanList.querySelectorAll(".kegiatan-item").length <= 1)
                return;
            item.remove();
            updateNumbers(editKegiatanList);
        });
        return item;
    }

    document
        .getElementById("btn-add-kegiatan-edit")
        ?.addEventListener("click", () => {
            const num =
                editKegiatanList.querySelectorAll(".kegiatan-item").length + 1;
            const newItem = createKegiatanItemEdit(num);
            editKegiatanList.appendChild(newItem);
            newItem.querySelector(".kegiatan-input").focus();
        });

    function updateNumbers(list) {
        list.querySelectorAll(".kegiatan-item").forEach((item, i) => {
            item.querySelector(".kegiatan-num").textContent = i + 1;
        });
    }

    // ===================== FILTER =====================
    document
        .getElementById("search-jadwal")
        ?.addEventListener("input", applyFilter);
    document
        .getElementById("filter-bulan")
        ?.addEventListener("change", applyFilter);
    document
        .getElementById("filter-tahun")
        ?.addEventListener("change", applyFilter);

    function applyFilter() {
        const search =
            document
                .getElementById("search-jadwal")
                ?.value.trim()
                .toLowerCase() || "";
        const bulan = document.getElementById("filter-bulan")?.value || "";
        const tahun = document.getElementById("filter-tahun")?.value || "";

        const cards = [...document.querySelectorAll(".jadwal-card[data-id]")];
        cards.forEach((card) => {
            const location = (card.dataset.location || "").toLowerCase();
            const tema = (
                card.querySelector(".jadwal-tema")?.textContent || ""
            ).toLowerCase();
            const month = card.dataset.month || "";
            const year = card.dataset.year || "";

            const matchSearch =
                !search || location.includes(search) || tema.includes(search);
            const matchMonth = !bulan || bulan === month;
            const matchYear = !tahun || tahun === year;

            card.style.display =
                matchSearch && matchMonth && matchYear ? "" : "none";
        });

        const list = document.querySelector(".jadwal-list");
        if (!list) return;

        let currentDivider = null;
        let dividerHasVisibleCard = false;

        list.querySelectorAll(".month-divider, .jadwal-card[data-id]").forEach(
            (node) => {
                if (node.classList.contains("month-divider")) {
                    if (currentDivider) {
                        currentDivider.style.display = dividerHasVisibleCard
                            ? ""
                            : "none";
                    }
                    currentDivider = node;
                    dividerHasVisibleCard = false;
                    return;
                }

                const isVisible = node.style.display !== "none";
                if (isVisible) {
                    dividerHasVisibleCard = true;
                    if (currentDivider) currentDivider.style.display = "";
                }
            },
        );

        if (currentDivider) {
            currentDivider.style.display = dividerHasVisibleCard ? "" : "none";
        }
    }

    applyFilter();
});
