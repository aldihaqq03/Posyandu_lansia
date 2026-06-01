<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasRandomId;

class Keluarga extends Model
{
    use HasRandomId;
    protected $table = 'keluarga';
    protected $primaryKey = 'id_keluarga';
    public $incrementing = false;
    protected $keyType = 'int';
    protected $fillable = ['id_lansia', 'nama_keluarga', 'no_sama', 'alamat'];

    /**
     * Relationship: Keluarga belongsTo Lansia
     */
    public function lansia()
    {
        return $this->belongsTo(Lansia::class, 'id_lansia', 'id_lansia');
    }
}
