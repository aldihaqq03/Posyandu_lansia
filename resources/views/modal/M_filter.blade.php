{{-- resources/views/modal/M_filter.blade.php --}}
<div class="modal-overlay" id="modal-filter-lansia">
    <div class="modal-content" style="max-width: 480px;">

        <div class="modal-header">
            <h2>Filter Lansia</h2>
            <button class="btn-close-modal" id="btn-close-filter-modal">&times;</button>
        </div>

        {{-- ── Filter Risiko ───────────────────────────────────── --}}
        <div style="margin-bottom: 24px;">
            <p style="font-size: 11px; font-weight: 700; color: #94a3b8; letter-spacing: 1px; margin-bottom: 10px;">
                FILTER RISIKO
            </p>
            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                <button type="button" class="filter-risk-tab active" data-risk="semua"
                    style="padding: 7px 16px; border-radius: 8px; border: 1px solid #e2e8f0; background: white; font-size: 13px; cursor: pointer;">
                    Semua
                </button>
                <button type="button" class="filter-risk-tab" data-risk="normal"
                    style="padding: 7px 16px; border-radius: 8px; border: 1px solid #e2e8f0; background: white; font-size: 13px; cursor: pointer;">
                    Normal
                </button>
                <button type="button" class="filter-risk-tab" data-risk="waspada"
                    style="padding: 7px 16px; border-radius: 8px; border: 1px solid #e2e8f0; background: white; font-size: 13px; cursor: pointer;">
                    Waspada
                </button>
                <button type="button" class="filter-risk-tab" data-risk="perlu"
                    style="padding: 7px 16px; border-radius: 8px; border: 1px solid #e2e8f0; background: white; font-size: 13px; cursor: pointer;">
                    Perlu Tindak Lanjut
                </button>
            </div>
        </div>

        <hr style="border: none; border-top: 1px solid #f1f5f9; margin-bottom: 20px;">

        {{-- ── Filter Penyakit (radio — hanya satu pilihan) ────── --}}
        <div id="filter-penyakit-block" style="transition: opacity 0.2s;">
            <p style="font-size: 11px; font-weight: 700; color: #94a3b8; letter-spacing: 1px; margin-bottom: 4px;">
                FILTER PENYAKIT
            </p>
            <p class="filter-penyakit-hint" style="font-size: 11px; color: #94a3b8; margin-bottom: 12px;">
                Tersedia saat Waspada / Perlu Tindak Lanjut dipilih
            </p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">

                @php
                    $penyakitList = [
                        'hipertensi' => ['label' => 'Hipertensi',  'icon' => 'fa-heart-crack'],
                        'hipotensi'  => ['label' => 'Hipotensi',   'icon' => 'fa-heart-pulse'],
                        'diabetes'   => ['label' => 'Gula Darah tinggi',    'icon' => 'fa-droplet'],
                        'kolesterol' => ['label' => 'Kolesterol',  'icon' => 'fa-circle-dot'],
                        'obesitas'   => ['label' => 'Obesitas',    'icon' => 'fa-weight-scale'],
                        'bb_kurang'  => ['label' => 'BB Kurang',   'icon' => 'fa-person-thin'],
                    ];
                @endphp

                @foreach ($penyakitList as $key => $item)
                    <label style="display: flex; align-items: center; gap: 10px; padding: 10px 12px;
                                  border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer;
                                  font-size: 13px; transition: background 0.15s; user-select: none;">
                        <input type="radio"
                               class="filter-penyakit-radio"
                               name="filter_penyakit"
                               value="{{ $key }}"
                               style="accent-color: #3b82f6; width: 15px; height: 15px; flex-shrink: 0;">
                        {{ $item['label'] }}
                    </label>
                @endforeach

            </div>
        </div>

        {{-- ── Footer ──────────────────────────────────────────── --}}
        <div class="modal-footer" style="justify-content: space-between;">
            <button type="button" id="btn-reset-filter" class="btn-secondary">
                Reset
            </button>
            <button type="button" id="btn-terapkan-filter" class="btn-primary">
                Terapkan Filter
            </button>
        </div>

    </div>
</div>

{{-- ── Active state styling untuk risk tab ─────────────────── --}}
<style>
.filter-risk-tab.active[data-risk="semua"]   { background: #eff6ff; border-color: #93c5fd; color: #1d4ed8; font-weight: 600; }
.filter-risk-tab.active[data-risk="normal"]  { background: #ecfdf5; border-color: #6ee7b7; color: #065f46; font-weight: 600; }
.filter-risk-tab.active[data-risk="waspada"] { background: #fffbeb; border-color: #fcd34d; color: #92400e; font-weight: 600; }
.filter-risk-tab.active[data-risk="perlu"]   { background: #fef2f2; border-color: #fca5a5; color: #991b1b; font-weight: 600; }
.filter-risk-tab:hover:not(.active)          { background: #f8fafc; }

#filter-penyakit-block label:has(input:checked) {
    background: #eff6ff;
    border-color: #93c5fd;
    color: #1d4ed8;
    font-weight: 600;
}
#filter-penyakit-block label:has(input:checked) i {
    color: #3b82f6;
}
</style>