<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'whatsapp',
        'password',
        'is_active'
    ];

    /**
     * RELATION
     */
    public function petugas()
    {
        return $this->hasOne(Petugas::class, 'id_user');
    }

    /**
     * ACCESSOR
     */
    public function getNamaAttribute()
    {
        return $this->petugas?->nama ?? '-';
    }

    public function getNikAttribute()
    {
        return $this->petugas?->nik ?? '-';
    }

    public function getJabatanAttribute()
    {
        return $this->petugas?->jabatan;
    }

    public function getWilayahKerjaAttribute()
    {
        return $this->petugas?->wilayah ?? '-';
    }

    /**
     * ROLE HELPER
     */
    public function isKepalaKader()
    {
        return $this->petugas?->jabatan === 'kepala_kader';
    }

    public function isKader()
    {
        return $this->petugas?->jabatan === 'kader';
    }

    /**
     * HIDDEN
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * CAST
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean', // 🔥 penting
        ];
    }
}