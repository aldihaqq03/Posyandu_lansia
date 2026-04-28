// =====================================================
// jadwal_posyandu.js
// =====================================================

// function formatDateIndo(dateStr) {
//     if (!dateStr) return "";
//     const date = new Date(dateStr);
//     const months = [
//         "Januari",
//         "Februari",
//         "Maret",
//         "April",
//         "Mei",
//         "Juni",
//         "Juli",
//         "Agustus",
//         "September",
//         "Oktober",
//         "November",
//         "Desember",
//     ];
//     return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
// }

// /**
//  * Kumpulkan jenis_skrining[] dari checkbox yang aktif.
//  * Kunjungan Rutin (1) selalu dimasukkan.
//  * prefix: '' untuk modal tambah, 'edit-' untuk modal edit
//  */
// function getJenisSkrining(prefix = "") {
//     const jenis = [1]; // Kunjungan Rutin selalu ada

//     if (prefix) {
//         // Edit mode: cari checkbox berdasarkan ID dengan prefix
//         const utama = document.getElementById(`${prefix}chk-utama`);
//         const ppok = document.getElementById(`${prefix}chk-ppok`);
//         if (utama?.checked) jenis.push(2);
//         if (ppok?.checked) jenis.push(3);
//     } else {
//         // Tambah mode: ambil semua checkbox dengan name="jenis_skrining" yang di-check
//         document
//             .querySelectorAll('input[name="jenis_skrining"]:checked')
//             .forEach((cb) => {
//                 const value = parseInt(cb.value);
//                 if (value > 1 && !jenis.includes(value)) {
//                     jenis.push(value);
//                 }
//             });
//     }

//     return jenis;
// }

// document.addEventListener("DOMContentLoaded", function () {
//     const csrfToken = document.querySelector(
//         'meta[name="csrf-token"]',
//     )?.content;

//     // ===================== MODAL TAMBAH =====================
//     const modalTambah = document.getElementById("modalTambahJadwal");
//     const btnTambah = document.getElementById("btn-tambah-jadwal");
//     const btnClose = document.getElementById("btn-close-modal");
//     const btnCancel = document.getElementById("btn-cancel-modal");

//     function openModalTambah() {
//         modalTambah.classList.add("open");
//         document.body.style.overflow = "hidden";
//     }
//     function closeModalTambah() {
//         modalTambah.classList.remove("open");
//         document.body.style.overflow = "";
//     }

//     btnTambah?.addEventListener("click", openModalTambah);
//     btnClose?.addEventListener("click", closeModalTambah);
//     btnCancel?.addEventListener("click", closeModalTambah);
//     modalTambah?.addEventListener("click", (e) => {
//         if (e.target === modalTambah) closeModalTambah();
//     });

//     // ===================== MODAL EDIT =====================
//     const modalEdit = document.getElementById("modalEditJadwal");
//     const btnCloseEdit = document.getElementById("btn-close-modal-edit");
//     const btnCancelEdit = document.getElementById("btn-cancel-modal-edit");
//     const editKegiatanList = document.getElementById("editKegiatanList");

//     function openModalEdit() {
//         modalEdit.classList.add("open");
//         document.body.style.overflow = "hidden";
//     }
//     function closeModalEdit() {
//         modalEdit.classList.remove("open");
//         document.body.style.overflow = "";
//     }

//     btnCloseEdit?.addEventListener("click", closeModalEdit);
//     btnCancelEdit?.addEventListener("click", closeModalEdit);
//     modalEdit?.addEventListener("click", (e) => {
//         if (e.target === modalEdit) closeModalEdit();
//     });

//     // ===================== FETCH DATA UNTUK MODAL EDIT =====================
//     document.querySelectorAll(".btn-edit").forEach((btn) => {
//         btn.addEventListener("click", function () {
//             const id = this.dataset.id;

//             fetch(`/jadwal_posyandu/${id}`, {
//                 headers: { Accept: "application/json" },
//             })
//                 .then((res) => res.json())
//                 .then((data) => {
//                     document.getElementById("edit-id").value =
//                         data.id_jadwal_posyandu;
//                     document.getElementById("edit-tanggal").value =
//                         data.tanggal_pelaksanaan;
//                     document.getElementById("edit-tema").value = data.tema;
//                     document.getElementById("edit-lokasi").value = data.lokasi;
//                     document.getElementById("edit-catatan").value =
//                         data.keterangan ?? "";

