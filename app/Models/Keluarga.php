<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keluarga extends Model
{
    protected $table = 'keluarga';
    protected $primaryKey = 'id_keluarga';
    protected $fillable = ['id_lansia', 'nama_keluarga', 'no_sama', 'alamat'];

    /**
     * Relationship: Keluarga belongsTo Lansia
     */
    public function lansia()
    {
        return $this->belongsTo(Lansia::class, 'id_lansia', 'id_lansia');
    }
}
