/* resources/js/jsAdmin/data_lansia.js */
document.addEventListener("DOMContentLoaded", function () {
    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    // 1. Animasi Angka Statistik
    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
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

    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    // 2. Pencarian Real-time
    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
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

    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    // 3. Klik Baris â†’ Tampilkan Detail Panel
    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
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
            const kodeUnik = this.dataset.kodeUnik || "";
            const pekerjaanRaw = this.dataset.pekerjaan || "-";
            function decodePekerjaan(v) {
                if (!v || v === "-") return "-";
                const m = String(v);
                const map = {
                    1: "TNI/POLRI",
                    2: "PNS",
                    3: "Karyawan Swasta",
                    4: "Buruh",
                    5: "Petani/Nelayan",
                    6: "Tidak Bekerja / IRT",
                    7: "Lainnya",
                };
                if (/^\d+$/.test(m) && map[m]) return map[m];
                return v;
            }

            // â”€â”€ Simpan state ke sessionStorage â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
            sessionStorage.setItem("lastSelectedLansiaId", id);

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
            setText("detail-tb", "Tinggi: -");
            setText("d-nik", nik);
            setText("d-hp", hp);
            setText("d-email", email);
            setText("d-ttl", tempatLahir + ", " + tanggal);
            setText("d-jk-text", jk);
            setText("d-status", status);
            setText("d-alamat", alamat);
            setText("d-riwayat", riwayat);
            setText("d-keterangan", keterangan);
            setText("d-pekerjaan", decodePekerjaan(pekerjaanRaw));

            // Update QR Code Telegram
            const qrTelegramEl = document.getElementById("detail-qr-telegram");
            const kodeTelegramEl = document.getElementById(
                "detail-kode-telegram",
            );
            if (qrTelegramEl && kodeTelegramEl) {
                if (kodeUnik) {
                    qrTelegramEl.src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://t.me/simpell_bot?start=${kodeUnik}`;
                    qrTelegramEl.style.display = "block";
                    kodeTelegramEl.innerText = `Kode: ${kodeUnik}`;
                } else {
                    qrTelegramEl.src = "";
                    qrTelegramEl.style.display = "none";
                    kodeTelegramEl.innerText =
                        "Belum memiliki kode unik Telegram";
                }
            }

            const btnMonitoring = document.getElementById(
                "btn-monitoring-kesehatan",
            );
            if (btnMonitoring && id)
                btnMonitoring.href = `/lansia/${id}/monitoring`;

            const btnHistori = document.getElementById("btn-histori-skrining");
            if (btnHistori && id)
                btnHistori.href = `/lansia/${id}/histori-skrining`;

            fetchHealthSummary(id);
            fetchKeluargaData(id);

            requestAnimationFrame(() => {
                detailPanel.style.transition = "opacity 0.3s ease";
                detailPanel.style.opacity = "1";
            });

            detailPanel.scrollIntoView({ behavior: "smooth", block: "start" });
        });
    });

    // â”€â”€ Restore selected lansia saat kembali dari halaman lain â”€â”€
    const lastId = sessionStorage.getItem("lastSelectedLansiaId");
    if (lastId) {
        const targetRow = document.querySelector(
            `.selectable-row[data-id="${lastId}"]`,
        );
        if (targetRow) {
            // Trigger click tanpa scroll animasi â€” langsung render panel
            targetRow.click();
        } else {
            // ID tidak ada di halaman ini (misal pindah halaman paginator) â€” hapus state
            sessionStorage.removeItem("lastSelectedLansiaId");
        }
    }

    function setText(id, val) {
        const el = document.getElementById(id);
        if (el) el.innerText = val;
    }

    async function fetchHealthSummary(id) {
        [
            "d-sistolik",
            "d-diastolik",
            "d-gula",
            "d-kolesterol",
            "d-imt",
        ].forEach((k) => setText(k, "--"));
        // Reset card status colors
        [
            "hcard-sistolik",
            "hcard-diastolik",
            "hcard-gula",
            "hcard-kolesterol",
            "hcard-imt",
        ].forEach((id) => {
            const el = document.getElementById(id);
            if (el) el.className = "h-card";
        });
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
            setText("d-imt", data.imt ?? "-");
            setText(
                "detail-tb",
                data.tinggi_badan
                    ? `Tinggi: ${data.tinggi_badan} cm`
                    : "Tinggi: -",
            );

            // Apply status colors to health cards based on elderly parameters from backend
            if (data.detail) {
                applyCardStatus("hcard-sistolik", data.detail.sistolik.status);
                applyCardStatus(
                    "hcard-diastolik",
                    data.detail.diastolik.status,
                );
                applyCardStatus("hcard-gula", data.detail.gula_darah.status);
                applyCardStatus(
                    "hcard-kolesterol",
                    data.detail.kolesterol.status,
                );
                applyCardStatus("hcard-imt", data.detail.imt.status);
            }
        } catch {
            [
                "d-sistolik",
                "d-diastolik",
                "d-gula",
                "d-kolesterol",
                "d-imt",
            ].forEach((k) => setText(k, "-"));
        }
    }

    function applyCardStatus(cardId, status) {
        const el = document.getElementById(cardId);
        if (!el || !status) return;
        // Map 'perlu_tindak_lanjut' to 'tinggi' to match existing CSS class (.status-tinggi)
        const mappedStatus =
            status === "perlu_tindak_lanjut" ? "tinggi" : status;
        el.classList.add(`status-${mappedStatus}`);
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

    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    // SHARED VALIDATION FUNCTIONS (dipakai oleh tambah & edit)
    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

    function validateNIK(value) {
        if (!value || !value.trim()) return "NIK tidak boleh kosong";
        if (!/^\d{16}$/.test(value.trim())) return "NIK harus 16 digit angka";
        return "";
    }

    function validateNama(value) {
        if (!value || !value.trim()) return "Nama Lansia tidak boleh kosong";
        if (value.trim().length < 3) return "Nama Lansia minimal 3 karakter";
        return "";
    }

    function validateTanggalLahir(value) {
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

    function validateNoHP(value) {
        if (!value || !value.trim()) return "";
        // Cukup cek digit 10-13 angka, boleh awali +62 atau 0
        if (!/^(\+62|0)[0-9]{9,12}$/.test(value.trim())) {
            return "Format No HP tidak valid (Contoh: 081234567890)";
        }
        return "";
    }

    function validateEmail(value) {
        if (!value || !value.trim()) return "";
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim()))
            return "Format Email tidak valid";
        return "";
    }

    function validateAlamat(value) {
        if (!value || !value.trim()) return "Alamat Lansia tidak boleh kosong";
        return "";
    }

    // Helper for checking uniqueness via AJAX
    async function checkUniqueField(
        fieldId,
        dbField,
        value,
        isEdit,
        ignoreId = null,
    ) {
        if (!value.trim()) return false; // let format validator handle empty
        try {
            let url = `/lansia/check-unique?field=${dbField}&value=${encodeURIComponent(value)}`;
            if (ignoreId) url += `&ignore_id=${ignoreId}`;

            const res = await fetch(url, {
                headers: { "X-Requested-With": "XMLHttpRequest" },
            });
            if (!res.ok) return false;
            const data = await res.json();
            return data.exists;
        } catch (err) {
            console.error("Gagal cek keunikan", err);
            return false;
        }
    }

    function validatePekerjaan(value) {
        if (!value || !String(value).trim())
            return "Pilih pekerjaan terlebih dahulu";
        const v = String(value).trim();
        if (/^\d+$/.test(v)) return ""; // numeric option selected
        if (v.length < 3)
            return "Masukkan pekerjaan lainnya minimal 3 karakter";
        return ""; // custom text OK
    }

    function isNumericString(v) {
        return /^\d+$/.test(String(v));
    }

    function transformSelectToInput(selectEl, initialValue) {
        if (!selectEl || selectEl.tagName !== "SELECT") return null;
        const parent = selectEl.parentNode;
        const origHtml = selectEl.innerHTML;
        // create input to replace select
        const input = document.createElement("input");
        input.type = "text";
        input.id = selectEl.id;
        input.name = selectEl.name;
        input.className = selectEl.className;
        input.placeholder =
            selectEl.getAttribute("data-placeholder") ||
            "Masukkan pekerjaan lainnya";
        input.value = initialValue || "";
        // mark original options to restore later
        input.dataset._origOptions = origHtml;
        input.dataset._wasSelect = "1";
        parent.replaceChild(input, selectEl);
        return input;
    }

    function transformInputToSelect(inputEl) {
        if (
            !inputEl ||
            inputEl.tagName !== "INPUT" ||
            !inputEl.dataset._wasSelect
        )
            return null;
        const parent = inputEl.parentNode;
        const select = document.createElement("select");
        select.id = inputEl.id;
        select.name = inputEl.name;
        select.className = inputEl.className;
        try {
            select.innerHTML =
                inputEl.dataset._origOptions ||
                "<option value=''>Pilih</option>";
        } catch (e) {
            select.innerHTML = "<option value=''>Pilih</option>";
        }

        parent.replaceChild(select, inputEl);
        return select;
    }

    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    // 4. MODAL TAMBAH LANSIA
    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    const modalTambah = document.getElementById("modal-tambah-lansia");
    const formTambah = document.getElementById("form-tambah-lansia");
    // âš ï¸ FIX: cari submitBtn di dalam form, bukan di luar
    const submitBtnTambah = formTambah?.querySelector('button[type="submit"]');

    // Helper show/hide error untuk form tambah
    function showTambahError(fieldId, message) {
        const errorEl = document.getElementById(`error-${fieldId}`);
        const inputEl = document.getElementById(fieldId);
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

    function isTambahFormValid() {
        // Prevent submit if there's an active uniqueness error
        const checkErr = (id) => {
            const el = document.getElementById(`error-${id}`);
            return (
                el &&
                el.style.display === "block" &&
                el.textContent.includes("sudah terdaftar")
            );
        };
        if (checkErr("nik") || checkErr("no_hp") || checkErr("email"))
            return false;

        return (
            !validateNIK(document.getElementById("nik")?.value) &&
            !validateNama(document.getElementById("nama_lansia")?.value) &&
            !validateTanggalLahir(
                document.getElementById("tanggal_lahir")?.value,
            ) &&
            !validateNoHP(document.getElementById("no_hp")?.value) &&
            !validateEmail(document.getElementById("email")?.value) &&
            !validateAlamat(document.getElementById("alamat")?.value) &&
            !validatePekerjaan(document.getElementById("pekerjaan")?.value)
        );
    }

    function updateTambahSubmitBtn() {
        if (submitBtnTambah) submitBtnTambah.disabled = !isTambahFormValid();
    }

    // Pasang event listener validasi real-time form tambah
    if (formTambah) {
        // NIK validation
        const nikEl = document.getElementById("nik");
        nikEl?.addEventListener("blur", async function () {
            let err = validateNIK(this.value);
            if (!err) {
                const exists = await checkUniqueField(
                    "nik",
                    "nik",
                    this.value,
                    false,
                );
                if (exists) err = "NIK ini sudah terdaftar.";
            }
            showTambahError("nik", err);
            updateTambahSubmitBtn();
        });
        nikEl?.addEventListener("input", function () {
            updateTambahSubmitBtn();
        });

        // Nama validation
        const namaEl = document.getElementById("nama_lansia");
        namaEl?.addEventListener("blur", function () {
            showTambahError("nama_lansia", validateNama(this.value));
            updateTambahSubmitBtn();
        });
        namaEl?.addEventListener("input", function () {
            updateTambahSubmitBtn();
        });

        // Tanggal Lahir validation - both change dan input untuk responsif
        const tanggalEl = document.getElementById("tanggal_lahir");
        tanggalEl?.addEventListener("change", function () {
            showTambahError("tanggal_lahir", validateTanggalLahir(this.value));
            updateTambahSubmitBtn();
        });
        tanggalEl?.addEventListener("input", function () {
            updateTambahSubmitBtn();
        });

        // No HP validation
        const noHpEl = document.getElementById("no_hp");
        noHpEl?.addEventListener("blur", async function () {
            let err = validateNoHP(this.value);
            if (!err && this.value.trim()) {
                const exists = await checkUniqueField(
                    "no_hp",
                    "no_hp",
                    this.value,
                    false,
                );
                if (exists) err = "No HP ini sudah terdaftar.";
            }
            showTambahError("no_hp", err);
            updateTambahSubmitBtn();
        });
        noHpEl?.addEventListener("input", function () {
            updateTambahSubmitBtn();
        });

        // Email validation
        const emailEl = document.getElementById("email");
        emailEl?.addEventListener("blur", async function () {
            let err = validateEmail(this.value);
            if (!err && this.value.trim()) {
                const exists = await checkUniqueField(
                    "email",
                    "email",
                    this.value,
                    false,
                );
                if (exists) err = "Email ini sudah terdaftar.";
            }
            showTambahError("email", err);
            updateTambahSubmitBtn();
        });
        emailEl?.addEventListener("input", function () {
            updateTambahSubmitBtn();
        });

        // Alamat validation
        const alamatEl = document.getElementById("alamat");
        alamatEl?.addEventListener("blur", function () {
            showTambahError("alamat", validateAlamat(this.value));
            updateTambahSubmitBtn();
        });
        alamatEl?.addEventListener("input", function () {
            updateTambahSubmitBtn();
        });

        // Pekerjaan validation: make 'Lainnya' editable inline (swap select -> input)
        let pekerjaanEl = document.getElementById("pekerjaan");
        if (pekerjaanEl) {
            pekerjaanEl.addEventListener("change", function () {
                if (this.value === "7") {
                    // replace select with input inline
                    const input = transformSelectToInput(this, "");
                    if (input) {
                        input.addEventListener("blur", function () {
                            showTambahError(
                                "pekerjaan",
                                validatePekerjaan(this.value),
                            );
                            updateTambahSubmitBtn();
                        });
                        input.addEventListener("input", function () {
                            updateTambahSubmitBtn();
                        });
                    }
                    // initial validation state
                    showTambahError(
                        "pekerjaan",
                        validatePekerjaan(input?.value),
                    );
                } else {
                    showTambahError("pekerjaan", validatePekerjaan(this.value));
                }
                updateTambahSubmitBtn();
            });
            pekerjaanEl.addEventListener("blur", function () {
                showTambahError("pekerjaan", validatePekerjaan(this.value));
                updateTambahSubmitBtn();
            });
        }

        // Submit handler form tambah
        formTambah.addEventListener("submit", function (e) {
            // Tampilkan semua error
            showTambahError(
                "nik",
                validateNIK(document.getElementById("nik")?.value),
            );
            showTambahError(
                "nama_lansia",
                validateNama(document.getElementById("nama_lansia")?.value),
            );
            showTambahError(
                "tanggal_lahir",
                validateTanggalLahir(
                    document.getElementById("tanggal_lahir")?.value,
                ),
            );
            showTambahError(
                "no_hp",
                validateNoHP(document.getElementById("no_hp")?.value),
            );
            showTambahError(
                "email",
                validateEmail(document.getElementById("email")?.value),
            );
            showTambahError(
                "alamat",
                validateAlamat(document.getElementById("alamat")?.value),
            );
            showTambahError(
                "pekerjaan",
                validatePekerjaan(document.getElementById("pekerjaan")?.value),
            );

            // Validasi minimal satu keluarga dengan nama terisi
            const keluargaContainer =
                document.getElementById("keluarga-container");
            const errorKeluargaEl = document.getElementById("error-keluarga");
            let hasValidKeluarga = false;
            if (keluargaContainer) {
                const keluargaItems =
                    keluargaContainer.querySelectorAll(".keluarga-item");
                keluargaItems.forEach((item) => {
                    const namaInput = item.querySelector(
                        ".nama_keluarga_input",
                    );
                    if (
                        namaInput &&
                        namaInput.value &&
                        namaInput.value.trim().length >= 3
                    ) {
                        hasValidKeluarga = true;
                    }
                });
            }

            if (!hasValidKeluarga) {
                const firstKeluargaItem =
                    keluargaContainer?.querySelector(".keluarga-item");
                if (firstKeluargaItem) {
                    const namaInput = firstKeluargaItem.querySelector(
                        ".nama_keluarga_input",
                    );
                    const errorNameEl = firstKeluargaItem.querySelector(
                        ".error-keluarga-nama-0",
                    );
                    if (namaInput) {
                        namaInput.style.borderColor = "#e74c3c";
                        namaInput.style.borderWidth = "2px";
                        // Show error message
                        if (errorNameEl) {
                            errorNameEl.textContent =
                                "Minimal nama anggota keluarga pertama minimal 3 karakter.";
                            errorNameEl.style.display = "block";
                        }
                        // Scroll to first keluarga
                        namaInput.focus();
                        namaInput.scrollIntoView({
                            behavior: "smooth",
                            block: "center",
                        });
                    }
                }
                if (errorKeluargaEl) {
                    errorKeluargaEl.textContent =
                        "Minimal satu anggota keluarga harus diisi.";
                    errorKeluargaEl.style.display = "block";
                }
                console.warn(
                    "âš ï¸ Form submit prevented: No valid keluarga found",
                );
                e.preventDefault();
                return;
            } else {
                // Clear error styling from keluarga
                const firstKeluargaItem =
                    keluargaContainer?.querySelector(".keluarga-item");
                if (firstKeluargaItem) {
                    const namaInput = firstKeluargaItem.querySelector(
                        ".nama_keluarga_input",
                    );
                    const errorNameEl = firstKeluargaItem.querySelector(
                        ".error-keluarga-nama-0",
                    );
                    if (namaInput) {
                        namaInput.style.borderColor = "";
                        namaInput.style.borderWidth = "";
                    }
                    if (errorNameEl) {
                        errorNameEl.style.display = "none";
                    }
                }
                if (errorKeluargaEl) {
                    errorKeluargaEl.style.display = "none";
                }
            }

            if (!isTambahFormValid()) {
                console.warn(
                    "âš ï¸ Form submit prevented: Form validation failed",
                );
                e.preventDefault();
            } else {
                console.log("✓ Form submission allowed");
            }
        });

        // Initial state: disable submit
        if (submitBtnTambah) submitBtnTambah.disabled = true;
    }

    // Dinamis tambah keluarga - form tambah
    let keluargaCount = 1;

    document
        .getElementById("btn-tambah-keluarga")
        ?.addEventListener("click", function (e) {
            e.preventDefault();
            const container = document.getElementById("keluarga-container");
            if (!container) return;
            keluargaCount++;

            const newItem = document.createElement("div");
            newItem.className = "keluarga-item";
            newItem.style.cssText =
                "padding: 15px; background-color: #f9f9f9; border-radius: 8px; margin-bottom: 15px; border: 1px solid #e0e0e0;";
            newItem.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <h4 style="margin: 0; color: #555;">Anggota Keluarga #${keluargaCount}</h4>
                <button type="button" class="btn-remove-keluarga" style="background: none; border: none; color: #e74c3c; cursor: pointer; font-size: 18px; padding: 0;">âœ•</button>
            </div>
            <div class="form-group">
                <label>Nama Keluarga</label>
                <input type="text" class="nama_keluarga_input" name="keluarga[${keluargaCount - 1}][nama_keluarga]" placeholder="Masukkan nama anggota keluarga">
            </div>
            <div class="form-group" style="display: flex; gap: 10px;">
                <div style="flex: 1;">
                    <label>No Telepon (Opsional)</label>
                    <input type="text" class="no_sama_input" name="keluarga[${keluargaCount - 1}][no_sama]" placeholder="Contoh: 081234567890">
                </div>
                <div style="flex: 1;">
                    <label>Alamat (Opsional)</label>
                    <input type="text" class="alamat_keluarga_input" name="keluarga[${keluargaCount - 1}][alamat]" placeholder="Alamat anggota keluarga">
                </div>
            </div>
        `;
            container.appendChild(newItem);

            // Add listener untuk input baru agar update button state
            const namaInput = newItem.querySelector(".nama_keluarga_input");
            namaInput?.addEventListener("input", function () {
                updateTambahSubmitBtn();
            });

            newItem
                .querySelector(".btn-remove-keluarga")
                .addEventListener("click", function () {
                    newItem.remove();
                    updateTambahSubmitBtn();
                });
        });

    // Pasang listener hapus untuk item pertama (yang sudah ada di HTML)
    document
        .querySelectorAll("#keluarga-container .btn-remove-keluarga")
        .forEach((btn) => {
            btn.addEventListener("click", function () {
                this.closest(".keluarga-item").remove();
                updateTambahSubmitBtn();
            });
        });

    // Pasang listener input untuk item pertama keluarga
    document
        .querySelectorAll("#keluarga-container .nama_keluarga_input")
        .forEach((inp) => {
            inp.addEventListener("input", function () {
                updateTambahSubmitBtn();
            });
        });

    // Buka modal tambah
    document
        .getElementById("btn-tambah-lansia")
        ?.addEventListener("click", () => {
            formTambah?.reset();
            // Reset semua error
            [
                "nik",
                "nama_lansia",
                "tanggal_lahir",
                "no_hp",
                "email",
                "alamat",
            ].forEach((f) => showTambahError(f, ""));
            // Reset pekerjaan fields: ensure select is present
            showTambahError("pekerjaan", "");
            let pj = document.getElementById("pekerjaan");
            if (pj && pj.tagName === "INPUT" && pj.dataset._wasSelect) {
                pj = transformInputToSelect(pj);
            }
            if (pj) {
                pj.value = "";
                if (pj.tagName === "SELECT") {
                    pj.addEventListener("change", function () {
                        if (this.value === "7") {
                            const input = transformSelectToInput(this, "");
                            if (input) {
                                input.addEventListener("blur", function () {
                                    showTambahError(
                                        "pekerjaan",
                                        validatePekerjaan(this.value),
                                    );
                                    updateTambahSubmitBtn();
                                });
                                input.addEventListener("input", function () {
                                    updateTambahSubmitBtn();
                                });
                            }
                        }
                        showTambahError(
                            "pekerjaan",
                            validatePekerjaan(this.value),
                        );
                        updateTambahSubmitBtn();
                    });
                    pj.addEventListener("blur", function () {
                        showTambahError(
                            "pekerjaan",
                            validatePekerjaan(this.value),
                        );
                        updateTambahSubmitBtn();
                    });
                }
            }
            // Reset submit button state
            if (submitBtnTambah) submitBtnTambah.disabled = true;
            // Reset keluarga styling
            const keluargaContainer =
                document.getElementById("keluarga-container");
            if (keluargaContainer) {
                const firstKeluargaItem =
                    keluargaContainer.querySelector(".keluarga-item");
                if (firstKeluargaItem) {
                    const namaInput = firstKeluargaItem.querySelector(
                        ".nama_keluarga_input",
                    );
                    if (namaInput) {
                        namaInput.style.borderColor = "";
                        namaInput.style.borderWidth = "";
                    }
                }
            }
            modalTambah?.classList.add("active");
        });

    setupModalClose(modalTambah, [
        document.getElementById("btn-close-modal"),
        document.getElementById("btn-cancel-modal"),
    ]);

    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    // 5. MODAL EDIT LANSIA
    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    const modalEdit = document.getElementById("modal-edit-lansia");
    const editForm = document.getElementById("form-edit-lansia");
    // âš ï¸ FIX: cari submitBtn di dalam form edit langsung
    const editSubmitBtn = editForm?.querySelector('button[type="submit"]');

    if (!editForm) {
        console.error("âŒ editForm (form-edit-lansia) NOT FOUND in DOM");
    } else {
        console.log("âœ“ editForm found at initialization");
    }

    // Helper show/hide error untuk form edit
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

    function isEditFormValid() {
        // Prevent submit if there's an active uniqueness error
        const checkErr = (id) => {
            const el = document.getElementById(`error-edit_${id}`);
            return (
                el &&
                el.style.display === "block" &&
                el.textContent.includes("sudah terdaftar")
            );
        };
        if (checkErr("nik") || checkErr("no_hp") || checkErr("email"))
            return false;

        return (
            !validateNIK(document.getElementById("edit_nik")?.value) &&
            !validateNama(document.getElementById("edit_nama_lansia")?.value) &&
            !validateTanggalLahir(
                document.getElementById("edit_tanggal_lahir")?.value,
            ) &&
            !validateNoHP(document.getElementById("edit_no_hp")?.value) &&
            !validateEmail(document.getElementById("edit_email")?.value) &&
            !validateAlamat(document.getElementById("edit_alamat")?.value) &&
            validateKeluargaPertama() &&
            !validatePekerjaan(document.getElementById("edit_pekerjaan")?.value)
        );
    }

    function updateEditSubmitBtn() {
        if (editSubmitBtn) editSubmitBtn.disabled = !isEditFormValid();
    }

    // Real-time validation edit form
    if (editForm) {
        document
            .getElementById("edit_nik")
            ?.addEventListener("blur", async function () {
                let err = validateNIK(this.value);
                if (!err) {
                    const lansiaId =
                        editForm.action.match(/\/lansia\/(\d+)/)?.[1];
                    const exists = await checkUniqueField(
                        "edit_nik",
                        "nik",
                        this.value,
                        true,
                        lansiaId,
                    );
                    if (exists) err = "NIK ini sudah terdaftar.";
                }
                showEditError("nik", err);
                updateEditSubmitBtn();
            });
        document
            .getElementById("edit_nama_lansia")
            ?.addEventListener("blur", function () {
                showEditError("nama_lansia", validateNama(this.value));
                updateEditSubmitBtn();
            });
        document
            .getElementById("edit_tanggal_lahir")
            ?.addEventListener("change", function () {
                showEditError(
                    "tanggal_lahir",
                    validateTanggalLahir(this.value),
                );
                updateEditSubmitBtn();
            });
        document
            .getElementById("edit_no_hp")
            ?.addEventListener("blur", async function () {
                let err = validateNoHP(this.value);
                if (!err && this.value.trim()) {
                    const lansiaId =
                        editForm.action.match(/\/lansia\/(\d+)/)?.[1];
                    const exists = await checkUniqueField(
                        "edit_no_hp",
                        "no_hp",
                        this.value,
                        true,
                        lansiaId,
                    );
                    if (exists) err = "No HP ini sudah terdaftar.";
                }
                showEditError("no_hp", err);
                updateEditSubmitBtn();
            });
        document
            .getElementById("edit_email")
            ?.addEventListener("blur", async function () {
                let err = validateEmail(this.value);
                if (!err && this.value.trim()) {
                    const lansiaId =
                        editForm.action.match(/\/lansia\/(\d+)/)?.[1];
                    const exists = await checkUniqueField(
                        "edit_email",
                        "email",
                        this.value,
                        true,
                        lansiaId,
                    );
                    if (exists) err = "Email ini sudah terdaftar.";
                }
                showEditError("email", err);
                updateEditSubmitBtn();
            });
        document
            .getElementById("edit_alamat")
            ?.addEventListener("blur", function () {
                showEditError("alamat", validateAlamat(this.value));
                updateEditSubmitBtn();
            });

        // Edit pekerjaan handlers: inline swap select -> input for 'Lainnya'
        const editPekerjaanEl = document.getElementById("edit_pekerjaan");
        if (editPekerjaanEl) {
            editPekerjaanEl.addEventListener("change", function () {
                if (this.value === "7") {
                    const input = transformSelectToInput(this, "");
                    if (input) {
                        input.addEventListener("blur", function () {
                            showEditError(
                                "pekerjaan",
                                validatePekerjaan(this.value),
                            );
                            updateEditSubmitBtn();
                        });
                        input.addEventListener("input", function () {
                            updateEditSubmitBtn();
                        });
                    }
                } else {
                    showEditError("pekerjaan", validatePekerjaan(this.value));
                }
                updateEditSubmitBtn();
            });
            editPekerjaanEl.addEventListener("blur", function () {
                showEditError("pekerjaan", validatePekerjaan(this.value));
                updateEditSubmitBtn();
            });
        }

        // Validasi realtime nama keluarga pertama (event delegation)
        document
            .getElementById("edit-keluarga-container")
            ?.addEventListener("input", function (e) {
                const container = document.getElementById(
                    "edit-keluarga-container",
                );
                const firstItem = container?.querySelector(
                    ".keluarga-item-edit",
                );
                if (!firstItem) return;
                const firstInput = firstItem.querySelector(
                    ".nama_keluarga_input",
                );
                if (e.target === firstInput) {
                    validateKeluargaPertama();
                    updateEditSubmitBtn();
                }
            });

        // Submit handler edit form
        editForm.addEventListener("submit", function (e) {
            console.log("ðŸ“ Edit form submitted!");
            console.log("  - Form action:", this.action);

            // Safety check: pastikan action sudah berisi ID yang valid
            if (
                !this.action ||
                this.action.includes("undefined") ||
                !this.action.match(/\/lansia\/\d+/)
            ) {
                console.error(
                    "âŒ ABORT: Form action tidak valid:",
                    this.action,
                );
                e.preventDefault();
                alert(
                    "âŒ Kesalahan: Form tidak siap. Coba tutup dan buka kembali modal edit.",
                );
                return;
            }

            // Tampilkan semua error
            showEditError(
                "nik",
                validateNIK(document.getElementById("edit_nik")?.value),
            );
            showEditError(
                "nama_lansia",
                validateNama(
                    document.getElementById("edit_nama_lansia")?.value,
                ),
            );
            showEditError(
                "tanggal_lahir",
                validateTanggalLahir(
                    document.getElementById("edit_tanggal_lahir")?.value,
                ),
            );
            showEditError(
                "no_hp",
                validateNoHP(document.getElementById("edit_no_hp")?.value),
            );
            showEditError(
                "email",
                validateEmail(document.getElementById("edit_email")?.value),
            );
            showEditError(
                "alamat",
                validateAlamat(document.getElementById("edit_alamat")?.value),
            );
            showEditError(
                "pekerjaan",
                validatePekerjaan(
                    document.getElementById("edit_pekerjaan")?.value,
                ),
            );
            validateKeluargaPertama();

            if (!isEditFormValid()) {
                e.preventDefault();
            } else {
                console.log("✓ Form Edit submission allowed");
                const loader = document.getElementById(
                    "global-loading-overlay",
                );
                if (loader) loader.style.display = "flex";
                if (editSubmitBtn) {
                    editSubmitBtn.style.pointerEvents = "none";
                    editSubmitBtn.innerText = "Menyimpan...";
                }
            }
        });
    }

    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    // EDIT KELUARGA â€” helpers
    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    let editKeluargaCount = 0;

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, "&amp;")
            .replace(/"/g, "&quot;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;");
    }

    function buatItemKeluargaEdit(idx, fam = {}, isFirst = false) {
        const nomor = idx + 1;
        const hapusBtn = isFirst
            ? ""
            : `<button type="button" class="btn-remove-keluarga-edit"
            style="background:none; border:none; color:#e74c3c; cursor:pointer; font-size:18px; padding:0;"
            title="Hapus anggota keluarga ini">âœ•</button>`;
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

    function renumberKeluargaEdit() {
        const container = document.getElementById("edit-keluarga-container");
        if (!container) return;
        container.querySelectorAll(".keluarga-item-edit").forEach((el, i) => {
            const h4 = el.querySelector("h4");
            if (h4) {
                const labelWajib =
                    i === 0
                        ? `<span style="color:#e74c3c; font-size:11px; margin-left:6px; font-weight:400;">*Wajib</span>`
                        : "";
                h4.innerHTML = `Anggota Keluarga #${i + 1} ${labelWajib}`;
            }
            el.querySelectorAll("input").forEach((inp) => {
                inp.name = inp.name.replace(
                    /keluarga\[\d+\]/,
                    `keluarga[${i}]`,
                );
            });
        });
    }

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

    // Tombol + Tambah Anggota Keluarga di modal edit
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
            const newIdx = currentItems.length;
            editKeluargaCount++;
            container.appendChild(buatItemKeluargaEdit(newIdx, {}, false));
        });

    // Buka modal edit saat tombol edit diklik
    document.querySelectorAll(".edit-btn").forEach((btn) => {
        btn.addEventListener("click", async function () {
            const row = btn.closest("tr");
            if (!row) {
                console.error("âŒ Row not found!");
                return;
            }

            const lansiaId = row.dataset.id;
            console.log("âœ“ Edit clicked, Lansia ID:", lansiaId);

            if (!editForm) {
                console.error("âŒ editForm is null!");
                return;
            }

            // âš ï¸ FIX UTAMA: Set form action dengan ID yang benar
            editForm.action = `/lansia/${lansiaId}`;
            console.log("âœ“ Form action set:", editForm.action);

            // Isi semua field
            setVal("edit_nama_lansia", row.dataset.nama);
            setVal("edit_nik", row.dataset.nik);
            setVal("edit_alamat", row.dataset.alamat);
            setVal("edit_tanggal_lahir", row.dataset.tanggalLahir);
            setVal("edit_jenis_kelamin", row.dataset.jenisKelamin || "L");
            setVal("edit_no_hp", row.dataset.noHp);
            setVal("edit_tempat_lahir", row.dataset.tempatLahir);
            setVal("edit_status_perkawinan", row.dataset.statusPerkawinan);
            setVal("edit_riwayat_penyakit", row.dataset.riwayatPenyakit);
            setVal("edit_keterangan", row.dataset.keterangan);
            setVal("edit_email", row.dataset.email);
            // Set pekerjaan field; if dataset contains custom text, swap select -> input
            setVal("edit_pekerjaan", row.dataset.pekerjaan);
            const editPjEl = document.getElementById("edit_pekerjaan");
            if (
                row.dataset.pekerjaan &&
                !isNumericString(row.dataset.pekerjaan)
            ) {
                // replace select with input and set custom value
                const input = transformSelectToInput(
                    editPjEl,
                    row.dataset.pekerjaan,
                );
                if (input) {
                    input.addEventListener("blur", function () {
                        showEditError(
                            "pekerjaan",
                            validatePekerjaan(this.value),
                        );
                        updateEditSubmitBtn();
                    });
                    input.addEventListener("input", function () {
                        updateEditSubmitBtn();
                    });
                }
            }

            // Reset semua pesan error
            [
                "nik",
                "nama_lansia",
                "tanggal_lahir",
                "no_hp",
                "email",
                "alamat",
            ].forEach((f) => showEditError(f, ""));

            // If edit_pekerjaan is still a select, trigger change to wire listeners
            setTimeout(() => {
                const el = document.getElementById("edit_pekerjaan");
                if (el && el.tagName === "SELECT")
                    el.dispatchEvent(new Event("change"));
            }, 50);

            // Load data keluarga
            await loadKeluargaForEdit(lansiaId);

            modalEdit?.classList.add("active");

            // Trigger validasi setelah modal terbuka dan data terisi
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
                updateEditSubmitBtn();
            }, 200);
        });
    });

    document
        .getElementById("btn-histori-skrining")
        ?.addEventListener("click", function (e) {
            if (this.getAttribute("href") === "#") {
                e.preventDefault();
                alert("Silakan pilih lansia dari tabel terlebih dahulu.");
            }
        });

    setupModalClose(modalEdit, [
        document.getElementById("btn-close-edit-modal"),
        document.getElementById("btn-cancel-edit-modal"),
    ]);

    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    // 6. Modal Hapus Lansia
    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
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

    // Form Hapus trigger loading overlay
    formHapus?.addEventListener("submit", () => {
        const loader = document.getElementById("global-loading-overlay");
        if (loader) loader.style.display = "flex";
        const submitBtn = formHapus.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.style.pointerEvents = "none";
            submitBtn.innerText = "Menghapus...";
        }
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

    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    // 7. Modal Filter
    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
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

    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    // HELPERS
    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
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
