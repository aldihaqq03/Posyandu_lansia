<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class jadwalPosyandu extends Model
{
    protected $table = 'jadwal_posyandu';
    protected $primaryKey = 'id_jadwal_posyandu';
    protected $fillable = [
        'id_petugas',
        'tanggal_pelaksanaan',
        'tema',
        'lokasi',
        'kegiatan',
        'keterangan',
        'status',
    ];

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }

    public function detailSkrining()
    {
        return $this->hasMany(DetailSkrining::class, 'id_jadwal_posyandu');
    }

    public function petugasTerlibat()
    {
        return $this->belongsToMany(Petugas::class, 'jadwal_petugas', 'id_jadwal_posyandu', 'id_petugas');
    }

    public function kehadiran()
    {
        return $this->hasMany(KehadiranPosyandu::class, 'id_jadwal_posyandu');
    }

    public function skrining()
    {
        return $this->hasMany(Skrining::class, 'id_jadwal_posyandu');
    }

    public function itemJadwal()
    {
        return $this->hasMany(ItemJadwalLansia::class, 'id_jadwal_posyandu');
    }
}
