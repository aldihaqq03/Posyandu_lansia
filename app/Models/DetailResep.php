<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailResep extends Model
{
    use HasFactory;
    protected $table = 'detail_resep';
    protected $primaryKey = 'id_detail_resep';
    public $timestamps = true;

    protected $fillable = [
        'id_resep',
        'id_obat',
        'dosis',
        'jenis_jadwal',
        'frekuensi',
        'hari_konsumsi',
        'durasi_hari',
        'jumlah_obat',
        'keterangan',
    ];

    protected $casts = [
        'hari_konsumsi' => 'array',
        'frekuensi' => 'integer',
        'jumlah_obat' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────

    /**
     * DetailResep belongs to Resep
     */
    public function resep()
    {
        return $this->belongsTo(Resep::class, 'id_resep');
    }

    /**
     * DetailResep belongs to Obat
     */
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }
}
