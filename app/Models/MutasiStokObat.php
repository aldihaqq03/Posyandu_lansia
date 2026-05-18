<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiStokObat extends Model
{
    use HasFactory;

    protected $table = 'mutasi_stok_obat';
    protected $primaryKey = 'id_mutasi';

    protected $fillable = [
        'id_obat',
        'id_resep',
        'tipe',
        'jumlah',
        'keterangan',
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat', 'id_obat');
    }

    public function resep()
    {
        return $this->belongsTo(Resep::class, 'id_resep', 'id_resep');
    }
}
