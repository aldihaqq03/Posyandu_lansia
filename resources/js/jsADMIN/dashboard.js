document.addEventListener('DOMContentLoaded', function () {
    // 1. Animasi Progress Bar
    // Kita ambil semua elemen fill
    const progressBars = document.querySelectorAll('.fill, .progress-fill');

    // Kita jalankan animasi setelah delay kecil agar mata user sempat melihat prosesnya
    setTimeout(() => {
        progressBars.forEach(bar => {
            // Mengambil nilai width dari atribut style HTML (misal: style="width: 70%")
            const targetWidth = bar.parentElement.dataset.width || bar.style.width;

            // Jika kamu menggunakan inline style di HTML, JS akan otomatis memicu transisi CSS
            // karena kita sudah set transition di CSS-nya.
            bar.style.width = targetWidth;
        });
    }, 500);

    // 2. Animasi Angka (Counter)
    const statsValues = document.querySelectorAll('.stat-value');
    statsValues.forEach(valueDisplay => {
        const text = valueDisplay.innerText.replace('.', '');
        const target = parseInt(text);
        let start = 0;
        const duration = 2000; // 2 detik
        const increment = target / (duration / 16); // 60fps

        const updateCount = () => {
            start += increment;
            if (start < target) {
                valueDisplay.innerText = Math.ceil(start).toLocaleString('id-ID');
                requestAnimationFrame(updateCount);
            } else {
                valueDisplay.innerText = target.toLocaleString('id-ID');
            }
        };
        updateCount();
    });
});