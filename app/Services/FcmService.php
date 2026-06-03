<?php

namespace App\Services;

use Google\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    /**
     * Kirim notifikasi ke device spesifik atau ke topik
     */
    public static function sendNotification($token, $title, $body, $data = [])
    {
        $accessToken = self::getAccessToken();

        if (!$accessToken) {
            Log::error('FCM: Gagal mendapatkan Access Token');
            return false;
        }

        $projectId = config('services.firebase.project_id'); // Kita ambil dari config nanti
        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => array_map('strval', $data),
                'android' => [
                    'priority' => 'HIGH',
                    'notification' => [
                        'channel_id' => 'jadwal_posyandu_channel',
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'sound' => 'default',
                    ],
                ],
            ],
        ];

        $response = Http::withToken($accessToken)->post($url, $payload);

        if ($response->successful()) {
            return true;
        }

        Log::error('FCM Error: ' . $response->body());
        return false;
    }

    /**
     * Mendapatkan Google Access Token menggunakan Service Account
     */
    private static function getAccessToken()
    {
        $path = storage_path('app/service-account.json');
        
        if (!file_exists($path)) {
            Log::error('FCM: File service-account.json tidak ditemukan di storage/app');
            return null;
        }

        $client = new Client();
        $client->setAuthConfig($path);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        
        $token = $client->fetchAccessTokenWithAssertion();
        return $token['access_token'] ?? null;
    }
}
