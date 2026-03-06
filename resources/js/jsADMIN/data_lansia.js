/* resources/js/jsAdmin/data_lansia.js */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Animasi Angka Statistik
    const statValues = document.querySelectorAll('.stat-number');
    statValues.forEach(val => {
        const textValue = val.innerText;
        const target = parseInt(textValue.replace(/\D/g, ''));
        if (!isNaN(target)) {
            let count = 0;
            const duration = 1000; // 1 detik
            const increment = target / (duration / 16);

            const update = () => {
                count += increment;
                if (count < target) {
                    val.innerText = Math.ceil(count);
                    requestAnimationFrame(update);
                } else {
                    val.innerText = textValue;
                }
            };
            update();
        }
    });

    // 2. Filter Pencarian Real-time
    const searchInput = document.getElementById('main-search');
    const rows = document.querySelectorAll('.custom-table tbody tr');

    if (searchInput && rows.length > 0) {
        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase();
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }

    // 3. Update Detail Card saat klik "Lihat" (Ikon Mata)
    const viewBtns = document.querySelectorAll('.view-btn');
    viewBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const row = this.closest('tr');
            if (row) {
                const nameElem = row.querySelector('.user-name');
                const name = nameElem ? nameElem.innerText : 'Detail Lansia';
                const detailSection = document.querySelector('.detail-container');

                if (detailSection) {
                    // Animasi Fade Out
                    detailSection.style.opacity = '0.5';

                    setTimeout(() => {
                        const dynamicName = document.getElementById('dynamic-name');
                        const nameDisplay = document.getElementById('name-display');

                        if (dynamicName) dynamicName.innerText = name;
                        if (nameDisplay) nameDisplay.innerText = name;

                        detailSection.style.opacity = '1';

                        // Scroll halus ke arah detail
                        detailSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 200);
                }
            }
        });
    });

    // --- MODAL UTILITIES --- //

    // 4. Modal Tambah Lansia
    const modalTambah = document.getElementById('modal-tambah-lansia');
    const btnTambah = document.getElementById('btn-tambah-lansia');
    const btnCloseModal = document.getElementById('btn-close-modal');
    const btnCancelModal = document.getElementById('btn-cancel-modal');
    const formTambah = modalTambah ? modalTambah.querySelector('form') : null;

    if (btnTambah && modalTambah) {
        btnTambah.addEventListener('click', () => {
            if (formTambah) formTambah.reset(); // Reset form saat dibuka
            modalTambah.classList.add('active');
        });

        const closeModal = () => modalTambah.classList.remove('active');

        if (btnCloseModal) btnCloseModal.addEventListener('click', closeModal);
        if (btnCancelModal) btnCancelModal.addEventListener('click', closeModal);

        modalTambah.addEventListener('click', (e) => {
            if (e.target === modalTambah) closeModal();
        });

        if (formTambah) {
            formTambah.addEventListener('submit', (e) => {
                e.preventDefault();
                // Logic tambah data ke Server bisa menggunakan fetch()
                alert(`Data Lansia Baru: ${document.getElementById('nama_lengkap').value} berhasil ditambahkan! (Simulasi)`);
                closeModal();
            });
        }
    }

    // 5. Modal Edit Lansia
    const modalEdit = document.getElementById('modal-edit-lansia');
    const btnCloseEditModal = document.getElementById('btn-close-edit-modal');
    const btnCancelEditModal = document.getElementById('btn-cancel-edit-modal');
    const editBtns = document.querySelectorAll('.edit-btn');
    const formEdit = modalEdit ? modalEdit.querySelector('form') : null;

    if (modalEdit) {
        const closeEditModal = () => modalEdit.classList.remove('active');

        editBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const row = btn.closest('tr');
                if (row) {
                    // Mengambil nilai referensi dari tabel untuk Form Edit
                    const name = row.querySelector('.user-name')?.innerText || '';
                    const nik = row.querySelector('.user-subtext')?.innerText || '';
                    const disease = row.querySelector('.badge-pill')?.innerText || '';
                    const address = row.querySelector('address')?.innerText || '';

                    // Memasukkan nilai ke field input modal Edit
                    const editName = document.getElementById('edit_nama_lengkap');
                    const editNik = document.getElementById('edit_nik');
                    const editAddr = document.getElementById('edit_alamat');
                    const editDisease = document.getElementById('edit_penyakit');

                    if (editName) editName.value = name;
                    if (editNik) editNik.value = nik;
                    if (editAddr) editAddr.value = address;
                    if (editDisease) editDisease.value = disease;
                }
                modalEdit.classList.add('active');
            });
        });

        if (btnCloseEditModal) btnCloseEditModal.addEventListener('click', closeEditModal);
        if (btnCancelEditModal) btnCancelEditModal.addEventListener('click', closeEditModal);

        modalEdit.addEventListener('click', (e) => {
            if (e.target === modalEdit) closeEditModal();
        });

        if (formEdit) {
            formEdit.addEventListener('submit', (e) => {
                e.preventDefault();
                alert(`Data Lansia: ${document.getElementById('edit_nama_lengkap').value} berhasil diperbarui! (Simulasi)`);
                closeEditModal();
            });
        }
    }

    // 6. Modal Konfirmasi Hapus
    const modalHapus = document.getElementById('modal-hapus-lansia');
    const btnCancelHapus = document.getElementById('btn-cancel-hapus');
    const btnConfirmHapus = document.getElementById('btn-confirm-hapus');
    const deleteBtns = document.querySelectorAll('.delete-btn');
    let rowToDelete = null;

    if (modalHapus) {
        const closeHapusModal = () => {
            modalHapus.classList.remove('active');
            rowToDelete = null;
        };

        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault(); // Mencegah form action
                rowToDelete = this.closest('tr');
                modalHapus.classList.add('active');
            });
        });

        if (btnCancelHapus) btnCancelHapus.addEventListener('click', closeHapusModal);

        if (btnConfirmHapus) {
            btnConfirmHapus.addEventListener('click', () => {
                if (rowToDelete) {
                    // Animasi transisi hapus elemen HTML tabel
                    rowToDelete.style.transition = "opacity 0.3s ease";
                    rowToDelete.style.opacity = "0";
                    setTimeout(() => {
                        rowToDelete.remove();
                    }, 300);
                }
                closeHapusModal();
            });
        }

        modalHapus.addEventListener('click', (e) => {
            if (e.target === modalHapus) closeHapusModal();
        });
    }

    // 7. Modal Filter
    const modalFilter = document.getElementById('modal-filter-lansia');
    const btnFilter = document.getElementById('btn-filter-lansia');
    const btnCloseFilter = document.getElementById('btn-close-filter-modal');
    const formFilter = modalFilter ? modalFilter.querySelector('form') : null;

    if (modalFilter && btnFilter) {
        const closeFilterModal = () => modalFilter.classList.remove('active');

        btnFilter.addEventListener('click', () => {
            modalFilter.classList.add('active');
        });

        if (btnCloseFilter) btnCloseFilter.addEventListener('click', closeFilterModal);

        modalFilter.addEventListener('click', (e) => {
            if (e.target === modalFilter) closeFilterModal();
        });

        if (formFilter) {
            formFilter.addEventListener('submit', (e) => {
                e.preventDefault();
                const status = document.getElementById('filter_status').value;
                const umur = document.getElementById('filter_umur').value;
                alert(`Filter Diterapkan: Status=${status || 'Semua'}, Umur=${umur || 'Semua'} (Simulasi)`);
                closeFilterModal();
            });
        }
    }
});