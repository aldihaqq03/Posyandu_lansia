/* resources/js/jsAdmin/monitoring.js */

document.addEventListener("DOMContentLoaded", function () {
    const lansiaId = document.getElementById("lansia-id")?.value;
    const gender = document.getElementById("lansia-gender")?.value || "L"; // L atau P

    if (!lansiaId) {
        console.error("Lansia ID tidak ditemukan.");
        return;
    }

    let saranNewIdx = 0;
    let healthHistoryData = []; // Simpan data riwayat untuk modal

    // ── Inisiasi lingkar perut zone label sesuai gender ──────
    const lpLimit = gender === "P" ? 80 : 90;
    document.getElementById("zone-lp").innerHTML = `
        <span class="mz mz-normal">Normal ≤${lpLimit} cm</span>
        <span class="mz mz-bahaya">Berisiko &gt;${lpLimit} cm</span>
    `;

    // ── Tabs Logic ────────────────────────────────────────────
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

    // ── Boot ──────────────────────────────────────────────────
    loadCharts(lansiaId);
    loadKeluhan(lansiaId);
    loadSaran(lansiaId);

    // ══════════════════════════════════════════════════════════
    // GRAFIK — ambil data lalu build masing-masing chart
    // ══════════════════════════════════════════════════════════

    async function loadCharts(id) {
        // Set semua ke loading awal
        ["tensi", "gula", "kolesterol", "bb", "lp", "imt"].forEach((k) =>
            setChartState(k, "loading"),
        );

        let rows = [];
        try {
            const res = await apiFetch(`/lansia/${id}/health-history`);
            const json = await res.json();
            healthHistoryData = json.data || [];
        } catch (e) {
            console.error("Gagal mengambil data health-history:", e);
        }

        buildTensiChart(healthHistoryData);
        buildGulaChart(healthHistoryData);
        buildKolesterolChart(healthHistoryData);
        buildBBChart(healthHistoryData);
        buildLPChart(healthHistoryData, lpLimit);
        buildIMTChart(healthHistoryData);
    }

    /* ────────────────────────────────────────────────────────
       HELPER: buat opsi Chart.js dengan time scale
    ──────────────────────────────────────────────────────── */
    function makeOptions(labelCallback) {
        return {
            responsive: true,
            maintainAspectRatio: false,
            parsing: false, // data sudah {x, y}
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
                            return new Date(raw).toLocaleDateString("id-ID", {
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
                        unit: "month",
                        displayFormats: { month: "MMM yyyy" },
                    },
                    ticks: {
                        font: { size: 11 },
                        color: "#9ca3af",
                        maxRotation: 0,
                        autoSkip: true,
                        maxTicksLimit: 8,
                    },
                    grid: { color: "#f3f4f6" },
                    border: { color: "#e5e7eb" },
                },
                y: {
                    ticks: { font: { size: 11 }, color: "#9ca3af" },
                    grid: { color: "#f3f4f6" },
                    border: { color: "#e5e7eb" },
                    beginAtZero: false,
                },
            },
        };
    }

    /* ── Buat dataset titik utama ── */
    function pts(rows, key) {
        return rows
            .filter((r) => r[key] != null)
            .map((r) => ({ x: new Date(r.tanggal), y: Number(r[key]) }));
    }

    /* ── Buat garis referensi horizontal ── */
    function refLine(rows, key, value, color) {
        const validRows = rows.filter((r) => r[key] != null);
        if (validRows.length < 1) return null;
        const dates = validRows
            .map((r) => new Date(r.tanggal))
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
            tension: 0,
            parsing: false,
        };
    }

    /* ── Dataset garis data ── */
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

    /* ── Render legend ── */
    function renderLegend(elId, items) {
        const el = document.getElementById(elId);
        if (!el) return;
        el.innerHTML = items
            .map(
                (i) =>
                    `<span>
                <span style="width:18px;height:2.5px;border-radius:2px;background:${i.dash
                        ? `repeating-linear-gradient(90deg,${i.color} 0,${i.color} 5px,transparent 5px,transparent 9px)`
                        : i.color
                    };display:inline-block;vertical-align:middle;margin-right:4px;"></span>
                ${i.label}
            </span>`,
            )
            .join("");
    }

    /* ── State chart (loading / chart / empty) ── */
    function setChartState(key, state) {
        const loading = document.getElementById(`loading-${key}`);
        const empty = document.getElementById(`empty-${key}`);
        const wrap = document.getElementById(`wrap-${key}`);
        if (loading)
            loading.style.display = state === "loading" ? "block" : "none";
        if (empty) empty.style.display = state === "empty" ? "flex" : "none";
        if (wrap) wrap.style.display = state === "chart" ? "block" : "none";
    }

    /* ── Render chart ── */
    function renderChart(canvasId, datasets, options) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;
        new Chart(canvas, { type: "line", data: { datasets }, options });
    }

    /* ════════════════════════════════════════════
       1. TENSI
    ════════════════════════════════════════════ */
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

        const ZONES_SIS = { normal: 120, waspada: 130 };
        const ZONES_DIAS = { normal: 80 };

        const datasets = [
            lineset(sisPts, "#3b82f6", "Sistolik"),
            lineset(diasPts, "#f97316", "Diastolik"),
            refLine(rows, "td_sistolik", ZONES_SIS.normal, "#10b981"),
            refLine(rows, "td_sistolik", ZONES_SIS.waspada, "#f59e0b"),
            refLine(rows, "td_diastolik", ZONES_DIAS.normal, "#10b981"),
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
                    ? v > 130
                        ? "🔴 Berbahaya"
                        : v > 120
                            ? "⚠️ Tinggi"
                            : "✅ Normal"
                    : v > 90
                        ? "🔴 Berbahaya"
                        : v > 80
                            ? "⚠️ Tinggi"
                            : "✅ Normal";
                return `  ${lbl}: ${v} mmHg  ${st}`;
            }),
        );
    }

    /* ════════════════════════════════════════════
       2. GULA DARAH
    ════════════════════════════════════════════ */
    function buildGulaChart(rows) {
        const data = pts(rows, "gula_darah");
        if (!data.length) {
            setChartState("gula", "empty");
            return;
        }
        setChartState("gula", "chart");

        renderLegend("legend-gula", [
            { color: "#2563eb", label: "Gula Darah", dash: false },
            { color: "#10b981", label: "Batas normal (100)", dash: true },
            { color: "#f59e0b", label: "Batas diabetes (126)", dash: true },
        ]);

        renderChart(
            "chart-gula",
            [
                lineset(data, "#2563eb", "Gula Darah"),
                refLine(rows, "gula_darah", 100, "#10b981"),
                refLine(rows, "gula_darah", 126, "#f59e0b"),
            ].filter(Boolean),
            makeOptions((ctx) => {
                const v = ctx.raw?.y;
                if (v == null || ctx.dataset.label?.startsWith("Ref"))
                    return null;
                const st =
                    v >= 126
                        ? "🔴 Diabetes"
                        : v >= 100
                            ? "⚠️ Pra-DM"
                            : "✅ Normal";
                return `  Gula Darah: ${v} mg/dL  ${st}`;
            }),
        );
    }

    /* ════════════════════════════════════════════
       3. KOLESTEROL
    ════════════════════════════════════════════ */
    function buildKolesterolChart(rows) {
        const data = pts(rows, "kolesterol");
        if (!data.length) {
            setChartState("kolesterol", "empty");
            return;
        }
        setChartState("kolesterol", "chart");

        renderLegend("legend-kolesterol", [
            { color: "#7c3aed", label: "Kolesterol", dash: false },
            { color: "#10b981", label: "Batas normal (200)", dash: true },
            { color: "#f59e0b", label: "Batas tinggi (240)", dash: true },
        ]);

        renderChart(
            "chart-kolesterol",
            [
                lineset(data, "#7c3aed", "Kolesterol"),
                refLine(rows, "kolesterol", 200, "#10b981"),
                refLine(rows, "kolesterol", 240, "#f59e0b"),
            ].filter(Boolean),
            makeOptions((ctx) => {
                const v = ctx.raw?.y;
                if (v == null || ctx.dataset.label?.startsWith("Ref"))
                    return null;
                const st =
                    v >= 240
                        ? "🔴 Tinggi"
                        : v >= 200
                            ? "⚠️ Batas"
                            : "✅ Normal";
                return `  Kolesterol: ${v} mg/dL  ${st}`;
            }),
        );
    }

    /* ════════════════════════════════════════════
       4. BERAT BADAN
    ════════════════════════════════════════════ */
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

        // Hitung rata-rata untuk context
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
            }),
        );
    }

    /* ════════════════════════════════════════════
       5. LINGKAR PERUT
    ════════════════════════════════════════════ */
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
                const st = v > limit ? "🔴 Berisiko" : "✅ Normal";
                return `  Lingkar Perut: ${v} cm  ${st}`;
            }),
        );
    }

    /* ════════════════════════════════════════════
       6. IMT
    ════════════════════════════════════════════ */
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
            { color: "#f59e0b", label: "Batas waspada (18.5 / 30)", dash: true },
        ]);

        renderChart(
            "chart-imt",
            [
                lineset(data, "#e11d48", "IMT"),
                refLine(rows, "imt", 22, "#10b981"),
                refLine(rows, "imt", 27, "#10b981"),
                refLine(rows, "imt", 18.5, "#f59e0b"),
                refLine(rows, "imt", 30, "#f59e0b"),
            ].filter(Boolean),
            makeOptions((ctx) => {
                const v = ctx.raw?.y;
                if (v == null || ctx.dataset.label?.startsWith("Ref"))
                    return null;
                const st =
                    (v >= 22 && v <= 27)
                        ? "✅ Normal"
                        : ((v >= 18.5 && v < 22) || (v > 27 && v < 30))
                            ? "⚠️ Waspada"
                            : "🔴 Abnormal";
                return `  IMT: ${v} kg/m²  ${st}`;
            }),
        );
    }

    // ══════════════════════════════════════════════════════════
    // MODAL DETAIL DATA
    // ══════════════════════════════════════════════════════════
    const modalDetail = document.getElementById("detail-modal");

    window.closeDetailModal = function () {
        if (modalDetail) modalDetail.style.display = "none";
    };

    modalDetail?.addEventListener("click", (e) => {
        if (e.target === modalDetail) closeDetailModal();
    });

    window.openDetailModal = function (type) {
        if (!modalDetail) return;

        const titleEl = document.getElementById("modal-title");
        const thead = document.getElementById("detail-thead");
        const tbody = document.getElementById("detail-tbody");

        let title = "";
        let thHTML = "";
        let tdBuilder = (r) => "";

        const hasData = (r, keys) => keys.some((k) => r[k] != null);
        let filteredData = [];

        switch (type) {
            case "tensi":
                title = "Detail Tekanan Darah";
                thHTML = `<th>Tanggal</th><th>Sistolik (mmHg)</th><th>Diastolik (mmHg)</th>`;
                filteredData = healthHistoryData.filter((r) =>
                    hasData(r, ["td_sistolik", "td_diastolik"]),
                );
                tdBuilder = (r) =>
                    `<tr><td>${fmtDate(r.tanggal)}</td><td>${r.td_sistolik || "-"}</td><td>${r.td_diastolik || "-"}</td></tr>`;
                break;
            case "gula":
                title = "Detail Gula Darah";
                thHTML = `<th>Tanggal</th><th>Gula Darah (mg/dL)</th>`;
                filteredData = healthHistoryData.filter((r) =>
                    hasData(r, ["gula_darah"]),
                );
                tdBuilder = (r) =>
                    `<tr><td>${fmtDate(r.tanggal)}</td><td>${r.gula_darah || "-"}</td></tr>`;
                break;
            case "kolesterol":
                title = "Detail Kolesterol";
                thHTML = `<th>Tanggal</th><th>Kolesterol (mg/dL)</th>`;
                filteredData = healthHistoryData.filter((r) =>
                    hasData(r, ["kolesterol"]),
                );
                tdBuilder = (r) =>
                    `<tr><td>${fmtDate(r.tanggal)}</td><td>${r.kolesterol || "-"}</td></tr>`;
                break;
            case "bb":
                title = "Detail Berat Badan";
                thHTML = `<th>Tanggal</th><th>Berat Badan (kg)</th>`;
                filteredData = healthHistoryData.filter((r) =>
                    hasData(r, ["berat_badan"]),
                );
                tdBuilder = (r) =>
                    `<tr><td>${fmtDate(r.tanggal)}</td><td>${r.berat_badan || "-"}</td></tr>`;
                break;
            case "lp":
                title = "Detail Lingkar Perut";
                thHTML = `<th>Tanggal</th><th>Lingkar Perut (cm)</th>`;
                filteredData = healthHistoryData.filter((r) =>
                    hasData(r, ["lingkar_perut"]),
                );
                tdBuilder = (r) =>
                    `<tr><td>${fmtDate(r.tanggal)}</td><td>${r.lingkar_perut || "-"}</td></tr>`;
                break;
            case "imt":
                title = "Detail IMT";
                thHTML = `<th>Tanggal</th><th>IMT (kg/m²)</th><th>Status</th>`;
                filteredData = healthHistoryData.filter((r) =>
                    hasData(r, ["imt"]),
                );
                tdBuilder = (r) => {
                    const v = r.imt;
                    let st = "-";
                    if (v != null) {
                        st = (v >= 22 && v <= 27) ? '✅ Normal' : ((v >= 18.5 && v < 22) || (v > 27 && v < 30)) ? '⚠️ Waspada' : '❌ Abnormal';
                    }
                    return `<tr><td>${fmtDate(r.tanggal)}</td><td>${v || "-"}</td><td>${st}</td></tr>`;
                };
                break;
        }

        titleEl.textContent = title;
        thead.innerHTML = thHTML;

        if (filteredData.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" style="text-align:center; padding: 20px;">Tidak ada riwayat data.</td></tr>`;
        } else {
            // Urutkan terbaru di atas
            const sorted = [...filteredData].sort(
                (a, b) => new Date(b.tanggal) - new Date(a.tanggal),
            );
            tbody.innerHTML = sorted.map(tdBuilder).join("");
        }

        modalDetail.style.display = "flex";
    };

    // ══════════════════════════════════════════════════════════
    // KELUHAN
    // ══════════════════════════════════════════════════════════

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

            show("keluhan-latest");

            el("keluhan-all-list").innerHTML = data
                .map(
                    (r) => `
                <div class="keluhan-row">
                    <div class="keluhan-row-date">${fmtDate(r.tanggal_skrining)}</div>
                    <div class="keluhan-row-isi">${r.keluhan ||
                        '<em style="color:#9ca3af;">Tidak ada keluhan</em>'
                        }</div>
                </div>
            `,
                )
                .join("");
        } catch (e) {
            console.error("loadKeluhan error:", e);
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

    // ══════════════════════════════════════════════════════════
    // SARAN
    // ══════════════════════════════════════════════════════════

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
                    <button class="dp-saran-btn edit" data-action="edit" title="Edit"><i class="fa-solid fa-pen"></i></button>
                    <button class="dp-saran-btn save" data-action="save" title="Simpan" style="display:none;"><i class="fa-solid fa-check"></i></button>
                    <button class="dp-saran-btn del"  data-action="del"  title="Hapus"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
            <p class="dp-saran-isi" id="sii-${s.id_saran}">${esc(s.isi_saran)}</p>
            <div id="sed-${s.id_saran}" style="display:none;">
                <input  type="text" class="dp-saran-edit-jenis" id="sej-${s.id_saran}" value="${esc(s.jenis_saran)}" placeholder="Judul saran...">
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
                <input  type="text" class="dp-input-new" id="nsj-${idx}" placeholder="Judul saran (cth: Pola Makan...)">
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

    // ══════════════════════════════════════════════════════════
    // UTILITIES
    // ══════════════════════════════════════════════════════════

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
            return new Date(str).toLocaleDateString("id-ID", {
                day: "2-digit",
                month: "short",
                year: "numeric",
            });
        } catch {
            return str;
        }
    }
});
