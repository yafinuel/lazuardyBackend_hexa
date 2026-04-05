<?php

namespace App\Domains\Notification\Infrastructure\External\Firebase;

use App\Domains\Notification\Ports\NotificationGatewayInterface;
use Exception;
use Illuminate\Support\Facades\Http;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FcmAdapter implements NotificationGatewayInterface
{
    protected string $projectId;
    
    public function __construct()
    {
        $this->projectId = config('services.firebase.project_id');
    }

    public function sendPush(string $token, string $title, string $body, array $data = []): bool
    {
        try {
            $accessToken = $this->getAccessToken();
            $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

            $payload = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => array_map('strval', $data),
                    'android' => [
                        'priority' => 'high',
                        'notification' => ['sound' => 'default']
                    ],
                    'apns' => [
                        'payload' => ['aps' => ['sound' => 'default']]
                    ]
                ]
            ];

            $response = Http::withToken($accessToken)->post($url, $payload);

            if ($response->failed()) {
                Log::error('FCM Send Error: ' . $response->body());
                return false;
            }

            return true;
        } catch (Exception $e) {
            Log::error('FCM Service Error: ' . $e->getMessage());
            return false;
        }
        
    }

    private function getAccessToken(): string
    {
        return Cache::remember('fcm_access_token', 3500, function () {
            $client = new GoogleClient();
            $client->setAuthConfig(storage_path('app/firebase-auth.json'));
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            
            $token = $client->fetchAccessTokenWithAssertion();
            
            if (!isset($token['access_token'])) {
                throw new Exception('Could not retrieve Google Access Token');
            }

            return $token['access_token'];
        });
    }
}
