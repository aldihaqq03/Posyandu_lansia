<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

/**
 * HasRandomId
 *
 * Gunakan trait ini pada model yang primary key-nya
 * ingin di-generate secara random (bukan auto-increment 1, 2, 3...).
 *
 * Format: integer 4 digit random, misal: 127, 045, 893
 * Range : 1000 – 9999 (pastikan jumlah record per tabel < 9000)
 *
 * Cara pakai:
 *   use App\Traits\HasRandomId;
 *   class MyModel extends Model {
 *       use HasRandomId;
 *       // $primaryKey tetap ditulis seperti biasa
 *   }
 */
trait HasRandomId
{
    /**
     * Non-aktifkan auto-increment Eloquent sehingga trait yang
     * mengatur primary key. Model tidak perlu lagi menetapkan
     * `$incrementing = false` secara manual.
     */
    public $incrementing = false;
    /**
     * Matikan auto-increment bawaan Laravel/MySQL di level model,
     * sehingga kita yang menentukan nilainya sendiri.
     */

    /**
     * Boot trait: daftarkan listener `creating` secara otomatis.
     * Laravel memanggil bootNamaTraitnya() secara konvensi.
     */
    protected static function bootHasRandomId(): void
    {
        static::creating(function ($model) {
            $model->incrementing = false;
            $pk = $model->getKeyName();

            // Hanya generate jika ID belum diisi secara eksplisit
            if (empty($model->$pk)) {
                $model->$pk = static::generateUniqueRandomId(
                    $model->getTable(),
                    $pk
                );
            }
        });
    }

    /**
     * Generate integer random 4 digit yang belum ada di tabel ini.
     * Menggunakan DB::table agar bekerja di semua model
     * (termasuk yang pakai SoftDeletes maupun tidak).
     */
    protected static function generateUniqueRandomId(string $table, string $pk): int
    {
        do {
            $id = random_int(1000, 9999);
        } while (DB::table($table)->where($pk, $id)->exists());

        return $id;
    }
}
