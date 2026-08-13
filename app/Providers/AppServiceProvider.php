<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\ReplenishmentInterface;
use App\Services\SimpleTopUpService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ReplenishmentInterface::class, SimpleTopUpService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
