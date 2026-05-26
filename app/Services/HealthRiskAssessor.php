<?php

namespace App\Services;

class HealthRiskAssessor
{
    const NORMAL = 'normal';

    const WASPADA = 'waspada';

    const PERLU_TL = 'perlu_tindak_lanjut';

    // Single source of truth untuk semua threshold
    const THRESHOLD = [
        'sistolik' => [
            'bahaya_bawah'  => 90,
            'waspada_bawah' => 100,
            'waspada_atas'  => 130,
            'bahaya_atas'   => 140,
        ],
        'diastolik' => [
            'bahaya_bawah'  => 60,
            'waspada_bawah' => 65,
            'waspada_atas'  => 85,
            'bahaya_atas'   => 90,
        ],
        'gula_darah' => [
            'waspada_atas' => 145,
            'bahaya_atas'  => 200,
        ],
        'kolesterol' => [
            'waspada_atas' => 150,
            'bahaya_atas'  => 190,
        ],
        'imt' => [
            'bahaya_bawah'  => 18.5,
            'waspada_bawah' => 22.0,
            'waspada_atas'  => 27.0,
            'bahaya_atas'   => 30.0,
        ],
        'lingkar_perut' => [
            'limit_p' => 80.0,
            'limit_l' => 90.0,
        ],
        'hipertensi' => [
            'sis_min'  => 140,
            'dias_min' => 90,
        ],
        'hipotensi' => [
            'sis_max'  => 90,
            'dias_max' => 60,
        ],
        'diabetes'   => ['min' => 200],
        'kolesterol_flag' => ['min' => 190],
        'bb_kurang'  => ['max' => 18.5],
    ];
    /**
     * Tentukan status risiko keseluruhan.
     *
     * @param  array  $data  [
     *                       'sistolik'       => int|null,
     *                       'diastolik'      => int|null,
     *                       'gula_darah'     => int|null,
     *                       'kolesterol'     => int|null,
     *                       'imt'            => float|null,
     *                       'lingkar_perut'  => float|null,
     *                       'jenis_kelamin'  => 'L'|'P',   // untuk lingkar perut
     *                       ]
     */
    public static function assess(array $data): ?string
    {
        $statuses = [];

        $sistolik = self::sistolik($data['sistolik'] ?? null);
        if ($sistolik) {
            $statuses[] = $sistolik;
        }

        $diastolik = self::diastolik($data['diastolik'] ?? null);
        if ($diastolik) {
            $statuses[] = $diastolik;
        }

        $gula = self::gulaDarah($data['gula_darah'] ?? null);
        if ($gula) {
            $statuses[] = $gula;
        }

        $kolesterol = self::kolesterol($data['kolesterol'] ?? null);
        if ($kolesterol) {
            $statuses[] = $kolesterol;
        }

        $imt = self::imt($data['imt'] ?? null);
        if ($imt) {
            $statuses[] = $imt;
        }

        $lp = self::lingkarPerut($data['lingkar_perut'] ?? null, $data['jenis_kelamin'] ?? 'L');
        if ($lp) {
            $statuses[] = $lp;
        }

        // Jika semua parameter null/tidak terisi
        if (empty($statuses)) {
            return null;
        }

        // Terapkan prioritas: Perlu Tindak Lanjut > Waspada > Normal
        if (in_array(self::PERLU_TL, $statuses)) {
            return self::PERLU_TL;
        }
        if (in_array(self::WASPADA, $statuses)) {
            return self::WASPADA;
        }

        return self::NORMAL;
    }

    public static function sistolik(?int $v): ?string
    {
        if ($v === null || $v <= 0) {
            return null;
        }
        $t = self::THRESHOLD['sistolik'];
        if ($v < $t['bahaya_bawah'])  return self::PERLU_TL;
        if ($v < $t['waspada_bawah']) return self::WASPADA;
        if ($v < $t['waspada_atas'])  return self::NORMAL;
        if ($v < $t['bahaya_atas'])   return self::WASPADA;
        return self::PERLU_TL;
    }

