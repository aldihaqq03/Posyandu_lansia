document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.btn-pilih'); // Sesuaikan dengan class di HTML Anda
    const namaTerpilihPlaceholder = document.querySelector('.form-title strong'); // Target ke <strong> Budi Santoso

    buttons.forEach(btn => {
        btn.addEventListener('click', function () {
            // 1. Reset semua tombol di dalam tabel
            buttons.forEach(otherBtn => {
                otherBtn.textContent = 'Pilih';
                otherBtn.classList.remove('btn-terpilih');
                otherBtn.classList.add('btn-pilih');

                const row = otherBtn.closest('tr');
                if (row) row.classList.remove('row-active');
            });

            // 2. Aktifkan tombol yang diklik
            this.textContent = 'Terpilih';
            this.classList.remove('btn-pilih');
            this.classList.add('btn-terpilih');

            const currentRow = this.closest('tr');
            if (currentRow) currentRow.classList.add('row-active');

            // 3. Ambil nama dari kolom kedua (td ke-2) di baris tersebut
            if (currentRow && namaTerpilihPlaceholder) {
                const namaLansia = currentRow.querySelector('td:nth-child(2)').textContent;
                namaTerpilihPlaceholder.textContent = namaLansia;
            }
        });
    });
});
document.addEventListener('DOMContentLoaded', function () {
    // 1. Ambil semua tombol (baik yang sedang 'btn-pilih' maupun 'btn-terpilih')
    // Kita gunakan selector universal agar semua tombol aksi tertangkap
    const tableBody = document.querySelector('.pemeriksaan-table tbody');
    const namaTerpilihPlaceholder = document.querySelector('.form-title strong');

    if (!tableBody) return; // Keamanan jika tabel tidak ditemukan

    tableBody.addEventListener('click', function (e) {
        // 2. Pastikan yang diklik adalah tombol
        const clickedBtn = e.target.closest('button');

        if (clickedBtn && (clickedBtn.classList.contains('btn-pilih') || clickedBtn.classList.contains('btn-terpilih'))) {

            // 3. LOGIKA HANYA SATU: Reset semua baris dan tombol di dalam tabel
            const allRows = tableBody.querySelectorAll('tr');
            allRows.forEach(row => {
                row.classList.remove('row-active');
                const btn = row.querySelector('button');
                if (btn) {
                    btn.textContent = 'Pilih';
                    btn.className = 'btn-pilih'; // Kembalikan ke class dasar
                }
            });

            // 4. Aktifkan baris dan tombol yang baru saja diklik
            const currentRow = clickedBtn.closest('tr');
            currentRow.classList.add('row-active');

            clickedBtn.textContent = 'Terpilih';
            clickedBtn.className = 'btn-terpilih';

            // 5. Update nama di bagian form secara dinamis
            // Mengambil nama dari kolom ke-2 (index 1)
            const namaLansia = currentRow.cells[1].innerText;
            if (namaTerpilihPlaceholder) {
                namaTerpilihPlaceholder.textContent = namaLansia;
            }
        }
    });
});