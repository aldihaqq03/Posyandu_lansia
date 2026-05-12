<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\API\TelegramWebhookController;

class TelegramPoll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:poll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poll Telegram getUpdates API untuk menangkap pesan masuk secara realtime di local';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        if (!$token) {
            $this->error('TELEGRAM_BOT_TOKEN belum diset di .env');
            return Command::FAILURE;
        }

        $this->info("Memulai polling pesan Telegram... (Tekan Ctrl+C untuk berhenti)");

        $url = "https://api.telegram.org/bot{$token}/getUpdates";
        $offset = 0;

        while (true) {
            try {
                $response = Http::timeout(35)
                    ->connectTimeout(15)
                    ->withOptions([
                        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                    ])
                    ->get($url, [
                        'offset' => $offset,
                        'timeout' => 20 // Long polling
                    ]);

                if ($response->successful()) {
                    $updates = $response->json('result');
                    
                    if (!empty($updates)) {
                        foreach ($updates as $update) {
                            $offset = $update['update_id'] + 1; // Update offset untuk menandai pesan sudah dibaca

                            if (isset($update['message'])) {
                                $this->info("Menerima pesan dari: " . ($update['message']['chat']['first_name'] ?? 'Unknown'));
                                // Panggil logic pemrosesan yang sama dengan Webhook
                                TelegramWebhookController::processMessage($update['message']);
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                $this->error("Error: " . $e->getMessage());
                sleep(2); // Tunggu sebentar sebelum mencoba lagi jika error network
            }

            // Mencegah loop berjalan terlalu cepat tanpa jeda
            usleep(500000); // 0.5 detik
        }
    }
}
