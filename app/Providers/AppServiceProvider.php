<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\ReplenishmentInterface;
use GuzzleHttp\Client;
use App\Services\SimpleTopUpService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ReplenishmentInterface::class, SimpleTopUpService::class);
        $this->app->bind(ExchangeRate::class, function () {

            $client = new Client([
                'curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4],
            ]);
            return new ExchangeRate($client);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
