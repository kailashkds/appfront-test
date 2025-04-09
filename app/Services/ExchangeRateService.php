<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExchangeRateService
{
    const DEFAULT_EXCHANGE_RATE = 0.85;

    protected string $apiUrl = 'https://open.er-api.com/v6/latest/USD';

    public function getRate(string $currency): float
    {
        try {
            $response = Http::timeout(5)->get($this->apiUrl);

            if ($response->successful()) {
                $data = $response->json();
                return $data['rates'][$currency] ?? $this->fallbackRate($currency);
            }
        } catch (\Exception $e) {
            Log::warning('Exchange rate API failed: ' . $e->getMessage());
        }

        return $this->fallbackRate($currency);
    }

    protected function fallbackRate(string $currency): float
    {
        return match ($currency) {
            'EUR' => (float) env('EXCHANGE_RATE', self::DEFAULT_EXCHANGE_RATE),
            default => 1.0,
        };
    }
}
