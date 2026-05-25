<?php

namespace App\Services;

use App\Models\Lansia;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TrenPenyakitService
{
    // ============================================================
    // EXISTING METHOD — tidak diubah
    // ============================================================
    public static function getTrend(): array
    {
        return Cache::remember('dash_tren_penyakit', 300, function () {
            $latestKunjungan = self::queryLatestKunjungan()->get()->keyBy('id_lansia');
            $latestUtama     = self::queryLatestUtama()->get()->keyBy('id_lansia');

            $counts = [
                'hipertensi' => 0,
                'hipotensi'  => 0,
                'diabetes'   => 0,
                'kolesterol' => 0,
                'obesitas'   => 0,
                'bb_kurang'  => 0,
            ];

            $allLansia = Lansia::select('id_lansia', 'jenis_kelamin')->get();

            foreach ($allLansia as $lansia) {
                $id = $lansia->id_lansia;
                $k  = $latestKunjungan[$id] ?? null;
                $u  = $latestUtama[$id] ?? null;

                $sistolik    = $k?->td_sistolik;
                $diastolik   = $k?->td_diastolik;
                $imt         = $k?->imt;
                $lingkarPerut = $k?->lingkar_perut;
                $gula        = $u?->gula_darah;
                $koles       = $u?->kolesterol;

                if (self::hasPositiveNumber($sistolik) && ($sistolik >= 140 || (self::hasPositiveNumber($diastolik) && $diastolik >= 90))) {
                    $counts['hipertensi']++;
                }
                if ((self::hasPositiveNumber($sistolik) && $sistolik < 90) || (self::hasPositiveNumber($diastolik) && $diastolik < 60)) {
                    $counts['hipotensi']++;
                }
                if (self::hasPositiveNumber($gula) && $gula >= 200) {
                    $counts['diabetes']++;
                }
                if (self::hasPositiveNumber($koles) && $koles >= 190) {
                    $counts['kolesterol']++;
                }
                $limitLP = strtoupper((string) $lansia->jenis_kelamin) === 'P' ? 80.0 : 90.0;
                if (self::hasPositiveNumber($lingkarPerut) && (float) $lingkarPerut > $limitLP) {
                    $counts['obesitas']++;
                }
                if (self::hasPositiveNumber($imt) && (float) $imt < 18.5) {
                    $counts['bb_kurang']++;
                }
            }

            arsort($counts);
            return $counts;
        });
    }

    // ============================================================
    // NEW METHOD — filter lansia untuk halaman index
    //
    // @param  string $filterRisk     semua|normal|waspada|perlu
    // @param  string $filterPenyakit hipertensi|hipotensi|diabetes|
    //                                kolesterol|obesitas|bb_kurang|''
    // @param  int    $page           halaman paginator
    // @param  int    $perPage        item per halaman
    // @param  string $requestUrl     untuk paginator link
    // @param  array  $requestQuery   query string tambahan
    //
    // @return array [
    //   'paginator'       => LengthAwarePaginator,
    //   'total_lansia'    => int,
    //   'kondisi_normal'  => int,
    //   'waspada'         => int,
    //   'perlu_perhatian' => int,
    // ]
    // ============================================================
    public static function getFilteredLansia(
        string $filterRisk     = 'semua',
        string $filterPenyakit = '',
        int    $page           = 1,
        int    $perPage        = 10,
        string $requestUrl     = '',
        array  $requestQuery   = []
    ): array {
        // ── 1. Ambil data medis terbaru satu query ──────────────
        $latestKunjungan = self::queryLatestKunjungan()->get()->keyBy('id_lansia');
        $latestUtama     = self::queryLatestUtama()->get()->keyBy('id_lansia');

        // ── 2. Ambil semua lansia ───────────────────────────────
        $allLansia = Lansia::with('latestSkriningUtama')
            ->latest()
            ->get();

        // ── 3. Enrichment: risiko + flag penyakit per lansia ───
        $allLansia = $allLansia->map(function ($lansia) use ($latestKunjungan, $latestUtama) {
            $id = $lansia->id_lansia;
            $k  = $latestKunjungan[$id] ?? null;
            $u  = $latestUtama[$id] ?? null;

            $sistolik    = $k?->td_sistolik;
            $diastolik   = $k?->td_diastolik;
            $imt         = $k?->imt;
            $lingkarPerut = $k?->lingkar_perut;
            $gula        = $u?->gula_darah;
            $koles       = $u?->kolesterol;
            $jk          = strtoupper((string) $lansia->jenis_kelamin);

            // Risiko keseluruhan via HealthRiskAssessor
            $riskStatus = HealthRiskAssessor::assess([
                'sistolik'      => $sistolik,
                'diastolik'     => $diastolik,
                'gula_darah'    => $gula,
                'kolesterol'    => $koles,
                'imt'           => $imt,
                'lingkar_perut' => $lingkarPerut,
                'jenis_kelamin' => $jk,
            ]);

            // risk_level untuk badge di tabel (mapping ke CSS class yg sudah ada)
            $lansia->risk_level = $riskStatus === HealthRiskAssessor::PERLU_TL
                ? 'tinggi'
                : $riskStatus;

            // Flag penyakit (threshold dari TrenPenyakitService)
            $limitLP = $jk === 'P' ? 80.0 : 90.0;
            $lansia->_risk_status  = $riskStatus; // internal, untuk filter risiko
            $lansia->_penyakit_flags = [
                'hipertensi' => self::hasPositiveNumber($sistolik) &&
                                ($sistolik >= 140 ||
                                    (self::hasPositiveNumber($diastolik) && $diastolik >= 90)),

                'hipotensi'  => (self::hasPositiveNumber($sistolik) && $sistolik < 90) ||
                                (self::hasPositiveNumber($diastolik) && $diastolik < 60),

                'diabetes'   => self::hasPositiveNumber($gula) && $gula >= 200,

                'kolesterol' => self::hasPositiveNumber($koles) && $koles >= 190,

                'obesitas'   => self::hasPositiveNumber($lingkarPerut) &&
                                (float) $lingkarPerut > $limitLP,

                'bb_kurang'  => self::hasPositiveNumber($imt) && (float) $imt < 18.5,
            ];

            return $lansia;
        });

        // ── 4. Stat cards — selalu dari semua lansia ───────────
        $total_lansia    = $allLansia->count();
        $kondisi_normal  = $allLansia->filter(fn($l) => $l->_risk_status === HealthRiskAssessor::NORMAL)->count();
        $waspada_count   = $allLansia->filter(fn($l) => $l->_risk_status === HealthRiskAssessor::WASPADA)->count();
        $perlu_perhatian = $allLansia->filter(fn($l) => $l->_risk_status === HealthRiskAssessor::PERLU_TL)->count();

        // ── 5. Filter risiko ────────────────────────────────────
        $filtered = match ($filterRisk) {
            'normal'  => $allLansia->filter(fn($l) => $l->_risk_status === HealthRiskAssessor::NORMAL),
            'waspada' => $allLansia->filter(fn($l) => $l->_risk_status === HealthRiskAssessor::WASPADA),
            'perlu'   => $allLansia->filter(fn($l) => $l->_risk_status === HealthRiskAssessor::PERLU_TL),
            default   => $allLansia,
        };

        // ── 6. Filter penyakit — hanya aktif saat waspada/perlu ─
        //    Karena threshold penyakit di TrenPenyakitService tidak
        //    membedakan waspada vs perlu_tl, filter ini hanya menyaring
        //    "lansia di level risiko yg dipilih YANG JUGA punya kondisi
        //    penyakit tersebut". Jadi kombinasinya tetap akurat.
        if (in_array($filterRisk, ['waspada', 'perlu']) && $filterPenyakit !== '') {
            $filtered = $filtered->filter(
                fn($l) => $l->_penyakit_flags[$filterPenyakit] ?? false
            );
        }

        // ── 7. Paginate dari Collection ─────────────────────────
        $paginator = new LengthAwarePaginator(
            $filtered->forPage($page, $perPage)->values(),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => $requestUrl, 'query' => $requestQuery]
        );

        return [
            'paginator'       => $paginator,
            'total_lansia'    => $total_lansia,
            'kondisi_normal'  => $kondisi_normal,
            'waspada'         => $waspada_count,
            'perlu_perhatian' => $perlu_perhatian,
        ];
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    /**
     * Query data kunjungan terbaru per lansia (reusable).
     */
    private static function queryLatestKunjungan()
    {
        return DB::table('skrining_kunjungan as sk')
            ->join('skrining as s', 's.id_skrining', '=', 'sk.id_skrining')
            ->select('s.id_lansia', 'sk.td_sistolik', 'sk.td_diastolik', 'sk.imt', 'sk.lingkar_perut')
            ->whereIn('s.id_skrining', function ($q) {
                $q->select(DB::raw('MAX(s2.id_skrining)'))
                    ->from('skrining as s2')
                    ->join('skrining_kunjungan as sk2', 'sk2.id_skrining', '=', 's2.id_skrining')
                    ->groupBy('s2.id_lansia');
            });
    }

    /**
     * Query data utama terbaru per lansia (reusable).
     */
    private static function queryLatestUtama()
    {
        return DB::table('skrining_utama as su')
            ->join('skrining as s', 's.id_skrining', '=', 'su.id_skrining')
            ->select('s.id_lansia', 'su.gula_darah', 'su.kolesterol')
            ->whereIn('s.id_skrining', function ($q) {
                $q->select(DB::raw('MAX(s2.id_skrining)'))
                    ->from('skrining as s2')
                    ->join('skrining_utama as su2', 'su2.id_skrining', '=', 's2.id_skrining')
                    ->groupBy('s2.id_lansia');
            });
    }

    private static function hasPositiveNumber($value): bool
    {
        return $value !== null && is_numeric($value) && (float) $value > 0;
    }
}