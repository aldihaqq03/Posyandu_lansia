document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById("chartTrenPenyakit").getContext("2d");

    // Ambil value dari object dataTrenPenyakit yang di-passing dari blade view
    const labelMapping = {
        hipertensi: "Hipertensi",
        hipotensi: "Hipotensi",
        diabetes: "Gula darah tinggi",
        kolesterol: "Kolesterol Tinggi",
        obesitas: "Obesitas",
        bb_kurang: "Berat Badan Kurang",
    };

    const labels = Object.keys(dataTrenPenyakit).map(
        (key) => labelMapping[key] || key,
    );
    const dataValues = Object.values(dataTrenPenyakit);

    new Chart(ctx, {
        type: "bar", // Tipe dasar tetap bar
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Jumlah Lansia",
                    data: dataValues,
                    backgroundColor: [
                        "rgba(239, 68, 68, 0.85)", // Hipertensi -> Danger Red
                        "rgba(59, 130, 246, 0.85)", // Hipotensi -> Primary Blue
                        "rgba(251, 191, 36, 0.85)", // Diabetes -> Warning Amber
                        "rgba(139, 92, 246, 0.85)", // Kolesterol -> Purple
                        "rgba(20, 184, 166, 0.85)", // Obesitas -> Teal
                        "rgba(163, 230, 53, 0.85)", // BB Kurang -> Lime Green
                    ],
                    borderRadius: 8, // Sudut bar tumpul halus biar senada tema dashboard modern
                    borderWidth: 0,
                    barThickness: 16, // Ketebalan bar item
                },
            ],
        },
        options: {
            indexAxis: "y", // Mengubah orientasi grafik dari vertikal menjadi horizontal (miring)
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false, // Mematikan kotak legend atas karena label sudah jelas di samping kiri
                },
                tooltip: {
                    backgroundColor: "#1e293b",
                    titleFont: { size: 12, weight: "bold" },
                    bodyFont: { size: 12 },
                    padding: 10,
                    cornerRadius: 6,
                },
            },
            scales: {
                x: {
                    grid: {
                        color: "#f1f5f9", // Garis bantu vertikal tipis seperti gambar kedua
                        drawBorder: false,
                    },
                    ticks: {
                        color: "#94a3b8",
                        font: { size: 11, weight: "600" },
                        stepSize: 1,
                    },
                },
                y: {
                    grid: {
                        display: false, // Menghilangkan garis grid horizontal agar bersih
                        drawBorder: false,
                    },
                    ticks: {
                        color: "#1e293b",
                        font: { size: 12, weight: "700" }, // Mengikuti rule font var(--text-main) Anda
                    },
                },
            },
        },
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById("chartTrenPenyakit").getContext("2d");

    // 1. Mapping label asli database ke label tampilan interface
    const labelMapping = {
        hipertensi: "Hipertensi",
        hipotensi: "Hipotensi",
        diabetes: "Diabetes",
        kolesterol: "Kolesterol Tinggi",
        obesitas: "Obesitas",
        bb_kurang: "Berat Badan Kurang",
    };

    // 2. Mapping warna default yang konsisten dengan desain Anda
    const colorMapping = {
        hipertensi: "rgba(239, 68, 68, 0.85)", // Red
        hipotensi: "rgba(59, 130, 246, 0.85)", // Blue
        diabetes: "rgba(251, 191, 36, 0.85)", // Amber
        kolesterol: "rgba(139, 92, 246, 0.85)", // Purple
        obesitas: "rgba(20, 184, 166, 0.85)", // Teal
        bb_kurang: "rgba(163, 230, 53, 0.85)", // Lime
    };

    // 3. Proses sorting data dari TERBANYAK ke TERSEDIKIT
    // Mengubah object {} menjadi array [] agar bisa di-sort nilainya
    const sortedData = Object.keys(dataTrenPenyakit)
        .map((key) => ({
            key: key,
            label: labelMapping[key] || key,
            value: dataTrenPenyakit[key] || 0,
            color: colorMapping[key] || "rgba(148, 163, 184, 0.85)",
        }))
        // Urutkan berdasarkan value secara Descending (terbanyak -> tersedikit)
        .sort((a, b) => b.value - a.value);

    // 4. Pisahkan kembali hasil sort ke dalam struktur array Chart.js
    const labels = sortedData.map((item) => item.label);
    const dataValues = sortedData.map((item) => item.value);
    const backgroundColors = sortedData.map((item) => item.color);

    // 5. Render Chart.js dengan data yang sudah terurut
    new Chart(ctx, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Jumlah Lansia",
                    data: dataValues,
                    backgroundColor: backgroundColors,
                    borderRadius: 8,
                    borderWidth: 0,
                    barThickness: 16,
                },
            ],
        },
        options: {
            indexAxis: "y", // Efek horizontal miring
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: "#1e293b",
                    titleFont: { size: 12, weight: "bold" },
                    bodyFont: { size: 12 },
                    padding: 10,
                    cornerRadius: 6,
                },
            },
            scales: {
                x: {
                    grid: {
                        color: "#f1f5f9",
                        drawBorder: false,
                    },
                    ticks: {
                        color: "#94a3b8",
                        font: { size: 11, weight: "600" },
                        stepSize: 1, // Memastikan angka skala melompat bulat (1, 2, 3...)
                    },
                },
                y: {
                    grid: {
                        display: false,
                        drawBorder: false,
                    },
                    ticks: {
                        color: "#1e293b",
                        font: { size: 12, weight: "700" },
                    },
                },
            },
        },
    });
});
