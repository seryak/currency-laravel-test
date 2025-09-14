<?php

namespace App\Services;

use App\DTO\CurrencyRateDtoCollection;
use Carbon\Carbon;

interface CurrencyRateFetcher
{

    public function fetch(?Carbon $date = null): CurrencyRateDtoCollection;
}