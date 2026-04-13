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

            'no_hp' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        $user = \App\Models\User::create([
            'email' => $request->email,
            'whatsapp' => $request->no_hp,
            'password' => $request->password
        ]);

        Petugas::create([
            'id_user' => $user->id,
            'nama' => $request->nama,
            'nik' => $request->nik,
            'jabatan' => $request->jabatan,

            'status' => 'aktif'
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
            'jabatan'=>$request->jabatan
        ]);

        if($petugas->user) {
            $petugas->user->update([
                'email' => $request->email,
                'whatsapp' => $request->no_hp
            ]);
        }

        return redirect('/data_petugas');
    }


    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);
        
        if($petugas->user) {
            $petugas->user->delete();
        } else {
            $petugas->delete();
        }

        return redirect('/data_petugas');
    }

}