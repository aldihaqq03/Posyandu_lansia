<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasRandomId;

class Saran extends Model
{
    use HasRandomId;
    protected $table = 'saran';
    protected $primaryKey = 'id_saran';
    public $incrementing = false;
    protected $keyType = 'int';

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

