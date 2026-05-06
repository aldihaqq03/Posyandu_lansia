<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Saran extends Model
{
    protected $table = 'saran';
    protected $primaryKey = 'id_saran';

    protected $fillable = [
        'id_lansia',
        'jenis_saran',
        'isi_saran'
    ];

    public function lansia()
    {
        return $this->belongsTo(Lansia::class, 'id_lansia');
    }
}

