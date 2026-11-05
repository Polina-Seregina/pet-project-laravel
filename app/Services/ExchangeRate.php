<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Enums\CurrencyEnum;
use Exception;

class ExchangeRate
{
    public function __construct(
        private Client $client
    ) {
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getAmountInForeignCurrency(string $preferredCurrency, float $amount): float
    {
        return round($this->getRate($preferredCurrency) * $amount, 2);
    }

    private function getRate(string $preferredCurrency)
    {
        if ($preferredCurrency === CurrencyEnum::USD->value) {
            return 1;
        }

        $response = $this->client->request('GET', config('services.exchange-rate-api.base-url') . config('services.exchange-rate-api.api-key') . '/' . 'latest/USD');
        $rate = json_decode($response->getBody(), true)['conversion_rates'][$preferredCurrency] ?? null;
        
        if (!is_numeric($rate) || $rate <= 0) {
            throw new Exception('Сервис перевода валют недоступен.');
        }
        return (float) $rate;
    }
}
