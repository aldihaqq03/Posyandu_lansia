<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPetugas extends Model
{
    protected $table = 'jadwal_petugas';
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'id_jadwal_posyandu',
        'id_petugas',
    ];

    public function jadwalPosyandu()
    {
        return $this->belongsTo(jadwalPosyandu::class, 'id_jadwal_posyandu');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }
}
