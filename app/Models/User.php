<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

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

    public function lansia()
    {
        return $this->hasOne(Lansia::class, 'id_user');
    }

    public function getNamaAttribute()
    {
        if ($this->petugas) return $this->petugas->nama;
        if ($this->lansia) return $this->lansia->nama_lansia;
        return '-';
    }

    public function getNikAttribute()
    {
        if ($this->petugas) return $this->petugas->nik;
        if ($this->lansia) return $this->lansia->nik;
        return '-';
    }

    public function getJabatanAttribute()
    {
        if ($this->petugas) return $this->petugas->jabatan;
        if ($this->lansia) return 'lansia';
        return 'User';
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
