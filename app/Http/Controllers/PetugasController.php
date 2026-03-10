<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{

    public function index()
{
    $petugas = Petugas::all();

    $total = Petugas::count();
    $aktif = Petugas::where('status','aktif')->count();
    $pending = Petugas::where('status','pending')->count();

    return view('admin.petugas.index', compact(
        'petugas',
        'total',
        'aktif',
        'pending'
    ));
}


    public function tambah()
    {
        return view('admin.petugas.tambah');
    }


    public function store(Request $request)
    {

        $request->validate([
            'nama' => 'required',
            'nik' => 'required|unique:petugas',
            'jabatan' => 'required',
            'wilayah' => 'required',
            'no_hp' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        Petugas::create([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'jabatan' => $request->jabatan,
            'wilayah' => $request->wilayah,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/data_petugas')->with('success','Data petugas berhasil ditambahkan');
    }


    public function edit($id)
    {
        $petugas = Petugas::findOrFail($id);
        return view('admin.petugas.edit', compact('petugas'));
    }


    public function update(Request $request, $id)
    {
        $petugas = Petugas::findOrFail($id);

        $petugas->update([
            'nama'=>$request->nama,
            'jabatan'=>$request->jabatan,
            'wilayah'=>$request->wilayah,
            'no_hp'=>$request->no_hp,
            'email'=>$request->email
        ]);

        return redirect('/data_petugas');
    }


    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);
        $petugas->delete();

        return redirect('/data_petugas');
    }

}