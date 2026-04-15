<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailSkrining extends Model
{
    protected $table = 'detail_skrining';
    protected $primaryKey = 'id_detail_skrining';
    protected $fillable = [
        'id_jadwal_posyandu',
        'jenis_skrining',
    ];

    const UTAMA = 1;
    const PPOK = 2;
    const KUNJUNGAN = 3;

    public static function getJenisLabel($jenis)
    {
        return match($jenis) {
            self::UTAMA => 'Utama',
            self::PPOK => 'PPOK',
            self::KUNJUNGAN => 'Kunjungan',
            default => 'Tidak Diketahui',
        };
    }

    public function jadwalPosyandu()
    {
        return $this->belongsTo(jadwalPosyandu::class, 'id_jadwal_posyandu');
    }
}
