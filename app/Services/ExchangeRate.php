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
        if ($preferredCurrency === "USD") {
            return 1;
        }

        $client = new Client([
            'base_uri' => config('services.currate.base-url'),
            'curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4],
            ]);

        $pair = "USD{$preferredCurrency}";
        $response = $client->request('GET', 'latest', [
            'query' => [
                'get' => 'rates',
                'pairs' => $pair,
                'key' => config('services.currate.api-key'),
                ]
        ]);

        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $arrayBody = json_decode($body);
            return $arrayBody->data->{$pair};
        }

    }
}
