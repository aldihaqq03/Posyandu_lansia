<?php

namespace App\Http\Controllers;

use App\Models\Lansia;
use App\Models\Skrining;
use App\Models\SkriningUtama;
use App\Models\JadwalPosyandu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $total_lansia = Lansia::count();
        
        // Ambil ID lansia dengan screening terbaru
        $latestScreeningIds = SkriningUtama::select(DB::raw('MAX(id_skrining_utama)'))
            ->groupBy('id_lansia');

        $penyakit_beresiko = ['Hipertensi', 'Diabetes', 'Jantung', 'Stroke', 'PPOK'];

        $resiko_tinggi = Lansia::where(function($q) use ($penyakit_beresiko) {
                foreach($penyakit_beresiko as $p) {
                    $q->orWhere('riwayat_penyakit', 'LIKE', '%' . $p . '%');
                }
            })
            ->orWhereIn('id_lansia', function($query) use ($latestScreeningIds) {
                $query->select('id_lansia')
                    ->from('skrining_utama')
                    ->whereIn('id_skrining_utama', $latestScreeningIds)
                    ->where(function($q) {
                        $q->where('gula_darah_kategori', 3)
                          ->orWhere('kolesterol_kategori', 3);
                    });
            })
            ->count();

        $pemeriksaan_selesai = Skrining::whereMonth('tanggal_skrining', now()->month)
            ->whereYear('tanggal_skrining', now()->year)
            ->count();

        // Data untuk Tren Keluhan (Kombinasi dari Riwayat Penyakit dan Keluhan Skrining Terbaru)
        $penyakit_counts = [
            'Hipertensi' => Lansia::where('riwayat_penyakit', 'LIKE', '%Hipertensi%')->orWhereIn('id_lansia', function($q) {
                $q->select('id_lansia')->from('skrining')->where('keluhan', 'LIKE', '%Hipertensi%')->orWhere('keluhan', 'LIKE', '%Darah Tinggi%');
            })->count(),
            'Diabetes' => Lansia::where('riwayat_penyakit', 'LIKE', '%Diabetes%')->orWhere('riwayat_penyakit', 'LIKE', '%Gula%')->orWhereIn('id_lansia', function($q) {
                $q->select('id_lansia')->from('skrining')->where('keluhan', 'LIKE', '%Diabetes%')->orWhere('keluhan', 'LIKE', '%Gula%');
            })->count(),
            'Asam Urat' => Lansia::where('riwayat_penyakit', 'LIKE', '%Asam Urat%')->orWhereIn('id_lansia', function($q) {
                $q->select('id_lansia')->from('skrining')->where('keluhan', 'LIKE', '%Asam Urat%');
            })->count(),
            'Kolesterol' => Lansia::where('riwayat_penyakit', 'LIKE', '%Kolesterol%')->orWhereIn('id_lansia', function($q) {
                $q->select('id_lansia')->from('skrining')->where('keluhan', 'LIKE', '%Kolesterol%');
            })->count(),
        ];

        // Total penyakit untuk persentase
        $total_penyakit = array_sum($penyakit_counts) ?: 1;
        $tren_keluhan = [];
        foreach ($penyakit_counts as $nama => $count) {
            $tren_keluhan[] = [
                'nama' => $nama,
                'persen' => round(($count / $total_penyakit) * 100),
                'color' => $this->getColorForPenyakit($nama)
            ];
        }

        // Riwayat Pemeriksaan Terakhir
        $riwayat_terakhir = Skrining::with(['lansia.latestSkriningUtama'])
            ->latest('tanggal_skrining')
            ->limit(5)
            ->get();

        // Lansia yang diperiksa bulan ini (untuk avatar group)
        $pemeriksaan_bulan_ini = Skrining::with('lansia')
            ->whereMonth('tanggal_skrining', now()->month)
            ->whereYear('tanggal_skrining', now()->year)
            ->latest()
            ->get();

        $pemeriksaan_selesai = $pemeriksaan_bulan_ini->count();
        $lansia_checked = $pemeriksaan_bulan_ini->take(3); // Ambil 3 untuk avatar

        return view('admin.dashboard', compact(
            'total_lansia',
            'resiko_tinggi',
            'pemeriksaan_selesai',
            'tren_keluhan',
            'riwayat_terakhir',
            'lansia_checked'
        ));
    }

    private function getColorForPenyakit($nama)
    {
        return match ($nama) {
            'Hipertensi' => 'var(--danger)',
            'Diabetes' => 'var(--warning)',
            'Asam Urat' => 'var(--primary)',
            'Kolesterol' => 'var(--success)',
            default => 'var(--primary)',
        };
    }

    public function testNotification()
    {
        // Get users with FCM token
        $users = \App\Models\User::whereNotNull('fcm_token')->get();
        $count = 0;

        foreach ($users as $user) {
            $success = \App\Services\FcmService::sendNotification(
                $user->fcm_token,
                'Notifikasi Test',
                'Ini adalah pesan uji coba dari Dashboard Web!',
                ['type' => 'jadwal_baru']
            );
            if ($success) $count++;
        }

        if ($count > 0) {
            return redirect()->back()->with('success', "Notifikasi uji coba berhasil dikirim ke $count perangkat!");
        } else {
            return redirect()->back()->with('error', 'Gagal mengirim notifikasi. Pastikan ada user yang login di HP (memiliki fcm_token).');
        }
    }
}
