document.addEventListener("DOMContentLoaded", function () {
    const lansiaId = document.getElementById("lansia-id")?.value;
    const gender = document.getElementById("lansia-gender")?.value || "L"; // L atau P

    if (!lansiaId) {
        console.error("Lansia ID tidak ditemukan.");
        return;
    }

    let saranNewIdx = 0;

    // ========== VARIABEL UNTUK FILTER ==========
    let fullHealthHistoryData = []; // data asli dari server
    let filteredHealthHistoryData = []; // data setelah filter
    let chartInstances = {}; // menyimpan objek Chart.js untuk di-destroy
    const TH = window.HEALTH_THRESHOLDS ?? {
        sistolik: {
            bahaya_bawah: 90,
            waspada_bawah: 100,
            waspada_atas: 130,
            bahaya_atas: 140,
        },
        diastolik: {
            bahaya_bawah: 60,
            waspada_bawah: 65,
            waspada_atas: 85,
            bahaya_atas: 90,
        },
        gula_darah: { waspada_atas: 145, bahaya_atas: 200 },
        kolesterol: { waspada_atas: 150, bahaya_atas: 190 },
        imt: {
            bahaya_bawah: 18.5,
            waspada_bawah: 22.0,
            waspada_atas: 27.0,
            bahaya_atas: 30.0,
        },
        lingkar_perut: { limit_p: 80.0, limit_l: 90.0 },
    };
    const lpLimit =
        gender === "P" ? TH.lingkar_perut.limit_p : TH.lingkar_perut.limit_l; // batas lingkar perut sesuai gender

    // ── Inisiasi lingkar perut zone label sesuai gender ──
    document.getElementById("zone-lp").innerHTML = `
        <span class="mz mz-normal">Normal ≤${lpLimit} cm</span>
        <span class="mz mz-bahaya">Berisiko &gt;${lpLimit} cm</span>
    `;

    // ── Tabs Logic ──
    const tabBtns = document.querySelectorAll(".mon-tab-btn");
    const tabContents = document.querySelectorAll(".mon-tab-content");

    tabBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            tabBtns.forEach((b) => b.classList.remove("active"));
            tabContents.forEach((p) => p.classList.remove("active"));
            btn.classList.add("active");
            const tabId = btn.getAttribute("data-tab");
            const pane = document.getElementById(`tab-${tabId}`);
            if (pane) pane.classList.add("active");
        });
    });

    // ========== FUNGSI-FUNGSI HELPER ==========
    function el(id) {
        return document.getElementById(id);
    }
    function show(id) {
        const e = el(id);
        if (e) e.style.display = "";
    }
    function hide(id) {
        const e = el(id);
        if (e) e.style.display = "none";
    }
    function csrf() {
        return document.querySelector('meta[name="csrf-token"]')?.content || "";
    }
    function apiFetch(url) {
        return fetch(url, {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        });
    }
    function fetchJSON(method, url, body) {
        const opts = {
            method,
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrf(),
                "X-Requested-With": "XMLHttpRequest",
            },
        };
        if (body) opts.body = JSON.stringify(body);
        return fetch(url, opts);
    }
    function esc(str) {
        return String(str || "")
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;");
    }
    function fmtDate(str) {
        if (!str) return "-";
        try {
            return new Date(str + "T00:00:00").toLocaleDateString("id-ID", {
                day: "2-digit",
                month: "short",
                year: "numeric",
            });
        } catch {
            return str;
        }
    }

    // Format bulan-tahun untuk info filter
    function formatMonthYear(date) {
        if (!date) return "";
        return date.toLocaleDateString("id-ID", {
            month: "short",
            year: "numeric",
        });
    }

    // Helper: dapatkan tanggal mundur (bulan)
    function getRelativeDate(monthsAgo) {
        const date = new Date();
        date.setMonth(date.getMonth() - monthsAgo);
        date.setHours(0, 0, 0, 0);
        return date;
    }

    // ========== STATE CHART ==========
    function setChartState(key, state) {
        const loading = document.getElementById(`loading-${key}`);
        const empty = document.getElementById(`empty-${key}`);
        const wrap = document.getElementById(`wrap-${key}`);
        if (loading)
            loading.style.display = state === "loading" ? "block" : "none";
        if (empty) empty.style.display = state === "empty" ? "flex" : "none";
        if (wrap) wrap.style.display = state === "chart" ? "block" : "none";
    }

    // ========== FUNGSI CHART (menerima parameter rows) ==========
    function pts(rows, key) {
        return rows
            .filter((r) => r[key] != null)
            .map((r) => ({
                x: new Date(r.tanggal + "T00:00:00").getTime(),
                y: Number(r[key]),
            }))
            .sort((a, b) => a.x - b.x);
    }

    function refLine(rows, key, value, color) {
        const validRows = rows.filter((r) => r[key] != null);
        if (validRows.length < 1) return null;
        const dates = validRows
            .map((r) => new Date(r.tanggal + "T00:00:00").getTime())
            .sort((a, b) => a - b);
        return {
            label: `Ref ${value}`,
            data: [
                { x: dates[0], y: value },
                { x: dates[dates.length - 1], y: value },
            ],
            borderColor: color,
            borderWidth: 1.2,
            borderDash: [5, 4],
            pointRadius: 0,
            fill: false,
            tension: 1,
            parsing: false,
        };
    }

    function lineset(data, color, label) {
        return {
            label,
            data,
            borderColor: color,
            backgroundColor: color + "18",
            borderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
            pointBackgroundColor: color,
            pointBorderColor: "#fff",
            pointBorderWidth: 1.5,
            fill: false,
            tension: 0.2,
            spanGaps: true,
            parsing: false,
        };
    }

    function renderLegend(elId, items) {
        const el = document.getElementById(elId);
        if (!el) return;
        el.innerHTML = items
            .map(
                (i) =>
                    `<span><span style="width:18px;height:2.5px;border-radius:2px;background:${i.dash ? `repeating-linear-gradient(90deg,${i.color} 0,${i.color} 5px,transparent 5px,transparent 9px)` : i.color};display:inline-block;vertical-align:middle;margin-right:4px;"></span> ${i.label}</span>`,
            )
            .join("");
    }

    function renderChart(canvasId, datasets, options) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;
        if (chartInstances[canvasId]) chartInstances[canvasId].destroy();
        const ctx = canvas.getContext("2d");
        chartInstances[canvasId] = new Chart(ctx, {
            type: "line",
            data: { datasets },
            options,
        });
    }

    function makeOptions(labelCallback, yMax = 300) {
        return {
            responsive: true,
            maintainAspectRatio: false,
            parsing: false,
            interaction: { mode: "index", intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: "#1f2937",
                    titleColor: "#f9fafb",
                    bodyColor: "#d1d5db",
                    padding: 10,
                    callbacks: {
                        title(items) {
                            if (!items.length) return "";
                            const raw = items[0].raw?.x;
                            if (!raw) return "";
                            const d = raw instanceof Date ? raw : new Date(raw);
                            return d.toLocaleDateString("id-ID", {
                                day: "numeric",
                                month: "long",
                                year: "numeric",
                            });
                        },
                        label: labelCallback,
                    },
                },
            },
            scales: {
                x: {
                    type: "time",
                    time: {
                        displayFormats: {
                            day: "d MMM yy",
                            week: "d MMM yy",
                            month: "MMM yyyy",
                            year: "yyyy",
                        },
                    },
                    ticks: {
                        font: { size: 11 },
                        color: "#9ca3af",
                        maxRotation: 45,
                        autoSkip: true,
                        maxTicksLimit: 8,
                        source: "auto",
                    },
                    grid: { color: "#f3f4f6" },
                    border: { color: "#e5e7eb" },
                },
                y: {
                    ticks: { font: { size: 11 }, color: "#9ca3af" },
                    grid: { color: "#f3f4f6" },
                    border: { color: "#e5e7eb" },
                    beginAtZero: true,
                    suggestedMax: yMax,
                },
            },
        };
    }

    // ========== BUILD CHART (menggunakan parameter rows) ==========
    function buildTensiChart(rows) {
        const sisPts = pts(rows, "td_sistolik");
        const diasPts = pts(rows, "td_diastolik");

        if (!sisPts.length && !diasPts.length) {
            setChartState("tensi", "empty");
            return;
        }
        setChartState("tensi", "chart");
        renderLegend("legend-tensi", [
            { color: "#3b82f6", label: "Sistolik", dash: false },
            { color: "#f97316", label: "Diastolik", dash: true },
            { color: "#10b981", label: "Batas normal", dash: true },
            { color: "#f59e0b", label: "Batas waspada", dash: true },
        ]);
        const ts = TH.sistolik;
        const td = TH.diastolik;
        const datasets = [
            lineset(sisPts, "#3b82f6", "Sistolik"),
            lineset(diasPts, "#f97316", "Diastolik"),
            refLine(rows, "td_sistolik", ts.waspada_atas, "#f59e0b"),
            refLine(rows, "td_sistolik", ts.bahaya_atas, "#ef4444"),
            refLine(rows, "td_sistolik", ts.bahaya_bawah, "#ef4444"),
            refLine(rows, "td_diastolik", td.waspada_atas, "#f59e0b"),
            refLine(rows, "td_diastolik", td.bahaya_atas, "#ef4444"),
            refLine(rows, "td_diastolik", td.bahaya_bawah, "#ef4444"),
        ].filter(Boolean);
        renderChart(
            "chart-tensi",
            datasets,
            makeOptions((ctx) => {
                const v = ctx.raw?.y;
                if (v == null) return null;
                const lbl = ctx.dataset.label;
                if (lbl?.startsWith("Ref")) return null;
                const isSis = lbl === "Sistolik";
                const st = isSis
                    ? v >= ts.bahaya_atas || v < ts.bahaya_bawah
                        ? "🔴 Perlu Tindak Lanjut"
                        : v >= ts.waspada_atas || v < ts.waspada_bawah
                          ? "⚠️ Waspada"
                          : "✅ Normal"
                    : v >= td.bahaya_atas || v < td.bahaya_bawah
                      ? "🔴 Perlu Tindak Lanjut"
                      : v >= td.waspada_atas || v < td.waspada_bawah
                        ? "⚠️ Waspada"
                        : "✅ Normal";
                return `  ${lbl}: ${v} mmHg  ${st}`;
            }, 300),
        );
    }

    function buildGulaChart(rows) {
        const data = pts(rows, "gula_darah");
        if (!data.length) {
            setChartState("gula", "empty");
            return;
        }
        setChartState("gula", "chart");
        const tg = TH.gula_darah;
        renderLegend("legend-gula", [
            { color: "#2563eb", label: "Gula Darah", dash: false },
            {
                color: "#10b981",
                label: `Batas normal (< ${tg.waspada_atas})`,
                dash: true,
            },
            {
                color: "#f59e0b",
                label: `Batas perlu tindak lanjut (≥ ${tg.bahaya_atas})`,
                dash: true,
            },
        ]);
        renderChart(
            "chart-gula",
            [
                lineset(data, "#2563eb", "Gula Darah"),
                refLine(rows, "gula_darah", tg.waspada_atas - 1, "#10b981"),
                refLine(rows, "gula_darah", tg.bahaya_atas, "#f59e0b"),
            ].filter(Boolean),
            makeOptions((ctx) => {
                const v = ctx.raw?.y;
                if (v == null || ctx.dataset.label?.startsWith("Ref"))
                    return null;
                const st =
                    v >= tg.bahaya_atas
                        ? "🔴 Perlu Tindak Lanjut"
                        : v >= tg.waspada_atas
                          ? "⚠️ Waspada"
                          : "✅ Normal";
                return `  Gula Darah: ${v} mg/dL  ${st}`;
            }, 400),
        );
    }

    function buildKolesterolChart(rows) {
        const data = pts(rows, "kolesterol");
        if (!data.length) {
            setChartState("kolesterol", "empty");
            return;
        }
        setChartState("kolesterol", "chart");
        const tk = TH.kolesterol;
        renderLegend("legend-kolesterol", [
            { color: "#7c3aed", label: "Kolesterol", dash: false },
            {
                color: "#10b981",
                label: `Batas normal (< ${tk.waspada_atas})`,
                dash: true,
            },
            {
                color: "#f59e0b",
                label: `Batas perlu tindak lanjut (≥ ${tk.bahaya_atas})`,
                dash: true,
            },
        ]);
        renderChart(
            "chart-kolesterol",
            [
                lineset(data, "#7c3aed", "Kolesterol"),
                refLine(rows, "kolesterol", tk.waspada_atas - 1, "#10b981"),
                refLine(rows, "kolesterol", tk.bahaya_atas, "#f59e0b"),
            ].filter(Boolean),
            makeOptions((ctx) => {
                const v = ctx.raw?.y;
                if (v == null || ctx.dataset.label?.startsWith("Ref"))
                    return null;
                const st =
                    v >= tk.bahaya_atas
                        ? "🔴 Perlu Tindak Lanjut"
                        : v >= tk.waspada_atas
                          ? "⚠️ Waspada"
                          : "✅ Normal";
                return `  Kolesterol: ${v} mg/dL  ${st}`;
            }, 350),
        );
    }

    function buildBBChart(rows) {
        const data = pts(rows, "berat_badan");
        if (!data.length) {
            setChartState("bb", "empty");
            return;
        }
        setChartState("bb", "chart");
        renderLegend("legend-bb", [
            { color: "#0891b2", label: "Berat Badan (kg)", dash: false },
        ]);
        const avg = Math.round(data.reduce((s, d) => s + d.y, 0) / data.length);
        renderChart(
            "chart-bb",
            [lineset(data, "#0891b2", "Berat Badan")],
            makeOptions((ctx) => {
                const v = ctx.raw?.y;
                if (v == null) return null;
                const diff = v - avg;
                const note =
                    Math.abs(diff) < 2
                        ? "Stabil"
                        : diff > 0
                          ? `↑ ${diff} kg dari rata-rata`
                          : `↓ ${Math.abs(diff)} kg dari rata-rata`;
                return `  Berat Badan: ${v} kg  (${note})`;
            }, 150),
        );
    }

    function buildLPChart(rows, limit) {
        const data = pts(rows, "lingkar_perut");
        if (!data.length) {
            setChartState("lp", "empty");
            return;
        }
        setChartState("lp", "chart");
        renderLegend("legend-lp", [
            { color: "#0d9488", label: "Lingkar Perut (cm)", dash: false },
            {
                color: "#ef4444",
                label: `Batas risiko (${limit} cm)`,
                dash: true,
            },
        ]);
        renderChart(
            "chart-lp",
            [
                lineset(data, "#0d9488", "Lingkar Perut"),
                refLine(rows, "lingkar_perut", limit, "#ef4444"),
            ].filter(Boolean),
            makeOptions((ctx) => {
                const v = ctx.raw?.y;
                if (v == null || ctx.dataset.label?.startsWith("Ref"))
                    return null;
                const st = v >= limit ? "🔴 Perlu Tindak Lanjut" : "✅ Normal";
                return `  Lingkar Perut: ${v} cm  ${st}`;
            }, 150),
        );
    }

    function buildIMTChart(rows) {
        const data = pts(rows, "imt");
        if (!data.length) {
            setChartState("imt", "empty");
            return;
        }
        setChartState("imt", "chart");
        renderLegend("legend-imt", [
            { color: "#e11d48", label: "IMT (kg/m²)", dash: false },
            { color: "#10b981", label: "Batas normal bawah (22)", dash: true },
            { color: "#10b981", label: "Batas normal atas (27)", dash: true },
            {
                color: "#f59e0b",
                label: "Batas waspada (18.5 / 30)",
                dash: true,
            },
        ]);
        renderChart(
            "chart-imt",
            [
                lineset(data, "#e11d48", "IMT"),
                refLine(rows, "imt", TH.imt.waspada_bawah, "#10b981"),
                refLine(rows, "imt", TH.imt.waspada_atas, "#10b981"),
                refLine(rows, "imt", TH.imt.bahaya_bawah, "#f59e0b"),
                refLine(rows, "imt", TH.imt.bahaya_atas, "#f59e0b"),
            ].filter(Boolean),
            makeOptions((ctx) => {
                const v = ctx.raw?.y;
                if (v == null || ctx.dataset.label?.startsWith("Ref"))
                    return null;
                const st =
                    v >= 22 && v <= 27
                        ? "✅ Normal"
                        : (v >= 18.5 && v < 22) || (v > 27 && v < 30)
                          ? "⚠️ Waspada"
                          : "🔴 Perlu Tindak Lanjut";
                return `  IMT: ${v} kg/m²  ${st}`;
            }, 45),
        );
    }

    // ========== FILTER LOGIC ==========
    function renderAllCharts() {
        ["tensi", "gula", "kolesterol", "bb", "lp", "imt"].forEach((k) =>
            setChartState(k, "loading"),
        );
        Object.values(chartInstances).forEach((chart) => {
            if (chart && typeof chart.destroy === "function") chart.destroy();
        });
        chartInstances = {};
        buildTensiChart(filteredHealthHistoryData);
        buildGulaChart(filteredHealthHistoryData);
        buildKolesterolChart(filteredHealthHistoryData);
        buildBBChart(filteredHealthHistoryData);
        buildLPChart(filteredHealthHistoryData, lpLimit);
        buildIMTChart(filteredHealthHistoryData);
    }

    function updateFilterInfo(start, end) {
        const span = document.getElementById("filter-range-text");
        if (!span) return;
        if (!start && !end) span.innerText = "Semua data";
        else if (start && end)
            span.innerText = `${formatMonthYear(start)} - ${formatMonthYear(end)}`;
        else if (start) span.innerText = `Sejak ${formatMonthYear(start)}`;
        else if (end) span.innerText = `Sampai ${formatMonthYear(end)}`;
    }

    function applyFilter(startDate, endDate) {
        if (!fullHealthHistoryData.length) {
            filteredHealthHistoryData = [];
            renderAllCharts();
            updateFilterInfo(startDate, endDate);
            return;
        }
        let filtered = [...fullHealthHistoryData];
        if (startDate) {
            const startTs = startDate.getTime();
            filtered = filtered.filter(
                (r) => new Date(r.tanggal + "T00:00:00").getTime() >= startTs,
            );
        }
        if (endDate) {
            const endTs = endDate.getTime();
            filtered = filtered.filter(
                (r) => new Date(r.tanggal + "T00:00:00").getTime() <= endTs,
            );
        }
        filteredHealthHistoryData = filtered;
        renderAllCharts();
        updateFilterInfo(startDate, endDate);
    }

    function applyDefaultFilter() {
        const today = new Date();
        const oneYearAgo = getRelativeDate(12);
        applyFilter(oneYearAgo, today);
    }

    function initFilters() {
        const presetBtns = document.querySelectorAll(".mon-filter-btn");
        const customBtn = document.getElementById("apply-custom-filter");
        const startInput = document.getElementById("filter-start-month");
        const endInput = document.getElementById("filter-end-month");

        presetBtns.forEach((btn) => {
            btn.addEventListener("click", () => {
                presetBtns.forEach((b) => b.classList.remove("active"));
                btn.classList.add("active");
                const filterVal = btn.getAttribute("data-filter");
                if (filterVal === "all") applyFilter(null, null);
                else {
                    const months = parseInt(filterVal, 10);
                    applyFilter(getRelativeDate(months), new Date());
                }
                if (startInput) startInput.value = "";
                if (endInput) endInput.value = "";
            });
        });

        customBtn?.addEventListener("click", () => {
            let startDate = null,
                endDate = null;
            if (startInput?.value)
                startDate = new Date(startInput.value + "-01");
            if (endInput?.value) {
                const [year, month] = endInput.value.split("-");
                const nextMonth = new Date(parseInt(year), parseInt(month), 1);
                endDate = new Date(nextMonth - 86400000);
            }
            applyFilter(startDate, endDate);
            presetBtns.forEach((b) => b.classList.remove("active"));
        });
    }

    // ========== LOAD DATA ==========
    async function loadCharts(id) {
        ["tensi", "gula", "kolesterol", "bb", "lp", "imt"].forEach((k) =>
            setChartState(k, "loading"),
        );
        try {
            const res = await apiFetch(`/lansia/${id}/health-history`);
            const json = await res.json();
            fullHealthHistoryData = json.data || [];
            applyDefaultFilter();
        } catch (e) {
            console.error("Gagal mengambil data health-history:", e);
            fullHealthHistoryData = [];
            applyDefaultFilter();
        }
    }

    // ========== MODAL DETAIL DATA (menggunakan filteredHealthHistoryData) ==========
    const modalDetail = document.getElementById("detail-modal");
    window.closeDetailModal = function () {
        if (modalDetail) modalDetail.style.display = "none";
    };
    modalDetail?.addEventListener("click", (e) => {
        if (e.target === modalDetail) closeDetailModal();
    });

    window.openDetailModal = function (type) {
        if (!modalDetail) return;
        const dataSource = filteredHealthHistoryData.length
            ? filteredHealthHistoryData
            : fullHealthHistoryData;
        const titleEl = document.getElementById("modal-title");
        const thead = document.getElementById("detail-thead");
        const tbody = document.getElementById("detail-tbody");
        let title = "",
            thHTML = "",
            tdBuilder = (r) => "";
        let filteredData = [];

        const hasData = (r, keys) => keys.some((k) => r[k] != null);

        // Fungsi bantu untuk memberi warna teks
        function colorText(value, condition) {
            if (condition === "normal")
                return `<span style="color: #10b981; font-weight:500;">${value}</span>`;
            if (condition === "waspada")
                return `<span style="color: #f59e0b; font-weight:500;">${value}</span>`;
            if (condition === "bahaya")
                return `<span style="color: #ef4444; font-weight:500;">${value}</span>`;
            return value;
        }

        // Status untuk tensi
        function getTensiStatus(sis, dias) {
            const ts = TH.sistolik;
            const td = TH.diastolik;
            const sisStatus =
                sis >= ts.bahaya_atas || sis < ts.bahaya_bawah
                    ? "bahaya"
                    : sis >= ts.waspada_atas || sis < ts.waspada_bawah
                      ? "waspada"
                      : "normal";
            const diasStatus =
                dias >= td.bahaya_atas || dias < td.bahaya_bawah
                    ? "bahaya"
                    : dias >= td.waspada_atas || dias < td.waspada_bawah
                      ? "waspada"
                      : "normal";
            return { sisStatus, diasStatus };
        }

        switch (type) {
            case "tensi":
                title = "Detail Tekanan Darah";
                thHTML =
                    "<th>Tanggal</th><th>Sistolik (mmHg)</th><th>Diastolik (mmHg)</th>";
                filteredData = dataSource.filter((r) =>
                    hasData(r, ["td_sistolik", "td_diastolik"]),
                );
                tdBuilder = (r) => {
                    const sis = r.td_sistolik;
                    const dias = r.td_diastolik;
                    const { sisStatus, diasStatus } = getTensiStatus(sis, dias);
                    const sisHtml = sis ? colorText(sis, sisStatus) : "-";
                    const diasHtml = dias ? colorText(dias, diasStatus) : "-";
                    return `<tr><td>${fmtDate(r.tanggal)}</td><td>${sisHtml}</td><td>${diasHtml}</td></tr>`;
                };
                break;
            case "gula":
                title = "Detail Gula Darah";
                thHTML = "<th>Tanggal</th><th>Gula Darah (mg/dL)</th>";
                filteredData = dataSource.filter((r) =>
                    hasData(r, ["gula_darah"]),
                );
                tdBuilder = (r) => {
                    let val = r.gula_darah;
                    const tg = TH.gula_darah;
                    let status = "normal";
                    if (val >= tg.bahaya_atas) status = "bahaya";
                    else if (val >= tg.waspada_atas) status = "waspada";
                    const valHtml = val ? colorText(val, status) : "-";
                    return `<tr><td>${fmtDate(r.tanggal)}</td><td>${valHtml}</td></tr>`;
                };
                break;
            case "kolesterol":
                title = "Detail Kolesterol";
                thHTML = "<th>Tanggal</th><th>Kolesterol (mg/dL)</th>";
                filteredData = dataSource.filter((r) =>
                    hasData(r, ["kolesterol"]),
                );
                tdBuilder = (r) => {
                    let val = r.kolesterol;
                    const tk = TH.kolesterol;
                    let status = "normal";
                    if (val >= tk.bahaya_atas) status = "bahaya";
                    else if (val >= tk.waspada_atas) status = "waspada";
                    const valHtml = val ? colorText(val, status) : "-";
                    return `<tr><td>${fmtDate(r.tanggal)}</td><td>${valHtml}</td></tr>`;
                };
                break;
            case "bb":
                title = "Detail Berat Badan";
                thHTML = "<th>Tanggal</th><th>Berat Badan (kg)</th>";
                filteredData = dataSource.filter((r) =>
                    hasData(r, ["berat_badan"]),
                );
                tdBuilder = (r) =>
                    `<tr><td>${fmtDate(r.tanggal)}</td><td>${r.berat_badan || "-"}</td></tr>`;
                break;
            case "lp":
                title = "Detail Lingkar Perut";
                thHTML = "<th>Tanggal</th><th>Lingkar Perut (cm)</th>";
                filteredData = dataSource.filter((r) =>
                    hasData(r, ["lingkar_perut"]),
                );
                tdBuilder = (r) => {
                    let val = r.lingkar_perut;
                    let status = val >= lpLimit ? "bahaya" : "normal";
                    const valHtml = val ? colorText(val, status) : "-";
                    return `<tr><td>${fmtDate(r.tanggal)}</td><td>${valHtml}</td></tr>`;
                };
                break;
            case "imt":
                title = "Detail IMT";
                // HAPUS kolom status, hanya tanggal dan nilai IMT
                thHTML = "<th>Tanggal</th><th>IMT (kg/m²)</th>";
                filteredData = dataSource.filter((r) => hasData(r, ["imt"]));
                tdBuilder = (r) => {
                    let val = r.imt;
                    const ti = TH.imt;
                    let status = "normal";
                    if (val != null) {
                        if (
                            (val >= ti.bahaya_bawah &&
                                val < ti.waspada_bawah) ||
                            (val > ti.waspada_atas && val < ti.bahaya_atas)
                        )
                            status = "waspada";
                        else if (val < ti.bahaya_bawah || val >= ti.bahaya_atas)
                            status = "bahaya";
                        else status = "normal";
                    }
                    const valHtml = val ? colorText(val, status) : "-";
                    return `<tr><td>${fmtDate(r.tanggal)}</td><td>${valHtml}</td></tr>`;
                };
                break;
        }

        titleEl.textContent = title;
        thead.innerHTML = thHTML;

        if (!filteredData.length) {
            tbody.innerHTML = `<tr><td colspan="5" style="text-align:center; padding:20px;">Tidak ada riwayat data.</td></tr>`;
        } else {
            const sorted = [...filteredData].sort(
                (a, b) => new Date(b.tanggal) - new Date(a.tanggal),
            );
            tbody.innerHTML = sorted.map(tdBuilder).join("");
        }
        modalDetail.style.display = "flex";
    };

    // ========== KELUHAN (tidak berubah) ==========
    async function loadKeluhan(id) {
        show("keluhan-loading");
        hide("keluhan-empty");
        hide("keluhan-latest");
        hide("keluhan-all-wrapper");
        try {
            const res = await apiFetch(`/lansia/${id}/keluhan-history`);
            const json = await res.json();
            const data = json.data || [];
            hide("keluhan-loading");
            if (!data.length) {
                show("keluhan-empty");
                return;
            }
            const latest = data[0];
            el("kl-tanggal").textContent = fmtDate(latest.tanggal_skrining);
            el("kl-isi").textContent = latest.keluhan || "Tidak ada keluhan.";
            el("kl-diagnosis").textContent =
                latest.diagnosis || "Tidak ada diagnosis.";
            show("keluhan-latest");
            el("keluhan-all-list").innerHTML = data
                .map(
                    (r) =>
                        `<div class="keluhan-row" style="display:grid; grid-template-columns:140px minmax(0,1fr) minmax(0,1fr); gap:12px; align-items:start;"><div class="keluhan-row-date">${fmtDate(r.tanggal_skrining)}</div><div class="keluhan-row-isi">${r.keluhan || '<em style="color:#9ca3af;">Tidak ada keluhan</em>'}</div><div class="keluhan-row-isi">${r.diagnosis || '<em style="color:#9ca3af;">Tidak ada diagnosis</em>'}</div></div>`,
                )
                .join("");
        } catch (e) {
            console.error(e);
            hide("keluhan-loading");
            show("keluhan-empty");
        }
    }
    el("btn-lihat-semua-keluhan")?.addEventListener("click", () => {
        show("keluhan-all-wrapper");
        hide("keluhan-latest");
    });
    el("btn-tutup-keluhan")?.addEventListener("click", () => {
        hide("keluhan-all-wrapper");
        show("keluhan-latest");
    });

    // ========== SARAN (tidak berubah) ==========
    async function loadSaran(id) {
        show("dp-saran-loading");
        hide("dp-saran-empty");
        el("dp-saran-list").innerHTML = "";
        el("dp-saran-new-list").innerHTML = "";
        saranNewIdx = 0;
        try {
            const res = await apiFetch(`/lansia/${id}/saran`);
            const json = await res.json();
            const data = json.data || [];
            hide("dp-saran-loading");
            data.length ? renderSaranList(data) : show("dp-saran-empty");
        } catch {
            hide("dp-saran-loading");
            show("dp-saran-empty");
        }
    }
    function renderSaranList(data) {
        const list = el("dp-saran-list");
        list.innerHTML = "";
        hide("dp-saran-empty");
        data.forEach((s) => list.appendChild(buildSaranItem(s)));
    }
    function buildSaranItem(s) {
        const div = document.createElement("div");
        div.className = "dp-saran-item";
        div.dataset.id = s.id_saran;
        div.innerHTML = `
            <div class="dp-saran-item-header">
                <span class="dp-saran-jenis" id="sji-${s.id_saran}">${esc(s.jenis_saran)}</span>
                <div class="dp-saran-actions">
                    <button class="dp-saran-btn edit" data-action="edit"><i class="fa-solid fa-pen"></i></button>
                    <button class="dp-saran-btn save" data-action="save" style="display:none;"><i class="fa-solid fa-check"></i></button>
                    <button class="dp-saran-btn del" data-action="del"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
            <p class="dp-saran-isi" id="sii-${s.id_saran}">${esc(s.isi_saran)}</p>
            <div id="sed-${s.id_saran}" style="display:none;">
                <input type="text" class="dp-saran-edit-jenis" id="sej-${s.id_saran}" value="${esc(s.jenis_saran)}" placeholder="Judul saran...">
                <textarea class="dp-saran-edit-isi" id="sei-${s.id_saran}" rows="3">${esc(s.isi_saran)}</textarea>
            </div>
        `;
        div.querySelector('[data-action="edit"]').addEventListener(
            "click",
            () => toggleEdit(s.id_saran, true),
        );
        div.querySelector('[data-action="save"]').addEventListener(
            "click",
            () => saveSaran(s.id_saran),
        );
        div.querySelector('[data-action="del"]').addEventListener("click", () =>
            delSaran(s.id_saran),
        );
        return div;
    }
    function toggleEdit(id, on) {
        const item = document.querySelector(`.dp-saran-item[data-id="${id}"]`);
        if (!item) return;
        item.classList.toggle("editing", on);
        item.querySelector('[data-action="edit"]').style.display = on
            ? "none"
            : "flex";
        item.querySelector('[data-action="save"]').style.display = on
            ? "flex"
            : "none";
        el(`sji-${id}`).style.display = on ? "none" : "";
        el(`sii-${id}`).style.display = on ? "none" : "";
        el(`sed-${id}`).style.display = on ? "block" : "none";
    }
    async function saveSaran(id) {
        const jenis = el(`sej-${id}`)?.value.trim();
        const isi = el(`sei-${id}`)?.value.trim();
        if (!jenis || !isi) {
            alert("Judul dan isi wajib diisi.");
            return;
        }
        const res = await fetchJSON("PUT", `/lansia/${lansiaId}/saran/${id}`, {
            jenis_saran: jenis,
            isi_saran: isi,
        });
        const json = await res.json();
        if (json.success) {
            el(`sji-${id}`).textContent = jenis;
            el(`sii-${id}`).textContent = isi;
            toggleEdit(id, false);
        }
    }
    async function delSaran(id) {
        if (!confirm("Hapus saran ini?")) return;
        const res = await fetchJSON(
            "DELETE",
            `/lansia/${lansiaId}/saran/${id}`,
        );
        const json = await res.json();
        if (json.success) {
            document.querySelector(`.dp-saran-item[data-id="${id}"]`)?.remove();
            if (!el("dp-saran-list").children.length) show("dp-saran-empty");
        }
    }
    el("dp-btn-add-saran")?.addEventListener("click", () => {
        hide("dp-saran-empty");
        const idx = saranNewIdx++;
        const row = document.createElement("div");
        row.className = "dp-new-saran-row";
        row.id = `new-saran-row-${idx}`;
        row.innerHTML = `
            <div class="dp-new-saran-inner">
                <input type="text" class="dp-input-new" id="nsj-${idx}" placeholder="Judul saran (cth: Pola Makan...)">
                <textarea class="dp-input-new" id="nsi-${idx}" rows="3" placeholder="Tulis isi saran..."></textarea>
                <div class="dp-new-saran-footer">
                    <button type="button" class="dp-btn-cancel-new">Batal</button>
                    <button type="button" class="dp-btn-save-new"><i class="fa-solid fa-check"></i> Simpan</button>
                </div>
            </div>
        `;
        el("dp-saran-new-list").appendChild(row);
        row.querySelector(".dp-btn-cancel-new").addEventListener("click", () =>
            row.remove(),
        );
        row.querySelector(".dp-btn-save-new").addEventListener("click", () =>
            submitNewSaran(idx, row),
        );
        row.querySelector(`#nsj-${idx}`).focus();
    });
    async function submitNewSaran(idx, row) {
        const jenis = el(`nsj-${idx}`)?.value.trim();
        const isi = el(`nsi-${idx}`)?.value.trim();
        if (!jenis || !isi) {
            alert("Judul dan isi wajib diisi.");
            return;
        }
        const res = await fetchJSON("POST", `/lansia/${lansiaId}/saran`, {
            jenis_saran: jenis,
            isi_saran: isi,
        });
        const json = await res.json();
        if (json.success) {
            row.remove();
            el("dp-saran-list").appendChild(buildSaranItem(json.data));
            hide("dp-saran-empty");
        }
    }

    // ========== EKSEKUSI AWAL ==========
    loadCharts(lansiaId);
    loadKeluhan(lansiaId);
    loadSaran(lansiaId);
    initFilters(); // panggil inisialisasi filter
});
