<?php

namespace App\Http\Controllers;

use App\Models\Lansia;
use App\Models\Skrining;
use App\Models\SkriningUtama;
use App\Models\SkriningPPOK;
use Illuminate\Http\Request;

class LansiaController extends Controller
{

    public function index()
    {
        $lansias = Lansia::with('latestSkriningUtama')->latest()->paginate(10);
        $total_lansia = Lansia::count();

        // Ambil ID lansia dengan screening terbaru untuk menghitung status risiko
        $latestScreeningIds = SkriningUtama::select(\DB::raw('MAX(id_skrining_utama)'))
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

        $status_sehat = Lansia::where(function($q) use ($penyakit_beresiko) {
                foreach($penyakit_beresiko as $p) {
                    $q->where('riwayat_penyakit', 'NOT LIKE', '%' . $p . '%')
                      ->orWhereNull('riwayat_penyakit');
                }
            })
            ->whereIn('id_lansia', function($query) use ($latestScreeningIds) {
                $query->select('id_lansia')
                    ->from('skrining_utama')
                    ->whereIn('id_skrining_utama', $latestScreeningIds)
                    ->where('gula_darah_kategori', 1)
                    ->where('kolesterol_kategori', 1);
            })
            ->count();

        $jadwal_periksa = \App\Models\JadwalPosyandu::whereIn('status', [1, 2])->count();

        return view('admin.data_lansia', compact(
            'lansias',
            'total_lansia',
            'resiko_tinggi',
            'status_sehat',
            'jadwal_periksa'
        ));
    }

    public function create()
    {
        return view('lansia.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|digits:16|unique:lansia,nik',
            'nama_lansia' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:50',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|digits_between:10,13',
            'status_perkawinan' => 'nullable|string|max:20',
            'riwayat_penyakit' => 'nullable|string',
            'tanggal_daftar' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'email' => 'nullable|email|max:30'
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $request) {
            // Auto create user for Lansia (Tanpa Email)
            // Password default menggunakan No HP, jika tidak ada gunakan NIK
            $defaultPassword = $request->no_hp ? $request->no_hp : $request->nik;

            $user = \App\Models\User::create([
                'email' => null, // Lansia tidak wajib punya email
                'whatsapp' => $request->no_hp ?? '', // Nomor telepon untuk login mobile nanti
                'password' => bcrypt($defaultPassword),
            ]);

            $validated['id_user'] = $user->id;
            Lansia::create($validated);
        });

        return redirect()->route('data_lansia')
            ->with('success', 'Data lansia berhasil ditambahkan.');
    }

    public function show(Lansia $lansia)
    {
        // Menggunakan relasi Eloquent agar lebih bersih
        $skriningUtama = $lansia->skriningUtamas()
            ->with('skrining') // eager loading tabel induk
            ->orderByDesc('created_at')
            ->get();

        $skriningPPOK = SkriningPPOK::whereHas('skrining', function($q) use ($lansia) {
                $q->where('id_lansia', $lansia->id_lansia);
            })
            ->with('skrining')
            ->orderByDesc('created_at')
            ->get();

        // Contoh pemanggilan relasi lain jika diperlukan
        $jadwalMingguan = \Illuminate\Support\Facades\DB::table('item_jadwal_lansia')
            ->join('skrining', 'item_jadwal_lansia.id_skrining', '=', 'skrining.id_skrining')
            ->where('skrining.id_lansia', $lansia->id_lansia)
            ->orderBy('item_jadwal_lansia.hari', 'asc')
            ->get();

        return view('lansia.show', compact('lansia', 'skriningUtama', 'skriningPPOK', 'jadwalMingguan'));
    }

    public function edit(Lansia $lansia)
    {
        return view('lansia.edit', compact('lansia'));
    }

    public function update(Request $request, Lansia $lansia)
    {
        $validated = $request->validate([
            'nik' => 'required|digits:16|unique:lansia,nik,' . $lansia->id_lansia . ',id_lansia',
            'nama_lansia' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:50',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|digits_between:10,13',
            'status_perkawinan' => 'nullable|string|max:20',
            'riwayat_penyakit' => 'nullable|string',
            'tanggal_daftar' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'email' => 'nullable|email|max:30'
        ]);

        $lansia->update($validated);

        return redirect()->route('data_lansia')
            ->with('success', 'Data lansia berhasil diperbarui.');
    }

    public function destroy(Lansia $lansia)
    {
        $lansia->delete();

        return redirect()->route('data_lansia')
            ->with('success', 'Data lansia berhasil dihapus.');
    }
}