<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\lansia;

class LaporanController extends Controller
{
    public function index()
    {
        // 1. Data Harian (Kehadiran 7 Hari Terakhir)
        $harianLabels = [];
        $harianData = [];

        $kunjunganQuery = DB::table('kunjungan');


        for ($i = 6; $i >= 0; $i--) {
            // Mengambil tanggal dari 6 hari lalu sampai hari ini
            $date = Carbon::now()->subDays($i);
            $harianLabels[] = $date->translatedFormat('l'); // Nama hari dalam bahasa lokal

            // Query total kehadiran per hari tersebut
            $count = (clone $kunjunganQuery)
                ->whereDate('kunjungan.tanggal_kunjungan', $date->toDateString())
                ->count();
            $harianData[] = $count;
        }

        $harian = [
            'labels' => $harianLabels,
            'data' => $harianData
        ];

        // 2. Data Mingguan (Kehadiran rentang minggu dalam Bulan Ini)
        $mingguanLabels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
        $mingguanData = [0, 0, 0, 0];

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $kunjunganBulanIni = (clone $kunjunganQuery)
            ->whereMonth('kunjungan.tanggal_kunjungan', $currentMonth)
            ->whereYear('kunjungan.tanggal_kunjungan', $currentYear)
            ->select('kunjungan.*')
            ->get();

        foreach ($kunjunganBulanIni as $kunjungan) {
            $hari = Carbon::parse($kunjungan->tanggal_kunjungan)->day;
            if ($hari <= 7) {
                $mingguanData[0]++;
            } elseif ($hari <= 14) {
                $mingguanData[1]++;
            } elseif ($hari <= 21) {
                $mingguanData[2]++;
            } else {
                $mingguanData[3]++;
            }
        }

        $mingguan = [
            'labels' => $mingguanLabels,
            'data' => $mingguanData
        ];

        // 3. Data Tahunan (Kehadiran 12 Bulan)
        $tahunanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $tahunanData = array_fill(0, 12, 0); // isi 0 untuk 12 index array

        $kunjunganTahunIni = (clone $kunjunganQuery)
            ->select(DB::raw('MONTH(kunjungan.tanggal_kunjungan) as bulan'), DB::raw('count(*) as total'))
            ->whereYear('kunjungan.tanggal_kunjungan', $currentYear)
            ->groupBy('bulan')
            ->get();

        foreach ($kunjunganTahunIni as $k) {
            $tahunanData[$k->bulan - 1] = $k->total;
        }

        $tahunan = [
            'labels' => $tahunanLabels,
            'data' => $tahunanData
        ];

        // 4. Summary Atas
        // 4. Summary Atas
$hari_ini = lansia::whereDate('created_at', Carbon::today())->count();

$minggu_ini = lansia::whereBetween('created_at', [
    Carbon::now()->startOfWeek(),
    Carbon::now()->endOfWeek()
])->count();

$tahun_ini = lansia::whereYear('created_at', Carbon::now()->year)->count();

        $summary = [
            'hari_ini' => $hari_ini,
            'minggu_ini' => $minggu_ini,
            'tahun_ini' => $tahun_ini,
        ];

        return view('admin.laporan', compact('harian', 'mingguan', 'tahunan', 'summary'));
    }
}
