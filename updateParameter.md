Perubahan yang Perlu Dilakukan
Hanya 2 file PHP yang diubah, JS inject otomatis dari Blade.

1. HealthRiskAssessor.php — Tambah THRESHOLD, perbaiki sistolik & diastolik
php<?php

namespace App\Services;

class HealthRiskAssessor
{
    const NORMAL   = 'normal';
    const WASPADA  = 'waspada';
    const PERLU_TL = 'perlu_tindak_lanjut';

    // ── TAMBAHKAN KONSTANTA INI (BARU) ──────────────────────────
    // Single source of truth untuk semua threshold.
    // TrenPenyakitService dan JS (via Blade inject) wajib pakai ini.
    const THRESHOLD = [
        'sistolik' => [
            'bahaya_bawah'  => 90,   // hipotensi berat
            'waspada_bawah' => 100,  // hipotensi ringan
            'waspada_atas'  => 130,  // pre-hipertensi
            'bahaya_atas'   => 140,  // hipertensi
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
        // Threshold untuk flag penyakit di TrenPenyakitService
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

    public static function assess(array $data): ?string
    {
        // ... tidak berubah
    }

    // UBAH: sistolik — tambah batas bawah
    public static function sistolik(?int $v): ?string
    {
        if ($v === null || $v <= 0) return null;
        $t = self::THRESHOLD['sistolik'];
        if ($v < $t['bahaya_bawah'])  return self::PERLU_TL; // <90
        if ($v < $t['waspada_bawah']) return self::WASPADA;  // 90-99
        if ($v < $t['waspada_atas'])  return self::NORMAL;   // 100-129
        if ($v < $t['bahaya_atas'])   return self::WASPADA;  // 130-139
        return self::PERLU_TL;                                // ≥140
    }

    // UBAH: diastolik — tambah batas bawah
    public static function diastolik(?int $v): ?string
    {
        if ($v === null || $v <= 0) return null;
        $t = self::THRESHOLD['diastolik'];
        if ($v < $t['bahaya_bawah'])  return self::PERLU_TL; // <60
        if ($v < $t['waspada_bawah']) return self::WASPADA;  // 60-64
        if ($v < $t['waspada_atas'])  return self::NORMAL;   // 65-84
        if ($v < $t['bahaya_atas'])   return self::WASPADA;  // 85-89
        return self::PERLU_TL;                                // ≥90
    }

    // gulaDarah, kolesterol, imt, lingkarPerut — tidak berubah logikanya
    // ... sisanya sama seperti sebelumnya
}

2. TrenPenyakitService.php — Hapus angka hardcode, pakai THRESHOLD
Di getTrend() dan getFilteredLansia(), ada angka hardcode yang duplikat. Ganti semua kondisi penyakit:
php// SEBELUM (ada di getTrend DAN getFilteredLansia, dua tempat):
if (self::hasPositiveNumber($sistolik) && ($sistolik >= 140 || ...)) { ... }
if ((self::hasPositiveNumber($sistolik) && $sistolik < 90) || ...) { ... }

// SESUDAH — tambahkan di atas loop/map, ambil threshold sekali:
$th = HealthRiskAssessor::THRESHOLD;

// Hipertensi:
self::hasPositiveNumber($sistolik) &&
($sistolik >= $th['hipertensi']['sis_min'] ||
    (self::hasPositiveNumber($diastolik) && $diastolik >= $th['hipertensi']['dias_min']))

// Hipotensi:
(self::hasPositiveNumber($sistolik) && $sistolik < $th['hipotensi']['sis_max']) ||
(self::hasPositiveNumber($diastolik) && $diastolik < $th['hipotensi']['dias_max'])

// Diabetes:
self::hasPositiveNumber($gula) && $gula >= $th['diabetes']['min']

// Kolesterol:
self::hasPositiveNumber($koles) && $koles >= $th['kolesterol_flag']['min']

// BB Kurang:
self::hasPositiveNumber($imt) && (float)$imt < $th['bb_kurang']['max']

// Obesitas/lingkar perut — tidak berubah karena sudah pakai $limitLP dinamis

3. Blade monitoring — Inject threshold ke JS (tambah saja, tidak hapus)
Di file Blade halaman monitoring, tepat sebelum <script src="...monitoring.js">:
html<script>
    const HEALTH_THRESHOLDS = @json(\App\Services\HealthRiskAssessor::THRESHOLD);
</script>

4. monitoring.js — Pakai HEALTH_THRESHOLDS
Ganti buildTensiChart tooltip:
javascript// SEBELUM:
const st = isSis
    ? v >= 140 ? "🔴 Perlu Tindak Lanjut" : v >= 130 ? "⚠️ Waspada" : "✅ Normal"
    : v >= 90  ? "🔴 Perlu Tindak Lanjut" : v >= 85  ? "⚠️ Waspada" : "✅ Normal";

// SESUDAH:
const ts = HEALTH_THRESHOLDS.sistolik;
const td = HEALTH_THRESHOLDS.diastolik;
const st = isSis
    ? (v >= ts.bahaya_atas || v < ts.bahaya_bawah)   ? "🔴 Perlu Tindak Lanjut"
    : (v >= ts.waspada_atas || v < ts.waspada_bawah) ? "⚠️ Waspada"
    : "✅ Normal"
    : (v >= td.bahaya_atas || v < td.bahaya_bawah)   ? "🔴 Perlu Tindak Lanjut"
    : (v >= td.waspada_atas || v < td.waspada_bawah) ? "⚠️ Waspada"
    : "✅ Normal";
Ganti getTensiStatus di modal detail:
javascript// SEBELUM:
function getTensiStatus(sis, dias) {
    let sisStatus = "normal", diasStatus = "normal";
    if (sis >= 140) sisStatus = "bahaya";
    else if (sis >= 130) sisStatus = "waspada";
    if (dias >= 90) diasStatus = "bahaya";
    else if (dias >= 85) diasStatus = "waspada";
    return { sisStatus, diasStatus };
}

// SESUDAH:
function getTensiStatus(sis, dias) {
    const ts = HEALTH_THRESHOLDS.sistolik;
    const td = HEALTH_THRESHOLDS.diastolik;
    const sisStatus =
        (sis >= ts.bahaya_atas || sis < ts.bahaya_bawah)   ? "bahaya"
      : (sis >= ts.waspada_atas || sis < ts.waspada_bawah) ? "waspada"
      : "normal";
    const diasStatus =
        (dias >= td.bahaya_atas || dias < td.bahaya_bawah)   ? "bahaya"
      : (dias >= td.waspada_atas || dias < td.waspada_bawah) ? "waspada"
      : "normal";
    return { sisStatus, diasStatus };
}
Tambah refline bawah di buildTensiChart:
javascript// Tambahkan ke array datasets (yang lama jangan dihapus):
refLine(rows, "td_sistolik",  HEALTH_THRESHOLDS.sistolik.bahaya_bawah,  "#ef4444"),
refLine(rows, "td_diastolik", HEALTH_THRESHOLDS.diastolik.bahaya_bawah, "#ef4444"),

Prompt untuk AI Agent
Saya punya project Laravel dengan dua file service:
- App\Services\HealthRiskAssessor
- App\Services\TrenPenyakitService

Dan satu file JS: monitoring.js (diload di halaman Blade monitoring kesehatan lansia).

MASALAH:
Threshold angka (batas normal/waspada/bahaya tekanan darah, gula, kolesterol, dll)
diduplikasi di tiga tempat: HealthRiskAssessor.php, TrenPenyakitService.php, dan
monitoring.js. Akibatnya saat threshold diubah di satu tempat, dua tempat lain
tidak ikut berubah. Selain itu, tidak ada batas bawah (hipotensi) untuk tekanan
darah, sehingga sistolik 50 mmHg dianggap Normal padahal itu hipotensi berat.

YANG HARUS DILAKUKAN:

1. Di HealthRiskAssessor.php:
   - Tambahkan konstanta THRESHOLD (array) yang berisi semua nilai batas untuk
     sistolik, diastolik, gula_darah, kolesterol, imt, lingkar_perut, dan
     flag penyakit (hipertensi, hipotensi, diabetes, kolesterol_flag, bb_kurang).
   - Ubah method sistolik() agar pakai THRESHOLD dan tambahkan batas bawah:
     <90 = PERLU_TL, 90-99 = WASPADA, 100-129 = NORMAL, 130-139 = WASPADA, ≥140 = PERLU_TL.
   - Ubah method diastolik() agar pakai THRESHOLD dan tambahkan batas bawah:
     <60 = PERLU_TL, 60-64 = WASPADA, 65-84 = NORMAL, 85-89 = WASPADA, ≥90 = PERLU_TL.
   - Method lain (gulaDarah, kolesterol, imt, lingkarPerut) tidak perlu diubah logikanya,
     opsional refactor agar pakai THRESHOLD.

2. Di TrenPenyakitService.php:
   - Hapus semua angka hardcode untuk kondisi hipertensi, hipotensi, diabetes,
     kolesterol, bb_kurang.
   - Ganti dengan HealthRiskAssessor::THRESHOLD['hipertensi']['sis_min'] dst.
   - Perubahan ini berlaku di DUA tempat: method getTrend() dan getFilteredLansia().

3. Di file Blade halaman monitoring (resources/views/...monitoring.blade.php):
   - Tambahkan sebelum tag <script src="monitoring.js">:
     <script>
         const HEALTH_THRESHOLDS = @json(\App\Services\HealthRiskAssessor::THRESHOLD);
     </script>

4. Di monitoring.js:
   - Ganti logika status tooltip di buildTensiChart agar pakai HEALTH_THRESHOLDS
     dari window, dengan batas atas DAN batas bawah.
   - Ganti fungsi getTensiStatus() di modal detail agar pakai HEALTH_THRESHOLDS.
   - Tambahkan dua refLine bawah di buildTensiChart untuk garis bahaya hipotensi:
     refLine(rows, "td_sistolik",  HEALTH_THRESHOLDS.sistolik.bahaya_bawah,  "#ef4444")
     refLine(rows, "td_diastolik", HEALTH_THRESHOLDS.diastolik.bahaya_bawah, "#ef4444")

TUJUAN AKHIR:
Setelah perubahan ini, jika threshold diubah, cukup ubah di HealthRiskAssessor::THRESHOLD
saja — TrenPenyakitService dan monitoring.js otomatis ikut karena keduanya membaca
dari sumber yang sama.

Jangan ubah file selain yang disebutkan di atas.