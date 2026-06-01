{{-- resources/views/components/parameter-modal.blade.php --}}
@php
    use App\Services\HealthRiskAssessor;
    $threshold = HealthRiskAssessor::THRESHOLD;

    // Fungsi helper untuk menghasilkan teks rentang berdasarkan threshold dan logika service
    function formatRangeSistolik() {
        $t = \App\Services\HealthRiskAssessor::THRESHOLD['sistolik'];
        $normal = $t['waspada_bawah'] . '–' . ($t['waspada_atas']-1);
        $waspada = $t['bahaya_bawah'] . '–' . ($t['waspada_bawah']-1) . ' atau ' . $t['waspada_atas'] . '–' . ($t['bahaya_atas']-1);
        $tl = '< ' . $t['bahaya_bawah'] . ' atau ≥ ' . $t['bahaya_atas'];
        return ['normal'=>$normal, 'waspada'=>$waspada, 'perlu_tindak_lanjut'=>$tl];
    }
    function formatRangeDiastolik() {
        $t = \App\Services\HealthRiskAssessor::THRESHOLD['diastolik'];
        $normal = $t['waspada_bawah'] . '–' . ($t['waspada_atas']-1);
        $waspada = $t['bahaya_bawah'] . '–' . ($t['waspada_bawah']-1) . ' atau ' . $t['waspada_atas'] . '–' . ($t['bahaya_atas']-1);
        $tl = '< ' . $t['bahaya_bawah'] . ' atau ≥ ' . $t['bahaya_atas'];
        return ['normal'=>$normal, 'waspada'=>$waspada, 'perlu_tindak_lanjut'=>$tl];
    }
    function formatRangeGula() {
        $t = \App\Services\HealthRiskAssessor::THRESHOLD['gula_darah'];
        return ['normal'=>'< '.$t['waspada_atas'], 'waspada'=>$t['waspada_atas'].'–'.($t['bahaya_atas']-1), 'perlu_tindak_lanjut'=>'≥ '.$t['bahaya_atas']];
    }
    function formatRangeKolesterol() {
        $t = \App\Services\HealthRiskAssessor::THRESHOLD['kolesterol'];
        return ['normal'=>'< '.$t['waspada_atas'], 'waspada'=>$t['waspada_atas'].'–'.($t['bahaya_atas']-1), 'perlu_tindak_lanjut'=>'≥ '.$t['bahaya_atas']];
    }
    function formatRangeIMT() {
        $t = \App\Services\HealthRiskAssessor::THRESHOLD['imt'];
        $normal = $t['waspada_bawah'].' – '.$t['waspada_atas'];
        $waspada = $t['bahaya_bawah'].' – '.($t['waspada_bawah']-0.1).' atau '.($t['waspada_atas']+0.1).' – '.($t['bahaya_atas']-0.1);
        $tl = '< '.$t['bahaya_bawah'].' atau ≥ '.$t['bahaya_atas'];
        return ['normal'=>$normal, 'waspada'=>$waspada, 'perlu_tindak_lanjut'=>$tl];
    }
    $lpLimit = $threshold['lingkar_perut'];
@endphp

