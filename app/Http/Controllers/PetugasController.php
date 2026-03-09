<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PetugasController extends Controller
{
    public function index()
    {
        return view('admin.petugas.index');
    }

    public function tambah()
    {
        return view('admin.petugas.tambah');
    }

    public function store(Request $request)
    {
        // nanti untuk simpan database
        return redirect('/petugas');
    }
}