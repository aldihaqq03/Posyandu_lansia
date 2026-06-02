<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;


trait HasRandomId
{
   
    protected static function bootHasRandomId(): void
    {
        static::creating(function ($model) {
            $pk = $model->getKeyName();

          
            if (empty($model->$pk)) {
                $model->$pk = static::generateUniqueRandomId(
                    $model->getTable(),
                    $pk
                );
            }
        });
    }

    protected static function generateUniqueRandomId(string $table, string $pk): int
    {
        do {
            $id = random_int(1000, 9999);
        } while (DB::table($table)->where($pk, $id)->exists());

        return $id;
    }
}
