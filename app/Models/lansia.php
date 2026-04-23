<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lansia extends Model
{
    use HasFactory;

    protected $table = 'lansia';
    protected $primaryKey = 'id_lansia';

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
        'wilayah',
    ];

    public function skrinings()
    {
        return $this->hasMany(Skrining::class, 'id_lansia');
    }

    public function skriningUtamas()
    {
        return $this->hasMany(SkriningUtama::class, 'id_lansia');
    }
}