//                     // ── Centang checkbox skrining sesuai detail_skrining dari server ──
//                     // data.detail_skrining adalah array objek { jenis_skrining: 1/2/3 }
//                     const aktifJenis = (data.detail_skrining ?? []).map(
//                         (d) => d.jenis_skrining,
//                     );
//                     document.getElementById("edit-chk-utama").checked =
//                         aktifJenis.includes(2);
//                     document.getElementById("edit-chk-ppok").checked =
//                         aktifJenis.includes(3);

//                     // Minimal tanggal (H+1)
//                     const minDate = new Date(data.tanggal_pelaksanaan);
//                     minDate.setDate(minDate.getDate() + 1);
//                     const minDateStr = minDate.toISOString().split("T")[0];

//                     const inputTanggal =
//                         document.getElementById("edit-tanggal");
//                     inputTanggal.min = minDateStr;
//                     inputTanggal.dataset.minDate = minDateStr;

//                     const hintEl = document.getElementById("edit-tanggal-hint");
//                     if (hintEl) {
//                         hintEl.textContent = `Minimal tanggal: ${formatDateIndo(minDateStr)} (H+1 dari jadwal semula)`;
//                         hintEl.style.display = "block";
//                         hintEl.style.color = "var(--primary-mid)";
//                         hintEl.style.fontSize = "12px";
//                         hintEl.style.marginTop = "4px";
//                     }

//                     // Isi kegiatan
//                     editKegiatanList.innerHTML = "";
//                     const kegiatan = data.kegiatan ?? [];
//                     if (kegiatan.length > 0) {
//                         kegiatan.forEach((k, i) =>
//                             editKegiatanList.appendChild(
//                                 createKegiatanItemEdit(i + 1, k.nama, k.jam),
//                             ),
//                         );
//                     } else {
//                         editKegiatanList.appendChild(
//                             createKegiatanItemEdit(1, "", ""),
//                         );
//                     }

//                     openModalEdit();
//                 })
//                 .catch((err) => {
//                     console.error(err);
//                     alert("Gagal mengambil data jadwal!");
//                 });
//         });
//     });

//     // ===================== SIMPAN JADWAL BARU =====================
//     document
//         .getElementById("btn-simpan-jadwal")
//         ?.addEventListener("click", function () {
//             const tanggal = document.getElementById("input-tanggal").value;
//             const tema = document.getElementById("input-tema").value.trim();
//             const lokasi = document.getElementById("input-lokasi").value.trim();

//             if (!tanggal || !tema || !lokasi) {
//                 alert("Tanggal, Tema, dan Lokasi wajib diisi!");
//                 return;
//             }

//             const kegiatan = [];
//             document
//                 .getElementById("kegiatanList")
//                 ?.querySelectorAll(".kegiatan-item")
//                 .forEach((item) => {
//                     const nama = item
//                         .querySelector(".kegiatan-input")
//                         .value.trim();
//                     const jam = item.querySelector(".jam-input").value;
//                     if (nama) kegiatan.push({ nama, jam: jam || null });
//                 });

//             const payload = {
//                 tanggal_pelaksanaan: tanggal,
//                 tema: tema,
//                 lokasi: lokasi,
//                 jenis_skrining: getJenisSkrining(""), // ← array [1], [1,2], [1,3], [1,2,3]
//                 kegiatan: kegiatan,
//                 keterangan:
//                     document.getElementById("input-catatan").value.trim() ||
//                     null,
//             };

//             fetch("/jadwal_posyandu", {
//                 method: "POST",
//                 headers: {
//                     "Content-Type": "application/json",
//                     "X-CSRF-TOKEN": csrfToken,
//                     Accept: "application/json",
//                 },
//                 body: JSON.stringify(payload),
//             })
//                 .then(async (res) => {
//                     const data = await res.json();
//                     if (!res.ok)
//                         throw new Error(
//                             data.error || data.message || "Gagal menyimpan",
//                         );
//                     return data;
//                 })
//                 .then(() => window.location.reload())
//                 .catch((err) =>
//                     alert("Gagal menyimpan jadwal: " + err.message),
//                 );
//         });

