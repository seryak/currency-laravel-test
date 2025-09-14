<?php

namespace App\Services;

use App\DTO\CurrencyRateDto;
use App\DTO\CurrencyRateDtoCollection;
use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseCurrencyExchangeRateStorage implements CurrencyRateStorage
{
    private array $currencyDictionary;
    public function __construct()
    {
        $this->currencyDictionary = Currency::all()->mapWithKeys(function ($currency) {
            return [$currency->code => $currency->id];
        })->toArray();
    }

    public function save(CurrencyRateDtoCollection $rates): void
    {
        DB::beginTransaction();

        try {
            $rates->each(function (CurrencyRateDto $dto) {
                try {
                    $this->updateOrCreate($dto);
                } catch (\Exception $e) {
                    Log::error("Failed to save rate for {$dto->currencyCode}: {$e->getMessage()}");
                }
            });

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to save currency rates: {$e->getMessage()}");
            throw $e;
        }
    }

    protected function updateOrCreate(CurrencyRateDto $rate): ExchangeRate
    {
        return ExchangeRate::updateOrCreate(
            [
                'currency_id' => $this->currencyDictionary[$rate->currencyCode],
                'date' => $rate->date->toDateString(),
            ],
            [
                'rate' => $rate->rate,
            ]
        );
    }
}