<?php

namespace App\Services;

use GuzzleHttp\Client;

class ExchangeRate
{
    public function getAmountInForeignCurrency(String $preferredCurrency, Float $amount)
    {
        return round($this->getRate($preferredCurrency) * $amount, 2);
    }

    private function getRate(String $preferredCurrency)
    {
        $client = new Client([
            //'base_uri' => 'https://allratestoday.com/api/v1/',
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

        /*$response = $client->request('GET', 'rates', [
            'headers' => [
                'Authorization' => "Bearer ".env('CURRATE_API'),
            ],
            'query' => [
                'source' => "USD",
                'target' => "{$preferredCurrency}",
                'amount' => "1",
                ]
        ]);

        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            dd($arrayBody = json_decode($body));
            return $arrayBody[0]->rate;
        }*/

    }
}