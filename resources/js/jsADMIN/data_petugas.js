document.addEventListener("DOMContentLoaded", function () {
    const roleHost = document.querySelector("[data-current-role]");
    const currentRole = roleHost?.dataset.currentRole || "";
    const modal = document.getElementById("modalTambahPetugas");
    const editModal = document.getElementById("modalEditPetugas");
    const btnOpen = document.getElementById("btn-tambah-petugas");
    const btnClose = document.getElementById("btn-close-tambah-petugas");
    const btnCancel = document.getElementById("btn-batal-tambah-petugas");
    const btnCloseEdit = document.getElementById("btn-close-edit-petugas");
    const btnCancelEdit = document.getElementById("btn-batal-edit-petugas");
    const form = document.getElementById("form-tambah-petugas");
    const editForm = document.getElementById("form-edit-petugas");
    const submitBtn = document.getElementById("btn-submit-tambah-petugas");
    const submitEditBtn = document.getElementById("btn-submit-edit-petugas");
    const photoInput = document.getElementById("foto");
    const photoZone = document.getElementById("petugas-photo-zone");
    const photoPreview = document.getElementById("petugas-photo-preview");
    const photoFallback = document.getElementById("petugas-photo-fallback");
    const jabatanSelects = document.querySelectorAll(
        'select[name="jabatan"], #jabatan',
    );
    const editButtons = document.querySelectorAll(".btn-open-edit-petugas");
    const editFields = {
        nama: document.getElementById("edit-nama"),
        nik: document.getElementById("edit-nik"),
        jabatan: document.getElementById("edit-jabatan"),
        no_hp: document.getElementById("edit-no_hp"),
        email: document.getElementById("edit-email"),
    };

    function openEditModal() {
        if (!editModal) return;
        editModal.classList.add("open");
        document.body.style.overflow = "hidden";
    }

    function closeEditModal() {
        if (!editModal) return;
        editModal.classList.remove("open");
        document.body.style.overflow = "";
    }

    function lockJabatanForKepalaKader() {
        if (currentRole !== "kepala_kader") return;

        jabatanSelects.forEach((select) => {
            if (!select || select.tagName !== "SELECT") return;

            const kepalaKaderOption = select.querySelector(
                'option[value="kepala_kader"]',
            );
            if (kepalaKaderOption) {
                kepalaKaderOption.remove();
            }

            const kaderOption = select.querySelector('option[value="kader"]');
            if (kaderOption) {
                kaderOption.selected = true;
            }

            select.value = "kader";
            select.disabled = false;
        });
    }

    lockJabatanForKepalaKader();

    function openModal() {
        if (!modal) return;
        modal.classList.add("open");
        document.body.style.overflow = "hidden";
    }

    function closeModal() {
        if (!modal) return;
        modal.classList.remove("open");
        document.body.style.overflow = "";
    }

    btnOpen?.addEventListener("click", openModal);
    btnClose?.addEventListener("click", closeModal);
    btnCancel?.addEventListener("click", closeModal);
    btnCloseEdit?.addEventListener("click", closeEditModal);
    btnCancelEdit?.addEventListener("click", closeEditModal);

    modal?.addEventListener("click", function (e) {
        if (e.target === modal) closeModal();
    });

    editModal?.addEventListener("click", function (e) {
        if (e.target === editModal) closeEditModal();
    });

    editButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const id = this.dataset.petugasId || "";
            const updateTemplate = this.dataset.updateUrlTemplate || "";

            if (editForm && updateTemplate) {
                editForm.action = updateTemplate.replace("__ID__", id);
            }

            if (editFields.nama)
                editFields.nama.value = this.dataset.petugasNama || "";
            if (editFields.nik)
                editFields.nik.value = this.dataset.petugasNik || "";
            if (editFields.no_hp)
                editFields.no_hp.value = this.dataset.petugasNoHp || "";
            if (editFields.email)
                editFields.email.value = this.dataset.petugasEmail || "";

            if (editFields.jabatan) {
                const requestedJabatan = this.dataset.petugasJabatan || "kader";
                editFields.jabatan.value = requestedJabatan;
                if (currentRole === "kepala_kader") {
                    editFields.jabatan.value = "kader";
                }
            }

            openEditModal();
        });
    });

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") closeModal();
    });

    photoZone?.addEventListener("click", function () {
        photoInput?.click();
    });

    photoInput?.addEventListener("change", function () {
        const file = this.files && this.files[0];
        if (!file || !photoPreview) return;

        const reader = new FileReader();
        reader.onload = function (event) {
            photoPreview.innerHTML = `<img src="${event.target.result}" alt="Preview Foto">`;
            photoZone?.classList.add("has-photo");
            photoFallback?.remove();
        };
        reader.readAsDataURL(file);
    });

    function showError(fieldId, message) {
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
            return;
        }

        errorEl.style.display = "none";
        if (inputEl) {
            inputEl.style.borderColor = "";
            inputEl.style.borderWidth = "";
        }
    }

    function validateNama(value) {
        if (!value || !value.trim()) return "Nama Lengkap tidak boleh kosong";
        if (value.trim().length < 3) return "Nama Lengkap minimal 3 karakter";
        return "";
    }

    function validateNIK(value) {
        if (!value || !value.trim()) return "NIK tidak boleh kosong";
        if (!/^\d{16}$/.test(value.trim())) return "NIK harus 16 digit angka";
        return "";
    }

    function validateJabatan(value) {
        if (!value || !String(value).trim()) return "Jabatan wajib dipilih";
        return "";
    }

    function validateNoHP(value) {
        if (!value || !value.trim()) return "Nomor WhatsApp tidak boleh kosong";
        if (!/^(\+62|0)[0-9]{9,12}$/.test(value.trim())) {
            return "Format Nomor WhatsApp tidak valid (contoh: 081234567890)";
        }
        return "";
    }

    function validateEmail(value) {
        if (!value || !value.trim()) return "Email tidak boleh kosong";
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim())) {
            return "Format Email tidak valid";
        }
        return "";
    }

    function validatePassword(value) {
        if (!value) return "Kata Sandi tidak boleh kosong";
        if (value.length < 6) return "Kata Sandi minimal 6 karakter";
        return "";
    }

    function isFormValid() {
        return (
            !validateNama(document.getElementById("nama")?.value) &&
            !validateNIK(document.getElementById("nik")?.value) &&
            !validateJabatan(document.getElementById("jabatan")?.value) &&
            !validateNoHP(document.getElementById("no_hp")?.value) &&
            !validateEmail(document.getElementById("email")?.value) &&
            !validatePassword(document.getElementById("password")?.value)
        );
    }

    function updateSubmitState() {
        if (submitBtn) submitBtn.disabled = !isFormValid();
    }

    if (form) {
        const namaEl = document.getElementById("nama");
        const nikEl = document.getElementById("nik");
        const jabatanEl = document.getElementById("jabatan");
        const noHpEl = document.getElementById("no_hp");
        const emailEl = document.getElementById("email");
        const passwordEl = document.getElementById("password");

        namaEl?.addEventListener("blur", function () {
            showError("nama", validateNama(this.value));
            updateSubmitState();
        });
        namaEl?.addEventListener("input", updateSubmitState);

        nikEl?.addEventListener("blur", function () {
            showError("nik", validateNIK(this.value));
            updateSubmitState();
        });
        nikEl?.addEventListener("input", updateSubmitState);

        jabatanEl?.addEventListener("change", function () {
            showError("jabatan", validateJabatan(this.value));
            updateSubmitState();
        });

        noHpEl?.addEventListener("blur", function () {
            showError("no_hp", validateNoHP(this.value));
            updateSubmitState();
        });
        noHpEl?.addEventListener("input", updateSubmitState);

        emailEl?.addEventListener("blur", function () {
            showError("email", validateEmail(this.value));
            updateSubmitState();
        });
        emailEl?.addEventListener("input", updateSubmitState);

        passwordEl?.addEventListener("blur", function () {
            showError("password", validatePassword(this.value));
            updateSubmitState();
        });
        passwordEl?.addEventListener("input", updateSubmitState);

        form.addEventListener("submit", function (e) {
            showError("nama", validateNama(namaEl?.value));
            showError("nik", validateNIK(nikEl?.value));
            showError("jabatan", validateJabatan(jabatanEl?.value));
            showError("no_hp", validateNoHP(noHpEl?.value));
            showError("email", validateEmail(emailEl?.value));
            showError("password", validatePassword(passwordEl?.value));

            if (!isFormValid()) {
                e.preventDefault();
                return;
            }

            if (submitBtn) submitBtn.disabled = true;
        });

        updateSubmitState();
    }

    if (document.getElementById("petugas-form-errors")) {
        openModal();
        ["nama", "nik", "jabatan", "no_hp", "email", "password"].forEach(
            (id) => {
                const errorEl = document.getElementById(`error-${id}`);
                const inputEl = document.getElementById(id);
                if (
                    errorEl &&
                    errorEl.textContent &&
                    errorEl.textContent.trim() &&
                    inputEl
                ) {
                    errorEl.style.display = "block";
                    inputEl.style.borderColor = "#e74c3c";
                    inputEl.style.borderWidth = "2px";
                }
            },
        );
    }

    if (editForm) {
        editForm.addEventListener("submit", function () {
            if (submitEditBtn) submitEditBtn.disabled = true;
        });
    }
});
const searchInput = document.getElementById("searchPetugas");
const filterStatus = document.getElementById("filterStatus");

function filterPetugas() {
    const keyword = searchInput.value.toLowerCase();
    const status = filterStatus.value.toLowerCase();

    document.querySelectorAll(".table tbody tr").forEach((row) => {
        const nama = row.dataset.nama || "";
        const rowStatus = row.dataset.status || "";

        const cocokNama = nama.includes(keyword);
        const cocokStatus = status === "" || rowStatus === status;

        row.style.display = cocokNama && cocokStatus ? "" : "none";
    });
}

searchInput?.addEventListener("input", filterPetugas);
filterStatus?.addEventListener("change", filterPetugas);
