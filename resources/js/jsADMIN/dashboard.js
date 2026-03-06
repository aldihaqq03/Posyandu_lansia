/* resources/js/jsAdmin/dashboard.js */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Animasi Progress Bar
    const progressBars = document.querySelectorAll('.fill, .progress-fill');
    setTimeout(() => {
        progressBars.forEach(bar => {
            const targetWidth = bar.style.width;
            bar.style.width = '0%';
            // Memaksa reflow browser sebelum menjalankan animasi
            void bar.offsetWidth;
            bar.style.width = targetWidth; // animasi berjalan ke ukuran aslinya
        });
    }, 100);

    // 2. Animasi Angka (Counter)
    const statsValues = document.querySelectorAll('.stat-value');
    statsValues.forEach(valueDisplay => {
        const textValue = valueDisplay.innerText;
        // Hapus titik pemisah ribuan sebelum parsing
        const target = parseInt(textValue.replace(/\./g, ''));

        if (!isNaN(target)) {
            let start = 0;
            const duration = 1500; // 1.5 detik
            const increment = Math.max(1, target / (duration / 16));

            const updateCount = () => {
                start += increment;
                if (start < target) {
                    valueDisplay.innerText = Math.ceil(start).toLocaleString('id-ID');
                    requestAnimationFrame(updateCount);
                } else {
                    valueDisplay.innerText = target.toLocaleString('id-ID');
                }
            };

            // Set ke 0 dari awal sebelum animasi berjalan
            valueDisplay.innerText = '0';
            updateCount();
        }
    });

    // 3. Setup simulasi Filter untuk Tabel Catatan Terakhir
    const btnFilter = document.querySelector('.btn-filter');
    const tableBody = document.querySelector('.data-table tbody');

    if (btnFilter && tableBody) {
        btnFilter.addEventListener('click', () => {
            const rows = Array.from(tableBody.querySelectorAll('tr'));

            // Efek loading sementara visual
            tableBody.style.transition = 'opacity 0.3s ease';
            tableBody.style.opacity = '0.3';

            setTimeout(() => {
                // Simulasi membalikkan urutan tabel (terlama/terbaru)
                rows.reverse().forEach(row => tableBody.appendChild(row));
                tableBody.style.opacity = '1';
            }, 400);
        });
    }

    // 4. Simulasi Tombol "Tampilkan Semua Data"
    const btnViewAll = document.querySelector('.btn-view-all');
    if (btnViewAll) {
        btnViewAll.addEventListener('click', (e) => {
            e.preventDefault();
            // Anda dapat mengarahkannya langsung ke halaman yang relevan seperti /pemeriksaan atau /lansia
            window.location.href = '/data_lansia';
            // window.location.href = '/pemeriksaan';
        });
    }
});