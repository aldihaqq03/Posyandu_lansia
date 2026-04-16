<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    protected $table = 'petugas';
protected $primaryKey = 'id_petugas';
    protected $fillable = [
        'id_user',
        'nama',
        'nik',
        'jabatan',
        'wilayah',
        'foto',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : '-';
    }

    public function getNoHpAttribute()
    {
        return $this->user ? $this->user->whatsapp : '-';
    }

    public function jadwalDipimpin()
    {
        return $this->hasMany(jadwalPosyandu::class, 'id_petugas');
    }

    public function jadwalTerlibat()
    {
        return $this->belongsToMany(jadwalPosyandu::class, 'jadwal_petugas', 'id_petugas', 'id_jadwal_posyandu');
    }

    public function skrining()
    {
        return $this->hasMany(Skrining::class, 'id_petugas');
    }

    public function saran()
    {
        return $this->hasMany(Saran::class, 'id_petugas');
    }

    public function resep()
    {
        return $this->hasMany(Resep::class, 'id_petugas');
    }
}