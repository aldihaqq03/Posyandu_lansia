<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lansia extends Model
{
    use HasFactory;

    protected $table      = 'lansia';
    protected $primaryKey = 'id_lansia';

    protected $fillable = [
        'id_user',
        'nik',
        'nama_lansia',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'status_perkawinan',
        'riwayat_penyakit',
        'tanggal_daftar',
        'keterangan',
        'email',
    ];

    // ─── Relasi User ────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // ─── Semua Skrining milik lansia ini ────────────────────────
    public function skrinings()
    {
        return $this->hasMany(Skrining::class, 'id_lansia');
    }

    // ─── Skrining Utama (via Skrining) ──────────────────────────
    public function skriningUtamas()
    {
        return $this->hasManyThrough(
            SkriningUtama::class,  // Final model
            Skrining::class,       // Intermediate model
            'id_lansia',           // FK in Skrining
            'id_skrining',         // FK in SkriningUtama
            'id_lansia',           // Local key in Lansia
            'id_skrining'          // Local key in Skrining
        );
    }

    public function latestSkriningUtama()
    {
        return $this->hasOneThrough(
            SkriningUtama::class,  // Final model
            Skrining::class,       // Intermediate model
            'id_lansia',           // FK in Skrining
            'id_skrining',         // FK in SkriningUtama
            'id_lansia',           // Local key in Lansia
            'id_skrining'          // Local key in Skrining
        )->orderByDesc('skrining_utama.id_skrining_utama');
    }

    // ─── Keluarga (Anggota Keluarga) ────────────────────────────
    public function keluargas()
    {
        return $this->hasMany(Keluarga::class, 'id_lansia');
    }

    // ─── Saran ──────────────────────────────────────────────────
    public function sarans()
    {
        return $this->hasMany(Saran::class, 'id_lansia');
    }
}