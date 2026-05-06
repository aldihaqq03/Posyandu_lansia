<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyContact extends Model
{
    use HasFactory;

    protected $table = 'emergency_contacts';
    
    protected $fillable = [
        'id_lansia',
        'chat_id',
        'nama_telegram'
    ];

    public function lansia()
    {
        return $this->belongsTo(Lansia::class, 'id_lansia', 'id_lansia');
    }
}
