<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Lansia;
use App\Models\EmergencyContact;

class TelegramWebhookController extends Controller
{
    /**
     * UNTUK MENGAKTIFKAN WEBHOOK:
     * 1. Jalankan ngrok atau pastikan URL server Anda bisa diakses publik (HTTPS)
     * 2. Buka URL ini di browser (ganti YOUR_BOT_TOKEN dan YOUR_URL):
     *    https://api.telegram.org/botYOUR_BOT_TOKEN/setWebhook?url=https://YOUR_URL/api/telegram/webhook
     * 3. Pastikan route /api/telegram/webhook diarahkan ke method handle() ini.
     * 4. Jika menggunakan Webhook, matikan proses Polling (php artisan telegram:poll)
     */
    public function handle(Request $request)
    {
        $update = $request->all();

        if (isset($update['message'])) {
            $this->processMessage($update['message']);
        }

        return response('OK', 200);
    }

    /**
     * Helper untuk memproses pesan, sengaja dipisah agar bisa digunakan
     * oleh Webhook Controller maupun Polling Command.
     */
    public static function processMessage($message)
    {
        $chatId = $message['chat']['id'] ?? null;
        $text = $message['text'] ?? '';
        $firstName = $message['chat']['first_name'] ?? 'Keluarga';

        if (!$chatId) return;

        if (strpos($text, '/start ') === 0) {
            $kodeUnik = trim(str_replace('/start ', '', $text));

            $lansia = Lansia::where('kode_unik', $kodeUnik)->first();

            if ($lansia) {
                EmergencyContact::updateOrCreate(
                    ['id_lansia' => $lansia->id_lansia, 'chat_id' => $chatId],
                    ['nama_telegram' => $firstName]
                );

                $reply = "✅ Berhasil terhubung!\n\nAnda sekarang terdaftar sebagai kontak darurat untuk lansia: *" . $lansia->nama_lansia . "*.\n\nSistem akan mengirimkan pesan ke obrolan ini jika terjadi keadaan darurat.";
                self::sendMessage($chatId, $reply);
            } else {
                $reply = "❌ *Kode tidak valid.*\n\nPastikan Anda menekan link yang benar dari pesan WhatsApp / aplikasi Posyandu Lansia.";
                self::sendMessage($chatId, $reply);
            }
        } else if ($text === '/start') {
            self::sendMessage($chatId, "Selamat datang! Untuk mendaftar sebagai kontak darurat, silakan gunakan link yang dikirimkan via WhatsApp atau ketik manual: \n\n`/start KODE_LANSIA`");
        }
    }

    public static function sendMessage($chatId, $text)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        if (!$token) return;

        $url = "https://api.telegram.org/bot{$token}/sendMessage";

        Http::timeout(10)
            ->withOptions([
                CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            ])
            ->post($url, [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'Markdown'
            ]);
    }
}
