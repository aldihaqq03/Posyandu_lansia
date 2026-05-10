/* resources/js/jsAdmin/monitoring.js */
document.addEventListener("DOMContentLoaded", function () {
    const lansiaIdInput = document.getElementById("lansia-id");
    const currentLansiaId = lansiaIdInput ? lansiaIdInput.value : null;

    if (!currentLansiaId) {
        console.error("Lansia ID is missing.");
        return;
    }

    let healthChartInstance = null;
    let allHealthData = {
        labels: [],
        tensi_sistolik: [],
        tensi_diastolik: [],
        gula: [],
        kolesterol: [],
    };
    let currentChartMode = "tensi";
    let saranNewIdx = 0;

    // Load all sections on page load
    loadAllSections(currentLansiaId);

    function loadAllSections(id) {
        console.log("🔄 loadAllSections() called, id:", id);
        loadHealthChart(id);
        loadKeluhan(id);
        loadSaran(id);
    }

    /* ── Grafik Kesehatan ── */
    async function loadHealthChart(id) {
        console.log("🔄 loadHealthChart() called, id:", id);
        showEl("grafik-loading");
        hideEl("grafik-empty");
        hideEl("grafik-container");

        try {
            const url = `/lansia/${id}/health-history`;
            console.log("📍 Fetch URL:", url);
            const res = await apiFetchExt(url);
            console.log("✓ Response status:", res.status);
            const json = await res.json();
            console.log("✓ JSON data:", json);

            if (!json.data || json.data.length === 0) {
                console.log("⚠️ No health data found");
                hideEl("grafik-loading");
                showEl("grafik-empty");
                return;
            }

            console.log("✓ Processing", json.data.length, "health records");
            allHealthData = parseHealthData(json.data);
            console.log("✓ Parsed health data:", allHealthData);
            hideEl("grafik-loading");
            showEl("grafik-container");
            renderChart(currentChartMode);
        } catch (err) {
            console.error("❌ loadHealthChart() error:", err);
            hideEl("grafik-loading");
            showEl("grafik-empty");
        }
    }

    function parseHealthData(rows) {
        const labels = [],
            sis = [],
            dias = [],
            gula = [],
            kol = [];
        rows.forEach((r) => {
            labels.push(formatTanggalExt(r.tanggal));
            sis.push(r.td_sistolik ?? null);
            dias.push(r.td_diastolik ?? null);
            gula.push(r.gula_darah ?? null);
            kol.push(r.kolesterol ?? null);
        });
        return {
            labels,
            tensi_sistolik: sis,
            tensi_diastolik: dias,
            gula,
            kolesterol: kol,
        };
    }

    const ZONES = {
        tensi_sistolik: [
            { max: 120, cls: "normal", label: "Normal <120" },
            { max: 130, cls: "waspada", label: "Tinggi 120–130" },
            { max: 999, cls: "bahaya", label: "Berbahaya >130" },
        ],
        tensi_diastolik: [
            { max: 80, cls: "normal", label: "Normal <80" },
            { max: 90, cls: "waspada", label: "Tinggi 80–90" },
            { max: 999, cls: "bahaya", label: "Berbahaya >90" },
        ],
        gula: [
            { max: 100, cls: "normal", label: "Normal <100" },
            { max: 126, cls: "waspada", label: "Pra-DM 100–125" },
            { max: 999, cls: "bahaya", label: "Diabetes ≥126" },
        ],
        kolesterol: [
            { max: 200, cls: "normal", label: "Normal <200" },
            { max: 240, cls: "waspada", label: "Batas 200–239" },
            { max: 999, cls: "bahaya", label: "Tinggi ≥240" },
        ],
    };

    const SERIES_META = {
        tensi_sistolik: { label: "Sistolik", color: "#ef4444", dash: [] },
        tensi_diastolik: { label: "Diastolik", color: "#f97316", dash: [5, 3] },
        gula: { label: "Gula Darah", color: "#2563eb", dash: [] },
        kolesterol: { label: "Kolesterol", color: "#7c3aed", dash: [5, 3] },
    };

    const MODE_SERIES = {
        tensi: ["tensi_sistolik", "tensi_diastolik"],
        gula: ["gula"],
        kolesterol: ["kolesterol"],
        semua: ["tensi_sistolik", "tensi_diastolik", "gula", "kolesterol"],
    };

    function renderChart(mode) {
        const seriesKeys = MODE_SERIES[mode];
        const datasets = seriesKeys.map((k) => ({
            label: SERIES_META[k].label,
            data: allHealthData[k],
            borderColor: SERIES_META[k].color,
            backgroundColor: SERIES_META[k].color + "22",
            borderWidth: 2.5,
            borderDash: SERIES_META[k].dash,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointBackgroundColor: SERIES_META[k].color,
            fill: false,
            tension: 0,
            spanGaps: true,
        }));

        const legendEl = document.getElementById("chart-legend");
        legendEl.innerHTML = seriesKeys
            .map(
                (k) =>
                    `<span><span class="legend-dot" style="background:${SERIES_META[k].color}"></span>${SERIES_META[k].label}</span>`,
            )
            .join("");

        const zoneEl = document.getElementById("chart-zone-info");
        const refKey = seriesKeys[0];
        if (ZONES[refKey]) {
            zoneEl.innerHTML = ZONES[refKey]
                .map(
                    (z) =>
                        `<span class="zone-badge zone-${z.cls}">${z.label}</span>`,
                )
                .join("");
        } else {
            zoneEl.innerHTML = "";
        }

        const refLines = [];
        if (mode !== "semua" && ZONES[refKey]) {
            ZONES[refKey].slice(0, -1).forEach((z) => {
                refLines.push({
                    label: z.label,
                    data: allHealthData.labels.map(() => z.max),
                    borderColor: z.cls === "waspada" ? "#f59e0b" : "#10b981",
                    borderWidth: 1,
                    borderDash: [4, 4],
                    pointRadius: 0,
                    fill: false,
                    tension: 0,
                    spanGaps: true,
                    order: 99,
                });
            });
        }

        if (healthChartInstance) {
            healthChartInstance.destroy();
        }

        healthChartInstance = new Chart(
            document.getElementById("health-chart"),
            {
                type: "line",
                data: {
                    labels: allHealthData.labels,
                    datasets: [...datasets, ...refLines],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: "index", intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label(ctx) {
                                    const v = ctx.parsed.y;
                                    if (v === null) return null;
                                    const k = seriesKeys[ctx.datasetIndex];
                                    const unit =
                                        k === "gula" || k === "kolesterol"
                                            ? " mg/dL"
                                            : " mmHg";
                                    let status = "";
                                    if (k && ZONES[k]) {
                                        const zone = ZONES[k].find(
                                            (z) => v <= z.max,
                                        );
                                        if (zone) {
                                            const icons = {
                                                normal: "✅",
                                                waspada: "⚠️",
                                                bahaya: "🔴",
                                            };
                                            status = " " + icons[zone.cls];
                                        }
                                    }
                                    return ` ${SERIES_META[k]?.label || ctx.dataset.label}: ${v}${unit}${status}`;
                                },
                            },
                        },
                    },
                    scales: {
                        x: {
                            ticks: { font: { size: 11 }, maxRotation: 45 },
                            grid: { color: "#f3f4f6" },
                        },
                        y: {
                            ticks: { font: { size: 11 } },
                            grid: { color: "#f3f4f6" },
                            beginAtZero: false,
                        },
                    },
                },
            },
        );
    }

    document.querySelectorAll(".chart-tab").forEach((btn) => {
        btn.addEventListener("click", function () {
            document
                .querySelectorAll(".chart-tab")
                .forEach((b) => b.classList.remove("active"));
            this.classList.add("active");
            currentChartMode = this.dataset.chart;
            if (healthChartInstance) renderChart(currentChartMode);
        });
    });

    /* ── Keluhan ── */
    async function loadKeluhan(id) {
        console.log("🔄 loadKeluhan() called, id:", id);
        showEl("keluhan-loading");
        hideEl("keluhan-empty");
        hideEl("keluhan-latest");
        hideEl("keluhan-all-wrapper");

        try {
            const url = `/lansia/${id}/keluhan-history`;
            console.log("📍 Fetch URL:", url);
            const res = await apiFetchExt(url);
            console.log("✓ Response status:", res.status);
            const json = await res.json();
            console.log("✓ JSON data:", json);
            const data = json.data || [];

            hideEl("keluhan-loading");

            if (data.length === 0) {
                console.log("⚠️ No keluhan data found");
                showEl("keluhan-empty");
                return;
            }

            console.log("✓ Processing", data.length, "keluhan records");
            const latest = data[0];
            document.getElementById("kl-tanggal").textContent =
                formatTanggalExt(latest.tanggal_skrining);
            document.getElementById("kl-isi").textContent =
                latest.keluhan || "Tidak ada keluhan.";

            const vitalsEl = document.getElementById("kl-vitals");
            const chips = [];
            if (latest.td_sistolik)
                chips.push(`Sistolik: ${latest.td_sistolik} mmHg`);
            if (latest.td_diastolik)
                chips.push(`Diastolik: ${latest.td_diastolik} mmHg`);
            if (latest.berat_badan) chips.push(`BB: ${latest.berat_badan} kg`);
            vitalsEl.innerHTML = chips
                .map((c) => `<span class="keluhan-vital-chip">${c}</span>`)
                .join("");

            showEl("keluhan-latest");

            const allList = document.getElementById("keluhan-all-list");
            if (data.length === 0) {
                allList.innerHTML =
                    '<div class="keluhan-row-empty">Tidak ada riwayat keluhan.</div>';
            } else {
                allList.innerHTML = data
                    .map(
                        (r) => `
                    <div class="keluhan-row">
                        <div class="keluhan-row-date">
                            <i class="fa-solid fa-calendar-day" style="margin-right:4px;font-size:11px;color:#9ca3af;"></i>
                            ${formatTanggalExt(r.tanggal_skrining)}
                        </div>
                        <div class="keluhan-row-isi">${r.keluhan || '<em style="color:#9ca3af;">Tidak ada keluhan</em>'}</div>
                    </div>
                `,
                    )
                    .join("");
            }
        } catch (err) {
            console.error("❌ loadKeluhan() error:", err);
            hideEl("keluhan-loading");
            showEl("keluhan-empty");
        }
    }

    document
        .getElementById("btn-lihat-semua-keluhan")
        ?.addEventListener("click", () => {
            showEl("keluhan-all-wrapper");
            hideEl("keluhan-latest");
        });
    document
        .getElementById("btn-tutup-keluhan")
        ?.addEventListener("click", () => {
            hideEl("keluhan-all-wrapper");
            showEl("keluhan-latest");
        });

    /* ── Manajemen Saran ── */
    async function loadSaran(id) {
        console.log("🔄 loadSaran() called, id:", id);
        showEl("dp-saran-loading");
        hideEl("dp-saran-empty");
        document.getElementById("dp-saran-list").innerHTML = "";
        document.getElementById("dp-saran-new-list").innerHTML = "";
        saranNewIdx = 0;

        try {
            const url = `/lansia/${id}/saran`;
            console.log("📍 Fetch URL:", url);
            const res = await apiFetchExt(url);
            console.log("✓ Response status:", res.status);
            const json = await res.json();
            console.log("✓ JSON data:", json);
            const data = json.data || [];

            hideEl("dp-saran-loading");

            if (data.length === 0) {
                console.log("⚠️ No saran data found");
                showEl("dp-saran-empty");
            } else {
                console.log("✓ Processing", data.length, "saran records");
                renderSaranList(data);
            }
        } catch (err) {
            console.error("❌ loadSaran() error:", err);
            hideEl("dp-saran-loading");
            showEl("dp-saran-empty");
        }
    }

    function renderSaranList(data) {
        const list = document.getElementById("dp-saran-list");
        list.innerHTML = "";
        hideEl("dp-saran-empty");

        data.forEach((s) => {
            const item = buildSaranItem(s);
            list.appendChild(item);
        });
    }

    function buildSaranItem(s) {
        const el = document.createElement("div");
        el.className = "dp-saran-item";
        el.dataset.id = s.id_saran;
        el.innerHTML = `
            <div class="dp-saran-item-header">
                <span class="dp-saran-jenis" id="sji-${s.id_saran}">${escHtmlExt(s.jenis_saran)}</span>
                <div class="dp-saran-actions">
                    <button class="dp-saran-btn edit" title="Edit" data-action="edit" data-id="${s.id_saran}">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <button class="dp-saran-btn save" title="Simpan" data-action="save" data-id="${s.id_saran}" style="display:none;">
                        <i class="fa-solid fa-check"></i>
                    </button>
                    <button class="dp-saran-btn del" title="Hapus" data-action="del" data-id="${s.id_saran}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
            <p class="dp-saran-isi" id="sii-${s.id_saran}">${escHtmlExt(s.isi_saran)}</p>
            <div id="sed-${s.id_saran}" style="display:none;">
                <input type="text" class="dp-saran-edit-jenis" id="sej-${s.id_saran}" value="${escHtmlExt(s.jenis_saran)}" placeholder="Judul saran...">
                <textarea class="dp-saran-edit-isi" id="sei-${s.id_saran}" rows="3" placeholder="Isi saran...">${escHtmlExt(s.isi_saran)}</textarea>
            </div>
        `;

        el.querySelector('[data-action="edit"]').addEventListener("click", () =>
            toggleEditSaran(s.id_saran, true),
        );
        el.querySelector('[data-action="save"]').addEventListener("click", () =>
            saveEditSaran(s.id_saran),
        );
        el.querySelector('[data-action="del"]').addEventListener("click", () =>
            deleteSaran(s.id_saran),
        );

        return el;
    }

    function toggleEditSaran(id, editing) {
        const item = document.querySelector(`.dp-saran-item[data-id="${id}"]`);
        const jiEl = document.getElementById(`sji-${id}`);
        const isiEl = document.getElementById(`sii-${id}`);
        const edEl = document.getElementById(`sed-${id}`);
        const editBtn = item.querySelector('[data-action="edit"]');
        const saveBtn = item.querySelector('[data-action="save"]');

        if (editing) {
            item.classList.add("editing");
            jiEl.style.display = "none";
            isiEl.style.display = "none";
            edEl.style.display = "block";
            editBtn.style.display = "none";
            saveBtn.style.display = "flex";
        } else {
            item.classList.remove("editing");
            jiEl.style.display = "";
            isiEl.style.display = "";
            edEl.style.display = "none";
            editBtn.style.display = "flex";
            saveBtn.style.display = "none";
        }
    }

    async function saveEditSaran(id) {
        const jenis = document.getElementById(`sej-${id}`).value.trim();
        const isi = document.getElementById(`sei-${id}`).value.trim();
        if (!jenis || !isi) {
            alert("Judul dan isi saran wajib diisi.");
            return;
        }

        try {
            const res = await fetch(`/lansia/${currentLansiaId}/saran/${id}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfTokenExt(),
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: JSON.stringify({ jenis_saran: jenis, isi_saran: isi }),
            });
            const json = await res.json();
            if (json.success) {
                document.getElementById(`sji-${id}`).textContent = jenis;
                document.getElementById(`sii-${id}`).textContent = isi;
                document.getElementById(`sej-${id}`).value = jenis;
                document.getElementById(`sei-${id}`).value = isi;
                toggleEditSaran(id, false);
            } else {
                alert("Gagal menyimpan saran.");
            }
        } catch {
            alert("Terjadi kesalahan jaringan.");
        }
    }

    async function deleteSaran(id) {
        if (!confirm("Hapus saran ini?")) return;
        try {
            const res = await fetch(`/lansia/${currentLansiaId}/saran/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": csrfTokenExt(),
                    "X-Requested-With": "XMLHttpRequest",
                },
            });
            const json = await res.json();
            if (json.success) {
                const el = document.querySelector(
                    `.dp-saran-item[data-id="${id}"]`,
                );
                el?.remove();
                if (!document.getElementById("dp-saran-list").children.length) {
                    showEl("dp-saran-empty");
                }
            }
        } catch {
            alert("Gagal menghapus saran.");
        }
    }

    document
        .getElementById("dp-btn-add-saran")
        ?.addEventListener("click", () => {
            hideEl("dp-saran-empty");
            const list = document.getElementById("dp-saran-new-list");
            const idx = saranNewIdx++;
            const row = document.createElement("div");
            row.className = "dp-new-saran-row";
            row.id = `new-saran-row-${idx}`;
            row.innerHTML = `
            <div class="dp-new-saran-inner">
                <div class="dp-new-saran-fields">
                    <input type="text" class="dp-input-new" id="nsj-${idx}" placeholder="Judul saran (cth: Pola Makan, Aktivitas Fisik...)">
                    <textarea class="dp-input-new" id="nsi-${idx}" rows="3" placeholder="Tulis isi saran untuk lansia..."></textarea>
                </div>
                <div class="dp-new-saran-footer">
                    <button type="button" class="dp-btn-cancel-new" data-idx="${idx}">Batal</button>
                    <button type="button" class="dp-btn-save-new" data-idx="${idx}">
                        <i class="fa-solid fa-check"></i> Simpan
                    </button>
                </div>
            </div>
        `;
            list.appendChild(row);
            row.querySelector(".dp-btn-cancel-new").addEventListener(
                "click",
                () => row.remove(),
            );
            row.querySelector(".dp-btn-save-new").addEventListener(
                "click",
                () => submitNewSaran(idx),
            );
            row.querySelector(`#nsj-${idx}`).focus();
        });

    async function submitNewSaran(idx) {
        const jenis = document.getElementById(`nsj-${idx}`)?.value.trim();
        const isi = document.getElementById(`nsi-${idx}`)?.value.trim();
        if (!jenis || !isi) {
            alert("Judul dan isi saran wajib diisi.");
            return;
        }

        try {
            const res = await fetch(`/lansia/${currentLansiaId}/saran`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfTokenExt(),
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: JSON.stringify({ jenis_saran: jenis, isi_saran: isi }),
            });
            const json = await res.json();
            if (json.success) {
                document.getElementById(`new-saran-row-${idx}`)?.remove();
                const list = document.getElementById("dp-saran-list");
                const item = buildSaranItem(json.data);
                list.appendChild(item);
                hideEl("dp-saran-empty");
            } else {
                alert("Gagal menyimpan saran.");
            }
        } catch {
            alert("Terjadi kesalahan jaringan.");
        }
    }

    /* ── Utilities untuk Extension ── */
    function showEl(id) {
        const el = document.getElementById(id);
        if (el) el.style.display = "";
    }
    function hideEl(id) {
        const el = document.getElementById(id);
        if (el) el.style.display = "none";
    }
    function csrfTokenExt() {
        return document.querySelector('meta[name="csrf-token"]')?.content || "";
    }
    function apiFetchExt(url) {
        return fetch(url, {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        });
    }
    function escHtmlExt(str) {
        return String(str || "")
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;");
    }
    function formatTanggalExt(str) {
        if (!str) return "-";
        try {
            const d = new Date(str);
            return d.toLocaleDateString("id-ID", {
                day: "2-digit",
                month: "short",
                year: "numeric",
            });
        } catch {
            return str;
        }
    }
});
