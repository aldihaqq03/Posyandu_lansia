<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register()
    {
        return view('simpel.register');
    }

    public function proses_register(Request $request)
    {
        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'nik' => $request->nik,
            'whatsapp' => $request->whatsapp,
            'jabatan' => $request->jabatan,
            'wilayah_kerja' => $request->wilayah_kerja,
            'password' => Hash::make($request->password)
        ]);

        return redirect('/login');
    }

    public function login()
    {
        return view('simpel.login');
    }

    public function proses_login(Request $request)
    {

        $credentials = $request->only('email','password');

        if(Auth::attempt($credentials)){

            return redirect('/dashboard');
        }

        return back()->with('error','Email atau Password salah');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

}