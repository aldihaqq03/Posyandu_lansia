/* resources/js/jsAdmin/dataLansia.js */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Animasi Angka Statistik
    const statValues = document.querySelectorAll('.stat-value');
    statValues.forEach(val => {
        const target = parseInt(val.getAttribute('data-target'));
        let count = 0;
        const duration = 1000; // 1 detik
        const increment = target / (duration / 16);

        const update = () => {
            count += increment;
            if (count < target) {
                val.innerText = Math.ceil(count);
                requestAnimationFrame(update);
            } else {
                val.innerText = target;
            }
        };
        update();
    });

    // 2. Filter Pencarian Real-time
    const searchInput = document.getElementById('search-input');
    const rows = document.querySelectorAll('.data-table tbody tr');

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });

    // 3. Update Detail Card saat klik "Lihat" (Ikon Mata)
    const viewBtns = document.querySelectorAll('.btn-view');
    viewBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const name = this.getAttribute('data-name');
            const detailSection = document.getElementById('detail-section');

            // Animasi Fade Out
            detailSection.style.opacity = '0.5';

            setTimeout(() => {
                document.getElementById('detail-name').innerText = name;
                document.getElementById('summary-name').innerText = name;
                detailSection.style.opacity = '1';

                // Scroll halus ke arah detail
                detailSection.scrollIntoView({ behavior: 'smooth' });
            }, 200);
        });
    });
});