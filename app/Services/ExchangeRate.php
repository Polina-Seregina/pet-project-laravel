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

    public function getAmountInForeignCurrency(String $preferredCurrency, Float $amount): Float
    {
        return round($this->getRate($preferredCurrency) * $amount, 2);
    }

    private function getRate(String $preferredCurrency)
    {
        if ($preferredCurrency === CurrencyEnum::USD->value) {
            return 1;
        }

        $pair = "USD{$preferredCurrency}";
        $response = $this->client->request('GET', 'latest', [
            'query' => [
                'get' => 'rates',
                'pairs' => $pair,
                'key' => config('services.currate.api-key'),
                ]
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new Exception('Сервис перевода валют недоступен.');
        }

        $body = $response->getBody();
        $arrayBody = json_decode($body);
        return $arrayBody->data->{$pair};

    }
}
