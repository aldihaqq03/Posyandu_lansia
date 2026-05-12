<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkriningKunjungan extends Model
{
    protected $table = 'skrining_kunjungan';

    protected $primaryKey = 'id_kunjungan';

    protected $fillable = [
        'id_skrining',
        'berat_badan',
        'tinggi_badan',
        'lingkar_perut',
        'td_sistolik',
        'td_diastolik',
        'keluhan',
    ];

public function kunjungan()
{
    return $this->hasOne(SkriningKunjungan::class, 'id_skrining', 'id_skrining');
}
}