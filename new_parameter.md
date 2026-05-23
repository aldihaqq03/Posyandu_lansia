# HealthRiskAssessor — Dokumentasi & Panduan Implementasi

## Tujuan

Buatkan sebuah class PHP `App\Services\HealthRiskAssessor` yang bertugas
menentukan **status risiko kesehatan lansia** berdasarkan parameter
pemeriksaan fisik dan biokimia.

Class ini **berbeda** dari `App\Helpers\SkriningHelper` yang sudah ada.
`SkriningHelper` hanya mendecode nilai DB menjadi teks label untuk tampilan.
`HealthRiskAssessor` bertugas menghitung dan menentukan status risiko.

---

## Relasi dengan SkriningHelper (PENTING — baca dulu)

File `App\Helpers\SkriningHelper` sudah ada di project dan JANGAN diubah.
Perhatikan bahwa di `SkriningHelper` sudah ada:

- Kategori gula darah: `1=Baik, 2=Sedang, 3=Tidak Baik` — ini label tampilan saja
- Kategori kolesterol: `1=Baik, 2=Sedang, 3=Tidak Baik` — ini label tampilan saja
- Kategori IMT: `Sangat Kurus/Kurus/Normal/Overweight/Obesitas` — ini label tampilan saja
- SRQ total ≥6 = indikasi gangguan jiwa — ini milik SkriningHelper, tidak masuk HealthRiskAssessor
- PUMA total ≥6 = Risiko PPOK — ini milik SkriningHelper, tidak masuk HealthRiskAssessor

`HealthRiskAssessor` hanya mengurusi 6 parameter di bawah ini,
tidak ada tumpang tindih dengan SkriningHelper.

---

## Parameter & Standar

### 1. Tekanan Darah Sistolik

| Status   | Nilai        |
| -------- | ------------ |
| Normal   | < 130 mmHg   |
| Waspada  | 130–139 mmHg |
| Perlu TL | ≥ 140 mmHg   |

### 2. Tekanan Darah Diastolik

| Status   | Nilai      |
| -------- | ---------- |
| Normal   | < 85 mmHg  |
| Waspada  | 85–89 mmHg |
| Perlu TL | ≥ 90 mmHg  |

### 3. Gula Darah Sewaktu

| Status   | Nilai         |
| -------- | ------------- |
| Normal   | 80–144 mg/dL  |
| Waspada  | 145–199 mg/dL |
| Perlu TL | ≥ 200 mg/dL   |

> Catatan: Kartu Skrining Posbindu PTM menggunakan gula darah **sewaktu**,

> `B=80-144, S=145-199, TB=≥200`.

### 4. Kolesterol Total

| Status   | Nilai         |
| -------- | ------------- |
| Normal   | < 150 mg/dL   |
| Waspada  | 150–189 mg/dL |
| Perlu TL | ≥ 190 mg/dL   |

> Catatan: Standar ini sesuai komentar kolom DB:
> `B=<150, S=150-189, TB=≥190`.

### 5. IMT (Indeks Massa Tubuh)

gunakan batas yang sudah ada itu udah benar 

### 6. Lingkar Perut

| Status   | Laki-laki | Perempuan |
| -------- | --------- | --------- |
| Normal   | < 90 cm   | < 80 cm   |
| Perlu TL | ≥ 90 cm   | ≥ 80 cm   |

> Catatan: Lingkar perut **tidak memiliki kategori Waspada**.
> Langsung Normal atau Perlu TL.
> Standar WHO Asia Pasifik.

---

## Rule Penentuan Status Akhir

```
Perlu Tindak Lanjut → jika minimal 1 parameter berstatus Perlu TL
Waspada             → jika minimal 1 parameter berstatus Waspada,
                      DAN tidak ada parameter berstatus Perlu TL
Normal              → jika semua parameter berstatus Normal
```

Prioritas: `Perlu Tindak Lanjut > Waspada > Normal`

---

## Konstanta Status

```php
const NORMAL   = 'normal';
const WASPADA  = 'waspada';
const PERLU_TL = 'perlu_tindak_lanjut';
```

---

## Struktur Class yang Diharapkan

```php
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
    public static function assess(array $data): string
    {
        // kumpulkan semua status per parameter
        // terapkan rule prioritas
        // return salah satu konstanta di atas
    }

    // Method individual per parameter:
    public static function sistolik(?int $v): string {}
    public static function diastolik(?int $v): string {}
    public static function gulaDarah(?int $v): string {}
    public static function kolesterol(?int $v): string {}
    public static function imt(?float $v): string {}
    public static function lingkarPerut(?float $v, string $jenisKelamin = 'L'): string {}

    /**
     * Return array detail per parameter — berguna untuk tampilan badge.
     * Format: [ 'label' => string, 'status' => string ]
     */
    public static function detail(array $data): array {}

    /**
     * Label teks untuk status.
     */
    public static function label(string $status): string
    {
        return match($status) {
            self::PERLU_TL => 'Perlu Tindak Lanjut',
            self::WASPADA  => 'Waspada',
            default        => 'Normal',
        };
    }

    /**
     * Warna badge Tailwind untuk status.
     */
    public static function badgeClass(string $status): string
    {
        return match($status) {
            self::PERLU_TL => 'bg-red-100 text-red-700',
            self::WASPADA  => 'bg-orange-100 text-orange-700',
            default        => 'bg-green-100 text-green-700',
        };
    }
}
```

---

## Cara Penggunaan di Controller / Blade

```php
// Di Controller
use App\Services\HealthRiskAssessor;

$status = HealthRiskAssessor::assess([
    'sistolik'      => $skrining->td_sistolik,
    'diastolik'     => $skrining->td_diastolik,
    'gula_darah'    => $skrining->gula_darah,
    'kolesterol'    => $skrining->kolesterol,
    'imt'           => $skrining->imt,
    'lingkar_perut' => $skrining->lingkar_perut,
    'jenis_kelamin' => $lansia->jenis_kelamin, // 'L' atau 'P'
]);

$label  = HealthRiskAssessor::label($status);
$badge  = HealthRiskAssessor::badgeClass($status);
$detail = HealthRiskAssessor::detail([...]); // untuk tooltip/breakdown
```

```blade
{{-- Di Blade — badge status risiko --}}
<span class="px-2 py-1 rounded-full text-xs font-semibold {{ $badge }}">
    {{ $label }}
</span>
```

---

## Catatan Tambahan

- Jika sebuah parameter `null` (tidak diisi/tidak diukur), **skip** parameter
  tersebut dari perhitungan — jangan dianggap Normal atau Perlu TL.
- Jika **semua parameter null**, kembalikan `null` atau status khusus
  `'belum_diukur'` — sesuaikan dengan kebutuhan tampilan.
- Field DB yang relevan dari tabel `skrining_utama`:
  `td_sistolik`, `td_diastolik`, `gula_darah`, `kolesterol`,
  `imt`, `lingkar_perut`
- Field `jenis_kelamin` diambil dari relasi model `Lansia`,
  bukan dari tabel skrining.
