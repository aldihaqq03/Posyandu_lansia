<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'nama' => 'required|string|min:3|max:100',
            'nik' => 'required|digits:16|unique:petugas,nik',
            'jabatan' => 'required|in:kader,kepala_kader',
            'no_hp' => ['required', 'regex:/^(\+62|0)[0-9]{9,12}$/', 'unique:users,whatsapp'],
            'email' => 'required|email:rfc|unique:users,email',
            'password' => 'required|string|min:6',
            'foto' => 'nullable|image|max:2048',
        ], [
            'nik.digits' => 'NIK harus 16 digit angka.',
            'no_hp.regex' => 'Format Nomor WhatsApp tidak valid.',
            'no_hp.unique' => 'Nomor WhatsApp sudah digunakan.',
            'email.unique' => 'Email sudah digunakan.',
        ]);

        $user = DB::transaction(function () use ($request) {
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('petugas', 'public');
            }

            $user = User::create([
                'email' => $request->email,
                'whatsapp' => $request->no_hp,
                'password' => $request->password,
            ]);

            Petugas::create([
                'id_user' => $user->id,
                'nama' => $request->nama,
                'nik' => $request->nik,
                'jabatan' => $request->jabatan,
                'foto' => $fotoPath,
                'status' => 'pending',
            ]);

            return $user;
        });

        $user->sendEmailVerificationNotification();

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