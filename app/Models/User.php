<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'whatsapp',
        'password',
    ];

    public function petugas()
    {
        return $this->hasOne(Petugas::class, 'id_user');
    }

    public function getNamaAttribute()
    {
        return $this->petugas ? $this->petugas->nama : '-';
    }

    public function getNikAttribute()
    {
        return $this->petugas ? $this->petugas->nik : '-';
    }

    public function getJabatanAttribute()
    {
        return $this->petugas ? $this->petugas->jabatan : 'User';
    }

    public function getWilayahKerjaAttribute()
    {
        return $this->petugas ? $this->petugas->wilayah : '-';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
