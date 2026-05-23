<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'data' => $harianData,
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
            'data' => $mingguanData,
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
            'data' => $tahunanData,
        ];

        // 4. Summary Atas
        // 4. Summary Atas

$hari_ini = DB::table('skrining_kunjungan')
    ->whereDate('created_at', now()->toDateString())
    ->count();

$minggu_ini = DB::table('skrining_kunjungan')
    ->whereBetween('created_at', [
        Carbon::now()->startOfWeek(),
        Carbon::now()->endOfWeek()
    ])
    ->count();

$tahun_ini = DB::table('skrining_kunjungan')
    ->whereYear('created_at', Carbon::now()->year)
    ->count();

       


        // DATA TABEL LAPORAN
        $laporan = DB::table('jadwal_posyandu')
            ->select(
                'id_jadwal_posyandu',
                'tanggal_pelaksanaan',
                'tema'
            )
            ->latest('tanggal_pelaksanaan')
            ->get();

        $summary = [
            'hari_ini' => $hari_ini,
            'minggu_ini' => $minggu_ini,
            'tahun_ini' => $tahun_ini,
        ];

        return view('admin.laporan', compact(
            'harian',
            'mingguan',
            'tahunan',
            'summary',
            'laporan'
        ));
    }
   public function detail($id)
   
{
    
    // ambil data jadwal
    $jadwal = DB::table('jadwal_posyandu')
        ->where('id_jadwal_posyandu', $id)
        ->first();

    // cek apakah jadwal sudah lewat
    $isSelesai = Carbon::parse($jadwal->tanggal_pelaksanaan)->isPast();

    // =========================
    // STATUS KEHADIRAN
    // =========================

    if ($isSelesai) {

        // tampilkan semua lansia
        $data = DB::table('lansia')
            ->leftJoin('skrining', function ($join) use ($id) {

                $join->on('lansia.id_lansia', '=', 'skrining.id_lansia')
                    ->where('skrining.id_jadwal_posyandu', '=', $id);

            })
            ->select(
                'lansia.nama_lansia',
                'lansia.jenis_kelamin',
                DB::raw("
                    CASE
                        WHEN skrining.id_skrining IS NOT NULL
                        THEN 'Hadir'
                        ELSE 'Tidak Hadir'
                    END as status_kehadiran
                ")
            )
            ->get();

    } else {

        // kalau belum selesai → tampil hadir aja
        $data = DB::table('skrining_kunjungan')
            ->join('skrining', 'skrining_kunjungan.id_skrining', '=', 'skrining.id_skrining')
            ->join('lansia', 'skrining.id_lansia', '=', 'lansia.id_lansia')
            ->where('skrining.id_jadwal_posyandu', $id)
            ->select(
                'lansia.nama_lansia',
                'lansia.jenis_kelamin',
                DB::raw("'Hadir' as status_kehadiran")
            )
            ->get();
    }

    // =========================
    // PETUGAS
    // =========================

    $petugas = DB::table('skrining')
        ->join('petugas', 'skrining.id_petugas', '=', 'petugas.id_petugas')
        ->where('skrining.id_jadwal_posyandu', $id)
        ->select(
            'petugas.nama',
            DB::raw('count(skrining.id_lansia) as jumlah_lansia')
        )
        ->groupBy('petugas.nama')
        ->get();

        $jadwal = DB::table('jadwal_posyandu')
    ->where('id_jadwal_posyandu', $id)
    ->first();

    // =========================
// OBAT KELUAR
// =========================

$obat = DB::table('detail_resep')
    ->join('resep', 'detail_resep.id_resep', '=', 'resep.id_resep')
    ->join('skrining', 'resep.id_skrining', '=', 'skrining.id_skrining')
    ->join('obat', 'detail_resep.id_obat', '=', 'obat.id_obat')
    ->where('skrining.id_jadwal_posyandu', $id)
    ->select(
        'obat.nama_obat',
        'detail_resep.jumlah_obat as jumlah_keluar'
    )
    ->get();

    return response()->json([
    'status' => $data,
    'petugas' => $petugas,
    'obat' => $obat,

    'jadwal' => [
        'tanggal' => $jadwal
            ? Carbon::parse($jadwal->tanggal_pelaksanaan)
                ->translatedFormat('d F Y')
            : '-',

        'tempat' => $jadwal->tempat ?? '-',

        'tema' => $jadwal->tema ?? '-'
    ]
]);
}
public function exportPdf($id)
{
    $jadwal = DB::table('jadwal_posyandu')
        ->where('id_jadwal_posyandu', $id)
        ->first();

    $lansia = DB::table('skrining')
    ->join('lansia','skrining.id_lansia','=','lansia.id_lansia')
    ->where('skrining.id_jadwal_posyandu',$id)
    ->select(
        'lansia.id_lansia',
        'lansia.nama_lansia',
        'lansia.nik',
        'lansia.jenis_kelamin',
        'lansia.alamat',
        'lansia.tanggal_lahir'
    )
    ->get();

    foreach($lansia as $item){
    $item->umur =
        \Carbon\Carbon::parse($item->tanggal_lahir)->age;
}
    foreach($lansia as $item){

    $item->umur =
        \Carbon\Carbon::parse($item->tanggal_lahir)->age;

    $obat = DB::table('detail_resep')
        ->join('resep', 'detail_resep.id_resep', '=', 'resep.id_resep')
        ->join('skrining', 'resep.id_skrining', '=', 'skrining.id_skrining')
        ->join('obat', 'detail_resep.id_obat', '=', 'obat.id_obat')
        ->where('skrining.id_lansia', $item->id_lansia)
        ->where('skrining.id_jadwal_posyandu', $id)
        ->pluck('obat.nama_obat')
        ->toArray();

    $item->obat = !empty($obat)
        ? implode(', ', $obat)
        : '-';
}

    $petugas = DB::table('skrining')
        ->join('petugas', 'skrining.id_petugas', '=', 'petugas.id_petugas')
        ->where('skrining.id_jadwal_posyandu', $id)
        ->select('petugas.nama')
        ->distinct()
        ->get();

    $pdf = Pdf::loadView(
        'pdf.laporan_posyandu',
        compact(
            'jadwal',
            'lansia',
            'petugas'
        )
    );

    return $pdf->stream('laporan-posyandu.pdf');
}
public function exportObat($id)
{
    $jadwal = DB::table('jadwal_posyandu')
        ->where('id_jadwal_posyandu', $id)
        ->first();

    $obat = DB::table('detail_resep')
        ->join('resep', 'detail_resep.id_resep', '=', 'resep.id_resep')
        ->join('skrining', 'resep.id_skrining', '=', 'skrining.id_skrining')
        ->join('obat', 'detail_resep.id_obat', '=', 'obat.id_obat')
        ->where('skrining.id_jadwal_posyandu', $id)
        ->select(
            'obat.nama_obat',
            'detail_resep.jumlah_obat'
        )
        ->get();

    $pdf = Pdf::loadView(
        'pdf.laporan_obat',
        compact('jadwal', 'obat')
    );

    return $pdf->stream('laporan-obat.pdf');
}
}