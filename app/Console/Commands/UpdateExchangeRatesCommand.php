<?php

namespace App\Console\Commands;

use App\Services\CurrencyRateFetcher;
use App\Services\CurrencyRateStorage;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Command\Command as CommandAlias;

class UpdateExchangeRatesCommand extends Command
{
    protected $signature = 'currency:update-rates';
    protected $description = 'Update currency exchange rates from FreeCurrencyAPI';

    public function handle(): int
    {
        try {
            $fetcher = app(CurrencyRateFetcher::class);
            $collection = $fetcher->fetch(Carbon::now());
            $rateStorage = app(CurrencyRateStorage::class);
            $rateStorage->save($collection);

            return CommandAlias::SUCCESS;
        } catch (\Exception $e) {
            Log::error('Failed to update currency rates: ' . $e->getMessage());
            return CommandAlias::FAILURE;
        }
    }
}