<?php

namespace App\Http\Controllers;

use App\Models\Lansia;
use Illuminate\Http\Request;

class LansiaController extends Controller
{

    public function index()
    {
        if (auth()->user()->petugas && auth()->user()->petugas->jabatan == 'kader') {
            $wilayah = auth()->user()->petugas->wilayah;
            $lansias = Lansia::where('wilayah', $wilayah)->latest()->paginate(10);
            $total_lansia = Lansia::where('wilayah', $wilayah)->count();
        } else {
            $lansias = Lansia::latest()->paginate(10);
            $total_lansia = Lansia::count();
        }

        $resiko_tinggi = 0;
        $status_sehat = 0;
        $jadwal_periksa = 0;

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

        if (auth()->check() && auth()->user()->petugas) {
            $validated['wilayah'] = auth()->user()->petugas->wilayah;
        }

        Lansia::create($validated);

        return redirect()->route('data_lansia')
            ->with('success', 'Data lansia berhasil ditambahkan.');
    }

    public function show(Lansia $lansia)
    {
        return view('lansia.show', compact('lansia'));
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