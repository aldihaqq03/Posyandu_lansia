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

    // Konstanta supaya tidak pakai magic number di mana-mana
    const SKRINING_UTAMA  = 1;
    const SKRINING_PPOK   = 2;
    const KUNJUNGAN_RUTIN = 3;

    // Alias untuk kompatibilitas dengan kode lama
    const UTAMA = 1;
    const PPOK = 2;
    const KUNJUNGAN = 3;

    public static function labelMap(): array
    {
        return [
            self::SKRINING_UTAMA  => 'Skrining Utama',
            self::SKRINING_PPOK   => 'Skrining PPOK',
            self::KUNJUNGAN_RUTIN => 'Kunjungan Rutin',
        ];
    }

    public static function getJenisLabel($jenis)
    {
        return self::labelMap()[$jenis] ?? 'Tidak Diketahui';
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalPosyandu::class, 'id_jadwal_posyandu');
    }
}
