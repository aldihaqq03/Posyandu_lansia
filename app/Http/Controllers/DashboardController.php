<?php

namespace App\Http\Controllers;

use App\Models\Lansia;
use App\Models\Skrining;
use App\Models\SkriningUtama;
use App\Models\JadwalPosyandu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $total_lansia = Cache::remember('dash_total_lansia', 600, fn() => Lansia::count());
        
        // Ambil ID lansia dengan screening terbaru
        $resiko_tinggi = Cache::remember('dash_resiko_tinggi', 600, function() {
            $latestScreeningIds = SkriningUtama::select(DB::raw('MAX(id_skrining_utama)'))
                ->groupBy('id_lansia');

            $penyakit_beresiko = ['Hipertensi', 'Diabetes', 'Jantung', 'Stroke', 'PPOK'];

            return Lansia::where(function($q) use ($penyakit_beresiko) {
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
        });

        $pemeriksaan_selesai = Cache::remember('dash_pemeriksaan_selesai', 600, function() {
            return Skrining::whereMonth('tanggal_skrining', now()->month)
                ->whereYear('tanggal_skrining', now()->year)
                ->count();
        });

        // Data untuk Tren Keluhan
        $penyakit_counts = Cache::remember('dash_penyakit_counts', 600, function() {
            return [
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
        });

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
        $riwayat_terakhir = Cache::remember('dash_riwayat_terakhir', 600, function() {
            return Skrining::with(['lansia.latestSkriningUtama'])
                ->latest('tanggal_skrining')
                ->limit(5)
                ->get();
        });

        // Lansia yang diperiksa bulan ini (untuk avatar group)
        $lansia_checked = Cache::remember('dash_lansia_checked', 600, function() {
            return Skrining::with('lansia')
                ->whereMonth('tanggal_skrining', now()->month)
                ->whereYear('tanggal_skrining', now()->year)
                ->latest()
                ->limit(3)
                ->get();
        });

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
