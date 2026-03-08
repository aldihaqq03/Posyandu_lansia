<?php

namespace App\Http\Controllers;

use App\Models\Lansia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class LansiaController extends Controller
{

    public function index()
    {
        $lansias = Lansia::latest()->paginate(10);
        $total_lansia = Lansia::count();
        $resiko_tinggi = 0; // Placeholder for now
        $status_sehat = 0; // Placeholder for now
        $jadwal_periksa = 0; // Placeholder for now

        return view('admin.data_lansia', compact('lansias', 'total_lansia', 'resiko_tinggi', 'status_sehat', 'jadwal_periksa'));
    }

    public function create()
    {
        return view('lansia.create');
    }




    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|numeric|digits:16|unique:lansia,nik',
            'nama_lengkap' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:100',
            'password' => 'nullable|string|min:6|confirmed'
        ]);

        // Map nama_lengkap to nama for the model
        $data = $validated;
        $data['nama'] = $data['nama_lengkap'];
        unset($data['nama_lengkap']);

        // Map jenis_kelamin from L/P to laki-laki/perempuan
        $jkMap = ['L' => 'laki-laki', 'P' => 'perempuan'];
        $data['jenis_kelamin'] = $jkMap[$data['jenis_kelamin']] ?? $data['jenis_kelamin'];

        // Remove password_confirmation from data (not needed in DB)
        unset($data['password_confirmation']);

        // enkripsi password
        if (isset($request->password) && $request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $lansia = Lansia::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Data lansia berhasil ditambahkan.',
            'data' => $lansia
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(Lansia $lansia)
    {
        return view('lansia.show', compact('lansia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lansia $lansia)
    {
        return view('lansia.edit', compact('lansia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lansia $lansia)
    {
        $validated = $request->validate([
            'nik' => 'required|numeric|digits:16|unique:lansia,nik,' . $lansia->id_lansia . ',id_lansia',
            'nama_lengkap' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:255',
            // Ignore penyakit and status_risiko as they are not in the database table
        ]);

        $data = $validated;
        
        // Map naming differences
        $data['nama'] = $data['nama_lengkap'];
        unset($data['nama_lengkap']);

        $lansia->update($data);

        return redirect()->route('data_lansia')->with('success', 'Data lansia berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lansia $lansia)
    {
        $lansia->delete();

        return redirect()->route('lansia.index')->with('success', 'Data lansia berhasil dihapus.');
    }
}