//     // ===================== UPDATE JADWAL =====================
//     document
//         .getElementById("btn-update-jadwal")
//         ?.addEventListener("click", function () {
//             const id = document.getElementById("edit-id").value;
//             const tanggal = document.getElementById("edit-tanggal").value;
//             const tema = document.getElementById("edit-tema").value.trim();
//             const lokasi = document.getElementById("edit-lokasi").value.trim();
//             const minDate =
//                 document.getElementById("edit-tanggal").dataset.minDate;

//             if (!tanggal || tanggal <= minDate) {
//                 alert(
//                     `Tanggal harus lebih dari ${formatDateIndo(minDate)} (H+1 dari jadwal semula)`,
//                 );
//                 return;
//             }
//             if (!tema || !lokasi) {
//                 alert("Tema dan Lokasi wajib diisi!");
//                 return;
//             }

//             const kegiatan = [];
//             editKegiatanList
//                 ?.querySelectorAll(".kegiatan-item")
//                 .forEach((item) => {
//                     const nama = item
//                         .querySelector(".kegiatan-input")
//                         .value.trim();
//                     const jam = item.querySelector(".jam-input").value;
//                     if (nama) kegiatan.push({ nama, jam: jam || null });
//                 });

//             const payload = {
//                 _method: "PUT",
//                 tanggal_pelaksanaan: tanggal,
//                 tema: tema,
//                 lokasi: lokasi,
//                 jenis_skrining: getJenisSkrining("edit-"), // ← array dari checkbox edit
//                 kegiatan: kegiatan,
//                 keterangan:
//                     document.getElementById("edit-catatan").value.trim() ||
//                     null,
//             };

//             fetch(`/jadwal_posyandu/${id}`, {
//                 method: "POST",
//                 headers: {
//                     "Content-Type": "application/json",
//                     "X-CSRF-TOKEN": csrfToken,
//                     Accept: "application/json",
//                 },
//                 body: JSON.stringify(payload),
//             })
//                 .then(async (res) => {
//                     const data = await res.json();
//                     if (!res.ok)
//                         throw new Error(
//                             data.error || data.message || "Gagal update",
//                         );
//                     return data;
//                 })
//                 .then(() => {
//                     closeModalEdit();
//                     window.location.reload();
//                 })
//                 .catch((err) =>
//                     alert("Gagal menyimpan perubahan: " + err.message),
//                 );
//         });

//     // ===================== ESC TUTUP MODAL =====================
//     document.addEventListener("keydown", (e) => {
//         if (e.key === "Escape") {
//             closeModalTambah();
//             closeModalEdit();
//         }
//     });

//     // ===================== TOGGLE SKRINING (klik area, bukan hanya checkbox) =====================
//     document
//         .querySelectorAll(".skrining-option:not(.disabled)")
//         .forEach((opt) => {
//             opt.addEventListener("click", function (e) {
//                 if (e.target.type !== "checkbox") {
//                     const cb = this.querySelector('input[type="checkbox"]');
//                     cb.checked = !cb.checked;
//                 }
//             });
//         });

//     // ===================== KEGIATAN — MODAL TAMBAH =====================
//     const kegiatanList = document.getElementById("kegiatanList");
//     const btnAddKegiatan = document.getElementById("btn-add-kegiatan");

//     function createKegiatanItem(num, nama = "", jam = "") {
//         const item = document.createElement("div");
//         item.className = "kegiatan-item";
//         item.innerHTML = `
//             <div class="kegiatan-num">${num}</div>
//             <input class="kegiatan-input" type="text" placeholder="Nama kegiatan" value="${nama}">
//             <span class="jam-label"><i class="fa-regular fa-clock"></i></span>
//             <input class="jam-input" type="time" value="${jam ?? ""}">
//             <button class="btn-remove" type="button"><i class="fa-solid fa-xmark"></i></button>
//         `;
//         item.querySelector(".btn-remove").addEventListener("click", () => {
//             if (kegiatanList.querySelectorAll(".kegiatan-item").length <= 1)
//                 return;
//             item.remove();
//             updateNumbers(kegiatanList);
//         });
//         return item;
//     }

