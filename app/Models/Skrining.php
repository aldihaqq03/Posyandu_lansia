<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class Skrining extends Model
// {
//     protected $table = 'skrining';
//     protected $primaryKey = 'id_skrining';

//     protected $fillable = [
//         'id_lansia',
//         'id_petugas',
//         'id_jadwal_posyandu',
//         'tanggal_skrining',
//         'keluhan'
//     ];

//     public function lansia()
//     {
//         return $this->belongsTo(Lansia::class, 'id_lansia');
//     }

//     public function petugas()
//     {
//         return $this->belongsTo(Petugas::class, 'id_petugas');
//     }

//     public function jadwal()
//     {
//         return $this->belongsTo(JadwalPosyandu::class, 'id_jadwal_posyandu');
//     }

//     public function utama()
//     {
//         return $this->hasOne(SkriningUtama::class, 'id_skrining');
//     }

//     public function ppok()
//     {
//         return $this->hasOne(SkriningPPOK::class, 'id_skrining');
//     }
// }

// app/Models/Skrining.php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class Skrining extends Model
// {
//     protected $table      = 'skrining';
//     protected $primaryKey = 'id_skrining';

//     protected $fillable = [
//         'id_lansia',
//         'id_petugas',
//         'id_jadwal_posyandu',
//         'tanggal_skrining',
//         'keluhan',
//     ];

//     public function lansia()
//     {
//         return $this->belongsTo(Lansia::class, 'id_lansia');
//     }

//     public function petugas()
//     {
//         return $this->belongsTo(Petugas::class, 'id_petugas');
//     }

//     public function jadwal()
//     {
//         return $this->belongsTo(JadwalPosyandu::class, 'id_jadwal_posyandu');
//     }

//     public function kunjungan()
//     {
//         return $this->hasOne(skrining_kunjungan::class, 'id_skrining');
//     }

//     public function utama()
//     {
//         return $this->hasOne(SkriningUtama::class, 'id_skrining');
//     }

//     public function ppok()
//     {
//         return $this->hasOne(SkriningPPOK::class, 'id_skrining');
//     }

//     public function resep()
//     {
//         return $this->hasOne(Resep::class, 'id_skrining');
//     }
    
// }

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasRandomId;

class Skrining extends Model
{
    use HasFactory, HasRandomId;
    protected $table      = 'skrining';
    protected $primaryKey = 'id_skrining';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'id_lansia',
        'id_petugas',
        'id_jadwal_posyandu',
        'tanggal_skrining',
        'keluhan',
    ];

    // ─── Belongs To ─────────────────────────────────────────────
    public function lansia()
    {
        return $this->belongsTo(Lansia::class, 'id_lansia');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalPosyandu::class, 'id_jadwal_posyandu');
    }

    // ─── Has One (sub-skrining) ──────────────────────────────────
    public function kunjungan()
    {
        return $this->hasOne(skrining_kunjungan::class, 'id_skrining');
    }

    public function utama()
    {
        return $this->hasOne(SkriningUtama::class, 'id_skrining');
    }

    public function ppok()
    {
        return $this->hasOne(SkriningPPOK::class, 'id_skrining');
    }

    public function resep()
    {
        return $this->hasOne(Resep::class, 'id_skrining');
    }
}