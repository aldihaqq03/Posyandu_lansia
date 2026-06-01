<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasRandomId;

class DetailSkrining extends Model
{
    use HasRandomId;
    protected $table      = 'detail_skrining';
    protected $primaryKey = 'id_detail_skrining';
    public $incrementing = false;
    protected $keyType = 'int';
 
    protected $fillable = [
        'id_jadwal_posyandu',
        'jenis_skrining',
    ];
 
    // Konstanta supaya tidak pakai magic number di mana-mana
    // const KUNJUNGAN_RUTIN = 1;
    // const SKRINING_UTAMA  = 2;
    // const SKRINING_PPOK   = 3;

    const SKRINING_UTAMA  = 1;
    const SKRINING_PPOK   = 2;
    const KUNJUNGAN_RUTIN = 3;
   
    public static function labelMap(): array
    {
        return [
           
        self::SKRINING_UTAMA  => 'Skrining Utama',
        self::SKRINING_PPOK   => 'Skrining PPOK',
        self::KUNJUNGAN_RUTIN => 'Kunjungan Rutin',
        ];
    
    }
 
    public function jadwal()
    {
        return $this->belongsTo(JadwalPosyandu::class, 'id_jadwal_posyandu');
    }
}
