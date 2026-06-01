<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasRandomId;

class Resep extends Model
{
    use HasFactory, HasRandomId;
    protected $table = 'resep';
    protected $primaryKey = 'id_resep';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'id_skrining',
        'id_petugas',
        'catatan',
    ];

    // ─── Relationships ────────────────────────────────────────────────

    /**
     * Resep belongs to Skrining
     */
    public function skrining()
    {
        return $this->belongsTo(Skrining::class, 'id_skrining');
    }

    /**
     * Resep belongs to Petugas
     */
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }

    /**
     * Resep has many DetailResep
     */
    public function detailResep()
    {
        return $this->hasMany(DetailResep::class, 'id_resep');
    }
}
