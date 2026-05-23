<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Konten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KontenController extends Controller
{
    public function index(Request $request)
    {
        try {
            $konten = Konten::orderBy('created_at', 'desc')->get();

            $data = $konten->map(function ($item) {

                // Gambar URL
                $gambarUrl = '';
                if ($item->gambar && $item->gambar !== '') {
                    $gambarUrl = url('storage/' . $item->gambar);
                }

                // Video URL
                $videoUrl = '';
                if ($item->video && $item->video !== '') {
                    $videoUrl = url('storage/' . $item->video);
                }

                // full_url: sudah di-handle model via getFullUrlAttribute()
                // tapi kita override per tipe supaya lebih akurat
                $tipe = (int) $item->tipe_konten;
                if ($tipe === 1) {
                    // Video → full_url = video url
                    $fullUrl = $videoUrl ?: ($item->full_url ?? '');
                } elseif ($tipe === 2) {
                    // Gambar → full_url = gambar url
                    $fullUrl = $gambarUrl ?: ($item->full_url ?? '');
                } else {
                    // Artikel → pakai full_url dari model
                    $fullUrl = $item->full_url ?? '';
                }

                return [
                    'id_konten'       => $item->id_konten,  // ← FIX: pakai id_konten
                    'judul'           => $item->judul ?? '',
                    'deskripsi'       => $item->deskripsi ?? '',
                    'tipe_konten'     => $tipe,
                    'kategori_konten' => (int) ($item->kategori_konten ?? 0),
                    'gambar'          => $item->gambar ?? '',
                    'video'           => $item->video ?? '',
                    'path_konten'     => $item->path_konten ?? '',
                    'full_url'        => $fullUrl,
                    'thumbnail_url'   => $gambarUrl, // thumbnail selalu dari gambar
                    'durasi_detik'    => (int) ($item->durasi_detik ?? 0),
                    'created_at'      => $item->created_at
                        ? $item->created_at->toIso8601String()
                        : '',
                ];
            });

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data konten: ' . $e->getMessage(),
                'data'    => [],
            ], 500);
        }
    }
}