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
                const name = row.getAttribute('data-nama') || 'Detail Lansia';
                const nik = row.getAttribute('data-nik') || '-';
                const umur = row.getAttribute('data-umur') || '-';
                const noHp = row.getAttribute('data-no-hp') || 'Tidak tersedia';
                const alamat = row.getAttribute('data-alamat') || '-';

                const detailSection = document.querySelector('.detail-container');

                if (detailSection) {
                    // Animasi Fade Out
                    detailSection.style.opacity = '0.5';

                    setTimeout(() => {
                        const dynamicName = document.getElementById('dynamic-name');
                        const nameDisplay = document.getElementById('name-display');

                        if (dynamicName) dynamicName.innerText = name;
                        if (nameDisplay) nameDisplay.innerText = name;

                        // Tambahan data dinamis untuk detail
                        const ageText = detailSection.querySelector('.age-text');
                        if (ageText) ageText.innerText = umur + ' Tahun';

                        // Isi kolom informasi pribadi di view dengan pencarian teks label
                        const dataItems = detailSection.querySelectorAll('.data-item');
                        dataItems.forEach(item => {
                            const label = item.querySelector('label')?.innerText;
                            const p = item.querySelector('p');
                            if (p && label) {
                                if (label.includes('NIK')) p.innerText = nik;
                                if (label.includes('NOMOR HANDPHONE')) p.innerText = noHp || '-';
                                if (label.includes('ALAMAT LENGKAP')) p.innerText = alamat;
                            }
                        });


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
        // Get route URL inside the conditional check with null safety
        const routeMeta = document.querySelector('meta[name="route-store-lansia"]');
        const url = routeMeta ? routeMeta.content : null;
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
        //logic tambah data
        if (formTambah && url) {
            formTambah.addEventListener('submit', async (e) => {
                e.preventDefault();

                const csrfToken = document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content');

                const data = {
                    nik: document.getElementById('nik').value,
                    nama_lengkap: document.getElementById('nama_lengkap').value,
                    jenis_kelamin: document.getElementById('jenis_kelamin').value,
                    tanggal_lahir: document.getElementById('tanggal_lahir').value,
                    alamat: document.getElementById('alamat').value,
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                    password_confirmation: document.getElementById('password_confirmation').value
                };

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (response.ok) {
                        alert(result.message || "Data lansia berhasil ditambahkan");
                        formTambah.reset();
                        closeModal();

                        // refresh data tabel
                        location.reload();
                    } else {
                        // error validasi
                        if (result.errors) {
                            let pesan = "";
                            Object.values(result.errors).forEach(err => {
                                pesan += err + "\n";
                            });
                            alert(pesan);
                        } else {
                            alert(result.message || "Terjadi kesalahan");
                        }
                    }

                } catch (error) {
                    console.error("Error:", error);
                    alert("Server tidak merespon.");
                }
            });
        }

        // 5. Modal Edit Lansia
        const modalEdit = document.getElementById('modal-edit-lansia');
        const btnCloseEditModal = document.getElementById('btn-close-edit-modal');
        const btnCancelEditModal = document.getElementById('btn-cancel-edit-modal');
        const editBtns = document.querySelectorAll('.edit-btn');
        const formEdit = document.getElementById('form-edit-lansia');

        if (modalEdit) {
            const closeEditModal = () => modalEdit.classList.remove('active');

            editBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const row = btn.closest('tr');
                    if (row) {
                        // Mengambil nilai referensi dari tabel untuk Form Edit
                        const id = row.getAttribute('data-id');
                        const name = row.getAttribute('data-nama');
                        const nik = row.getAttribute('data-nik');
                        const tglLahir = row.getAttribute('data-tanggal-lahir');
                        const alamat = row.getAttribute('data-alamat');
                        const jk = row.getAttribute('data-jenis-kelamin');

                        // Set the dynamic action URL
                        if (formEdit) {
                            formEdit.action = `/lansia/${id}`;
                        }

                        // Memasukkan nilai ke field input modal Edit
                        const editName = document.getElementById('edit_nama_lengkap');
                        const editNik = document.getElementById('edit_nik');
                        const editAddr = document.getElementById('edit_alamat');
                        const editTglLahir = document.getElementById('edit_tanggal_lahir');
                        const editJk = document.getElementById('edit_jenis_kelamin');

                        if (editName) editName.value = name;
                        if (editNik) editNik.value = nik;
                        if (editAddr) editAddr.value = alamat;
                        if (editTglLahir) editTglLahir.value = tglLahir;
                        if (editJk) editJk.value = jk || 'L';
                    }
                    modalEdit.classList.add('active');
                });
            });

            if (btnCloseEditModal) btnCloseEditModal.addEventListener('click', closeEditModal);
            if (btnCancelEditModal) btnCancelEditModal.addEventListener('click', closeEditModal);

            modalEdit.addEventListener('click', (e) => {
                if (e.target === modalEdit) closeEditModal();
            });

            // Form Edit now submits naturally due to action + method POST + @method('PUT'), NO fetch strictly needed unless you want to stay single-page.
            // But since LansiaController@update uses redirect(), normal submission is best.
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
    }
});