//     kegiatanList?.querySelectorAll(".btn-remove").forEach((btn) => {
//         btn.addEventListener("click", () => {
//             if (kegiatanList.querySelectorAll(".kegiatan-item").length <= 1)
//                 return;
//             btn.closest(".kegiatan-item").remove();
//             updateNumbers(kegiatanList);
//         });
//     });

//     btnAddKegiatan?.addEventListener("click", () => {
//         const num = kegiatanList.querySelectorAll(".kegiatan-item").length + 1;
//         const newItem = createKegiatanItem(num);
//         kegiatanList.appendChild(newItem);
//         newItem.querySelector(".kegiatan-input").focus();
//     });

//     // ===================== KEGIATAN — MODAL EDIT =====================
//     function createKegiatanItemEdit(num, nama = "", jam = "") {
//         const item = document.createElement("div");
//         item.className = "kegiatan-item";
//         item.innerHTML = `
//             <div class="kegiatan-num">${num}</div>
//             <input class="kegiatan-input" type="text" placeholder="Nama kegiatan" value="${nama}">
//             <span class="jam-label"><i class="fa-regular fa-clock"></i></span>
//             <input class="jam-input" type="time" value="${jam ?? ""}">
//             <button class="btn-remove" type="button"><i class="fa-solid fa-xmark"></i></button>
//         `;
//         item.querySelector(".btn-remove").addEventListener("click", () => {
//             if (editKegiatanList.querySelectorAll(".kegiatan-item").length <= 1)
//                 return;
//             item.remove();
//             updateNumbers(editKegiatanList);
//         });
//         return item;
//     }

//     document
//         .getElementById("btn-add-kegiatan-edit")
//         ?.addEventListener("click", () => {
//             const num =
//                 editKegiatanList.querySelectorAll(".kegiatan-item").length + 1;
//             const newItem = createKegiatanItemEdit(num);
//             editKegiatanList.appendChild(newItem);
//             newItem.querySelector(".kegiatan-input").focus();
//         });

//     function updateNumbers(list) {
//         list.querySelectorAll(".kegiatan-item").forEach((item, i) => {
//             item.querySelector(".kegiatan-num").textContent = i + 1;
//         });
//     }

//     // ===================== FILTER =====================
//     const searchInput = document.getElementById("search-jadwal");
//     const filterStatus = document.getElementById("filter-status");
//     const filterBulan = document.getElementById("filter-bulan");

//     function applyFilter() {
//         // TODO: implementasi filter via AJAX
//     }

//     searchInput?.addEventListener("input", applyFilter);
//     filterStatus?.addEventListener("change", applyFilter);
//     filterBulan?.addEventListener("change", applyFilter);
// });
// =====================================================
// jadwal_posyandu.js
// =====================================================
// Mapping konstanta sesuai database:
// detail_skrining.jenis_skrining: 1=Utama, 2=PPOK, 3=Kunjungan

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
                    document.getElementById("edit-tanggal").value =
                        data.tanggal_pelaksanaan;
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

                    // Minimal tanggal H+1
                    const minDate = new Date(data.tanggal_pelaksanaan);
                    minDate.setDate(minDate.getDate() + 1);
                    const minDateStr = minDate.toISOString().split("T")[0];

                    const inputTanggal =
                        document.getElementById("edit-tanggal");
                    inputTanggal.min = minDateStr;
                    inputTanggal.dataset.minDate = minDateStr;

                    const hintEl = document.getElementById("edit-tanggal-hint");
                    if (hintEl) {
                        hintEl.textContent = `Minimal tanggal: ${formatDateIndo(minDateStr)} (H+1 dari jadwal semula)`;
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

            if (!tanggal || tanggal <= minDate) {
                alert(
                    `Tanggal harus lebih dari ${formatDateIndo(minDate)} (H+1 dari jadwal semula)`,
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
        .getElementById("filter-status")
        ?.addEventListener("change", applyFilter);
    document
        .getElementById("filter-bulan")
        ?.addEventListener("change", applyFilter);
    function applyFilter() {
        /* TODO: AJAX filter */
    }
});
