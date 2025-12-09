<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmaarApiClient
{
    private string $apiKey;
    private string $dailyCookie;
    private string $monthlyCookie;

    public function __construct()
    {
        $this->apiKey = config('emaar.x_api_key');
        $this->dailyCookie = 'EmaarCookie=!tAB8Pf0MVogZ81obZ8iAVHPu2zwut7wDvb73T7QDsF0aUCzEuhjUSUZvMZiwD0VeouOB5V0+Kv66YS0=; TS01fca733=01eddcdb62efcffc8d0c00e6f84b95c8e52b17e81cea2ca77195390615b40d588afa878e1d5303b9f77dea3f21bae0300a2f41b0b1';
        $this->monthlyCookie = 'EmaarCookie=!tAB8Pf0MVogZ81obZ8iAVHPu2zwut7wDvb73T7QDsF0aUCzEuhjUSUZvMZiwD0VeouOB5V0+Kv66YS0=; TS01fca733=01eddcdb6230bb43843ae89e1ad8382a82e2ba8d05fc8333a21e1889d2c615540e93a6807dee364624a6955deb89639270a87e294b';
    }

    public function sendDailyOrders(array $payload): ?object
    {
        return $this->sendRequest(config('emaar.daily_url'), $payload, $this->dailyCookie);
    }

    public function sendMonthlyOrders(array $payload): ?object
    {
        return $this->sendRequest(config('emaar.monthly_url'), $payload, $this->monthlyCookie);
    }

    private function sendRequest(string $url, array $payload, string $cookie): ?object
    {
        try {
            $response = Http::withHeaders([
                'x-apikey' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Cookie' => $cookie,
            ])->post($url, $payload);

            if ($response->successful()) {
                return json_decode($response->body());
            }

            Log::error('Emaar API request failed', [
                'url' => $url,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Emaar API exception', [
                'url' => $url,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }
}

