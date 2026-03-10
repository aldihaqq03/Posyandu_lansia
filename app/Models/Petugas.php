<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    protected $table = 'petugas';

    protected $fillable = [
        'nama',
        'nik',
        'jabatan',
        'wilayah',
        'no_hp',
        'email',
        'password',
        'foto',
        'status'
    ];
}