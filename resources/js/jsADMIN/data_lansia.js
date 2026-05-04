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
            const email = this.dataset.email || "-";
            const tempatLahir = this.dataset.tempatLahir || "-";
            const tanggal = this.dataset.formatTanggal || "-";
            const status = this.dataset.statusPerkawinan || "-";
            const keterangan = this.dataset.keterangan || "-";

            detailPanel.style.opacity = "0";
            detailPanel.style.display = "block";

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
            setText("d-email", email);
            setText("d-ttl", tempatLahir + ", " + tanggal);
            setText("d-jk-text", jk);
            setText("d-status", status);
            setText("d-alamat", alamat);
            setText("d-riwayat", riwayat);
            setText("d-keterangan", keterangan);

            const btnHistori = document.getElementById("btn-histori-skrining");
            if (btnHistori && id) {
                btnHistori.href = `/lansia/${id}/histori-skrining`;
            }

            fetchHealthSummary(id);
            fetchKeluargaData(id);

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

    async function fetchKeluargaData(id) {
        const section = document.getElementById("keluarga-info-section");
        if (!section) return;

        section.innerHTML =
            '<p style="color: #999; text-align: center; padding: 20px;">Memuat...</p>';

        try {
            const res = await fetch(`/lansia/${id}/keluarga`, {
                headers: { "X-Requested-With": "XMLHttpRequest" },
            });
            if (!res.ok) throw new Error("HTTP " + res.status);
            const { keluarga } = await res.json();

            if (!keluarga || keluarga.length === 0) {
                section.innerHTML =
                    '<p style="color: #999; text-align: center; padding: 20px;">Tidak ada data keluarga</p>';
                return;
            }

            let html = '<div style="display: grid; gap: 15px;">';
            keluarga.forEach((fam) => {
                html += `
                    <div style="padding: 12px; background-color: #f5f5f5; border-radius: 8px; border-left: 4px solid #007bff;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <div>
                                <label style="font-size: 12px; color: #666; text-transform: uppercase;">Nama</label>
                                <p style="margin: 5px 0 0 0; font-weight: 500;">${fam.nama_keluarga || "-"}</p>
                            </div>
                            <div>
                                <label style="font-size: 12px; color: #666; text-transform: uppercase;">No Telepon</label>
                                <p style="margin: 5px 0 0 0; font-weight: 500;">${fam.no_sama || "-"}</p>
                            </div>
                        </div>
                        <div style="margin-top: 10px;">
                            <label style="font-size: 12px; color: #666; text-transform: uppercase;">Alamat</label>
                            <p style="margin: 5px 0 0 0; font-weight: 500; white-space: pre-wrap;">${fam.alamat || "-"}</p>
                        </div>
                    </div>
                `;
            });
            html += "</div>";
            section.innerHTML = html;
        } catch (err) {
            section.innerHTML =
                '<p style="color: #999; text-align: center; padding: 20px;">Gagal memuat data keluarga</p>';
        }
    }

    // ─────────────────────────────────────────────────────────────
    // EDIT KELUARGA — state & helpers
    // ─────────────────────────────────────────────────────────────
    let editKeluargaCount = 0;

    /**
     * Buat item keluarga di edit modal.
     * @param {number} idx   — index array untuk name="keluarga[idx][...]"
     * @param {object} fam   — data awal { nama_keluarga, no_sama, alamat }
     * @param {boolean} isFirst — jika true, tombol hapus disembunyikan & field wajib
     */
    function buatItemKeluargaEdit(idx, fam = {}, isFirst = false) {
        const nomor = idx + 1;
        const hapusBtn = isFirst
            ? "" // item pertama tidak bisa dihapus
            : `<button type="button" class="btn-remove-keluarga-edit"
                   style="background:none; border:none; color:#e74c3c; cursor:pointer; font-size:18px; padding:0;"
                   title="Hapus anggota keluarga ini">✕</button>`;

        const labelWajib = isFirst
            ? `<span style="color:#e74c3c; font-size:11px; margin-left:6px; font-weight:400;">*Wajib</span>`
            : "";

        const requiredAttr = isFirst ? "required" : "";

        const item = document.createElement("div");
        item.className = "keluarga-item-edit";
        item.dataset.keluargaIdx = idx;
        item.style.cssText =
            "padding:15px; background-color:#f9f9f9; border-radius:8px; margin-bottom:15px; border:1px solid #e0e0e0;";

        item.innerHTML = `
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                <h4 style="margin:0; color:#555;">Anggota Keluarga #${nomor} ${labelWajib}</h4>
                ${hapusBtn}
            </div>
            <div class="form-group">
                <label>Nama Keluarga${isFirst ? ' <span style="color:#e74c3c;">*</span>' : ""}</label>
                <input type="text"
                       class="nama_keluarga_input"
                       name="keluarga[${idx}][nama_keluarga]"
                       value="${escapeHtml(fam.nama_keluarga || "")}"
                       placeholder="Masukkan nama anggota keluarga"
                       ${requiredAttr}>
                ${isFirst ? '<small class="error-nama-keluarga-0" style="color:#e74c3c; font-size:12px; display:none;"></small>' : ""}
            </div>
            <div class="form-group" style="display:flex; gap:10px;">
                <div style="flex:1;">
                    <label>No Telepon (Opsional)</label>
                    <input type="text"
                           class="no_sama_input"
                           name="keluarga[${idx}][no_sama]"
                           value="${escapeHtml(fam.no_sama || "")}"
                           placeholder="Contoh: 081234567890">
                </div>
                <div style="flex:1;">
                    <label>Alamat (Opsional)</label>
                    <input type="text"
                           class="alamat_keluarga_input"
                           name="keluarga[${idx}][alamat]"
                           value="${escapeHtml(fam.alamat || "")}"
                           placeholder="Alamat anggota keluarga">
                </div>
            </div>
        `;

        // Pasang listener hapus (hanya untuk item bukan pertama)
        if (!isFirst) {
            item.querySelector(".btn-remove-keluarga-edit").addEventListener(
                "click",
                function () {
                    item.remove();
                    renumberKeluargaEdit();
                },
            );
        }

        return item;
    }

    /**
     * Perbarui nomor urut header setelah item dihapus.
     */
    function renumberKeluargaEdit() {
        const container = document.getElementById("edit-keluarga-container");
        if (!container) return;
        container.querySelectorAll(".keluarga-item-edit").forEach((el, i) => {
            const h4 = el.querySelector("h4");
            if (h4) {
                const isFirst = i === 0;
                const labelWajib = isFirst
                    ? `<span style="color:#e74c3c; font-size:11px; margin-left:6px; font-weight:400;">*Wajib</span>`
                    : "";
                h4.innerHTML = `Anggota Keluarga #${i + 1} ${labelWajib}`;
            }
            // Perbarui name attribute semua input di item ini
            el.querySelectorAll("input").forEach((inp) => {
                inp.name = inp.name.replace(
                    /keluarga\[\d+\]/,
                    `keluarga[${i}]`,
                );
            });
        });
    }

    /**
     * Escape HTML untuk nilai yang dimasukkan ke value attribute.
     */
    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, "&amp;")
            .replace(/"/g, "&quot;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;");
    }

    /**
     * Muat data keluarga ke dalam edit modal.
     */
    async function loadKeluargaForEdit(id) {
        const container = document.getElementById("edit-keluarga-container");
        if (!container) return;

        container.innerHTML =
            '<p style="color:#999; text-align:center; padding:10px;">Memuat data keluarga...</p>';
        editKeluargaCount = 0;

        try {
            const res = await fetch(`/lansia/${id}/keluarga`, {
                headers: { "X-Requested-With": "XMLHttpRequest" },
            });
            if (!res.ok) throw new Error("HTTP " + res.status);
            const { keluarga } = await res.json();

            container.innerHTML = "";

            if (!keluarga || keluarga.length === 0) {
                // Tidak ada data → buat 1 item kosong wajib
                editKeluargaCount = 1;
                container.appendChild(buatItemKeluargaEdit(0, {}, true));
            } else {
                keluarga.forEach((fam, idx) => {
                    editKeluargaCount++;
                    container.appendChild(
                        buatItemKeluargaEdit(idx, fam, idx === 0),
                    );
                });
            }
        } catch (err) {
            console.error("Gagal memuat keluarga:", err);
            container.innerHTML = "";
            editKeluargaCount = 1;
            container.appendChild(buatItemKeluargaEdit(0, {}, true));
        }
    }

    // Tombol "+ Tambah Anggota Keluarga" di modal edit
    document
        .getElementById("btn-tambah-keluarga-edit")
        ?.addEventListener("click", function (e) {
            e.preventDefault();
            const container = document.getElementById(
                "edit-keluarga-container",
            );
            if (!container) return;

            const currentItems = container.querySelectorAll(
                ".keluarga-item-edit",
            );
            const newIdx = currentItems.length; // index berikutnya
            editKeluargaCount++;

            container.appendChild(buatItemKeluargaEdit(newIdx, {}, false));
        });

    document
        .getElementById("btn-histori-skrining")
        ?.addEventListener("click", function (e) {
            if (this.getAttribute("href") === "#") {
                e.preventDefault();
                alert("Silakan pilih lansia dari tabel terlebih dahulu.");
            }
        });

    // ─────────────────────────────────────────────────────────────
    // VALIDASI EDIT FORM
    // ─────────────────────────────────────────────────────────────
    const editForm = document.getElementById("form-edit-lansia");
    const editSubmitBtn = editForm?.querySelector('button[type="submit"]');

    // Verify editForm exists
    if (!editForm) {
        console.error("❌ editForm (form-edit-lansia) NOT FOUND in DOM");
    } else {
        console.log("✓ editForm found at initialization");
    }

    function validateEditNIK(value) {
        if (!value) return "NIK tidak boleh kosong";
        if (!/^\d{16}$/.test(value.trim())) return "NIK harus 16 digit angka";
        return "";
    }

    function validateEditNama(value) {
        if (!value) return "Nama Lansia tidak boleh kosong";
        if (value.trim().length < 3) return "Nama Lansia minimal 3 karakter";
        return "";
    }

    function validateEditTanggalLahir(value) {
        if (!value) return "Tanggal Lahir tidak boleh kosong";
        const birth = new Date(value);
        const today = new Date();
        let age = today.getFullYear() - birth.getFullYear();
        const m = today.getMonth() - birth.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;
        if (age < 40)
            return `Umur harus minimal 40 tahun (saat ini ${age} tahun)`;
        return "";
    }

    function validateEditNoHP(value) {
        if (!value) return "";
        if (!/^(\+62|0)[0-9]{9,12}$/.test(value))
            return "Format No HP tidak valid (Contoh: 081234567890)";
        return "";
    }

    function validateEditEmail(value) {
        if (!value) return "";
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value))
            return "Format Email tidak valid";
        return "";
    }

    function validateEditAlamat(value) {
        if (!value) return "Alamat Lansia tidak boleh kosong";
        return "";
    }

    function validateKeluargaPertama() {
        const container = document.getElementById("edit-keluarga-container");
        if (!container) return true;
        const firstItem = container.querySelector(".keluarga-item-edit");
        if (!firstItem) return false;
        const namaInput = firstItem.querySelector(".nama_keluarga_input");
        const errorEl = firstItem.querySelector(".error-nama-keluarga-0");
        const val = namaInput?.value?.trim();
        if (!val) {
            if (errorEl) {
                errorEl.textContent =
                    "Nama anggota keluarga pertama wajib diisi.";
                errorEl.style.display = "block";
            }
            if (namaInput) {
                namaInput.style.borderColor = "#e74c3c";
                namaInput.style.borderWidth = "2px";
            }
            return false;
        }
        if (val.length < 3) {
            if (errorEl) {
                errorEl.textContent = "Nama keluarga minimal 3 karakter.";
                errorEl.style.display = "block";
            }
            if (namaInput) {
                namaInput.style.borderColor = "#e74c3c";
                namaInput.style.borderWidth = "2px";
            }
            return false;
        }
        if (errorEl) errorEl.style.display = "none";
        if (namaInput) {
            namaInput.style.borderColor = "";
            namaInput.style.borderWidth = "";
        }
        return true;
    }

    function showEditError(fieldId, message) {
        const errorEl = document.getElementById(`error-edit_${fieldId}`);
        const inputEl = document.getElementById(`edit_${fieldId}`);
        if (!errorEl) return;
        if (message) {
            errorEl.textContent = message;
            errorEl.style.display = "block";
            if (inputEl) {
                inputEl.style.borderColor = "#e74c3c";
                inputEl.style.borderWidth = "2px";
            }
        } else {
            errorEl.style.display = "none";
            if (inputEl) {
                inputEl.style.borderColor = "";
                inputEl.style.borderWidth = "";
            }
        }
    }

    function isEditFormValid() {
        const valid =
            !validateEditNIK(document.getElementById("edit_nik")?.value) &&
            !validateEditNama(
                document.getElementById("edit_nama_lansia")?.value,
            ) &&
            !validateEditTanggalLahir(
                document.getElementById("edit_tanggal_lahir")?.value,
            ) &&
            !validateEditNoHP(document.getElementById("edit_no_hp")?.value) &&
            !validateEditEmail(document.getElementById("edit_email")?.value) &&
            !validateEditAlamat(
                document.getElementById("edit_alamat")?.value,
            ) &&
            validateKeluargaPertama();
        return valid;
    }

    // Real-time validation
    document.getElementById("edit_nik")?.addEventListener("blur", function () {
        showEditError("nik", validateEditNIK(this.value));
        if (editSubmitBtn) editSubmitBtn.disabled = !isEditFormValid();
    });

    document
        .getElementById("edit_nama_lansia")
        ?.addEventListener("blur", function () {
            showEditError("nama_lansia", validateEditNama(this.value));
            if (editSubmitBtn) editSubmitBtn.disabled = !isEditFormValid();
        });

    document
        .getElementById("edit_tanggal_lahir")
        ?.addEventListener("change", function () {
            showEditError(
                "tanggal_lahir",
                validateEditTanggalLahir(this.value),
            );
            if (editSubmitBtn) editSubmitBtn.disabled = !isEditFormValid();
        });

    document
        .getElementById("edit_no_hp")
        ?.addEventListener("blur", function () {
            showEditError("no_hp", validateEditNoHP(this.value));
            if (editSubmitBtn) editSubmitBtn.disabled = !isEditFormValid();
        });

    document
        .getElementById("edit_email")
        ?.addEventListener("blur", function () {
            showEditError("email", validateEditEmail(this.value));
            if (editSubmitBtn) editSubmitBtn.disabled = !isEditFormValid();
        });

    document
        .getElementById("edit_alamat")
        ?.addEventListener("blur", function () {
            showEditError("alamat", validateEditAlamat(this.value));
            if (editSubmitBtn) editSubmitBtn.disabled = !isEditFormValid();
        });

    // Validasi realtime nama keluarga pertama (delegasi event)
    document
        .getElementById("edit-keluarga-container")
        ?.addEventListener("input", function (e) {
            const container = document.getElementById(
                "edit-keluarga-container",
            );
            const firstItem = container?.querySelector(".keluarga-item-edit");
            if (!firstItem) return;
            const firstInput = firstItem.querySelector(".nama_keluarga_input");
            if (e.target === firstInput) {
                validateKeluargaPertama();
                if (editSubmitBtn) editSubmitBtn.disabled = !isEditFormValid();
            }
        });

    // Submit handler
    editForm?.addEventListener("submit", function (e) {
        console.log("📝 Form submitted!");
        console.log("  - Form action:", this.action);
        console.log("  - Form method:", this.method);
        console.log(
            "  - NIK value:",
            document.getElementById("edit_nik")?.value,
        );

        // SAFETY CHECK: Ensure form action has ID
        if (
            !this.action ||
            this.action.includes("undefined") ||
            !this.action.includes("/lansia/")
        ) {
            console.error("❌ ABORT: Form action is invalid:", this.action);
            e.preventDefault();
            alert(
                "❌ Kesalahan: Form tidak siap untuk submit. Coba refresh halaman dan klik edit lagi.",
            );
            return;
        }

        // Tampilkan semua error
        showEditError(
            "nik",
            validateEditNIK(document.getElementById("edit_nik")?.value),
        );
        showEditError(
            "nama_lansia",
            validateEditNama(
                document.getElementById("edit_nama_lansia")?.value,
            ),
        );
        showEditError(
            "tanggal_lahir",
            validateEditTanggalLahir(
                document.getElementById("edit_tanggal_lahir")?.value,
            ),
        );
        showEditError(
            "no_hp",
            validateEditNoHP(document.getElementById("edit_no_hp")?.value),
        );
        showEditError(
            "email",
            validateEditEmail(document.getElementById("edit_email")?.value),
        );
        showEditError(
            "alamat",
            validateEditAlamat(document.getElementById("edit_alamat")?.value),
        );
        validateKeluargaPertama();

        if (!isEditFormValid()) {
            e.preventDefault();
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
    // NOTE: editForm already defined above in VALIDASI EDIT FORM section

    document.querySelectorAll(".edit-btn").forEach((btn) => {
        btn.addEventListener("click", async () => {
            const row = btn.closest("tr");
            if (!row) {
                console.error("❌ Row not found!");
                return;
            }

            const lansiaId = row.dataset.id;
            console.log("✓ Edit button clicked, ID Lansia:", lansiaId);

            if (!editForm) {
                console.error("❌ editForm is null, cannot set action!");
                return;
            }

            // Set form action
            const newAction = `/lansia/${lansiaId}`;
            editForm.action = newAction;
            console.log("✓ Form action set to:", editForm.action);
            console.log("✓ Form method:", editForm.method);
            console.log(
                "✓ Form verify action again:",
                document.getElementById("form-edit-lansia")?.action,
            );

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

            // Reset error sebelumnya
            [
                "nik",
                "nama_lansia",
                "tanggal_lahir",
                "no_hp",
                "email",
                "alamat",
            ].forEach((f) => showEditError(f, ""));

            // Load keluarga (termasuk buat item pertama wajib)
            await loadKeluargaForEdit(row.dataset.id);

            modalEdit?.classList.add("active");

            // Trigger validasi awal agar tombol submit aktif/nonaktif sesuai data
            setTimeout(() => {
                document
                    .getElementById("edit_nik")
                    ?.dispatchEvent(new Event("blur"));
                document
                    .getElementById("edit_nama_lansia")
                    ?.dispatchEvent(new Event("blur"));
                document
                    .getElementById("edit_tanggal_lahir")
                    ?.dispatchEvent(new Event("change"));
                document
                    .getElementById("edit_alamat")
                    ?.dispatchEvent(new Event("blur"));
                if (editSubmitBtn) editSubmitBtn.disabled = !isEditFormValid();
            }, 150);
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
