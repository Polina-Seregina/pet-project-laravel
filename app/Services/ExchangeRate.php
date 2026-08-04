<?php

namespace App\Services;

use GuzzleHttp\Client;

class ExchangeRate
{
    public function getAmountInForeignCurrency(String $preferredCurrency, Float $amount): Float
    {
        return round($this->getRate($preferredCurrency) * $amount, 2);
    }

    private function getRate(String $preferredCurrency)
    {
        $client = new Client([
            'base_uri' => 'https://currate.ru/api/',
        ]);

        $pair = "USD{$preferredCurrency}";
        $response = $client->request('GET', 'latest', [
            'query' => [
                'get' => 'rates',
                'pairs' => $pair,
                'key' => 'a5a6c5429b4533a15ef9ff255178eb8b',
                ]
        ]);

        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $arrayBody = json_decode($body);
            return $arrayBody->data->{$pair};
        }

    }
}
