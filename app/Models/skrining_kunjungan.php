<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasRandomId;

class Skrining_Kunjungan extends Model
{
    use HasRandomId;
    protected $table = 'skrining_kunjungan';

    protected $primaryKey = 'id_skrining_kunjungan';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'id_skrining',
        'berat_badan',
        'tinggi_badan',
        'imt',
        'lingkar_perut',
        'td_sistolik',
        'td_diastolik',
        'keluhan',
        'diagnosis',
    ];

    // Relasi ke parent (opsional tapi bagus)
    public function skrining()
    {
        return $this->belongsTo(Skrining::class, 'id_skrining', 'id_skrining');
    }
}
