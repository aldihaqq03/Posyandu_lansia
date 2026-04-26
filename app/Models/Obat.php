<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Obat extends Model
{
    use SoftDeletes;

    protected $table = 'obat';
    protected $primaryKey = 'id_obat';
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'id_obat',
        'nama_obat',
        'tipe_obat',
        'stock',
        'keterangan',
    ];

    /**
     * Boot method untuk generate ID otomatis
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_obat)) {
                $model->id_obat = \App\Helpers\IdGenerator::generateObatId();
            }
        });
    }
}
