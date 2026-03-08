<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lansia extends Model
{
    use HasFactory;

    protected $table = 'lansia';
    protected $primaryKey = 'id_lansia';
    protected $fillable = [
        'nama',
        'nik',
        'tanggal_lahir',
        'jenis_kelamin',
        'email',
        'password',
        'alamat',
        'no_hp',
        'pekerjaan',

    ];
}
