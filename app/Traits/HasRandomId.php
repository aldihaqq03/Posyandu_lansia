<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

/**
 * HasRandomId
 *
 * Gunakan trait ini pada model yang primary key-nya
 * ingin di-generate secara random (bukan auto-increment 1, 2, 3...).
 *
 * Format: integer 3 digit random, misal: 127, 045, 893
 * Range : 100 – 999 (pastikan jumlah record per tabel < 900)
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
     * Matikan auto-increment bawaan Laravel/MySQL di level model,
     * sehingga kita yang menentukan nilainya sendiri.
     */
    public $incrementing = false;

    /**
     * Boot trait: daftarkan listener `creating` secara otomatis.
     * Laravel memanggil bootNamaTraitnya() secara konvensi.
     */
    protected static function bootHasRandomId(): void
    {
        static::creating(function ($model) {
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
     * Generate integer random 3 digit yang belum ada di tabel ini.
     * Menggunakan DB::table agar bekerja di semua model
     * (termasuk yang pakai SoftDeletes maupun tidak).
     */
    protected static function generateUniqueRandomId(string $table, string $pk): int
    {
        do {
            $id = random_int(100, 999);
        } while (DB::table($table)->where($pk, $id)->exists());

        return $id;
    }
}
