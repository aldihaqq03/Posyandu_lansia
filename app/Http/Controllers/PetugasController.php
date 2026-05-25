<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PetugasController extends Controller
{

    private function currentRole(): string
    {
        return strtolower(auth()->user()->jabatan ?? '');
    }

    private function isSuperAdmin(): bool
    {
        return $this->currentRole() === 'super_admin';
    }

    private function visiblePetugasQuery()
    {
        $query = Petugas::query();

        if (! $this->isSuperAdmin()) {
            $query->where('jabatan', 'kader');
        }

        return $query;
    }

    private function ensureCanManagePetugas(Petugas $petugas): void
    {
        if ($this->isSuperAdmin()) {
            return;
        }

        if (strtolower($petugas->jabatan ?? '') !== 'kader') {
            abort(403, 'Unauthorized access.');
        }
    }

    public function index()
    {
        $petugasQuery = $this->visiblePetugasQuery();

        $petugas = $petugasQuery->get();
        $total = (clone $petugasQuery)->count();
        $aktif = (clone $petugasQuery)->where('status', 'aktif')->count();
        $pending = (clone $petugasQuery)->where('status', 'pending')->count();

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
        $allowedJabatan = $this->isSuperAdmin()
            ? ['kader', 'kepala_kader']
            : ['kader'];

        $request->validate([
            'nama' => 'required|string|min:3|max:100',
            'nik' => 'required|digits:16|unique:petugas,nik',
            'jabatan' => ['required', Rule::in($allowedJabatan)],
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

            $jabatan = $this->isSuperAdmin() ? $request->jabatan : 'kader';

            $user = User::create([
                'email' => $request->email,
                'whatsapp' => $request->no_hp,
                'password' => $request->password,
            ]);

            Petugas::create([
                'id_user' => $user->id,
                'nama' => $request->nama,
                'nik' => $request->nik,
                'jabatan' => $jabatan,
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
        $this->ensureCanManagePetugas($petugas);

        return view('admin.petugas.edit', compact('petugas'));
    }


    public function update(Request $request, $id)
    {
        $petugas = Petugas::findOrFail($id);

        $this->ensureCanManagePetugas($petugas);

        $allowedJabatan = $this->isSuperAdmin()
            ? ['kader', 'kepala_kader', 'super_admin']
            : ['kader'];

        $request->validate([
            'nama' => 'required|string|min:3|max:100',
            'nik' => 'required|digits:16|unique:petugas,nik,' . $petugas->id_petugas . ',id_petugas',
            'jabatan' => ['required', Rule::in($allowedJabatan)],
            'no_hp' => 'required|regex:/^(\+62|0)[0-9]{9,12}$/|unique:users,whatsapp,' . $petugas->id_user,
            'email' => 'required|email:rfc|unique:users,email,' . $petugas->id_user,
        ], [
            'nik.digits' => 'NIK harus 16 digit angka.',
            'no_hp.regex' => 'Format Nomor WhatsApp tidak valid.',
            'no_hp.unique' => 'Nomor WhatsApp sudah digunakan.',
            'email.unique' => 'Email sudah digunakan.',
        ]);

        $jabatan = $this->isSuperAdmin() ? $request->jabatan : 'kader';

        $petugas->update([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'jabatan' => $jabatan,
        ]);

        if($petugas->user) {
            $petugas->user->update([
                'email' => $request->email,
                'whatsapp' => $request->no_hp,
            ]);
        }

        return redirect('/data_petugas');
    }


    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);
        $this->ensureCanManagePetugas($petugas);
        
        if($petugas->user) {
            $petugas->user->delete();
        } else {
            $petugas->delete();
        }

        return redirect('/data_petugas');
    }

}