<?php

namespace App\Http\Controllers;

use App\Models\JadwalPosyandu;
use App\Models\Lansia;
use App\Models\Obat;
use App\Services\HealthRiskAssessor;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
        $trenPenyakit = Cache::remember('dash_tren_penyakit', 300, function () {
            $latestKunjungan = DB::table('skrining_kunjungan as sk')
                ->join('skrining as s', 's.id_skrining', '=', 'sk.id_skrining')
                ->select('s.id_lansia', 'sk.td_sistolik', 'sk.td_diastolik', 'sk.imt')
                ->whereIn('s.id_skrining', function ($q) {
                    $q->select(DB::raw('MAX(s2.id_skrining)'))
                        ->from('skrining as s2')
                        ->join('skrining_kunjungan as sk2', 'sk2.id_skrining', '=', 's2.id_skrining')
                        ->groupBy('s2.id_lansia');
                })->get()->keyBy('id_lansia');

            $latestUtama = DB::table('skrining_utama as su')
                ->join('skrining as s', 's.id_skrining', '=', 'su.id_skrining')
                ->select('s.id_lansia', 'su.gula_darah', 'su.kolesterol')
                ->whereIn('s.id_skrining', function ($q) {
                    $q->select(DB::raw('MAX(s2.id_skrining)'))
                        ->from('skrining as s2')
                        ->join('skrining_utama as su2', 'su2.id_skrining', '=', 's2.id_skrining')
                        ->groupBy('s2.id_lansia');
                })->get()->keyBy('id_lansia');

            $counts = ['hipertensi' => 0, 'hipotensi' => 0, 'diabetes' => 0, 'kolesterol' => 0, 'obesitas' => 0, 'bb_kurang' => 0];

            foreach (Lansia::pluck('id_lansia') as $id) {
                $k = $latestKunjungan[$id] ?? null;
                $u = $latestUtama[$id] ?? null;
                $sistolik = $k?->td_sistolik;
                $diastolik = $k?->td_diastolik;
                $imt = $k?->imt;
                $gula = $u?->gula_darah;
                $koles = $u?->kolesterol;

                if ($sistolik && ($sistolik >= 140 || ($diastolik && $diastolik >= 90))) {
                    $counts['hipertensi']++;
                }
                if ($sistolik && $sistolik < 90) {
                    $counts['hipotensi']++;
                }
                if ($gula && $gula >= 200) {
                    $counts['diabetes']++;
                }
                if ($koles && $koles >= 190) {
                    $counts['kolesterol']++;
                }
                if ($imt && $imt >= 30) {
                    $counts['obesitas']++;
                }
                if ($imt && $imt < 18.5) {
                    $counts['bb_kurang']++;
                }
            }

            // Urutkan nilai array dari terbesar ke terkecil secara langsung di PHP
            arsort($counts);

            return $counts;
        });

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
