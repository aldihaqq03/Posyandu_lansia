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
}
