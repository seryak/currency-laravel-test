<?php

namespace App\Services;

use App\DTO\CurrencyRateDtoCollection;

interface CurrencyRateStorage
{
    public function save(CurrencyRateDtoCollection $rates): void;
}