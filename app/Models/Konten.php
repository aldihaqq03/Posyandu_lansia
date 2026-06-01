<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasRandomId;

class Konten extends Model
{
    use HasFactory, HasRandomId;

    protected $table = 'konten';
    protected $primaryKey = 'id_konten';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'judul',
        'tipe_konten',
        'kategori_konten',
        'path_konten',
        'gambar',
        'video',
        'durasi_detik',
        'deskripsi',
    ];

    protected $appends = ['full_url'];

    public function getFullUrlAttribute()
    {
        if ($this->path_konten) {
            return url('storage/' . $this->path_konten);
        }
        return null;
    }
}
