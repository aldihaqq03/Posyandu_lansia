// =====================================================
// jadwal_posyandu.js
// =====================================================

// Helper function: Format tanggal ke Bahasa Indonesia
function formatDateIndo(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
}

document.addEventListener('DOMContentLoaded', function () {

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    // ===================== MODAL TAMBAH =====================
    const modalTambah = document.getElementById('modalTambahJadwal');
    const btnTambah = document.getElementById('btn-tambah-jadwal');
    const btnClose = document.getElementById('btn-close-modal');
    const btnCancel = document.getElementById('btn-cancel-modal');

    function openModalTambah() {
        modalTambah.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeModalTambah() {
        modalTambah.classList.remove('open');
        document.body.style.overflow = '';
    }

    btnTambah?.addEventListener('click', openModalTambah);
    btnClose?.addEventListener('click', closeModalTambah);
    btnCancel?.addEventListener('click', closeModalTambah);
    modalTambah?.addEventListener('click', e => { if (e.target === modalTambah) closeModalTambah(); });

    // ===================== MODAL EDIT =====================
    const modalEdit = document.getElementById('modalEditJadwal');
    const btnCloseEdit = document.getElementById('btn-close-modal-edit');
    const btnCancelEdit = document.getElementById('btn-cancel-modal-edit');
    const editKegiatanList = document.getElementById('editKegiatanList');

    function openModalEdit() {
        modalEdit.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeModalEdit() {
        modalEdit.classList.remove('open');
        document.body.style.overflow = '';
    }

    btnCloseEdit?.addEventListener('click', closeModalEdit);
    btnCancelEdit?.addEventListener('click', closeModalEdit);
    modalEdit?.addEventListener('click', e => { if (e.target === modalEdit) closeModalEdit(); });

    // Klik tombol Edit di card — fetch data lalu isi modal
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;

            fetch(`/jadwal_posyandu/${id}`, {
                headers: { 'Accept': 'application/json' }
            })
                .then(res => res.json())
                .then(data => {
                    // Isi field modal edit dengan data dari server
                    document.getElementById('edit-id').value = data.id_jadwal_posyandu;
                    document.getElementById('edit-tanggal').value = data.tanggal_pelaksanaan;
                    document.getElementById('edit-tema').value = data.tema;
                    document.getElementById('edit-lokasi').value = data.lokasi;
                    document.getElementById('edit-catatan').value = data.keterangan ?? '';
                    document.getElementById('edit-chk-utama').checked = data.ada_skrining_utama == 1;
                    document.getElementById('edit-chk-ppok').checked = data.ada_skrining_ppok == 1;

                    // ✅ HITUNG minimal tanggal (tanggal lama + 1 hari)
                    const tanggalLama = data.tanggal_pelaksanaan;
                    const minDate = new Date(tanggalLama);
                    minDate.setDate(minDate.getDate() + 1);
                    const minDateStr = minDate.toISOString().split('T')[0];

                    // ✅ SET minimal date di input
                    const inputTanggal = document.getElementById('edit-tanggal');
                    inputTanggal.min = minDateStr;
                    inputTanggal.dataset.minDate = minDateStr;

                    // ✅ Tampilkan hint untuk user
                    const hintEl = document.getElementById('edit-tanggal-hint');
                    hintEl.textContent = `Minimal tanggal: ${formatDateIndo(minDateStr)} (H+1 dari jadwal semula)`;
                    hintEl.style.display = 'block';
                    hintEl.style.color = 'var(--primary-mid)';
                    hintEl.style.fontSize = '12px';
                    hintEl.style.marginTop = '4px';

                    // Isi kegiatan
                    editKegiatanList.innerHTML = '';
                    const kegiatan = data.kegiatan ?? [];
                    if (kegiatan.length > 0) {
                        kegiatan.forEach((k, i) => {
                            editKegiatanList.appendChild(createKegiatanItemEdit(i + 1, k.nama, k.jam));
                        });
                    } else {
                        editKegiatanList.appendChild(createKegiatanItemEdit(1, '', ''));
                    }

                    openModalEdit();
                })
                .catch(err => {
                    console.error(err);
                    alert('Gagal mengambil data jadwal!');
                });
        });
    });

    // ===================== SIMPAN PERUBAHAN (UPDATE) =====================
    document.getElementById('btn-update-jadwal')?.addEventListener('click', function () {
        const id = document.getElementById('edit-id').value;
        const tanggal = document.getElementById('edit-tanggal').value;
        const tema = document.getElementById('edit-tema').value.trim();
        const lokasi = document.getElementById('edit-lokasi').value.trim();

        // ✅ VALIDASI tanggal baru > minimal tanggal (H+1)
        const minDate = document.getElementById('edit-tanggal').dataset.minDate;
        if (!tanggal || tanggal <= minDate) {
            alert(`Tanggal harus lebih dari ${formatDateIndo(minDate)} (H+1 dari jadwal semula)`);
            return;
        }

        if (!tema || !lokasi) {
            alert('Tema dan Lokasi wajib diisi!');
            return;
        }

        // Kumpulkan kegiatan dari modal edit
        const kegiatan = [];
        editKegiatanList.querySelectorAll('.kegiatan-item').forEach(item => {
            const nama = item.querySelector('.kegiatan-input').value.trim();
            const jam = item.querySelector('.jam-input').value;
            if (nama) kegiatan.push({ nama, jam: jam || null });
        });

        const payload = {
            _method: 'PUT',
            tanggal_pelaksanaan: tanggal,
            tema: tema,
            lokasi: lokasi,
            // ❌ TIDAK KIRIM status - status tetap dari database (selalu = 1)
            ada_skrining_utama: document.getElementById('edit-chk-utama').checked ? 1 : 0,
            ada_skrining_ppok: document.getElementById('edit-chk-ppok').checked ? 1 : 0,
            kegiatan: kegiatan,
            keterangan: document.getElementById('edit-catatan').value.trim() || null,
        };

        fetch(`/jadwal_posyandu/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload)
        })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Gagal update');
                return data;
            })
            .then(() => {
                closeModalEdit();
                window.location.reload();
            })
            .catch(err => {
                console.error(err);
                alert('Gagal menyimpan perubahan: ' + err.message);
            });
    });

    // ===================== ESC TUTUP SEMUA MODAL =====================
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeModalTambah();
            closeModalEdit();
        }
    });

    // ===================== TOGGLE SKRINING =====================
    document.querySelectorAll('.skrining-option:not(.disabled)').forEach(opt => {
        opt.addEventListener('click', function (e) {
            if (e.target.type !== 'checkbox') {
                const cb = this.querySelector('input[type="checkbox"]');
                cb.checked = !cb.checked;
            }
        });
    });

    // ===================== KEGIATAN TAMBAH =====================
    const kegiatanList = document.getElementById('kegiatanList');
    const btnAddKegiatan = document.getElementById('btn-add-kegiatan');

    function createKegiatanItem(num, nama = '', jam = '') {
        const item = document.createElement('div');
        item.className = 'kegiatan-item';
        item.innerHTML = `
            <div class="kegiatan-num">${num}</div>
            <input class="kegiatan-input" type="text" placeholder="Nama kegiatan" value="${nama}">
            <span class="jam-label"><i class="fa-regular fa-clock"></i></span>
            <input class="jam-input" type="time" value="${jam ?? ''}">
            <button class="btn-remove" type="button"><i class="fa-solid fa-xmark"></i></button>
        `;
        item.querySelector('.btn-remove').addEventListener('click', () => {
            if (kegiatanList.querySelectorAll('.kegiatan-item').length <= 1) return;
            item.remove();
            updateNumbers(kegiatanList);
        });
        return item;
    }

    // ===================== KEGIATAN EDIT =====================
    function createKegiatanItemEdit(num, nama = '', jam = '') {
        const item = document.createElement('div');
        item.className = 'kegiatan-item';
        item.innerHTML = `
            <div class="kegiatan-num">${num}</div>
            <input class="kegiatan-input" type="text" placeholder="Nama kegiatan" value="${nama}">
            <span class="jam-label"><i class="fa-regular fa-clock"></i></span>
            <input class="jam-input" type="time" value="${jam ?? ''}">
            <button class="btn-remove" type="button"><i class="fa-solid fa-xmark"></i></button>
        `;
        item.querySelector('.btn-remove').addEventListener('click', () => {
            if (editKegiatanList.querySelectorAll('.kegiatan-item').length <= 1) return;
            item.remove();
            updateNumbers(editKegiatanList);
        });
        return item;
    }

    function updateNumbers(list) {
        list.querySelectorAll('.kegiatan-item').forEach((item, i) => {
            item.querySelector('.kegiatan-num').textContent = i + 1;
        });
    }

    // Pasang event remove ke item awal di modal tambah
    kegiatanList?.querySelectorAll('.btn-remove').forEach(btn => {
        btn.addEventListener('click', () => {
            if (kegiatanList.querySelectorAll('.kegiatan-item').length <= 1) return;
            btn.closest('.kegiatan-item').remove();
            updateNumbers(kegiatanList);
        });
    });

    btnAddKegiatan?.addEventListener('click', () => {
        const num = kegiatanList.querySelectorAll('.kegiatan-item').length + 1;
        const newItem = createKegiatanItem(num);
        kegiatanList.appendChild(newItem);
        newItem.querySelector('.kegiatan-input').focus();
    });

    document.getElementById('btn-add-kegiatan-edit')?.addEventListener('click', () => {
        const num = editKegiatanList.querySelectorAll('.kegiatan-item').length + 1;
        const newItem = createKegiatanItemEdit(num);
        editKegiatanList.appendChild(newItem);
        newItem.querySelector('.kegiatan-input').focus();
    });

    // ===================== SIMPAN JADWAL BARU =====================
    document.getElementById('btn-simpan-jadwal')?.addEventListener('click', function () {
        const tanggal = document.getElementById('input-tanggal').value;
        const tema = document.getElementById('input-tema').value.trim();
        const lokasi = document.getElementById('input-lokasi').value.trim();

        if (!tanggal || !tema || !lokasi) {
            alert('Tanggal, Tema, dan Lokasi wajib diisi!');
            return;
        }

        const kegiatan = [];
        kegiatanList.querySelectorAll('.kegiatan-item').forEach(item => {
            const nama = item.querySelector('.kegiatan-input').value.trim();
            const jam = item.querySelector('.jam-input').value;
            if (nama) kegiatan.push({ nama, jam: jam || null });
        });

        const payload = {
            tanggal_pelaksanaan: tanggal,
            tema: tema,
            lokasi: lokasi,
            ada_skrining_utama: document.getElementById('chk-utama').checked ? 1 : 0,
            ada_skrining_ppok: document.getElementById('chk-ppok').checked ? 1 : 0,
            kegiatan: kegiatan,
            keterangan: document.getElementById('input-catatan').value.trim() || null,
        };

        fetch('/jadwal_posyandu', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload)
        })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Gagal menyimpan');
                return data;
            })
            .then(() => window.location.reload())
            .catch(err => alert('Gagal menyimpan jadwal: ' + err.message));
    });

    // ===================== FILTER =====================
    const searchInput = document.getElementById('search-jadwal');
    const filterStatus = document.getElementById('filter-status');
    const filterBulan = document.getElementById('filter-bulan');

    function applyFilter() {
        console.log('Filter:', {
            search: searchInput?.value,
            status: filterStatus?.value,
            bulan: filterBulan?.value,
        });
        // TODO: implementasi filter via AJAX
    }

    searchInput?.addEventListener('input', applyFilter);
    filterStatus?.addEventListener('change', applyFilter);
    filterBulan?.addEventListener('change', applyFilter);

});