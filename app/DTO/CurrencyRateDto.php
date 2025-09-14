<?php

namespace App\DTO;

use App\Enums\CurrencyEnum;
use Carbon\Carbon;

readonly class CurrencyRateDto
{
    public string $baseCurrencyCode;
    public string $currencyCode;
    public float $rate;
    public Carbon $date;

    public function __construct(CurrencyEnum $baseCurrencyCode, CurrencyEnum $currencyCode, float $rate, Carbon $date)
    {
        $this->baseCurrencyCode = $baseCurrencyCode->name;
        $this->currencyCode = $currencyCode->name;
        $this->rate = $rate;
        $this->date = $date;
    }

    public function toArray(): array
    {
        return [
            'base_currency_code' => $this->baseCurrencyCode,
            'currency_code' => $this->currencyCode,
            'rate' => $this->rate,
            'date' => $this->date,
        ];
    }
}