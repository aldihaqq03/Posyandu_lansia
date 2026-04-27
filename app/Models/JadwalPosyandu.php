<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPosyandu extends Model
{
    protected $table = 'jadwal_posyandu';
    protected $primaryKey = 'id_jadwal_posyandu';
    
    protected $fillable = [
        'id_petugas',
        'tanggal_pelaksanaan',
        'lokasi',
        'tema',
        'kegiatan',
        'keterangan',
        'status',
        'ada_skrining_utama',
        'ada_skrining_ppok'
    ];

    public function petugass()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }

    public function skrinings()
    {
        return $this->hasMany(Skrining::class, 'id_jadwal_posyandu');
    }
}
