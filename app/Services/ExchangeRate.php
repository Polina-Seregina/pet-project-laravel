<?php

namespace App\Services;

use GuzzleHttp\Client;

class ExchangeRate
{
    public function getRate(String $preferredCurrency)
    {
        $client = new Client([
            'base_uri' => 'https://allratestoday.com/api/v1/',
        ]);

        $response = $client->request('GET', 'rates', [
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
            $arrayBody = json_decode($body);
            return $arrayBody[0]->rate;
        }

    }
}
