<?php
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class JadwalPosyandu extends Model
{
    protected $table      = 'jadwal_posyandu';
    protected $primaryKey = 'id_jadwal_posyandu';
 
    protected $fillable = [
        'id_petugas',
        'tanggal_pelaksanaan',
        'lokasi',
        'tema',
        'kegiatan',
        'keterangan',
        'status',
    ];
 
    // Status

const STATUS_TERJADWAL   = 0;
const STATUS_BERLANGSUNG = 1;
const STATUS_SELESAI     = 2;
const STATUS_BATAL       = 3;
 
     
    // ─── Relations ────────────────────────────────────────────────────────────
 
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }
 
    public function detailSkrining()
    {
        return $this->hasMany(DetailSkrining::class, 'id_jadwal_posyandu');
    }
 
    public function skrinings()
    {
        return $this->hasMany(Skrining::class, 'id_jadwal_posyandu');
    }
 
    // ─── Helpers ──────────────────────────────────────────────────────────────
 
    /**
     * Cek apakah jenis skrining tertentu aktif di jadwal ini.
     * Contoh: $jadwal->hasJenisSkrining(DetailSkrining::SKRINING_PPOK)
     */
    public function hasJenisSkrining(int $jenis): bool
    {
        return $this->detailSkrining->contains('jenis_skrining', $jenis);
    }
}