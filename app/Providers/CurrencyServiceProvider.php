<?php

namespace App\Providers;

use App\Services\CurrencyConverter;
use App\Services\CurrencyRateFetcher;
use App\Services\CurrencyRateStorage;
use Illuminate\Support\ServiceProvider;

class CurrencyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CurrencyRateFetcher::class, function () {
            return new \App\Services\FreeCurrencyApiFetcher(\App\Enums\CurrencyEnum::USD);
        });

        $this->app->singleton(CurrencyRateStorage::class, function () {
            return new \App\Services\DatabaseCurrencyExchangeRateStorage(\App\Enums\CurrencyEnum::USD);
        });
    }

    public function boot(): void
    {
        //
    }
}