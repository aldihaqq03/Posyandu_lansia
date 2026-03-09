<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lansia extends Model
{
    use HasFactory;

    protected $table = 'lansia';
    protected $primaryKey = 'id_lansia';
<<<<<<< Updated upstream
    protected $fillable = [
        'nama',
        'nik',
        'tanggal_lahir',
        'jenis_kelamin',
        'email',
        'password',
        'alamat',
        'duno_hp',
        'pekerjaan',
      
=======

    protected $fillable = [
        'nik',
        'nama_lansia',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'status_perkawinan',
        'riwayat_penyakit',
        'tanggal_daftar',
        'keterangan',
        'email',
>>>>>>> Stashed changes
    ];
}
