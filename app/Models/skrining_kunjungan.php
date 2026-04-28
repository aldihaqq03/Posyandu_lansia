<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skrining_Kunjungan extends Model
{
    protected $table = 'skrining_kunjungan';

    protected $primaryKey = 'id_skrining_kunjungan';

    protected $fillable = [
        'id_skrining',
        'berat_badan',
        'tinggi_badan',
        'lingkar_perut',
        'td_sistolik',
        'td_diastolik',
        'keluhan',
    ];

    // Relasi ke parent (opsional tapi bagus)
    public function skrining()
    {
        return $this->belongsTo(Skrining::class, 'id_skrining', 'id_skrining');
    }
}