<?php

namespace App\Http\Controllers;

use App\Models\Konten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KontenController extends Controller
{
    public function index()
    {
        $konten = Konten::orderBy('created_at', 'desc')->get();

        $kontenJson = $konten->map(function ($item) {
            return [
                'id'         => $item->id_konten,
                'judul'      => $item->judul,
                'tipe'       => $item->tipe_konten,
                'kategori'   => $item->kategori_konten,
                'deskripsi'  => $item->deskripsi,
                'gambar_url' => $item->gambar ? asset('storage/' . $item->gambar) : null,
                'video_url'  => $item->video  ? asset('storage/' . $item->video)  : null,
            ];
        })->values()->toArray();

        return view('admin.konten.index', compact('konten', 'kontenJson'));
    }

    public function create()
    {
        return view('admin.konten.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'          => 'required|string|max:255',
            'tipe_konten'    => 'required|integer',
            'kategori_konten'=> 'required|integer',
            'gambar'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'video'          => 'nullable|mimes:mp4,mov,avi,wmv|max:40960',
            'deskripsi'      => 'nullable|string|max:65000',
        ], [
            'judul.max'      => 'Judul terlalu panjang, maksimal 255 karakter.',
            'video.max'      => 'File video terlalu besar. Maksimal ukuran yang diizinkan adalah 40MB.',
            'video.mimes'    => 'Format video tidak didukung. Gunakan MP4, MOV, AVI, atau WMV.',
            'gambar.max'     => 'File foto terlalu besar. Maksimal ukuran yang diizinkan adalah 5MB.',
            'gambar.mimes'   => 'Format foto tidak didukung. Gunakan JPEG, PNG, atau GIF.',
            'deskripsi.max'  => 'Deskripsi terlalu panjang. Maksimal 65.000 karakter.',
        ]);

        $data = $request->only(['judul', 'tipe_konten', 'kategori_konten', 'deskripsi']);

        // tipe_konten: 1 = Video, 2 = Gambar, 3 = Artikel
        if ((int)$request->tipe_konten === 1 && $request->hasFile('video')) {
            $file     = $request->file('video');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path     = $file->storeAs('konten/video', $filename, 'public');
            $data['video']      = $path;
            $data['path_konten'] = $path;

        } elseif ((int)$request->tipe_konten === 2 && $request->hasFile('gambar')) {
            $file     = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path     = $file->storeAs('konten/gambar', $filename, 'public');
            $data['gambar']     = $path;
            $data['path_konten'] = $path;
        }
        // tipe 3 (Artikel): tidak ada file, deskripsi sudah terisi dari CKEditor

        $konten = Konten::create($data);

        // --- Kirim Notifikasi FCM ---
        try {
            $tokens = \App\Models\User::role('lansia')
                ->whereNotNull('fcm_token')
                ->pluck('fcm_token')
                ->toArray();
            
            $tipeLabel = $konten->tipe_konten == 1 ? 'Video' : ($konten->tipe_konten == 2 ? 'Foto' : 'Artikel');
            $title = 'Edukasi Baru!';
            $body = 'Petugas baru saja mengunggah ' . $tipeLabel . ' baru: ' . $konten->judul;
            
            foreach ($tokens as $token) {
                \App\Services\FcmService::sendNotification($token, $title, $body, [
                    'category' => 'Info',
                    'id' => (string) $konten->id_konten,
                    'action' => 'Buka Konten',
                    'content_url' => $konten->full_url ?? ''
                ]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("FCM Konten Store Error: " . $e->getMessage());
        }

        return redirect()->route('konten.index')->with('success', 'Konten berhasil ditambahkan!');
    }

    public function show($id)
    {
        $konten = Konten::findOrFail($id);
        return view('admin.konten.show', compact('konten'));
    }

    public function edit($id)
    {
        $konten = Konten::findOrFail($id);
        return view('admin.konten.edit', compact('konten'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul'          => 'required|string|max:255',
            'kategori_konten'=> 'required|integer',
            'deskripsi'      => 'nullable|string',
        ]);

        $konten = Konten::findOrFail($id);

        // Hanya update field yang diizinkan
        $konten->judul           = $request->judul;
        $konten->kategori_konten = $request->kategori_konten;
        $konten->deskripsi       = $request->deskripsi;
        $konten->save();

        return redirect()->route('konten.index')->with('success', 'Konten berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $konten = Konten::findOrFail($id);

        if ($konten->gambar) {
            Storage::disk('public')->delete($konten->gambar);
        }
        if ($konten->video) {
            Storage::disk('public')->delete($konten->video);
        }

        $konten->delete();

        return redirect()->route('konten.index')->with('success', 'Konten berhasil dihapus!');
    }
}