    public static function diastolik(?int $v): ?string
    {
        if ($v === null || $v <= 0) {
            return null;
        }
        $t = self::THRESHOLD['diastolik'];
        if ($v < $t['bahaya_bawah'])  return self::PERLU_TL;
        if ($v < $t['waspada_bawah']) return self::WASPADA;
        if ($v < $t['waspada_atas'])  return self::NORMAL;
        if ($v < $t['bahaya_atas'])   return self::WASPADA;
        return self::PERLU_TL;
    }

    public static function gulaDarah(?int $v): ?string
    {
        if ($v === null || $v <= 0) {
            return null;
        }
        $t = self::THRESHOLD['gula_darah'];
        if ($v < $t['waspada_atas']) return self::NORMAL;
        if ($v < $t['bahaya_atas'])  return self::WASPADA;
        return self::PERLU_TL;
    }

    public static function kolesterol(?int $v): ?string
    {
        if ($v === null || $v <= 0) {
            return null;
        }
        $t = self::THRESHOLD['kolesterol'];
        if ($v < $t['waspada_atas']) return self::NORMAL;
        if ($v < $t['bahaya_atas'])  return self::WASPADA;
        return self::PERLU_TL;
    }

    public static function imt(?float $v): ?string
    {
        if ($v === null || $v <= 0) {
            return null;
        }
        $t = self::THRESHOLD['imt'];
        if ($v >= $t['waspada_bawah'] && $v <= $t['waspada_atas']) {
            return self::NORMAL;
        }
        if (($v >= $t['bahaya_bawah'] && $v < $t['waspada_bawah']) || ($v > $t['waspada_atas'] && $v < $t['bahaya_atas'])) {
            return self::WASPADA;
        }

        return self::PERLU_TL;
    }

    public static function lingkarPerut(?float $v, string $jenisKelamin = 'L'): ?string
    {
        if ($v === null || $v <= 0) {
            return null;
        }
        $t = self::THRESHOLD['lingkar_perut'];
        $limit = (strtoupper($jenisKelamin) === 'P') ? $t['limit_p'] : $t['limit_l'];

        return ($v < $limit) ? self::NORMAL : self::PERLU_TL;
    }

    /**
     * Return array detail per parameter — berguna untuk tampilan badge.
     * Format: [ 'label' => string, 'status' => string ]
     */
    public static function detail(array $data): array
    {
        return [
            'sistolik' => [
                'label' => self::label(self::sistolik($data['sistolik'] ?? null)),
                'status' => self::sistolik($data['sistolik'] ?? null),
            ],
            'diastolik' => [
                'label' => self::label(self::diastolik($data['diastolik'] ?? null)),
                'status' => self::diastolik($data['diastolik'] ?? null),
            ],
            'gula_darah' => [
                'label' => self::label(self::gulaDarah($data['gula_darah'] ?? null)),
                'status' => self::gulaDarah($data['gula_darah'] ?? null),
            ],
            'kolesterol' => [
                'label' => self::label(self::kolesterol($data['kolesterol'] ?? null)),
                'status' => self::kolesterol($data['kolesterol'] ?? null),
            ],
            'imt' => [
                'label' => self::label(self::imt($data['imt'] ?? null)),
                'status' => self::imt($data['imt'] ?? null),
            ],
            'lingkar_perut' => [
                'label' => self::label(self::lingkarPerut($data['lingkar_perut'] ?? null, $data['jenis_kelamin'] ?? 'L')),
                'status' => self::lingkarPerut($data['lingkar_perut'] ?? null, $data['jenis_kelamin'] ?? 'L'),
            ],
        ];
    }

    /**
     * Label teks untuk status.
     */
    public static function label(?string $status): string
    {
        if ($status === null) {
            return '-';
        }

        return match ($status) {
            self::PERLU_TL => 'Perlu Tindak Lanjut',
            self::WASPADA => 'Waspada',
            default => 'Normal',
        };
    }

    /**
     * Warna badge Tailwind untuk status.
     */
    public static function badgeClass(?string $status): string
    {
        if ($status === null) {
            return '';
        }

        return match ($status) {
            self::PERLU_TL => 'bg-red-100 text-red-700',
            self::WASPADA => 'bg-orange-100 text-orange-700',
            default => 'bg-green-100 text-green-700',
        };
    }
}
