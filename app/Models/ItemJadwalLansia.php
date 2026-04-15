<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemJadwalLansia extends Model
{
    protected $table = 'item_jadwal_lansia';
    protected $primaryKey = 'id_item_jadwal';
    protected $fillable = [
        'id_jadwal_posyandu',
        'id_konten',
        'jenis_aktivitas',
        'nama_aktivitas',
        'deskripsi',
        'hari',
        'waktu_aktivitas',
        'durasi_menit',
    ];

    const OLAHRAGA = 1;
    const DIET = 2;
    const TERAPI = 3;
    const SOSIAL = 4;
    const ISTIRAHAT = 5;

    public static function getJenisLabel($jenis)
    {
        return match($jenis) {
            self::OLAHRAGA => 'Olahraga',
            self::DIET => 'Diet',
            self::TERAPI => 'Terapi',
            self::SOSIAL => 'Sosial',
            self::ISTIRAHAT => 'Istirahat',
            default => 'Tidak Diketahui',
        };
    }

    public static function getHariLabel($hari)
    {
        return match($hari) {
            0 => 'Senin',
            1 => 'Selasa',
            2 => 'Rabu',
            3 => 'Kamis',
            4 => 'Jumat',
            5 => 'Sabtu',
            6 => 'Minggu',
            default => 'Tidak Diketahui',
        };
    }

    public function jadwalPosyandu()
    {
        return $this->belongsTo(jadwalPosyandu::class, 'id_jadwal_posyandu');
    }

    public function konten()
    {
        return $this->belongsTo(Konten::class, 'id_konten');
    }
}