<style>
    /* Parameter modal styles */
    .param-modal-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,.45);
        backdrop-filter: blur(4px);
        display: flex; align-items: center; justify-content: center;
        z-index: 10000;
        opacity: 0; pointer-events: none;
        transition: opacity .25s ease;
    }
    .param-modal-overlay.active { opacity: 1; pointer-events: auto; }
    .param-modal-card {
        background: #fff;
        border-radius: 20px;
        width: 90%;
        max-width: 720px;
        max-height: 88vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 24px 60px rgba(0,0,0,.18);
        transform: translateY(24px) scale(.97);
        transition: transform .3s cubic-bezier(.175,.885,.32,1.275);
        overflow: hidden;
    }
    .param-modal-overlay.active .param-modal-card { transform: translateY(0) scale(1); }
    .param-modal-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
        flex-shrink: 0;
    }
    .param-modal-title-wrap {
        display: flex; align-items: center; gap: 14px;
    }
    .param-modal-icon-wrap {
        width: 44px; height: 44px;
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 18px;
    }
    .param-modal-header h3 { font-size: 17px; font-weight: 700; color: #0f172a; margin: 0 0 2px; }
    .param-modal-header p { font-size: 12px; color: #64748b; margin: 0; }
    .param-modal-close {
        width: 36px; height: 36px; border-radius: 10px;
        border: none; background: #f1f5f9; color: #64748b;
        cursor: pointer; font-size: 15px;
        display: flex; align-items: center; justify-content: center;
    }
    .param-modal-close:hover { background: #e2e8f0; color: #0f172a; }
    .param-modal-body {
        overflow-y: auto;
        padding: 20px 24px 24px;
        display: flex; flex-direction: column; gap: 20px;
    }
    .param-section-title {
        font-size: 13px; font-weight: 700; color: #1e40af;
        margin-bottom: 10px;
        display: flex; align-items: center; gap: 7px;
    }
    .param-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }
    .param-table thead tr { background: #f8fafc; }
    .param-table th { padding: 10px 14px; text-align: left; font-weight: 600; font-size: 12px; color: #475569; border-bottom: 1px solid #e2e8f0; }
    .param-table td { padding: 10px 14px; color: #334155; border-bottom: 1px solid #f1f5f9; }
    .param-table tbody tr:hover { background: #f8fafc; }
    .param-table th.badge-normal { color: #059669; }
    .param-table th.badge-waspada { color: #d97706; }
    .param-table th.badge-bahaya { color: #dc2626; }
</style>

<div class="param-modal-overlay" id="paramModal" onclick="closeParamIfOutside(event)">
    <div class="param-modal-card">
        <div class="param-modal-header">
            <div class="param-modal-title-wrap">
                <div class="param-modal-icon-wrap"><i class="fa-solid fa-heart-pulse"></i></div>
                <div><h3>Parameter Kesehatan</h3><p>Acuan nilai normal, waspada, dan perlu tindak lanjut</p></div>
            </div>
            <button class="param-modal-close" onclick="closeParamModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div class="param-modal-body">
            {{-- TEKANAN DARAH (sistolik & diastolik) --}}
            @php $sistolikRange = formatRangeSistolik(); $diastolikRange = formatRangeDiastolik(); @endphp
            <div class="param-section">
                <div class="param-section-title"><i class="fa-solid fa-droplet"></i> Tekanan Darah</div>
                <table class="param-table">
                    <thead><tr><th>Parameter</th><th class="badge-normal">Normal</th><th class="badge-waspada">Waspada</th><th class="badge-bahaya">Perlu Tindak Lanjut</th></tr></thead>
                    <tbody>
                        <tr><td>Sistolik (mmHg)</td><td>{{ $sistolikRange['normal'] }}</td><td>{{ $sistolikRange['waspada'] }}</td><td>{{ $sistolikRange['perlu_tindak_lanjut'] }}</td></tr>
                        <tr><td>Diastolik (mmHg)</td><td>{{ $diastolikRange['normal'] }}</td><td>{{ $diastolikRange['waspada'] }}</td><td>{{ $diastolikRange['perlu_tindak_lanjut'] }}</td></tr>
                    </tbody>
                </table>
            </div>

            {{-- GULA DARAH --}}
            @php $gulaRange = formatRangeGula(); @endphp
            <div class="param-section">
                <div class="param-section-title"><i class="fa-solid fa-vial"></i> Gula Darah</div>
                <table class="param-table">
                    <thead><tr><th>Parameter</th><th class="badge-normal">Normal</th><th class="badge-waspada">Waspada</th><th class="badge-bahaya">Perlu Tindak Lanjut</th></tr></thead>
                    <tbody><tr><td>Gula Darah (mg/dL)</td><td>{{ $gulaRange['normal'] }}</td><td>{{ $gulaRange['waspada'] }}</td><td>{{ $gulaRange['perlu_tindak_lanjut'] }}</td></tr></tbody>
                </table>
            </div>

            {{-- KOLESTEROL --}}
            @php $kolesterolRange = formatRangeKolesterol(); @endphp
            <div class="param-section">
                <div class="param-section-title"><i class="fa-solid fa-circle-dot"></i> Kolesterol</div>
                <table class="param-table">
                    <thead><tr><th>Parameter</th><th class="badge-normal">Normal</th><th class="badge-waspada">Waspada</th><th class="badge-bahaya">Perlu Tindak Lanjut</th></tr></thead>
                    <tbody><tr><td>Kolesterol (mg/dL)</td><td>{{ $kolesterolRange['normal'] }}</td><td>{{ $kolesterolRange['waspada'] }}</td><td>{{ $kolesterolRange['perlu_tindak_lanjut'] }}</td></tr></tbody>
                </table>
            </div>

            {{-- IMT --}}
            @php $imtRange = formatRangeIMT(); @endphp
            <div class="param-section">
                <div class="param-section-title"><i class="fa-solid fa-weight-scale"></i> Indeks Massa Tubuh (IMT)</div>
                <table class="param-table">
                    <thead><tr><th>Parameter</th><th class="badge-normal">Normal</th><th class="badge-waspada">Waspada</th><th class="badge-bahaya">Perlu Tindak Lanjut</th></tr></thead>
                    <tbody><tr><td>IMT (kg/m²)</td><td>{{ $imtRange['normal'] }}</td><td>{{ $imtRange['waspada'] }}</td><td>{{ $imtRange['perlu_tindak_lanjut'] }}</td></tr></tbody>
                </table>
            </div>

            {{-- LINGKAR PERUT --}}
            <div class="param-section">
                <div class="param-section-title"><i class="fa-solid fa-ruler-horizontal"></i> Lingkar Perut</div>
                <table class="param-table">
                    <thead><tr><th>Jenis Kelamin</th><th class="badge-normal">Normal</th><th class="badge-bahaya" colspan="2">Perlu Tindak Lanjut</th></tr></thead>
                    <tbody>
                        <tr><td>Laki-laki</td><td>&lt; {{ $lpLimit['limit_l'] }} cm</td><td colspan="2">≥ {{ $lpLimit['limit_l'] }} cm</td></tr>
                        <tr><td>Perempuan</td><td>&lt; {{ $lpLimit['limit_p'] }} cm</td><td colspan="2">≥ {{ $lpLimit['limit_p'] }} cm</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // pastikan fungsi global tidak ditimpa
    window.openParamModal = window.openParamModal || function() { document.getElementById('paramModal')?.classList.add('active'); };
    window.closeParamModal = window.closeParamModal || function() { document.getElementById('paramModal')?.classList.remove('active'); };
    window.closeParamIfOutside = window.closeParamIfOutside || function(e) { if(e.target === document.getElementById('paramModal')) window.closeParamModal(); };
</script>