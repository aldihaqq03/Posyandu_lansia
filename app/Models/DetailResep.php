<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailResep extends Model
{
    protected $table = 'detail_resep';
    protected $primaryKey = 'id_detail_resep';
    public $timestamps = true;

    protected $fillable = [
        'id_resep',
        'id_obat',
        'dosis',
        'frekuensi',
        'keterangan',
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
