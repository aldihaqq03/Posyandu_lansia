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
}