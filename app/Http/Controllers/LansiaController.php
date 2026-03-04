<?php

namespace App\Http\Controllers;

use App\Models\lansia;
use Illuminate\Http\Request;

class LansiaController extends Controller
{
  
    public function index()
    {
        $lansias = lansia::latest()->paginate(10);
        return view('lansia.index', compact('lansias'));
    }

    public function create()
    {
        return view('lansia.create');
    }

      public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|numeric|digits:16|unique:lansia,nik',
            'nama_lansia' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:50',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:15',
            'status_perkawinan' => 'nullable|string|max:20',
            'riwayat_penyakit' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        lansia::create($validated);

        return redirect()->route('lansia.index')->with('success', 'Data lansia berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(lansia $lansia)
    {
        return view('lansia.show', compact('lansia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(lansia $lansia)
    {
        return view('lansia.edit', compact('lansia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, lansia $lansia)
    {
        $validated = $request->validate([
            'nik' => 'required|numeric|digits:16|unique:lansia,nik,' . $lansia->id_lansia . ',id_lansia',
            'nama_lansia' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:50',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:15',
            'status_perkawinan' => 'nullable|string|max:20',
            'riwayat_penyakit' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $lansia->update($validated);

        return redirect()->route('lansia.index')->with('success', 'Data lansia berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(lansia $lansia)
    {
        $lansia->delete();

        return redirect()->route('lansia.index')->with('success', 'Data lansia berhasil dihapus.');
    }
}
