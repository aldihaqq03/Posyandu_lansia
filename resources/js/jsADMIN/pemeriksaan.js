/* resources/js/jsAdmin/pemeriksaan.js */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Logika Pemilihan Lansia di Tabel
    const tableBody = document.querySelector('.pemeriksaan-table tbody');
    const namaTerpilihPlaceholder = document.querySelector('.form-title strong');

    if (tableBody) {
        tableBody.addEventListener('click', function (e) {
            // Pastikan yang diklik adalah tombol pilih/terpilih
            const clickedBtn = e.target.closest('button');

            if (clickedBtn && (clickedBtn.classList.contains('btn-pilih') || clickedBtn.classList.contains('btn-terpilih'))) {
                const allRows = tableBody.querySelectorAll('tr');

                // Reset semua baris dan tombol
                allRows.forEach(row => {
                    row.classList.remove('row-active');
                    const btn = row.querySelector('button');
                    if (btn) {
                        btn.textContent = 'Pilih';
                        btn.className = 'btn-pilih'; // kembalikan ke class dasar
                    }
                });

                // Aktifkan baris dan tombol yang diklik
                const currentRow = clickedBtn.closest('tr');
                if (currentRow) {
                    currentRow.classList.add('row-active');
                    clickedBtn.textContent = 'Terpilih';
                    clickedBtn.className = 'btn-terpilih';

                    // Update nama di bagian form "Hasil Pemeriksaan"
                    const namaLansia = currentRow.querySelector('td:nth-child(2) strong');
                    if (namaTerpilihPlaceholder && namaLansia) {
                        namaTerpilihPlaceholder.textContent = namaLansia.textContent;
                    }
                }
            }
        });
    }

    // 2. Pencarian Lansia (Filter Real-time)
    const searchInput = document.getElementById('search-lansia');
    const btnClearSearch = document.getElementById('btn-clear-search');

    if (searchInput && tableBody) {
        const filterTable = () => {
            const query = searchInput.value.toLowerCase();
            const rows = tableBody.querySelectorAll('tr');

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });

            // Tampilkan atau sembunyikan tombol silang (X)
            if (btnClearSearch) {
                btnClearSearch.style.display = query.length > 0 ? 'flex' : 'none';
            }
        };

        searchInput.addEventListener('input', filterTable);

        if (btnClearSearch) {
            btnClearSearch.addEventListener('click', () => {
                searchInput.value = ''; // Kosongkan input
                filterTable(); // Refresh tabel agar semua data tampil lagi
                searchInput.focus(); // Kembalikan kursor ke kotak teks
            });
        }
    }

    // 3. Simulasi Submit Form Pemeriksaan
    const formPemeriksaan = document.querySelector('.form-section form');
    if (formPemeriksaan) {
        formPemeriksaan.addEventListener('submit', function (e) {
            e.preventDefault(); // Mencegah reload halaman

            const namaLansia = namaTerpilihPlaceholder ? namaTerpilihPlaceholder.textContent : 'Lansia';
            alert(`Data pemeriksaan kesehatan untuk ${namaLansia} berhasil disimpan! (Simulasi)`);

            // Opsional: bersihkan form setelah disubmit
            this.reset();
        });
    }
});