<?php

namespace App\Services;

class HealthRiskAssessor
{
    const NORMAL   = 'normal';
    const WASPADA  = 'waspada';
    const PERLU_TL = 'perlu_tindak_lanjut';

    /**
     * Tentukan status risiko keseluruhan.
     *
     * @param array $data [
     *   'sistolik'       => int|null,
     *   'diastolik'      => int|null,
     *   'gula_darah'     => int|null,
     *   'kolesterol'     => int|null,
     *   'imt'            => float|null,
     *   'lingkar_perut'  => float|null,
     *   'jenis_kelamin'  => 'L'|'P',   // untuk lingkar perut
     * ]
     */
    public static function assess(array $data): ?string
    {
        $statuses = [];

        $sistolik = self::sistolik($data['sistolik'] ?? null);
        if ($sistolik) $statuses[] = $sistolik;

        $diastolik = self::diastolik($data['diastolik'] ?? null);
        if ($diastolik) $statuses[] = $diastolik;

        $gula = self::gulaDarah($data['gula_darah'] ?? null);
        if ($gula) $statuses[] = $gula;

        $kolesterol = self::kolesterol($data['kolesterol'] ?? null);
        if ($kolesterol) $statuses[] = $kolesterol;

        $imt = self::imt($data['imt'] ?? null);
        if ($imt) $statuses[] = $imt;

        $lp = self::lingkarPerut($data['lingkar_perut'] ?? null, $data['jenis_kelamin'] ?? 'L');
        if ($lp) $statuses[] = $lp;

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
        if ($v === null || $v <= 0) return null;
        if ($v < 130) return self::NORMAL;
        if ($v <= 139) return self::WASPADA;
        return self::PERLU_TL;
    }

    public static function diastolik(?int $v): ?string
    {
        if ($v === null || $v <= 0) return null;
        if ($v < 85) return self::NORMAL;
        if ($v <= 89) return self::WASPADA;
        return self::PERLU_TL;
    }

    public static function gulaDarah(?int $v): ?string
    {
        if ($v === null || $v <= 0) return null;
        if ($v < 145) return self::NORMAL;
        if ($v <= 199) return self::WASPADA;
        return self::PERLU_TL;
    }

    public static function kolesterol(?int $v): ?string
    {
        if ($v === null || $v <= 0) return null;
        if ($v < 150) return self::NORMAL;
        if ($v <= 189) return self::WASPADA;
        return self::PERLU_TL;
    }

    public static function imt(?float $v): ?string
    {
        if ($v === null || $v <= 0) return null;
        if ($v >= 22.0 && $v <= 27.0) return self::NORMAL;
        if (($v >= 18.5 && $v < 22.0) || ($v > 27.0 && $v < 30.0)) return self::WASPADA;
        return self::PERLU_TL;
    }

    public static function lingkarPerut(?float $v, string $jenisKelamin = 'L'): ?string
    {
        if ($v === null || $v <= 0) return null;
        $limit = (strtoupper($jenisKelamin) === 'P') ? 80.0 : 90.0;
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
                'label'  => self::label(self::sistolik($data['sistolik'] ?? null)),
                'status' => self::sistolik($data['sistolik'] ?? null),
            ],
            'diastolik' => [
                'label'  => self::label(self::diastolik($data['diastolik'] ?? null)),
                'status' => self::diastolik($data['diastolik'] ?? null),
            ],
            'gula_darah' => [
                'label'  => self::label(self::gulaDarah($data['gula_darah'] ?? null)),
                'status' => self::gulaDarah($data['gula_darah'] ?? null),
            ],
            'kolesterol' => [
                'label'  => self::label(self::kolesterol($data['kolesterol'] ?? null)),
                'status' => self::kolesterol($data['kolesterol'] ?? null),
            ],
            'imt' => [
                'label'  => self::label(self::imt($data['imt'] ?? null)),
                'status' => self::imt($data['imt'] ?? null),
            ],
            'lingkar_perut' => [
                'label'  => self::label(self::lingkarPerut($data['lingkar_perut'] ?? null, $data['jenis_kelamin'] ?? 'L')),
                'status' => self::lingkarPerut($data['lingkar_perut'] ?? null, $data['jenis_kelamin'] ?? 'L'),
            ],
        ];
    }

    /**
     * Label teks untuk status.
     */
    public static function label(?string $status): string
    {
        if ($status === null) return '-';
        return match($status) {
            self::PERLU_TL => 'Perlu Tindak Lanjut',
            self::WASPADA  => 'Waspada',
            default        => 'Normal',
        };
    }

    /**
     * Warna badge Tailwind untuk status.
     */
    public static function badgeClass(?string $status): string
    {
        if ($status === null) return '';
        return match($status) {
            self::PERLU_TL => 'bg-red-100 text-red-700',
            self::WASPADA  => 'bg-orange-100 text-orange-700',
            default        => 'bg-green-100 text-green-700',
        };
    }
}
