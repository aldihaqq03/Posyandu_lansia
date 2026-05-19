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
        return view('admin.konten.index', compact('konten'));
    }

    public function create()
    {
        return view('admin.konten.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tipe_konten' => 'required|integer',
            'kategori_konten' => 'required|integer',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:51200', // 50MB
            'deskripsi' => 'nullable|string',
        ]);

        $data = $request->only(['judul', 'tipe_konten', 'kategori_konten', 'deskripsi']);

        // Handle Image Upload
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('konten/gambar', $filename, 'public');
            $data['gambar'] = $path;
            $data['path_konten'] = $path; // Simpan ke path utama untuk Flutter
        }

        // Handle Video Upload
        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('konten/video', $filename, 'public');
            $data['video'] = $path;
            $data['path_konten'] = $path; // Simpan ke path utama untuk Flutter
        }

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

    public function edit($id)
    {
        $konten = Konten::findOrFail($id);
        return view('admin.konten.edit', compact('konten'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tipe_konten' => 'required|integer',
            'kategori_konten' => 'required|integer',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:51200',
            'deskripsi' => 'nullable|string',
        ]);

        $konten = Konten::findOrFail($id);
        $data = $request->only(['judul', 'tipe_konten', 'kategori_konten', 'deskripsi']);

        // Handle Image Upload
        if ($request->hasFile('gambar')) {
            // Hapus file gambar lama jika ada
            if ($konten->gambar) {
                Storage::disk('public')->delete($konten->gambar);
            }
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('konten/gambar', $filename, 'public');
            $data['gambar'] = $path;
            $data['path_konten'] = $path; // Update path untuk Flutter
        }

        // Handle Video Upload
        if ($request->hasFile('video')) {
            // Hapus file video lama jika ada
            if ($konten->video) {
                Storage::disk('public')->delete($konten->video);
            }
            $file = $request->file('video');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('konten/video', $filename, 'public');
            $data['video'] = $path;
            $data['path_konten'] = $path; // Update path untuk Flutter
        }

        $konten->update($data);

        return redirect()->route('konten.index')->with('success', 'Konten berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $konten = Konten::findOrFail($id);
        
        // Delete files from storage
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
