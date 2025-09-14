<?php

namespace App\Services;

use App\Enums\CurrencyEnum;
use App\Models\Currency;
use App\Models\ExchangeRate;

class CurrencyConverter
{
    public function convert(float $amount, CurrencyEnum $fromCode, CurrencyEnum $toCode): float
    {
        $from = Currency::where('code', $fromCode)->first();
        $to = Currency::where('code', $toCode)->first();

        if (in_array(CurrencyEnum::USD, [$from->code, $to->code])) {
            $rate = ExchangeRate::where('currency_id', $to->id)
                ->whereDate('date', now())
                ->first()->rate;
        } else {
            $fromRate = ExchangeRate::where('currency_id', $from->id)
                ->whereDate('date', now())
                ->first()->rate;

            $toRate = ExchangeRate::where('currency_id', $to->id)
                ->whereDate('date', now())
                ->first()->rate;

            $rate = $toRate / $fromRate;
        }
        return $amount * $rate;
    }
}