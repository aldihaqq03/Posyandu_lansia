<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EmergencyApiController extends Controller
{
    public function sendAlert(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'pesan' => 'nullable',
        ]);

        $lat = $request->latitude;
        $lng = $request->longitude;
        $googleMapsUrl = "https://www.google.com/maps/search/?api=1&query={$lat},{$lng}";
        $message = "🚨 *MINTA TOLONG!* 🚨\nSaya dalam keadaan darurat.\n\nLokasi saya: {$googleMapsUrl}";

        try {
            $user = $request->user();
            $terkirim = 0;

            if ($user && $user->lansia) {
                $lansiaId = $user->lansia->id_lansia;
                $kontakDarurats = \App\Models\EmergencyContact::where('id_lansia', $lansiaId)->get();

                $teleToken = env('TELEGRAM_BOT_TOKEN');
                
                if (!$teleToken) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Token Telegram belum diatur di server.'
                    ], 500);
                }

                if ($kontakDarurats->count() > 0) {
                    $teleUrl = "https://api.telegram.org/bot{$teleToken}/sendMessage";
                    foreach ($kontakDarurats as $kontak) {
                        $response = Http::withoutVerifying()->post($teleUrl, [
                            'chat_id' => $kontak->chat_id,
                            'text' => $message,
                            'parse_mode' => 'Markdown'
                        ]);
                        
                        if ($response->successful()) {
                            $terkirim++;
                        }
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Belum ada kontak darurat Telegram yang terdaftar.'
                    ], 404);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data lansia tidak ditemukan.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => "Sinyal darurat terkirim ke {$terkirim} kontak Telegram."
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}
