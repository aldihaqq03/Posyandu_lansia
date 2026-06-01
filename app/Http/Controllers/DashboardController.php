<?php

namespace App\Http\Controllers;

use App\Models\JadwalPosyandu;
use App\Models\lansia;
use App\Models\Obat;
use App\Services\HealthRiskAssessor;
use App\Services\TrenPenyakitService;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // ── 1. STAT CARDS ──────────────────────────────────────────────
        $statData = Cache::remember('dash_stat_risiko', 300, function () {
            $allLansias = Lansia::with('latestSkriningUtama')->get();
            $total = $allLansias->count();
            $kondisi_normal = $waspada = $perlu_perhatian = 0;

            foreach ($allLansias as $l) {
                $kunj = $l->skrinings()
                    ->whereHas('kunjungan')
                    ->with('kunjungan:id_skrining_kunjungan,id_skrining,td_sistolik,td_diastolik,imt,lingkar_perut')
                    ->orderByDesc('tanggal_skrining')
                    ->first()?->kunjungan;
                $ut = $l->latestSkriningUtama;
                $status = HealthRiskAssessor::assess([
                    'sistolik' => $kunj?->td_sistolik,
                    'diastolik' => $kunj?->td_diastolik,
                    'gula_darah' => $ut?->gula_darah,
                    'kolesterol' => $ut?->kolesterol,
                    'imt' => $kunj?->imt,
                    'lingkar_perut' => $kunj?->lingkar_perut,
                    'jenis_kelamin' => $l->jenis_kelamin,
                ]);
                if ($status === HealthRiskAssessor::NORMAL) {
                    $kondisi_normal++;
                } elseif ($status === HealthRiskAssessor::WASPADA) {
                    $waspada++;
                } elseif ($status === HealthRiskAssessor::PERLU_TL) {
                    $perlu_perhatian++;
                }
            }

            return compact('total', 'kondisi_normal', 'waspada', 'perlu_perhatian');
        });

        $total_lansia = $statData['total'];
        $kondisi_normal = $statData['kondisi_normal'];
        $waspada = $statData['waspada'];
        $perlu_perhatian = $statData['perlu_perhatian'];

        // ── 2. TREN PENYAKIT (DIURUTKAN DARI TERBANYAK) ─────────────────
        $trenPenyakit = TrenPenyakitService::getTrend();

        // ── 3. JADWAL 30 HARI KE DEPAN ─────────────────────────────────
        $today = now('Asia/Jakarta')->format('Y-m-d');
        $thirtyDays = now('Asia/Jakarta')->addDays(30)->format('Y-m-d');
        $jadwalMendatang = JadwalPosyandu::with('detailSkrining')
            ->whereBetween('tanggal_pelaksanaan', [$today, $thirtyDays])
            ->whereIn('status', [JadwalPosyandu::STATUS_TERJADWAL, JadwalPosyandu::STATUS_BERLANGSUNG])
            ->orderBy('tanggal_pelaksanaan', 'asc')
            ->get();

        // ── 4. STOK OBAT MENIPIS ────────────────────────────────────────
        $obatMenipis = Obat::where('stock', '<', 10)->orderBy('stock', 'asc')->get();

        return view('admin.dashboard', compact(
            'total_lansia', 'kondisi_normal', 'waspada', 'perlu_perhatian',
            'trenPenyakit', 'jadwalMendatang', 'obatMenipis'
        ));
    }
}
